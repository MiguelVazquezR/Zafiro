<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestor de Cobros e IVA</title>
  
  <!-- TOKEN CSRF PARA LARAVEL -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Librerías de React y Tailwind -->
  <script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
  <script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
  <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">
 
  
  <div id="root"></div>

  <script type="text/babel">
    const { useState, useMemo, useEffect, useRef } = React;

    // Utilidad para hacer peticiones al backend (con Token CSRF)
    const apiCall = async (url, method = 'GET', body = null) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const options = {
            method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        };
        if (body) options.body = JSON.stringify(body);
        const response = await fetch(url, options);
        if (!response.ok) throw new Error('Error en la petición API');
        return response.json();
    };

    // Componente Tarjeta
    const Card = ({ title, value, icon, color, subtitle, onClick }) => (
      <div onClick={onClick} className={`p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-center ${color} ${onClick ? 'cursor-pointer hover:shadow-md hover:-translate-y-1 transition-all' : ''}`}>
        <div className="flex items-start justify-between">
          <div>
            <p className="text-sm font-medium text-slate-500 mb-1">{title}</p>
            <h3 className="text-2xl font-bold text-slate-800">{value}</h3>
            {subtitle && <p className="text-xs text-slate-500 mt-1">{subtitle}</p>}
          </div>
          <div className="text-3xl p-3 bg-white/60 rounded-xl shadow-sm border border-slate-50">{icon}</div>
        </div>
      </div>
    );

    const App = () => {
      // --- ESTADOS DE DATOS (Se cargan desde la DB) ---
      const [entries, setEntries] = useState([]);
      const [ivaRecords, setIvaRecords] = useState([]);
      const [customServices, setCustomServices] = useState([]);
      const [expenses, setExpenses] = useState([]);
      const [isLoading, setIsLoading] = useState(true);

      // Cargar datos iniciales desde el Backend
      useEffect(() => {
        apiCall('/finanzas/data').then(data => {
            setEntries(data.entries);
            setExpenses(data.expenses);
            setIvaRecords(data.invoices);
            setCustomServices(data.services);
            setIsLoading(false);
        }).catch(err => console.error("Error cargando datos", err));
      }, []);

      // Estados de la Interfaz
      const [isFormOpen, setIsFormOpen] = useState(false);
      const [editingId, setEditingId] = useState(null);
      const [filterText, setFilterText] = useState('');
      const [currentView, setCurrentView] = useState('dashboard');
      const [statusFilter, setStatusFilter] = useState('Todos');
      const [balanceFilter, setBalanceFilter] = useState('Todos');
      const [newServiceName, setNewServiceName] = useState('');
      
      // Estados de Formularios
      const [isDeclarationModalOpen, setIsDeclarationModalOpen] = useState(false);
      const [declarationSatAmount, setDeclarationSatAmount] = useState('');
      const [declarationFolio, setDeclarationFolio] = useState('');
      const [selectedInvoiceIds, setSelectedInvoiceIds] = useState([]);
      const [paymentModal, setPaymentModal] = useState({ 
        isOpen: false, entryId: null, amount: '', fecha: new Date().toISOString().split('T')[0], recibo: '' 
      });

      const [isExpenseModalOpen, setIsExpenseModalOpen] = useState(false);
      const [expenseFormData, setExpenseFormData] = useState({
        fecha: new Date().toISOString().split('T')[0],
        concepto: '', monto: '', facturado: false
      });

      const defaultServices = [
        'Deslinde de Parcela', 'Deslinde de Solar', 'Plano de ubicación', 
        'Plano de levantamiento', 'Plano de Lotificación', 'Deslinde de lotificación', 'Otro'
      ];
      const serviceTypes = [...defaultServices, ...customServices.map(s => s.nombre)];
      const statusTypes = ['En Proceso', 'Completado', 'Suspendido', 'Cancelado'];
      const TAX_RATE = 0.16;

      const [formData, setFormData] = useState({
        fecha: new Date().toISOString().split('T')[0],
        numeroRecibo: '', tipoServicio: 'Deslinde de Parcela', tipoServicioOtro: '',
        ubicacion: '', cliente: '', telefono: '', montoIngresado: '', modoIva: 'sin_iva',
        anticipo: '', estado: 'En Proceso'
      });

      const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
      };

      // --- FUNCIONES GASTOS ---
      const handleExpenseSubmit = async (e) => {
        e.preventDefault();
        const payload = {
          fecha: expenseFormData.fecha,
          concepto: expenseFormData.concepto,
          monto: parseFloat(expenseFormData.monto) || 0,
          facturado: expenseFormData.facturado
        };

        try {
            const res = await apiCall('/finanzas/expenses', 'POST', payload);
            const newExpense = { ...payload, id: res.id };
            setExpenses([newExpense, ...expenses]);
            setIsExpenseModalOpen(false);
            setExpenseFormData({ fecha: new Date().toISOString().split('T')[0], concepto: '', monto: '', facturado: false });
        } catch(e) { alert("Error guardando gasto"); }
      };

      const deleteExpense = async (id) => {
        if(window.confirm('¿Seguro que deseas eliminar este gasto?')) {
          await apiCall(`/finanzas/expenses/${id}`, 'DELETE');
          setExpenses(expenses.filter(ex => ex.id !== id));
        }
      };

      // --- FUNCIONES COBROS / TRÁMITES ---
      const handleSubmit = async (e) => {
        e.preventDefault();
        const monto = parseFloat(formData.montoIngresado) || 0;
        const anticipo = parseFloat(formData.anticipo) || 0;
        
        let honorariosBase = 0, ivaCalculado = 0, totalConIva = 0;

        if (formData.modoIva === 'con_iva') {
          totalConIva = monto;
          honorariosBase = monto / (1 + TAX_RATE);
          ivaCalculado = totalConIva - honorariosBase;
        } else {
          honorariosBase = monto;
          ivaCalculado = monto * TAX_RATE;
          totalConIva = honorariosBase + ivaCalculado;
        }

        const saldo = totalConIva - anticipo;
        const estadoFinal = (saldo <= 0 && formData.estado !== 'Cancelado') ? 'Completado' : formData.estado;

        const newEntryData = {
          ...formData,
          tipoServicio: formData.tipoServicio === 'Otro' ? formData.tipoServicioOtro : formData.tipoServicio,
          honorarios: honorariosBase, iva: ivaCalculado, totalConIva: totalConIva,
          anticipo: anticipo, saldo: saldo, estado: estadoFinal,
          montoOriginal: monto, modoIvaOriginal: formData.modoIva,
        };

        try {
            if (editingId) {
                await apiCall(`/finanzas/entries/${editingId}`, 'PUT', newEntryData);
                const oldEntry = entries.find(ent => ent.id === editingId);
                newEntryData.id = editingId;
                newEntryData.declarado = oldEntry?.declarado || false;
                newEntryData.folioFactura = oldEntry?.folioFactura || null;
                newEntryData.historialPagos = oldEntry?.historialPagos || [];
                setEntries(entries.map(ent => ent.id === editingId ? newEntryData : ent));
            } else {
                const res = await apiCall('/finanzas/entries', 'POST', newEntryData);
                newEntryData.id = res.id;
                newEntryData.declarado = false;
                newEntryData.historialPagos = anticipo > 0 ? [{id: Date.now(), fecha: formData.fecha, monto: anticipo, recibo: formData.numeroRecibo || 'S/N'}] : [];
                setEntries([newEntryData, ...entries]);
            }
            
            setIsFormOpen(false);
            setEditingId(null);
            setFormData({ fecha: new Date().toISOString().split('T')[0], numeroRecibo: '', tipoServicio: 'Deslinde de Parcela', tipoServicioOtro: '', ubicacion: '', cliente: '', telefono: '', montoIngresado: '', modoIva: 'sin_iva', anticipo: '', estado: 'En Proceso' });
        } catch (e) { alert("Error guardando trámite"); }
      };

      const deleteEntry = async (id) => {
        if(window.confirm('¿Seguro que deseas eliminar este registro? Toda la información y pagos vinculados se borrarán.')) {
          await apiCall(`/finanzas/entries/${id}`, 'DELETE');
          setEntries(entries.filter(e => e.id !== id));
        }
      };

      const handleLiquidate = async (entry) => {
          // Un atajo de liquidación (Requiere abrir modal de pago para registrar recibo, o forzarlo aquí)
          // Para mantener el histórico limpio, abriremos el modal de pago pre-llenado
          setPaymentModal({
              isOpen: true, entryId: entry.id, amount: entry.saldo.toString(), fecha: new Date().toISOString().split('T')[0], recibo: ''
          });
      };

      const handleSavePayment = async (e) => {
        e.preventDefault();
        const entry = entries.find(ent => ent.id === paymentModal.entryId);
        if(!entry) return;

        const amountToAdd = parseFloat(paymentModal.amount) || 0;
        if (amountToAdd <= 0) return;

        try {
            const payload = { entryId: entry.id, fecha: paymentModal.fecha, amount: amountToAdd, recibo: paymentModal.recibo || 'S/N' };
            const res = await apiCall('/finanzas/payments', 'POST', payload);
            
            const newPago = { id: res.id, fecha: paymentModal.fecha, monto: amountToAdd, recibo: paymentModal.recibo || 'S/N' };
            const newAnticipo = entry.anticipo + amountToAdd;
            const newSaldo = entry.totalConIva - newAnticipo;
            let newState = entry.estado;
            if (newSaldo <= 0) newState = 'Completado';

            const updatedHistorial = [...(entry.historialPagos || []), newPago];

            setEntries(entries.map(ent => ent.id === entry.id ? {
                ...ent, anticipo: newAnticipo, saldo: newSaldo, estado: newState, historialPagos: updatedHistorial
            } : ent));
            
            setPaymentModal({ isOpen: false, entryId: null, amount: '', fecha: new Date().toISOString().split('T')[0], recibo: '' });
        } catch(e) { alert("Error al guardar el pago"); }
      };

      const handleStatusChange = async (id, newStatus) => {
        await apiCall(`/finanzas/entries/${id}/status`, 'POST', { estado: newStatus });
        setEntries(entries.map(e => e.id === id ? { ...e, estado: newStatus } : e));
      };

      // --- SERVICIOS ---
      const handleAddService = async (e) => {
        e.preventDefault();
        const name = newServiceName.trim();
        if (name && !serviceTypes.includes(name)) {
            const res = await apiCall('/finanzas/services', 'POST', { nombre: name });
            setCustomServices([...customServices, { id: res.id, nombre: name }]);
            setNewServiceName('');
        }
      };

      const handleRemoveService = async (serviceObj) => {
        await apiCall(`/finanzas/services/${serviceObj.id}`, 'DELETE');
        setCustomServices(customServices.filter(s => s.id !== serviceObj.id));
      };

      // --- FACTURACIÓN ---
      const handleDeclareIva = async (e) => {
        e.preventDefault();
        const pagoSat = parseFloat(declarationSatAmount) || 0;
        const liberado = ivaReadyToDeclare - pagoSat;
        const folio = declarationFolio.trim() || 'Sin Folio';

        const payload = {
            fecha: new Date().toISOString().split('T')[0], folioFactura: folio,
            honorariosTotal: honorariosReadyToDeclare, ivaTotal: ivaReadyToDeclare,
            totalGeneral: totalReadyToDeclare, pagoSat: pagoSat, liberado: liberado, 
            registrosLiquidacion: selectedInvoiceIds.length, entryIds: selectedInvoiceIds
        };

        try {
            const res = await apiCall('/finanzas/invoices', 'POST', payload);
            
            const newRecord = { ...payload, id: res.id };
            setIvaRecords([newRecord, ...ivaRecords]);
            setEntries(entries.map(ent => selectedInvoiceIds.includes(ent.id) ? { ...ent, declarado: true, folioFactura: folio } : ent));

            setIsDeclarationModalOpen(false);
            setDeclarationSatAmount('');
            setDeclarationFolio('');
            setSelectedInvoiceIds([]);
        } catch(e) { alert("Error al registrar factura"); }
      };

      // --- CÁLCULOS ---
      const activeEntries = entries.filter(e => !e.declarado);
      const archivedEntries = entries.filter(e => e.declarado);

      const totals = useMemo(() => {
        return activeEntries.reduce((acc, curr) => {
          acc.honorarios += curr.honorarios; acc.iva += curr.iva;
          acc.anticipos += curr.anticipo; acc.saldos += curr.saldo;
          return acc;
        }, { honorarios: 0, iva: 0, anticipos: 0, saldos: 0 });
      }, [activeEntries]);

      const eligibleForInvoice = activeEntries.filter(e => e.saldo <= 0 && e.estado === 'Completado');
      const selectedEntriesForInvoice = eligibleForInvoice.filter(e => selectedInvoiceIds.includes(e.id));
      
      const honorariosReadyToDeclare = selectedEntriesForInvoice.reduce((sum, e) => sum + e.honorarios, 0);
      const ivaReadyToDeclare = selectedEntriesForInvoice.reduce((sum, e) => sum + e.iva, 0);
      const totalReadyToDeclare = honorariosReadyToDeclare + ivaReadyToDeclare;

      const ivaStats = useMemo(() => {
        const totalPagadoSat = ivaRecords.reduce((sum, r) => sum + r.pagoSat, 0);
        const totalLiberado = ivaRecords.reduce((sum, r) => sum + r.liberado, 0);
        const ivaGeneradoHistorico = ivaRecords.reduce((sum, r) => sum + r.ivaTotal, 0);
        return { totalPagadoSat, totalLiberado, ivaGeneradoHistorico };
      }, [ivaRecords]);

      const financialStats = useMemo(() => {
        const honorariosBase = entries.reduce((sum, e) => sum + e.honorarios, 0);
        const ivaGenerado = entries.reduce((sum, e) => sum + e.iva, 0);
        const totalConIva = honorariosBase + ivaGenerado;
        const ivaPagado = ivaRecords.reduce((sum, r) => sum + r.pagoSat, 0);
        const ivaLiberado = ivaRecords.reduce((sum, r) => sum + r.liberado, 0);
        const gananciaNeta = honorariosBase + ivaLiberado;
        const ingresosEfectivos = entries.reduce((sum, e) => sum + e.anticipo, 0);
        const gastosOperativos = expenses.reduce((sum, ex) => sum + ex.monto, 0); 

        return { honorariosBase, ivaGenerado, totalConIva, ivaPagado, ivaLiberado, gananciaNeta, ingresosEfectivos, gastosOperativos };
      }, [entries, ivaRecords, expenses]);

      const getFilteredEntries = (sourceEntries) => {
        return sourceEntries.filter(e => {
          const matchesText = e.cliente.toLowerCase().includes(filterText.toLowerCase()) || 
                              (e.ubicacion || '').toLowerCase().includes(filterText.toLowerCase()) ||
                              e.tipoServicio.toLowerCase().includes(filterText.toLowerCase()) ||
                              (e.folioFactura && e.folioFactura.toLowerCase().includes(filterText.toLowerCase())) ||
                              (e.numeroRecibo && e.numeroRecibo.toLowerCase().includes(filterText.toLowerCase()));
          const matchesStatus = statusFilter === 'Todos' || e.estado === statusFilter;
          const matchesBalance = balanceFilter === 'Todos' || 
                                (balanceFilter === 'Con Adeudo' && e.saldo > 0) ||
                                (balanceFilter === 'Liquidados' && e.saldo <= 0);
          return matchesText && matchesStatus && matchesBalance;
        });
      };

      const currentFilteredEntries = getFilteredEntries(currentView === 'history' ? archivedEntries : activeEntries);
      const formatCurrency = (amount) => new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(amount);

      const getStatusColor = (status) => {
        switch(status) {
          case 'Completado': return 'bg-green-100 text-green-800 border-green-200';
          case 'En Proceso': return 'bg-blue-100 text-blue-800 border-blue-200';
          case 'Suspendido': return 'bg-yellow-100 text-yellow-800 border-yellow-200';
          case 'Cancelado': return 'bg-red-100 text-red-800 border-red-200';
          default: return 'bg-slate-100 text-slate-800 border-slate-200';
        }
      };

      const openEditForm = (entry) => {
        setFormData({
          ...entry,
          numeroRecibo: entry.numeroRecibo || '',
          montoIngresado: entry.montoOriginal || entry.honorarios,
          modoIva: entry.modoIvaOriginal || 'sin_iva',
          tipoServicioOtro: serviceTypes.includes(entry.tipoServicio) ? '' : entry.tipoServicio,
          tipoServicio: serviceTypes.includes(entry.tipoServicio) ? entry.tipoServicio : 'Otro'
        });
        setEditingId(entry.id);
        setIsFormOpen(true);
      };

      if(isLoading) return <div className="min-h-screen flex items-center justify-center text-slate-500 font-bold">Cargando base de datos...</div>;

      return (
        <div className="min-h-screen bg-slate-50 p-4 md:p-8 font-sans text-slate-800">
          <div className="max-w-7xl mx-auto space-y-8">
            
            {/* ENCABEZADO */}
            <header className="flex flex-col xl:flex-row xl:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
              <div className="flex items-center gap-4">
                <div className="p-3 bg-indigo-100 text-indigo-600 rounded-xl text-3xl">
                  {currentView === 'dashboard' ? '📄' : currentView === 'history' ? '🗃️' : currentView === 'services' ? '⚙️' : currentView === 'finanzas' ? '📊' : currentView === 'gastos' ? '💸' : '🧾'}
                </div>
                <div>
                  <h1 className="text-2xl md:text-3xl font-bold text-slate-900">
                    {currentView === 'dashboard' ? 'Cuentas Activas' : 
                     currentView === 'history' ? 'Historial de Trámites' : 
                     currentView === 'services' ? 'Gestión de Servicios' : 
                     currentView === 'finanzas' ? 'Resumen Global de Ganancias' : 
                     currentView === 'gastos' ? 'Control de Gastos Operativos' : 
                     'Facturación e Impuestos'}
                  </h1>
                </div>
              </div>
              
              <div className="flex flex-wrap gap-2 md:gap-3">
                {/* --- NUEVO BOTÓN PARA REGRESAR AL ERP --- */}
                <a href="{{ route('dashboard') }}" className="flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-xl font-semibold shadow-md transition-colors">
                  🏠 Volver a ERP
                </a>

                {currentView !== 'dashboard' && (
                  <button onClick={() => setCurrentView('dashboard')} className="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-semibold transition-colors">
                    ⬅️ Volver
                  </button>
                )}
                
                {currentView === 'dashboard' && (
                  <>
                    <button onClick={() => setCurrentView('finanzas')} className="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-semibold transition-colors">📈 Finanzas</button>
                    <button onClick={() => setCurrentView('gastos')} className="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-semibold transition-colors">💸 Gastos</button>
                    <button onClick={() => setCurrentView('history')} className="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-semibold transition-colors">🗃️ Historial</button>
                    <button onClick={() => setCurrentView('services')} className="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 rounded-xl font-semibold transition-colors">⚙️ Servicios</button>
                    <button onClick={() => { setEditingId(null); setIsFormOpen(true); }} className="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-md transition-colors">➕ Nuevo</button>
                  </>
                )}
              </div>
            </header>

            {/* VISTA DASHBOARD / HISTORY */}
            {(currentView === 'dashboard' || currentView === 'history') && (
              <>
                {currentView === 'dashboard' && (
                  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Card title="Honorarios Activos" value={formatCurrency(totals.honorarios)} icon="💰" color="bg-white" />
                    <Card title="IVA Acumulado Activo" value={formatCurrency(totals.iva)} icon="🧮" color="bg-orange-50/50" subtitle="Clic para facturar" onClick={() => setCurrentView('iva')} />
                    <Card title="Pagos Recibidos" value={formatCurrency(totals.anticipos)} icon="↗️" color="bg-white" />
                    <Card title="Saldos Pendientes" value={formatCurrency(totals.saldos)} icon="↘️" color="bg-white" subtitle="Por cobrar a clientes" />
                  </div>
                )}

                <div className="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                  <div className="p-4 border-b border-slate-100 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <h2 className="text-lg font-bold text-slate-800">{currentView === 'dashboard' ? 'Registros Activos' : 'Registros Archivados'}</h2>
                    <div className="flex flex-col sm:flex-row w-full lg:w-auto gap-3">
                      <select value={statusFilter} onChange={(e) => setStatusFilter(e.target.value)} className="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                        <option value="Todos">Todos los Estados</option>
                        {statusTypes.map(s => <option key={s} value={s}>{s}</option>)}
                      </select>
                      {currentView === 'dashboard' && (
                        <select value={balanceFilter} onChange={(e) => setBalanceFilter(e.target.value)} className="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                          <option value="Todos">Todos los Saldos</option><option value="Con Adeudo">Con Adeudo</option><option value="Liquidados">Liquidados</option>
                        </select>
                      )}
                      <input type="text" placeholder="🔍 Buscar..." value={filterText} onChange={(e) => setFilterText(e.target.value)} className="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm" />
                    </div>
                  </div>
                  
                  <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse min-w-[900px]">
                      <thead>
                        <tr className="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider">
                          <th className="p-4 font-medium">Fecha / Servicio</th><th className="p-4 font-medium">Cliente / Ubicación</th>
                          {currentView === 'history' && <th className="p-4 font-medium text-center">Factura</th>}
                          <th className="p-4 font-medium text-right">Honorarios</th><th className="p-4 font-medium text-right">IVA</th>
                          <th className="p-4 font-medium text-right">Pagos</th><th className="p-4 font-medium text-right">Saldo</th>
                          <th className="p-4 font-medium text-center">Estado</th><th className="p-4 font-medium text-center w-36">Acciones</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-100">
                        {currentFilteredEntries.length === 0 ? (
                          <tr><td colSpan={currentView === 'history' ? "9" : "8"} className="p-8 text-center text-slate-400">No hay registros para mostrar.</td></tr>
                        ) : (
                          currentFilteredEntries.map((entry) => (
                            <tr key={entry.id} className="hover:bg-slate-50/50 transition-colors">
                              <td className="p-4"><div className="font-medium text-slate-800">{entry.tipoServicio}</div><div className="text-sm text-slate-500">{entry.fecha}</div></td>
                              <td className="p-4"><div className="font-medium text-slate-800">{entry.cliente}</div>{entry.telefono && (<div className="text-xs text-slate-500 mt-0.5">📞 {entry.telefono}</div>)}<div className="text-sm text-slate-500 truncate max-w-[150px] mt-0.5" title={entry.ubicacion}>{entry.ubicacion || 'Sin ubicación'}</div></td>
                              {currentView === 'history' && (<td className="p-4 text-center"><span className="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-mono border border-slate-200">{entry.folioFactura || 'S/N'}</span></td>)}
                              <td className="p-4 text-right font-medium text-slate-700">{formatCurrency(entry.honorarios)}</td>
                              <td className="p-4 text-right text-orange-600">{formatCurrency(entry.iva)}</td>
                              <td className="p-4 text-right"><div className="text-emerald-600 font-bold">{formatCurrency(entry.anticipo)}</div>{entry.historialPagos && entry.historialPagos.length > 0 && (<div className="text-[11px] text-slate-400 mt-0.5">Últ. Recibo: <span className="font-mono">#{entry.historialPagos[entry.historialPagos.length - 1].recibo || 'S/N'}</span></div>)}</td>
                              <td className="p-4 text-right font-bold text-slate-900">{formatCurrency(entry.saldo)}</td>
                              <td className="p-4 text-center">
                                {currentView === 'dashboard' ? (
                                  <select value={entry.estado} onChange={(e) => handleStatusChange(entry.id, e.target.value)} className={`px-2 py-1 rounded-full text-xs font-semibold border ${getStatusColor(entry.estado)} cursor-pointer text-center`}>{statusTypes.map(s => <option key={s} value={s}>{s}</option>)}</select>
                                ) : (<span className={`px-3 py-1 rounded-full text-xs font-semibold border ${getStatusColor(entry.estado)}`}>{entry.estado}</span>)}
                              </td>
                              <td className="p-4 text-center">
                                <div className="flex justify-center gap-1 text-lg">
                                  {!entry.declarado && entry.saldo > 0 && (<button onClick={() => handleLiquidate(entry)} className="p-1 hover:text-blue-600" title="Liquidar Saldo">✅</button>)}
                                  {!entry.declarado && (<button onClick={() => setPaymentModal({ isOpen: true, entryId: entry.id, amount: '', fecha: new Date().toISOString().split('T')[0], recibo: '' })} className="p-1 hover:text-emerald-600" title="Abonar Pago">💳</button>)}
                                  <button onClick={() => openEditForm(entry)} className="p-1 hover:text-indigo-600" title="Modificar">✏️</button>
                                  <button onClick={() => deleteEntry(entry.id)} className="p-1 hover:text-red-600" title="Eliminar">🗑️</button>
                                </div>
                              </td>
                            </tr>
                          ))
                        )}
                      </tbody>
                    </table>
                  </div>
                </div>
              </>
            )}

            {/* VISTA FINANZAS */}
            {currentView === 'finanzas' && (
              <div className="space-y-8">
                <div className="bg-gradient-to-br from-emerald-900 via-teal-800 to-emerald-900 rounded-3xl p-8 shadow-xl text-white">
                  <p className="text-emerald-200 font-semibold uppercase mb-2 text-sm">Después de IVA (Honorarios + IVA a tu favor)</p>
                  <h2 className="text-4xl md:text-6xl font-black mb-4">{formatCurrency(financialStats.gananciaNeta)}</h2>
                  <p className="text-emerald-100 text-sm">Esta es tu <strong>Ganancia Bruta General</strong> sumando todo el historial de cobros.</p>
                </div>
                
                {financialStats.gastosOperativos > 0 && (
                  <div className="bg-white rounded-2xl shadow-sm border border-rose-100 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                      <p className="text-sm font-bold text-rose-500 uppercase tracking-wide">Gastos Registrados</p>
                      <h3 className="text-3xl font-black text-slate-800">{formatCurrency(financialStats.gastosOperativos)}</h3>
                    </div>
                    <div className="text-right">
                      <p className="text-sm font-bold text-indigo-500 uppercase tracking-wide">Utilidad Real (Restando Gastos)</p>
                      <h3 className="text-3xl font-black text-indigo-700">{formatCurrency(financialStats.gananciaNeta - financialStats.gastosOperativos)}</h3>
                    </div>
                  </div>
                )}

                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <Card title="Monto Global (Con IVA)" value={formatCurrency(financialStats.totalConIva)} icon="🧮" color="bg-white" />
                  <Card title="Honorarios Base (Sin IVA)" value={formatCurrency(financialStats.honorariosBase)} icon="📄" color="bg-white" />
                  <Card title="Efectivo Ingresado Real" value={formatCurrency(financialStats.ingresosEfectivos)} icon="💰" color="bg-white" subtitle="Pagos en tu poder" />
                </div>
              </div>
            )}

            {/* VISTA DE GASTOS */}
            {currentView === 'gastos' && (
              <div className="space-y-6">
                <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100 gap-4">
                  <div>
                    <h2 className="text-xl font-bold text-slate-800">Directorio de Gastos</h2>
                    <p className="text-sm text-slate-500">Lleva el control de costos operativos o de equipo de trabajo.</p>
                  </div>
                  <button onClick={() => setIsExpenseModalOpen(true)} className="bg-rose-600 text-white px-5 py-2.5 rounded-xl font-semibold shadow-md hover:bg-rose-700 w-full sm:w-auto">
                    ➕ Nuevo Gasto
                  </button>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <Card title="Total Gastos" value={formatCurrency(expenses.reduce((sum, e) => sum + e.monto, 0))} icon="💸" color="bg-white" border="border-rose-100" />
                  <Card title="Gastos Facturados (Deducibles)" value={formatCurrency(expenses.filter(e => e.facturado).reduce((sum, e) => sum + e.monto, 0))} icon="🧾" color="bg-white" />
                  <Card title="Gastos No Facturados" value={formatCurrency(expenses.filter(e => !e.facturado).reduce((sum, e) => sum + e.monto, 0))} icon="📝" color="bg-white" />
                </div>

                <div className="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                  <table className="w-full text-left border-collapse">
                    <thead>
                      <tr className="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider">
                        <th className="p-4">Fecha</th>
                        <th className="p-4">Concepto</th>
                        <th className="p-4">Facturado</th>
                        <th className="p-4 text-right">Monto</th>
                        <th className="p-4 text-center">Acciones</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                      {expenses.length === 0 ? (
                         <tr><td colSpan="5" className="p-8 text-center text-slate-400">No hay gastos registrados en el sistema.</td></tr>
                      ) : expenses.map(ex => (
                         <tr key={ex.id} className="hover:bg-slate-50">
                           <td className="p-4 text-sm text-slate-500">{ex.fecha}</td>
                           <td className="p-4 font-medium text-slate-800">{ex.concepto}</td>
                           <td className="p-4">
                             {ex.facturado 
                               ? <span className="px-2.5 py-1 bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs rounded-lg font-bold">Sí (Deducible)</span> 
                               : <span className="px-2.5 py-1 bg-slate-100 text-slate-600 border border-slate-200 text-xs rounded-lg font-bold">No Facturado</span>
                             }
                           </td>
                           <td className="p-4 text-right font-bold text-rose-600">{formatCurrency(ex.monto)}</td>
                           <td className="p-4 text-center"><button onClick={() => deleteExpense(ex.id)} className="p-2 hover:text-red-600 text-lg">🗑️</button></td>
                         </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>
            )}

            {/* VISTA SERVICIOS */}
            {currentView === 'services' && (
              <div className="bg-white rounded-2xl shadow-sm border border-slate-100 max-w-2xl mx-auto">
                <div className="p-6 border-b border-slate-100"><h2 className="text-xl font-bold">Servicios Personalizados</h2></div>
                <div className="p-6">
                  <form onSubmit={handleAddService} className="flex gap-3 mb-6">
                    <input type="text" value={newServiceName} onChange={(e) => setNewServiceName(e.target.value)} placeholder="Nuevo servicio..." className="flex-1 p-3 bg-slate-50 border rounded-xl" />
                    <button type="submit" className="px-6 py-3 bg-indigo-600 text-white rounded-xl">Añadir</button>
                  </form>
                  <ul className="divide-y divide-slate-100 border rounded-xl">
                    {defaultServices.map(service => (<li key={service} className="p-4 bg-slate-50/50"><span className="font-medium text-slate-600">{service}</span></li>))}
                    {customServices.map(service => (<li key={service.id} className="flex justify-between p-4"><span className="font-medium text-indigo-700">{service.nombre}</span><button onClick={() => handleRemoveService(service)}>🗑️</button></li>))}
                  </ul>
                </div>
              </div>
            )}

            {/* VISTA IVA */}
            {currentView === 'iva' && (
              <div className="space-y-6">
                <div className="bg-slate-800 rounded-2xl p-8 text-white flex justify-between items-center">
                  <div><h2 className="text-2xl font-bold mb-2">Generar Factura</h2><p className="text-sm text-slate-300">Selecciona los registros liquidados.</p></div>
                  <div className="bg-white/10 p-5 rounded-2xl text-center"><p className="text-sm">Total a Facturar</p><p className="text-3xl font-bold">{formatCurrency(totalReadyToDeclare)}</p></div>
                </div>
                <div className="bg-white rounded-2xl shadow-sm overflow-hidden">
                  <div className="p-4 bg-slate-50 flex justify-between"><h3 className="font-bold">Trámites Listos</h3>
                    {eligibleForInvoice.length > 0 && (<label className="text-sm font-semibold text-indigo-600 cursor-pointer"><input type="checkbox" checked={selectedInvoiceIds.length === eligibleForInvoice.length} onChange={(e) => setSelectedInvoiceIds(e.target.checked ? eligibleForInvoice.map(ent => ent.id) : [])} className="mr-2"/>Seleccionar Todos</label>)}
                  </div>
                  <ul className="divide-y divide-slate-100 max-h-64 overflow-y-auto">
                    {eligibleForInvoice.map(entry => (
                      <li key={entry.id} className="p-4 flex gap-4 cursor-pointer" onClick={() => setSelectedInvoiceIds(selectedInvoiceIds.includes(entry.id) ? selectedInvoiceIds.filter(id => id !== entry.id) : [...selectedInvoiceIds, entry.id])}>
                        <input type="checkbox" checked={selectedInvoiceIds.includes(entry.id)} readOnly className="mt-1"/>
                        <div className="flex-1 grid grid-cols-3"><p>{entry.cliente}</p><p>{entry.tipoServicio}</p><p className="font-bold text-right">{formatCurrency(entry.totalConIva)}</p></div>
                      </li>
                    ))}
                  </ul>
                  <div className="p-4 bg-slate-50 flex justify-end"><button onClick={() => setIsDeclarationModalOpen(true)} disabled={selectedInvoiceIds.length === 0} className="bg-emerald-500 text-white px-6 py-2 rounded-xl font-bold disabled:bg-slate-300">Continuar 🧾</button></div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <Card title="Histórico IVA Facturado" value={formatCurrency(ivaStats.ivaGeneradoHistorico)} icon="🗃️" color="bg-white" subtitle="Suma de facturas pasadas" />
                  <Card title="Total Pagado al SAT" value={formatCurrency(ivaStats.totalPagadoSat)} icon="💳" color="bg-white" subtitle="Impuestos transferidos" />
                  <Card title="Ganancia Neta (Liberada)" value={formatCurrency(ivaStats.totalLiberado)} icon="💵" color="bg-green-50" subtitle="Fondo libre" />
                </div>

                <div className="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                  <div className="p-6 border-b border-slate-100"><h2 className="text-xl font-bold">Historial de Facturas</h2></div>
                  <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                      <thead><tr className="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider"><th className="p-4">Fecha</th><th className="p-4">Folio Factura</th><th className="p-4 text-center">Trámites Agrupados</th><th className="p-4 text-right">Facturado (Total)</th><th className="p-4 text-right text-blue-600">Pago al SAT</th><th className="p-4 text-right text-green-600">Saldo Liberado</th></tr></thead>
                      <tbody className="divide-y divide-slate-100">
                        {ivaRecords.map((record) => (
                          <tr key={record.id} className="hover:bg-slate-50"><td className="p-4">{record.fecha}</td><td className="p-4"><span className="px-3 py-1 bg-slate-100 rounded-lg text-sm font-mono">{record.folioFactura}</span></td><td className="p-4 text-center">{record.registrosLiquidacion}</td><td className="p-4 text-right"><div className="font-bold">{formatCurrency(record.totalGeneral)}</div><div className="text-xs text-slate-500">IVA: {formatCurrency(record.ivaTotal)}</div></td><td className="p-4 text-right text-blue-700 font-medium">{formatCurrency(record.pagoSat)}</td><td className="p-4 text-right font-bold text-green-700">{formatCurrency(record.liberado)}</td></tr>
                        ))}
                        {ivaRecords.length === 0 && <tr><td colSpan="6" className="p-8 text-center text-slate-400">No hay facturas registradas.</td></tr>}
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            )}

            {/* MODAL GASTOS */}
            {isExpenseModalOpen && (
              <div className="fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50">
                <div className="bg-white rounded-2xl w-full max-w-md p-6">
                  <h2 className="text-xl font-bold mb-4">Registrar Nuevo Gasto</h2>
                  <form onSubmit={handleExpenseSubmit} className="space-y-4">
                    <div><label className="text-sm font-semibold">Fecha</label><input required type="date" value={expenseFormData.fecha} onChange={e => setExpenseFormData({...expenseFormData, fecha: e.target.value})} className="w-full p-2 border rounded-lg" /></div>
                    <div><label className="text-sm font-semibold">Concepto (Ej. Gasolina, Papelería, Equipo)</label><input required type="text" value={expenseFormData.concepto} onChange={e => setExpenseFormData({...expenseFormData, concepto: e.target.value})} className="w-full p-2 border rounded-lg" /></div>
                    <div><label className="text-sm font-semibold">Monto del Gasto</label><input required type="number" step="0.01" min="0" value={expenseFormData.monto} onChange={e => setExpenseFormData({...expenseFormData, monto: e.target.value})} className="w-full p-2 border rounded-lg" /></div>
                    
                    <div className="flex items-center gap-3 mt-4 p-4 bg-slate-50 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-100 transition" onClick={() => setExpenseFormData({...expenseFormData, facturado: !expenseFormData.facturado})}>
                        <input type="checkbox" checked={expenseFormData.facturado} readOnly className="w-5 h-5 cursor-pointer accent-emerald-600" />
                        <label className="text-sm font-semibold cursor-pointer select-none">¿Fue facturado para deducir?</label>
                    </div>

                    <div className="flex gap-2 pt-4">
                        <button type="button" onClick={() => setIsExpenseModalOpen(false)} className="flex-1 py-2 bg-slate-200 font-semibold rounded-lg text-slate-700">Cancelar</button>
                        <button type="submit" className="flex-1 py-2 bg-rose-600 font-semibold text-white rounded-lg">Guardar Gasto</button>
                    </div>
                  </form>
                </div>
              </div>
            )}

            {/* MODAL TRÁMITE NUEVO / EDITAR */}
            {isFormOpen && (
              <div className="fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50">
                <div className="bg-white rounded-2xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
                  <h2 className="text-xl font-bold mb-4">{editingId ? 'Modificar' : 'Nuevo Trámite'}</h2>
                  <form onSubmit={handleSubmit} className="grid grid-cols-2 gap-4">
                    <div><label className="text-sm font-semibold">Fecha</label><input required type="date" name="fecha" value={formData.fecha} onChange={handleInputChange} className="w-full p-2 border rounded-lg" /></div>
                    <div><label className="text-sm font-semibold">Cliente</label><input required type="text" name="cliente" value={formData.cliente} onChange={handleInputChange} className="w-full p-2 border rounded-lg" /></div>
                    <div><label className="text-sm font-semibold">Teléfono</label><input type="tel" name="telefono" value={formData.telefono} onChange={handleInputChange} className="w-full p-2 border rounded-lg" /></div>
                    <div><label className="text-sm font-semibold">Servicio</label><select name="tipoServicio" value={formData.tipoServicio} onChange={handleInputChange} className="w-full p-2 border rounded-lg">{serviceTypes.map(t => <option key={t} value={t}>{t}</option>)}</select></div>
                    <div className="col-span-2"><label className="text-sm font-semibold">Ubicación</label><input type="text" name="ubicacion" value={formData.ubicacion} onChange={handleInputChange} className="w-full p-2 border rounded-lg" /></div>
                    <div className="col-span-2"><label className="text-sm font-semibold">Honorarios</label><div className="flex gap-2"><select name="modoIva" value={formData.modoIva} onChange={handleInputChange} className="p-2 border rounded-lg"><option value="sin_iva">Sin IVA</option><option value="con_iva">Con IVA</option></select><input required type="number" name="montoIngresado" value={formData.montoIngresado} onChange={handleInputChange} className="w-full p-2 border rounded-lg" /></div></div>
                    <div><label className="text-sm font-semibold">Anticipo</label><input type="number" name="anticipo" value={formData.anticipo} onChange={handleInputChange} className="w-full p-2 border rounded-lg" disabled={!!editingId} /></div>
                    <div><label className="text-sm font-semibold">Recibo #</label><input type="text" name="numeroRecibo" value={formData.numeroRecibo} onChange={handleInputChange} className="w-full p-2 border rounded-lg uppercase" disabled={!!editingId} /></div>
                    <div className="col-span-2 flex gap-2 mt-4"><button type="button" onClick={() => setIsFormOpen(false)} className="flex-1 py-2 bg-slate-200 rounded-lg">Cancelar</button><button type="submit" className="flex-1 py-2 bg-indigo-600 text-white rounded-lg">Guardar</button></div>
                  </form>
                </div>
              </div>
            )}

            {/* MODAL PAGOS */}
            {paymentModal.isOpen && (
              <div className="fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50">
                <div className="bg-white rounded-2xl w-full max-w-md p-6"><h2 className="text-xl font-bold mb-4">Abonar Pago</h2>
                  <form onSubmit={handleSavePayment} className="space-y-4">
                    <div className="grid grid-cols-2 gap-4"><div><label className="text-sm font-semibold">Fecha</label><input required type="date" value={paymentModal.fecha} onChange={e => setPaymentModal({...paymentModal, fecha: e.target.value})} className="w-full p-2 border rounded-lg" /></div><div><label className="text-sm font-semibold">Recibo #</label><input type="text" value={paymentModal.recibo} onChange={e => setPaymentModal({...paymentModal, recibo: e.target.value})} className="w-full p-2 border rounded-lg uppercase" /></div></div>
                    <div><label className="text-sm font-semibold">Monto</label><input required type="number" value={paymentModal.amount} onChange={e => setPaymentModal({...paymentModal, amount: e.target.value})} className="w-full p-2 border rounded-lg" /></div>
                    <div className="flex gap-2"><button type="button" onClick={() => setPaymentModal({ isOpen: false, entryId: null, amount: '', fecha: new Date().toISOString().split('T')[0], recibo: '' })} className="flex-1 py-2 bg-slate-200 rounded-lg">Cancelar</button><button type="submit" className="flex-1 py-2 bg-emerald-600 text-white rounded-lg">Guardar</button></div>
                  </form>
                </div>
              </div>
            )}

            {/* MODAL DECLARACIÓN IVA */}
            {isDeclarationModalOpen && (
              <div className="fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50">
                <div className="bg-white rounded-2xl w-full max-w-lg p-6"><h2 className="text-xl font-bold mb-4">Factura y Declaración</h2>
                  <form onSubmit={handleDeclareIva} className="space-y-4">
                    <div className="bg-indigo-50 p-4 rounded-xl border border-indigo-100 flex justify-between items-center">
                      <div><p className="text-xs font-semibold text-indigo-700">Honorarios Base</p><p className="font-medium text-indigo-900">{formatCurrency(honorariosReadyToDeclare)}</p></div>
                      <div className="text-center px-4 border-x border-indigo-200"><p className="text-xs font-semibold text-indigo-700">IVA (16%)</p><p className="font-medium text-indigo-900">{formatCurrency(ivaReadyToDeclare)}</p></div>
                      <div className="text-right"><p className="text-xs font-bold text-indigo-800">Total Factura</p><p className="text-xl font-black text-indigo-900">{formatCurrency(totalReadyToDeclare)}</p></div>
                    </div>
                    
                    <div className="space-y-1.5">
                      <label className="text-sm font-semibold">Folio Factura</label>
                      <input required type="text" value={declarationFolio} onChange={e => setDeclarationFolio(e.target.value)} placeholder="Ej. FAC-001" className="w-full p-2.5 border rounded-lg uppercase" />
                    </div>
                    <div className="space-y-1.5">
                      <label className="text-sm font-semibold">Pago al SAT (indicado por contador)</label>
                      <input required type="number" step="0.01" max={ivaReadyToDeclare} value={declarationSatAmount} onChange={e => setDeclarationSatAmount(e.target.value)} className="w-full p-2.5 border rounded-lg" />
                    </div>

                    {declarationSatAmount !== '' && (
                      <div className="bg-green-50 p-4 rounded-xl border border-green-200 flex justify-between items-center mt-2">
                        <span className="text-sm font-semibold">Saldo de IVA a tu favor:</span>
                        <span className="text-xl font-bold text-green-700">{formatCurrency(ivaReadyToDeclare - (parseFloat(declarationSatAmount) || 0))}</span>
                      </div>
                    )}

                    <div className="flex gap-2 pt-4"><button type="button" onClick={() => setIsDeclarationModalOpen(false)} className="flex-1 py-2.5 bg-slate-200 font-semibold rounded-lg">Cancelar</button><button type="submit" className="flex-1 py-2.5 bg-slate-800 font-semibold text-white rounded-lg">Confirmar</button></div>
                  </form>
                </div>
              </div>
            )}

          </div>
        </div>
      );
    };

    const root = ReactDOM.createRoot(document.getElementById('root'));
    root.render(<App />);
  </script>
</body>
</html>