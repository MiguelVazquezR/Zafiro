<template>
    <AppLayout title="Fraccionamientos">
        
        <!-- Contenedor Principal (Estilo One UI) -->
        <div class="max-w-7xl mx-auto space-y-6 px-2 sm:px-0">
            
            <!-- Encabezado de la página -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-indigo-100 text-indigo-600 rounded-2xl">
                        <el-icon size="24"><MapLocation /></el-icon>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Fraccionamientos</h2>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">Gestión de desarrollos y lotificaciones</p>
                    </div>
                </div>
                
                <el-button 
                    @click="router.get(route('subdivisions.create'))" 
                    type="primary" 
                    class="one-ui-btn !rounded-xl !border-none !bg-indigo-600 !text-white hover:!bg-indigo-700 shadow-md">
                    <el-icon class="mr-2"><Plus /></el-icon>
                    Nuevo Fraccionamiento
                </el-button>
            </div>

            <!-- Grid de Tarjetas -->
            <div v-if="localSubdivisions.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <SubdivisionCard 
                    v-for="subdivision in localSubdivisions" 
                    :key="subdivision.id" 
                    :subdivision="subdivision" 
                    @delete-subdivision="deleteSubdivision" 
                />
            </div>

            <!-- Estado Vacío (Empty State) -->
            <div v-else class="bg-white rounded-[24px] shadow-sm border border-gray-100 !p-12 text-center flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mb-4">
                    <el-icon size="40"><MapLocation /></el-icon>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No hay fraccionamientos</h3>
                <p class="text-gray-500 max-w-sm mx-auto mb-6">Aún no has registrado ningún desarrollo o fraccionamiento. Comienza agregando el primero.</p>
                <el-button @click="router.get(route('subdivisions.create'))" type="primary" class="!rounded-xl !bg-indigo-600 hover:!bg-indigo-700 !border-none font-bold shadow-md">
                    Crear mi primer fraccionamiento
                </el-button>
            </div>

        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import SubdivisionCard from "./Partials/SubdivisionCard.vue";

// Iconos de Element Plus
import { MapLocation, Plus } from '@element-plus/icons-vue';

// Props
const props = defineProps({
    subdivisions: {
        type: Object,
        required: true,
        default: () => ({ data: [] })
    }
});

// Estado Reactivo Local
const localSubdivisions = ref([...props.subdivisions.data]);

// Métodos
const deleteSubdivision = (subdivisionId) => {
    // Filtramos el arreglo local para remover el fraccionamiento eliminado (Reactividad segura en Vue 3)
    localSubdivisions.value = localSubdivisions.value.filter(sub => sub.id !== subdivisionId);
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