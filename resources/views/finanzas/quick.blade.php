<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4 font-sans">

    <div class="bg-white rounded-[32px] shadow-lg border border-gray-100 p-8 w-full max-w-sm text-center transform transition-all animate-bounce-in">
        
        <!-- Icono de Éxito animado -->
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-emerald-100 mb-6">
            <svg class="h-10 w-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        
        <h2 class="text-2xl font-black text-gray-900 mb-2">¡Registro Guardado!</h2>
        <p class="text-gray-500 font-medium mb-8">El movimiento se ha registrado correctamente en la base de datos.</p>
        
        <!-- Tarjeta de Resumen -->
        <div class="bg-slate-50 rounded-2xl p-5 text-left border border-slate-100">
            <div class="flex justify-between items-center mb-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tipo</span>
                <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $tipo === 'ingreso' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                    {{ strtoupper($tipo) }}
                </span>
            </div>
            
            <div class="mb-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Concepto / Cliente</span>
                <span class="text-sm font-bold text-gray-800">{{ $concepto }}</span>
            </div>

            <div class="border-t border-slate-200 mt-3 pt-3 flex justify-between items-center">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Monto</span>
                <span class="text-xl font-black text-gray-900">${{ number_format($monto, 2) }}</span>
            </div>
        </div>

        <!-- Botón para cerrar pestaña (opcional) -->
        <button onclick="window.close();" class="mt-8 w-full bg-slate-800 text-white font-bold py-3 px-4 rounded-xl hover:bg-slate-900 transition-colors">
            Cerrar Ventana
        </button>

    </div>

</body>
</html>