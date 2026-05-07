<template>
    <AppLayout title="Nuevo fraccionamiento">
        <div class="max-w-6xl mx-auto space-y-6 px-2 sm:px-0">
            
            <!-- Encabezado de la página -->
            <div class="flex items-center gap-4 mb-8">
                <el-button @click="router.get(route('subdivisions.index'))" circle class="!border-none !bg-gray-100 hover:!bg-gray-200">
                    <el-icon><ArrowLeft /></el-icon>
                </el-button>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Agregar fraccionamiento</h2>
                    <p class="text-sm text-gray-500 font-medium mt-0.5">Define las características del nuevo desarrollo</p>
                </div>
            </div>

            <!-- Formulario Reutilizable -->
            <SubdivisionForm :form="form" @submit="store">
                <template #actions>
                    <el-button @click="router.get(route('subdivisions.index'))" class="!rounded-xl !border-none !bg-gray-100 hover:!bg-gray-200 !text-gray-700 font-bold px-6">
                        Cancelar
                    </el-button>
                    <el-button type="primary" native-type="submit" :loading="form.processing" class="!rounded-xl !bg-indigo-600 hover:!bg-indigo-700 !border-none font-bold px-8 shadow-md">
                        Publicar Desarrollo
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

const form = useForm({
    name: null,
    type: null,
    batches_availables: null,
    lowest_price: null,
    lowest_surface: null,
    address: null,
    lat: null,
    lon: null,
    description: null,
    amenities: [],
    image_cover1: null,
    image_cover2: null,
    image_cover3: null,
    image_cover4: null,
    image_cover5: null,
    image_cover6: null,
    image_cover7: null,
    image_cover8: null,
    planos: null,
    soon: false,
});

const store = () => {
    form.post(route("subdivisions.store"), {
        onSuccess: () => {
            ElNotification({
                title: "Correcto",
                message: "Se ha agregado un nuevo fraccionamiento",
                type: "success",
            });
        },
    });
};
</script>