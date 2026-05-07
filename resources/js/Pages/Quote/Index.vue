<template>
    <AppLayout title="Cotizaciones">
        
        <!-- Contenedor Principal (Estilo One UI) -->
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Encabezado de la página -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 px-2">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-indigo-100 text-indigo-600 rounded-2xl">
                        <el-icon size="24"><Document /></el-icon>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Cotizaciones</h2>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">Gestión de presupuestos y solicitudes</p>
                    </div>
                </div>
                
                <el-button 
                    @click="router.get(route('quotes.create'))" 
                    type="primary" 
                    class="one-ui-btn !rounded-xl !border-none !bg-indigo-600 !text-white hover:!bg-indigo-700 shadow-md">
                    <el-icon class="mr-2"><Plus /></el-icon>
                    Nueva Cotización
                </el-button>
            </div>

            <!-- Tarjeta Principal Blanca -->
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden p-6 transition-all">
                
                <!-- Barra de Acciones (Botones y Paginación) -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <!-- Paginación Nativa -->
                    <div v-if="quotes.data.length > 0">
                        <el-pagination 
                            @current-change="handlePagination" 
                            layout="prev, pager, next"
                            :total="quotes.total || quotes.data.length"
                            :page-size="quotes.per_page || 10"
                            class="!p-0"
                        />
                    </div>
                    
                    <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">
                        <div class="text-sm font-semibold text-gray-500">
                            <span v-if="selectedQuotes.length > 0" class="text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                                {{ selectedQuotes.length }} seleccionado(s)
                            </span>
                        </div>

                        <el-popconfirm 
                            confirm-button-text="Sí, eliminar" 
                            cancel-button-text="Cancelar" 
                            confirm-button-type="danger"
                            icon-color="#f56c6c"
                            title="¿Continuar con la eliminación?" 
                            @confirm="deleteSelections"
                            width="250"
                        >
                            <template #reference>
                                <el-button 
                                    type="danger" 
                                    class="one-ui-btn !rounded-xl !border-none !bg-red-50 !text-red-600 hover:!bg-red-100" 
                                    :disabled="disableMassiveActions">
                                    <el-icon class="mr-2"><Delete /></el-icon>
                                    Eliminar
                                </el-button>
                            </template>
                        </el-popconfirm>
                    </div>
                </div>

                <!-- Tabla de Element Plus (One UI Style) -->
                <el-table 
                    :data="localQuotes" 
                    @row-click="handleRowClick" 
                    max-height="600" 
                    style="width: 100%"
                    @selection-change="handleSelectionChange" 
                    ref="multipleTableRef"
                    class="one-ui-table cursor-pointer"
                >
                    <el-table-column type="selection" width="55" align="center" />
                    
                    <el-table-column prop="folio" label="Folio" width="100">
                        <template #default="{ row }">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded-md text-gray-700 text-xs font-bold">{{ row.folio }}</span>
                        </template>
                    </el-table-column>
                    
                    <el-table-column prop="name" label="Nombre" min-width="250">
                        <template #default="{ row }">
                            <div class="flex flex-col justify-center">
                                <span class="font-bold text-gray-800">{{ row.name }}</span>
                                <div class="mt-1">
                                    <!-- Badges de Estado -->
                                    <span v-if="row.status === 'Esperando autorización del cliente'" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        <el-icon><Clock /></el-icon> Pendiente
                                    </span>
                                    <span v-else-if="row.status === 'Autorizada'" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        <el-icon><Check /></el-icon> Autorizada
                                    </span>
                                    <span v-else-if="row.status === 'Rechazada'" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        <el-icon><Close /></el-icon> Rechazada
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                        {{ row.status }}
                                    </span>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                    
                    <el-table-column prop="client" label="Cliente" min-width="200">
                        <template #default="{ row }">
                            <span class="text-gray-600 font-medium">{{ row.client }}</span>
                        </template>
                    </el-table-column>
                    
                    <el-table-column prop="price" label="Costo" width="140">
                        <template #default="{ row }">
                            <span class="font-bold text-gray-800">${{ formatCurrency(row.price) }}</span>
                        </template>
                    </el-table-column>
                    
                    <el-table-column prop="created_at" label="Registrado" width="140" />
                    
                    <el-table-column align="right" width="80" fixed="right">
                        <template #default="{ row }">
                            <el-dropdown trigger="click" @command="handleCommand" placement="bottom-end">
                                <button @click.stop class="flex items-center justify-center w-8 h-8 rounded-full text-gray-500 hover:bg-gray-100 transition-colors focus:outline-none">
                                    <el-icon><MoreFilled /></el-icon>
                                </button>
                                <template #dropdown>
                                    <el-dropdown-menu class="one-ui-dropdown">
                                        <el-dropdown-item :command="'show-' + row.id">
                                            <el-icon><View /></el-icon> Ver detalles
                                        </el-dropdown-item>
                                        <el-dropdown-item :command="'edit-' + row.id">
                                            <el-icon><EditPen /></el-icon> Editar
                                        </el-dropdown-item>
                                        
                                        <el-dropdown-item divided disabled class="!text-[10px] !font-bold !text-gray-400 uppercase tracking-wider">
                                            Cambiar Estado
                                        </el-dropdown-item>
                                        <el-dropdown-item :command="'pendent-' + row.id" class="!text-amber-600">
                                            <el-icon><Clock /></el-icon> Marcar Pendiente
                                        </el-dropdown-item>
                                        <el-dropdown-item :command="'authorized-' + row.id" class="!text-green-600">
                                            <el-icon><Check /></el-icon> Marcar Autorizada
                                        </el-dropdown-item>
                                        <el-dropdown-item :command="'rejected-' + row.id" class="!text-red-600">
                                            <el-icon><Close /></el-icon> Marcar Rechazada
                                        </el-dropdown-item>
                                    </el-dropdown-menu>
                                </template>
                            </el-dropdown>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from 'axios';
