<template>
    <AppLayout title="Nuevo lote">
        <div class="max-w-6xl mx-auto space-y-6 px-2 sm:px-0">
            
            <!-- Encabezado de la página -->
            <div class="flex items-center gap-4 mb-8">
                <el-button @click="router.get(route('batches.index'))" circle class="!border-none !bg-gray-100 hover:!bg-gray-200">
                    <el-icon><ArrowLeft /></el-icon>
                </el-button>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Agregar Lote</h2>
                    <p class="text-sm text-gray-500 font-medium mt-0.5">Define las características del terreno para venta</p>
                </div>
            </div>

            <!-- Formulario Reutilizable -->
            <BatchForm :form="form" :subdivisions="subdivisions" @submit="store">
                <template #actions>
                    <el-button @click="router.get(route('batches.index'))" class="!rounded-xl !border-none !bg-gray-100 hover:!bg-gray-200 !text-gray-700 font-bold px-6">
                        Cancelar
                    </el-button>
                    <el-button type="primary" native-type="submit" :loading="form.processing" class="!rounded-xl !bg-indigo-600 hover:!bg-indigo-700 !border-none font-bold px-8 shadow-md">
                        Publicar Lote
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
    }
});

const form = useForm({
    name: null,
    subdivision_id: null,
    price: null,
    surface: null,
    address: null,
    lon: null,
    lat: null,
    description: null,
    image_cover1: null,
    image_cover2: null,
    image_cover3: null,
    image_cover4: null,
    image_cover5: null,
    image_cover6: null,
    image_cover7: null,
    image_cover8: null,
    planos: null,
});

const store = () => {
    form.post(route("batches.store"), {
        onSuccess: () => {
            ElNotification({
                title: "Correcto",
                message: "Se ha agregado un nuevo lote exitosamente",
                type: "success",
            });
        },
    });
};
</script>