<template>
    <AppLayout title="Mensajería">
        
        <!-- Contenedor Principal (Estilo One UI) -->
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Encabezado de la página -->
            <div class="flex items-center gap-3 mb-8 px-2">
                <div class="p-3 bg-indigo-100 text-indigo-600 rounded-2xl">
                    <el-icon size="24"><Message /></el-icon>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Buzón de entrada</h2>
                    <p class="text-sm text-gray-500 font-medium mt-0.5">Gestiona las solicitudes y mensajes de los clientes</p>
                </div>
            </div>

            <!-- Tarjeta Principal Blanca -->
            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden p-6 transition-all">
                
                <!-- Barra de Acciones (Botones) -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div class="text-sm font-semibold text-gray-500">
                        <span v-if="selectedMessages.length > 0" class="text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                            {{ selectedMessages.length }} seleccionado(s)
                        </span>
                        <span v-else>Selecciona mensajes para gestionar</span>
                    </div>
                    
                    <div class="flex space-x-3 w-full sm:w-auto">
                        <el-button 
                            @click="markAsDispatched" 
                            type="primary" 
                            class="one-ui-btn !rounded-xl !border-none !bg-indigo-50 !text-indigo-700 hover:!bg-indigo-100" 
                            :disabled="disableMassiveActions">
                            <el-icon class="mr-2"><Check /></el-icon>
                            Marcar como atendidos
                        </el-button>
                        
                        <el-popconfirm 
                            confirm-button-text="Sí, eliminar" 
                            cancel-button-text="Cancelar" 
                            confirm-button-type="danger"
                            icon-color="#f56c6c"
                            title="¿Eliminar mensajes seleccionados?" 
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
                    :data="localMessages" 
                    max-height="600" 
                    style="width: 100%" 
                    @selection-change="handleSelectionChange"
                    ref="multipleTableRef" 
                    :row-class-name="tableRowClassName"
                    class="one-ui-table"
                >
                    <el-table-column type="selection" width="55" align="center" />
                    <el-table-column prop="name" label="Nombre" width="180">
                        <template #default="{ row }">
                            <span class="font-bold text-gray-800">{{ row.name }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column prop="address" label="Dirección" width="200" show-overflow-tooltip />
                    <el-table-column prop="service" label="Servicio">
                        <template #default="{ row }">
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-lg font-semibold">{{ row.service }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column prop="phone" label="Teléfono" width="140" />
                    <el-table-column prop="message" label="Mensaje" min-width="250" show-overflow-tooltip />
                    <el-table-column prop="created_at" label="Enviado el" width="160" />
                </el-table>
            </div>
            
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from 'axios';
import { useToast } from "vue-toastification";

// Iconos de Element Plus
import { 
    Message, 
    Check, 
    Delete 
} from '@element-plus/icons-vue';

// Props
const props = defineProps({
    messages: {
        type: Array,
        default: () => []
    },
});

// Referencias locales para evitar mutar las props directamente (Vue 3 warning)
const localMessages = ref([...props.messages]);
const selectedMessages = ref([]);
const multipleTableRef = ref(null);
const toast = useToast();

// Computed para deshabilitar botones si no hay selección
const disableMassiveActions = computed(() => selectedMessages.value.length === 0);

// Métodos
const handleSelectionChange = (val) => {
    selectedMessages.value = val;
};

const markAsDispatched = async () => {
    try {
        const response = await axios.post(route('messages.mark-as-dispatched', {
            messages: selectedMessages.value
        }));

        if (response.status === 200) {
            toast.success(response.data.message);

            // Actualizar el estado de los elementos seleccionados localmente
            selectedMessages.value.forEach(selectedElement => {
                const index = localMessages.value.findIndex(m => m.id === selectedElement.id);
                if (index !== -1) {
                    localMessages.value[index].status = 1;
                }
            });

            // Limpiar selección
            multipleTableRef.value.clearSelection();
        } else {
            toast.error(response.data.message);
        }

    } catch (err) {
        toast.error('Ocurrió un error al procesar la solicitud.');
        console.error(err);
    }
};

const deleteSelections = async () => {
    try {
        const response = await axios.post(route('messages.massive-delete', {
            messages: selectedMessages.value
        }));

        if (response.status === 200) {
            toast.success(response.data.message);

            // Filtrar y actualizar la lista local para remover los eliminados
            const idsToDelete = selectedMessages.value.map(msg => msg.id);
            localMessages.value = localMessages.value.filter(msg => !idsToDelete.includes(msg.id));
            
            // Limpiar selección
            multipleTableRef.value.clearSelection();
        } else {
            toast.error(response.data.message);
        }

    } catch (err) {
        toast.error('Ocurrió un error al eliminar los registros.');
        console.error(err);
    }
};

// Clases personalizadas para filas (Ej. resaltado verde si ya se atendió)
const tableRowClassName = ({ row }) => {
    if (row.status === 1) {
        return 'is-dispatched-row';
    }
    return '';
};

// Hook de montaje
onMounted(() => {
    // Inicialización si es requerida (el toast ya está instanciado globalmente)
});
</script>

<style scoped>
/* Estilos One UI para la Tabla de Element Plus */
:deep(.one-ui-table) {
    --el-table-border-color: transparent;
    --el-table-header-bg-color: #f8fafc; /* Gris azulado muy claro */
    --el-table-header-text-color: #64748b;
    --el-table-row-hover-bg-color: #f1f5f9;
    border-radius: 16px;
    overflow: hidden;
}

/* Encabezados de la tabla más limpios */
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

/* Quitar la línea inferior por defecto de Element Plus */
:deep(.el-table__inner-wrapper::before) {
    display: none;
}

/* Fila marcada como despachada (atendida) */
:deep(.el-table .is-dispatched-row td) {
    background-color: #f0fdf4 !important; /* Verde muy claro (Tailwind green-50) */
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