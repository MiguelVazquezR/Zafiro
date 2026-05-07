<template>
    <AppLayout title="Lotes">
        
        <!-- Contenedor Principal (Estilo One UI) -->
        <div class="max-w-7xl mx-auto space-y-6 px-2 sm:px-0">
            
            <!-- Encabezado de la página -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-purple-100 text-purple-600 rounded-2xl">
                        <el-icon size="24"><Grid /></el-icon>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Lotes Registrados</h2>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">Gestión y disponibilidad de terrenos</p>
                    </div>
                </div>
                
                <el-button 
                    @click="router.get(route('batches.create'))" 
                    type="primary" 
                    class="one-ui-btn !rounded-xl !border-none !bg-purple-600 !text-white hover:!bg-purple-700 shadow-md">
                    <el-icon class="mr-2"><Plus /></el-icon>
                    Nuevo Lote
                </el-button>
            </div>

            <!-- Grid de Tarjetas -->
            <div v-if="localBatches.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <BatchCard 
                    v-for="batch in localBatches" 
                    :key="batch.id" 
                    :batch="batch" 
                    @delete-batch="deletebatch" 
                />
            </div>

            <!-- Estado Vacío (Empty State) -->
            <div v-else class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-12 text-center flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-purple-50 text-purple-300 rounded-full flex items-center justify-center mb-4">
                    <el-icon size="40"><Grid /></el-icon>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No hay lotes registrados</h3>
                <p class="text-gray-500 max-w-sm mx-auto mb-6">Aún no tienes lotes vinculados a ningún fraccionamiento. Comienza creando el primero.</p>
                <el-button @click="router.get(route('batches.create'))" type="primary" class="!rounded-xl !bg-purple-600 hover:!bg-purple-700 !border-none font-bold shadow-md">
                    Registrar un Lote
                </el-button>
            </div>

        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import BatchCard from "./Partials/BatchCard.vue";

// Iconos de Element Plus
import { Grid, Plus } from '@element-plus/icons-vue';

// Props
const props = defineProps({
    batches: {
        type: Object,
        required: true,
        default: () => ({ data: [] })
    }
});

// Estado Reactivo Local
const localBatches = ref([...props.batches.data]);

// Métodos
const deletebatch = (batchId) => {
    // Filtrado reactivo en Vue 3 para remover el lote eliminado
    localBatches.value = localBatches.value.filter(sub => sub.id !== batchId);
};
</script>

<style scoped>
/* Botones con estilo píldora */
.one-ui-btn {
    font-weight: 700;
    letter-spacing: 0.02em;
    transition: all 0.2s ease;
}
</style>