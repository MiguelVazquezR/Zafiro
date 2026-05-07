<template>
    <!-- ESTADO: FRACCIONAMIENTO DISPONIBLE -->
    <section v-if="!subdivision.soon" @click="router.get(route('subdivisions.show', subdivision.id))"
        class="group bg-white rounded-[24px] border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 h-[420px] relative cursor-pointer overflow-hidden flex flex-col">
        
        <!-- Galería de Imágenes -->
        <figure class="h-1/2 bg-gray-100 relative overflow-hidden">
            <img v-if="subdivision.images?.length" 
                 class="object-cover h-full w-full transition-transform duration-500 group-hover:scale-105"
                 :src="subdivision.images[currentImage]?.original_url" 
                 alt="Portada de fraccionamiento">
            
            <div v-else class="flex items-center justify-center bg-slate-50 h-full w-full">
                <el-icon size="48" class="text-gray-300"><Picture /></el-icon>
            </div>
            
            <!-- Controles para cambiar imagen (Visibles solo al hacer hover) -->
            <div v-if="subdivision.images?.length > 1" 
                 class="absolute inset-0 flex justify-between items-center px-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <button @click.stop="handleMinusImage" class="w-8 h-8 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white hover:bg-black/60 transition-colors">
                    <el-icon><ArrowLeft /></el-icon>
                </button>
                <button @click.stop="handlePlusImage" class="w-8 h-8 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white hover:bg-black/60 transition-colors">
                    <el-icon><ArrowRight /></el-icon>
                </button>
            </div>

            <!-- Botones de Acción (Editar / Eliminar) -->
            <div v-if="$page.props.auth?.user?.id" class="absolute top-3 right-3 flex items-center gap-2">
                <button @click.stop="router.get(route('subdivisions.edit', subdivision.id))" 
                        class="w-8 h-8 rounded-full bg-white/80 backdrop-blur-md flex items-center justify-center text-gray-700 hover:bg-white hover:text-indigo-600 shadow-sm transition-all">
                    <el-icon><Edit /></el-icon>
                </button>
                
                <el-popconfirm confirm-button-text="Sí" cancel-button-text="No" confirm-button-type="danger" title="¿Eliminar desarrollo?" @confirm="deleteItem">
                    <template #reference>
                        <button @click.stop class="w-8 h-8 rounded-full bg-white/80 backdrop-blur-md flex items-center justify-center text-gray-700 hover:bg-white hover:text-red-600 shadow-sm transition-all">
                            <el-icon><Delete /></el-icon>
                        </button>
                    </template>
                </el-popconfirm>
            </div>
        </figure>

        <!-- Información del Fraccionamiento -->
        <div class="p-5 flex flex-col flex-1">
            <div class="flex justify-between items-start mb-2 gap-2">
                <h3 class="text-base font-bold text-gray-900 leading-tight line-clamp-2">{{ subdivision.name }}</h3>
                <span class="shrink-0 px-2.5 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider rounded-lg">
                    Lotes {{ subdivision.type }}
                </span>
            </div>

            <div class="mt-auto">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Lotes desde</p>
                <p class="text-2xl font-black text-gray-900 leading-none mb-3">${{ formatNumber(subdivision.lowest_price) }}</p>

                <div class="space-y-1.5 text-sm font-medium text-gray-500">
                    <p class="flex items-center gap-2 truncate" :title="subdivision.address">
                        <el-icon class="text-gray-400 shrink-0"><Location /></el-icon> 
                        <span class="truncate">{{ subdivision.address }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <el-icon class="text-gray-400 shrink-0"><Crop /></el-icon> Desde {{ subdivision.lowest_surface }} m²
                    </p>
                    <p class="flex items-center gap-2">
                        <el-icon class="text-gray-400 shrink-0"><Grid /></el-icon> {{ subdivision.batches_availables }} disponibles
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ESTADO: PRÓXIMAMENTE -->
    <section v-else 
        class="group rounded-[24px] shadow-sm hover:shadow-md transition-all duration-300 h-[420px] cursor-default overflow-hidden relative bg-slate-900 text-white flex flex-col items-center justify-center text-center p-6 border border-slate-800">
        
        <!-- Fondo Decorativo -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/40 to-slate-900 opacity-50"></div>
        
        <div class="relative z-10 flex flex-col items-center">
            <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6">
                <el-icon size="32" class="text-indigo-400"><Clock /></el-icon>
            </div>
            <span class="px-3 py-1 bg-indigo-500/20 text-indigo-300 text-[10px] font-bold uppercase tracking-widest rounded-full mb-4 border border-indigo-500/30">
                Próximamente
            </span>
            <h3 class="text-2xl font-black mb-2">{{ subdivision.name }}</h3>
            <p class="text-sm font-medium text-slate-400 max-w-[220px]">
                Espera los detalles exclusivos sobre estos terrenos muy pronto.
            </p>
        </div>

        <!-- Botones de Acción para Próximamente -->
        <div v-if="$page.props.auth?.user?.id" class="absolute top-3 right-3 flex items-center gap-2 z-20">
            <button @click.stop="router.get(route('subdivisions.edit', subdivision.id))" class="w-8 h-8 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white hover:bg-white hover:text-indigo-600 transition-all">
                <el-icon><Edit /></el-icon>
            </button>
            <el-popconfirm confirm-button-text="Sí" cancel-button-text="No" confirm-button-type="danger" title="¿Eliminar desarrollo?" @confirm="deleteItem">
                <template #reference>
                    <button @click.stop class="w-8 h-8 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white hover:bg-white hover:text-red-600 transition-all">
                        <el-icon><Delete /></el-icon>
                    </button>
                </template>
            </el-popconfirm>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { ElNotification } from 'element-plus';

// Iconos de Element Plus
import { 
    ArrowLeft, 
    ArrowRight, 
    Edit, 
    Delete, 
    Location, 
    Crop, 
    Grid, 
    Picture,
    Clock
} from '@element-plus/icons-vue';

const props = defineProps({
    subdivision: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['delete-subdivision']);

// Estado local reactivo
const currentImage = ref(0);

// Formateador robusto por si envían numberFormat desde backend o número crudo
const formatNumber = (value) => {
    const num = value?.numberFormat ?? value;
    if (!num) return '0.00';
    if (typeof num === 'string' && num.includes(',')) return num; // Ya viene formateado
    return Number(num).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

// Acciones
const deleteItem = async () => {
    try {
        const response = await axios.delete(route('subdivisions.destroy', props.subdivision.id));
        if (response.status === 200) {
            ElNotification({
                title: "Correcto",
                message: "Se ha eliminado el fraccionamiento",
                type: "success",
            });
            emit('delete-subdivision', props.subdivision.id);
        }
    } catch (error) {
        console.error(error);
        ElNotification({
            title: "Hubo un problema",
            message: "No se pudo eliminar el fraccionamiento",
            type: "error",
        });
    }
};

// Paginación de Imágenes
const handlePlusImage = () => {
    if (!props.subdivision.images?.length) return;
    currentImage.value = currentImage.value === (props.subdivision.images.length - 1) ? 0 : currentImage.value + 1;
};

const handleMinusImage = () => {
    if (!props.subdivision.images?.length) return;
    currentImage.value = currentImage.value === 0 ? props.subdivision.images.length - 1 : currentImage.value - 1;
};
</script>