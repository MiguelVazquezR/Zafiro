<template>
    <AppLayout title="Editar fraccionamiento">
        <div class="max-w-6xl mx-auto space-y-6 px-2 sm:px-0">
            
            <!-- Encabezado de la página -->
            <div class="flex items-center gap-4 mb-8">
                <el-button @click="router.get(route('subdivisions.index'))" circle class="!border-none !bg-gray-100 hover:!bg-gray-200">
                    <el-icon><ArrowLeft /></el-icon>
                </el-button>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
                        Editar fraccionamiento <span class="text-indigo-600">"{{ subdivision.name }}"</span>
                    </h2>
                    <p class="text-sm text-gray-500 font-medium mt-0.5">Modifica características, imágenes o planos del desarrollo</p>
                </div>
            </div>

            <!-- Formulario Reutilizable -->
            <SubdivisionForm :form="form" :isEdit="true" :subdivision="subdivision" @submit="update">
                <template #actions>
                    <el-button @click="router.get(route('subdivisions.index'))" class="!rounded-xl !border-none !bg-gray-100 hover:!bg-gray-200 !text-gray-700 font-bold px-6">
                        Cancelar
                    </el-button>
                    <el-button type="primary" native-type="submit" :loading="form.processing" class="!rounded-xl !bg-indigo-600 hover:!bg-indigo-700 !border-none font-bold px-8 shadow-md">
                        Guardar Cambios
                    </el-button>
                </template>
            </SubdivisionForm>

        </div>
    </AppLayout>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import SubdivisionForm from './Partials/SubdivisionForm.vue';
import { ElNotification } from 'element-plus';
import { ArrowLeft } from '@element-plus/icons-vue';

const props = defineProps({
    subdivision: {
        type: Object,
        required: true
    }
});

const form = useForm({
    name: props.subdivision.name,
    type: props.subdivision.type,
    batches_availables: props.subdivision.batches_availables,
    lowest_price: props.subdivision.lowest_price,
    lowest_surface: props.subdivision.lowest_surface,
    address: props.subdivision.address,
    lat: props.subdivision.lat,
    lon: props.subdivision.lon,
    description: props.subdivision.description,
    amenities: props.subdivision.amenities || [],
    soon: Boolean(props.subdivision.soon),
    
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
        form.post(route("subdivisions.update-with-media", props.subdivision.id), {
            onSuccess: () => {
                ElNotification({
                    title: "Correcto",
                    message: "Se ha editado el fraccionamiento exitosamente",
                    type: "success",
                });
            },
        });
    } else {
        // En caso contrario, actualización regular tipo PUT
        form.put(route("subdivisions.update", props.subdivision.id), {
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