<template>
    <AppLayout title="Nueva cotización">
        <div class="max-w-4xl mx-auto space-y-6">
            
            <!-- Encabezado de la página -->
            <div class="flex items-center gap-4 mb-8 px-2">
                <el-button @click="router.get(route('quotes.index'))" circle class="!border-none !bg-gray-100 hover:!bg-gray-200">
                    <el-icon><ArrowLeft /></el-icon>
                </el-button>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Crear cotización</h2>
                    <p class="text-sm text-gray-500 font-medium mt-0.5">Define los costos y servicios para un cliente nuevo</p>
                </div>
            </div>

            <!-- Formulario Reutilizable -->
            <QuoteForm :form="form" @submit="store">
                <template #actions>
                    <el-button @click="router.get(route('quotes.index'))" class="!rounded-xl !border-none !bg-gray-100 hover:!bg-gray-200 !text-gray-700 font-bold px-6">
                        Cancelar
                    </el-button>
                    <el-button type="primary" native-type="submit" :loading="form.processing" class="!rounded-xl !bg-indigo-600 hover:!bg-indigo-700 !border-none font-bold px-8 shadow-md">
                        Crear Cotización
                    </el-button>
                </template>
            </QuoteForm>

        </div>
    </AppLayout>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import QuoteForm from './Partials/QuoteForm.vue';
import { ElNotification } from 'element-plus';
import { ArrowLeft } from '@element-plus/icons-vue';

const form = useForm({
    client: null,
    name: null,
    description: null,
    price: null,
    payment_conditions: null,
});

const store = () => {
    form.post(route('quotes.store'), {
        onSuccess: () => {
            ElNotification({
                title: 'Correcto',
                message: 'Se ha creado la cotización exitosamente',
                type: 'success',
            });
        },
    });
};
</script>