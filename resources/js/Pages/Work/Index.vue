<template>
    <AppLayout title="Trabajos">
        
        <!-- Contenedor Principal (Estilo One UI) -->
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Encabezado de la página -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 px-2">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-indigo-100 text-indigo-600 rounded-2xl">
                        <el-icon size="24"><Briefcase /></el-icon>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Trabajos</h2>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">Gestión y seguimiento de proyectos</p>
                    </div>
                </div>
                
                <el-button 
                    @click="router.get(route('works.create'))" 
                    type="primary" 
                    class="one-ui-btn !rounded-xl !border-none !bg-indigo-600 !text-white hover:!bg-indigo-700 shadow-md">
                    <el-icon class="mr-2"><Plus /></el-icon>
                    Nuevo Trabajo
                </el-button>
            </div>

            <!-- Tarjeta Principal Blanca -->
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden p-6 transition-all">
                
                <!-- Barra de Acciones (Botones) -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div class="text-sm font-semibold text-gray-500">
                        <span v-if="selectedWorks.length > 0" class="text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                            {{ selectedWorks.length }} seleccionado(s)
                        </span>
                        <span v-else>Selecciona trabajos para gestionar</span>
                    </div>
                    
                    <div class="flex space-x-3 w-full sm:w-auto">
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
                                    Eliminar seleccionados
                                </el-button>
                            </template>
                        </el-popconfirm>
                    </div>
                </div>

                <!-- Tabla de Element Plus (One UI Style) -->
                <el-table 
                    :data="localWorks" 
                    @row-click="handleRowClick" 
                    max-height="600" 
                    style="width: 100%"
                    @selection-change="handleSelectionChange" 
                    ref="multipleTableRef" 
                    :row-class-name="tableRowClassName"
                    class="one-ui-table cursor-pointer"
                >
                    <el-table-column type="selection" width="55" align="center" />
                    
                    <el-table-column prop="folio" label="Folio" width="110">
                        <template #default="{ row }">
                            <span class="font-mono bg-gray-100 px-2 py-1 rounded-md text-gray-700 text-xs">{{ row.folio }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column label="Pagos" width="200">
                        <template #default="{ row }">
                            <div class="flex items-center gap-2">
                                <el-tooltip v-if="workIsPaid(row)" content="Liquidado" placement="top" effect="light">
                                    <div class="bg-green-100 p-1.5 rounded-full flex items-center justify-center">
                                        <el-icon class="text-green-600 font-bold"><Check /></el-icon>
                                    </div>
                                </el-tooltip>
                                <el-tooltip v-else content="Esperando liquidación" placement="top" effect="light">
                                    <div class="bg-amber-100 p-1.5 rounded-full flex items-center justify-center">
                                        <el-icon class="text-amber-600 font-bold"><Clock /></el-icon>
                                    </div>
                                </el-tooltip>
                                
                                <div class="flex flex-col leading-tight">
                                    <span class="font-bold text-gray-800">${{ formatCurrency(calcTotalPaid(row)) }}</span>
                                    <span class="text-xs text-gray-500 font-medium">{{ row.payments?.length ?? 0 }} pagos</span>
                                </div>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column prop="price" label="Costo" width="120">
                        <template #default="{ row }">
                            <span class="text-gray-700 font-medium">${{ formatCurrency(row.price) }}</span>
                        </template>
                    </el-table-column>
                    
                    <el-table-column prop="town" label="Municipio" show-overflow-tooltip />
                    <el-table-column prop="ejido" label="Ejido" show-overflow-tooltip />
                    
                    <el-table-column prop="work_type" label="Trabajo" width="160" show-overflow-tooltip>
                        <template #default="{ row }">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs rounded-lg font-semibold">{{ row.work_type }}</span>
                        </template>
                    </el-table-column>
                    
                    <el-table-column prop="start_date" label="Inicio" width="120" />
                    <el-table-column prop="created_at" label="Registrado" width="120" />
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
    Briefcase,
    Plus,
    Delete,
    Check,
    Clock
} from '@element-plus/icons-vue';

// Props
const props = defineProps({
    works: {
        type: Object,
        default: () => ({ data: [] })
    },
});

// Estado Reactivo Local
const localWorks = ref([...props.works.data]);
const selectedWorks = ref([]);
const multipleTableRef = ref(null);

// Computed Properties
const disableMassiveActions = computed(() => selectedWorks.value.length === 0);

// Utilidades
const formatCurrency = (amount) => {
    return Number(amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

const calcTotalPaid = (work) => {
    if (!work.payments?.length) return 0;
    return work.payments.reduce((acc, item) => acc + Number(item.amount), 0);
};

const workIsPaid = (work) => {
    return calcTotalPaid(work) >= Number(work.raw_price);
};

// Acciones de Tabla
const handleSelectionChange = (val) => {
    selectedWorks.value = val;
};

const handleRowClick = (row) => {
    router.get(route('works.edit', row.id || row));
};

// Acciones HTTP
const deleteSelections = async () => {
    try {
        const indexesToDelete = selectedWorks.value.map(item => item.id);
        const response = await axios.post(route('works.massive-delete', {
            works: indexesToDelete
        }));

        if (response.status === 200) {
            // Filtrar y actualizar la lista local para remover los eliminados
            localWorks.value = localWorks.value.filter(element => !indexesToDelete.includes(element.id));
            
            // Limpiar selección
            multipleTableRef.value.clearSelection();

            ElNotification({
                title: "Correcto",
                message: "Trabajos eliminados exitosamente.",
                type: "success",
            });
        }
    } catch (err) {
        console.error(err);
        ElNotification({
            title: "Error",
            message: "Problema al eliminar el/los registro(s).",
            type: "error",
        });
    }
};

// Estilos de Fila
const tableRowClassName = ({ row }) => {
    if (row.status === 1) {
        return 'is-dispatched-row'; 
    }
    return '';
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

/* Fila marcada (status 1) */
:deep(.el-table .is-dispatched-row td) {
    background-color: #f0fdf4 !important; 
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
</style>