import { ElNotification } from 'element-plus';

// Iconos de Element Plus
import { 
    Document,
    Plus,
    Delete,
    Clock,
    Check,
    Close,
    MoreFilled,
    View,
    EditPen
} from '@element-plus/icons-vue';

// Props
const props = defineProps({
    quotes: {
        type: Object,
        required: true,
        default: () => ({ data: [] })
    }
});

// Estado Reactivo Local
const localQuotes = ref([...props.quotes.data]);
const selectedQuotes = ref([]);
const multipleTableRef = ref(null);

// Computed
const disableMassiveActions = computed(() => selectedQuotes.value.length === 0);

// Utilidades
const formatCurrency = (amount) => {
    return Number(amount || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

// Acciones de Tabla
const handleSelectionChange = (val) => {
    selectedQuotes.value = val;
};

const handleRowClick = (row) => {
    router.get(route('quotes.show', row.id || row));
};

const handlePagination = (page) => {
    // Si tu backend maneja paginación estándar, esto navega con el parámetro de página.
    router.get(route('quotes.index', { page }));
};

// Acciones de Dropdown
const handleCommand = (command) => {
    const [commandName, rowId] = command.split('-');

    if (['pendent', 'authorized', 'rejected'].includes(commandName)) {
        updateStatus(commandName, rowId);
    } else {
        router.get(route(`quotes.${commandName}`, rowId));
    }
};

// Acciones HTTP
const deleteSelections = async () => {
    try {
        const indexesToDelete = selectedQuotes.value.map(item => item.id);
        const response = await axios.post(route('quotes.massive-delete', {
            quotes: selectedQuotes.value // Tu código enviaba los objetos completos, lo mantenemos igual
        }));

        if (response.status === 200) {
            // Filtrar reactivamente la lista local
            localQuotes.value = localQuotes.value.filter(item => !indexesToDelete.includes(item.id));
            multipleTableRef.value.clearSelection();

            ElNotification({
                title: 'Éxito',
                message: response.data.message || 'Cotizaciones eliminadas exitosamente.',
                type: 'success'
            });
        } else {
            ElNotification({
                title: 'Algo salió mal',
                message: response.data.message,
                type: 'error'
            });
        }
    } catch (err) {
        ElNotification({
            title: 'Error del sistema',
            message: err.message || 'Ocurrió un error al procesar la solicitud.',
            type: 'error'
        });
        console.error(err);
    }
};

const updateStatus = async (statusCommand, quoteId) => {
    try {
        const response = await axios.put(route('quotes.update-status', quoteId), { status: statusCommand });
        
        if (response.status === 200) {
            // Actualizar reactivamente el estado en la lista local
            const indexQuote = localQuotes.value.findIndex(item => item.id == quoteId);
            
            if (indexQuote !== -1) {
                // Asumimos que el backend devuelve el string de estado final en response.data.item
                localQuotes.value[indexQuote].status = response.data.item;
                
                ElNotification({
                    title: 'Estado actualizado',
                    message: 'El estado de la cotización se cambió exitosamente.',
                    type: 'success'
                });
            }
        }
    } catch (error) {
        ElNotification({
            title: 'Error',
            message: 'No se pudo actualizar el estado.',
            type: 'error'
        });
        console.error(error);
    }
};
</script>

<style scoped>
/* Estilos One UI para la Tabla de Element Plus */
:deep(.one-ui-table) {
    --el-table-border-color: transparent;
    --el-table-header-bg-color: #f8fafc;
    --el-table-header-text-color: #64748b;
    --el-table-row-hover-bg-color: #f1f5f9;
    border-radius: 16px;
    overflow: hidden;
}

/* Encabezados de la tabla */
:deep(.el-table__header th) {
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding: 16px 0;
    border-bottom: 1px solid #f1f5f9 !important;
}

/* Celdas de la tabla */
:deep(.el-table__body td) {
    padding: 16px 0;
    border-bottom: 1px solid #f8fafc !important;
    transition: background-color 0.2s ease;
}

/* Quitar la línea inferior por defecto */
:deep(.el-table__inner-wrapper::before) {
    display: none;
}

/* Botones con estilo píldora */
.one-ui-btn {
    font-weight: 700;
    letter-spacing: 0.02em;
    transition: all 0.2s ease;
}

/* Ajuste sutil de los checkbox */
:deep(.el-checkbox__inner) {
    border-radius: 6px;
    width: 18px;
    height: 18px;
}
:deep(.el-checkbox__inner::after) {
    height: 9px;
    left: 6px;
    top: 2px;
}

/* Dropdown One UI */
:deep(.one-ui-dropdown) {
    border-radius: 16px !important;
    padding: 8px !important;
}
:deep(.one-ui-dropdown .el-dropdown-menu__item) {
    border-radius: 10px;
    margin-bottom: 2px;
    font-weight: 600;
}
:deep(.one-ui-dropdown .el-dropdown-menu__item:hover) {
    background-color: #f1f5f9;
}
</style>