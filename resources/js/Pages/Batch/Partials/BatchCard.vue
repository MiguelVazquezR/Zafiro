<template>
    <div @click="router.get(route('batches.show', batch.id))"
         class="group bg-white rounded-[24px] border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 h-[240px] flex overflow-hidden cursor-pointer">

        <!-- Galería de Imágenes (Lado Izquierdo) -->
        <figure class="w-2/5 md:w-1/3 bg-slate-100 relative shrink-0 overflow-hidden">
            <img v-if="batch.images?.length"
                 class="object-cover h-full w-full transition-transform duration-500 group-hover:scale-105"
                 :src="batch.images[currentImage]?.original_url"
                 alt="Imagen del lote">
            
            <div v-else class="flex items-center justify-center bg-slate-50 h-full w-full">
                <el-icon size="40" class="text-gray-300"><Picture /></el-icon>
            </div>

            <!-- Botones de Acción (Editar / Eliminar) -->
            <div v-if="$page.props.auth?.user?.id" class="absolute top-3 left-3 flex flex-col gap-2 z-20">
                <button @click.stop="router.get(route('batches.edit', batch.id))"
                        class="w-8 h-8 rounded-full bg-white/80 backdrop-blur-md flex items-center justify-center text-gray-700 hover:bg-white hover:text-purple-600 shadow-sm transition-all">
                    <el-icon><Edit /></el-icon>
                </button>
                <el-popconfirm confirm-button-text="Sí" cancel-button-text="No" confirm-button-type="danger" title="¿Eliminar lote?" @confirm="deleteItem">
                    <template #reference>
                        <button @click.stop class="w-8 h-8 rounded-full bg-white/80 backdrop-blur-md flex items-center justify-center text-gray-700 hover:bg-white hover:text-red-600 shadow-sm transition-all">
                            <el-icon><Delete /></el-icon>
                        </button>
                    </template>
                </el-popconfirm>
            </div>

            <!-- Controles para cambiar imagen (Visibles al hover) -->
            <div v-if="batch.images?.length > 1"
                 class="absolute inset-y-0 w-full flex justify-between items-center px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                <button @click.stop="handleMinusImage" class="w-7 h-7 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white hover:bg-black/60 transition-colors">
                    <el-icon><ArrowLeft /></el-icon>
                </button>
                <button @click.stop="handlePlusImage" class="w-7 h-7 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white hover:bg-black/60 transition-colors">
                    <el-icon><ArrowRight /></el-icon>
                </button>
            </div>
        </figure>

        <!-- Información del Lote (Lado Derecho) -->
        <div class="p-5 flex flex-col flex-1 min-w-0">
            
            <div class="flex justify-between items-start gap-2 mb-2">
                <span class="shrink-0 px-2.5 py-1 bg-purple-50 text-purple-600 text-[10px] font-bold uppercase tracking-wider rounded-lg">
                    Venta de Lote
                </span>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider text-right truncate">
                    {{ batch.subdivision?.name }}
                </p>
            </div>

            <div class="mt-auto">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Precio de Venta</p>
                <p class="text-2xl lg:text-3xl font-black text-gray-900 leading-none mb-3">${{ formatNumber(batch.price) }}</p>

                <div class="space-y-1.5 text-sm font-medium text-gray-500">
                    <p v-if="batch.subdivision?.type" class="flex items-center gap-2">
                        <el-icon class="text-gray-400 shrink-0"><Menu /></el-icon> Lotes {{ batch.subdivision?.type }}
                    </p>
                    <p class="flex items-center gap-2 truncate" :title="batch.address">
                        <el-icon class="text-gray-400 shrink-0"><Location /></el-icon>
                        <span class="truncate">{{ batch.address }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <el-icon class="text-gray-400 shrink-0"><Crop /></el-icon> Superficie: {{ batch.surface }} m²
                    </p>
                </div>
            </div>
        </div>
    </div>
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
    Picture,
    Menu
} from '@element-plus/icons-vue';

const props = defineProps({
    batch: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['delete-batch']);

const currentImage = ref(0);

// Formateador robusto
const formatNumber = (value) => {
    const num = value?.numberFormat ?? value;
    if (!num) return '0.00';
    if (typeof num === 'string' && num.includes(',')) return num; 
    return Number(num).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

// Acciones
const deleteItem = async () => {
    try {
        const response = await axios.delete(route('batches.destroy', props.batch.id));
        if (response.status === 200) {
            ElNotification({
                title: "Correcto",
                message: "Se ha eliminado el lote exitosamente",
                type: "success",
            });
            emit('delete-batch', props.batch.id);
        }
    } catch (error) {
        console.error(error);
        ElNotification({
            title: "Hubo un problema",
            message: "No se pudo eliminar el lote",
            type: "error",
        });
    }
};

// Paginación de Imágenes
const handlePlusImage = () => {
    if (!props.batch.images?.length) return;
    currentImage.value = currentImage.value === (props.batch.images.length - 1) ? 0 : currentImage.value + 1;
};

const handleMinusImage = () => {
    if (!props.batch.images?.length) return;
    currentImage.value = currentImage.value === 0 ? props.batch.images.length - 1 : currentImage.value - 1;
};
</script>