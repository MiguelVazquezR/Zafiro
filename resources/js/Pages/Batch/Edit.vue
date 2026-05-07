<template>
    <AppLayout title="Editar lote">
        <div class="max-w-6xl mx-auto space-y-6 px-2 sm:px-0">
            
            <!-- Encabezado de la página -->
            <div class="flex items-center gap-4 mb-8">
                <el-button @click="router.get(route('batches.index'))" circle class="!border-none !bg-gray-100 hover:!bg-gray-200">
                    <el-icon><ArrowLeft /></el-icon>
                </el-button>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                        Editar Lote <span class="text-indigo-600">"{{ batch.name }}"</span>
                    </h2>
                    <p class="text-sm text-gray-500 font-medium mt-0.5">Actualiza dimensiones, precios o estado del terreno</p>
                </div>
            </div>

            <!-- Formulario Reutilizable -->
            <BatchForm :form="form" :isEdit="true" :subdivisions="subdivisions" :batch="batch" @submit="update">
                <template #actions>
                    <el-button @click="router.get(route('batches.index'))" class="!rounded-xl !border-none !bg-gray-100 hover:!bg-gray-200 !text-gray-700 font-bold px-6">
                        Cancelar
                    </el-button>
                    <el-button type="primary" native-type="submit" :loading="form.processing" class="!rounded-xl !bg-indigo-600 hover:!bg-indigo-700 !border-none font-bold px-8 shadow-md">
                        Guardar Cambios
                    </el-button>
                </template>
            </BatchForm>

        </div>
    </AppLayout>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import BatchForm from './Partials/BatchForm.vue';
import { ElNotification } from 'element-plus';
import { ArrowLeft } from '@element-plus/icons-vue';

const props = defineProps({
    subdivisions: {
        type: Array,
        required: true
    },
    batch: {
        type: Object,
        required: true
    }
});

const form = useForm({
    name: props.batch.name,
    subdivision_id: props.batch.subdivision_id,
    price: props.batch.price,
    surface: props.batch.surface,
    address: props.batch.address,
    lon: props.batch.lon,
    lat: props.batch.lat,
    description: props.batch.description,
    
    // Multimedia
    image_cover1: null,
    image_cover2: null,
    image_cover3: null,
    image_cover4: null,
    image_cover5: null,
    image_cover6: null,
    image_cover7: null,
    image_cover8: null,
    cleared_cover1: false,
    cleared_cover2: false,
    cleared_cover3: false,
    cleared_cover4: false,
    cleared_cover5: false,
    cleared_cover6: false,
    cleared_cover7: false,
    cleared_cover8: false,
    planos: null,
});

const update = () => {
    // Si hay un archivo nuevo (Planos o Portada principal), forzamos post con media.
    if (form.planos !== null || form.image_cover1 !== null) {
        form.post(route("batches.update-with-media", props.batch.id), {
            onSuccess: () => {
                ElNotification({
                    title: "Correcto",
                    message: "Se ha actualizado el lote exitosamente",
                    type: "success",
                });
            },
        });
    } else {
        // Actualización normal tipo PUT
        form.put(route("batches.update", props.batch.id), {
            onSuccess: () => {
                ElNotification({
                    title: "Correcto",
                    message: "Se han guardado los cambios exitosamente",
                    type: "success",
                });
            },
        });
    }
};
</script>