<template>
    <AppLayout title="Editar cotización">
        <div class="max-w-4xl mx-auto space-y-6">
            
            <!-- Encabezado de la página -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 px-2">
                <div class="flex items-center gap-4">
                    <el-button @click="router.get(route('quotes.index'))" circle class="!border-none !bg-gray-100 hover:!bg-gray-200">
                        <el-icon><ArrowLeft /></el-icon>
                    </el-button>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Editar cotización</h2>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">Modifica los detalles de este presupuesto</p>
                    </div>
                </div>
                
                <!-- Badge visualizando el estado actual -->
                <div>
                    <span v-if="quote.status === 'Esperando autorización del cliente'" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 shadow-sm">
                        <el-icon size="14"><Clock /></el-icon> Pendiente de autorización
                    </span>
                    <span v-else-if="quote.status === 'Autorizada'" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-700 shadow-sm">
                        <el-icon size="14"><Check /></el-icon> Cotización Autorizada
                    </span>
                    <span v-else-if="quote.status === 'Rechazada'" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700 shadow-sm">
                        <el-icon size="14"><Close /></el-icon> Cotización Rechazada
                    </span>
                </div>
            </div>

            <!-- Formulario Reutilizable -->
            <QuoteForm :form="form" @submit="update">
                <template #actions>
                    <el-button @click="router.get(route('quotes.index'))" class="!rounded-xl !border-none !bg-gray-100 hover:!bg-gray-200 !text-gray-700 font-bold px-6">
                        Cancelar
                    </el-button>
                    <el-button type="primary" native-type="submit" :loading="form.processing" class="!rounded-xl !bg-indigo-600 hover:!bg-indigo-700 !border-none font-bold px-8 shadow-md">
                        Guardar Cambios
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
import { ArrowLeft, Clock, Check, Close } from '@element-plus/icons-vue';

const props = defineProps({
    quote: {
        type: Object,
        required: true
    }
});

const form = useForm({
    client: props.quote.client,
    name: props.quote.name,
    description: props.quote.description,
    price: props.quote.price,
    payment_conditions: props.quote.payment_conditions,
});

const update = () => {
    form.put(route('quotes.update', props.quote.id), {
        onSuccess: () => {
            ElNotification({
                title: 'Correcto',
                message: 'La cotización se ha actualizado',
                type: 'success',
            });
        },
    });
};
</script>