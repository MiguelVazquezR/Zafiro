<template>
    <AppLayout title="Editar trabajo">
        <div class="max-w-4xl mx-auto space-y-6">
            
            <!-- Encabezado de la página -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 px-2">
                <div class="flex items-center gap-4">
                    <el-button @click="router.get(route('works.index'))" circle class="!border-none !bg-gray-100 hover:!bg-gray-200">
                        <el-icon><ArrowLeft /></el-icon>
                    </el-button>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Editar trabajo</h2>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">Modifica los datos o registra nuevos abonos</p>
                    </div>
                </div>

                <!-- Estado del Pago (One UI Tag) -->
                <div>
                    <div v-if="workIsPaid" class="flex items-center gap-2 bg-green-100 text-green-700 px-4 py-2 rounded-xl font-bold text-sm shadow-sm">
                        <el-icon size="18"><Check /></el-icon>
                        <span>Liquidado</span>
                    </div>
                    <div v-else class="flex items-center gap-2 bg-amber-100 text-amber-700 px-4 py-2 rounded-xl font-bold text-sm shadow-sm">
                        <el-icon size="18"><Clock /></el-icon>
                        <span>Esperando liquidación</span>
                    </div>
                </div>
            </div>

            <!-- Formulario Reutilizable -->
            <WorkForm :form="form" @submit="update">
                <template #actions>
                    <el-button 
                        @click="router.get(route('works.invoice', props.work.id))" 
                        class="!rounded-xl !border-none !bg-slate-800 hover:!bg-slate-900 !text-white font-bold px-6 shadow-md">
                        <el-icon class="mr-2"><Document /></el-icon> Ver recibo
                    </el-button>
                    
                    <el-button 
                        type="primary" 
                        native-type="submit" 
                        :loading="form.processing" 
                        class="!rounded-xl !bg-indigo-600 hover:!bg-indigo-700 !border-none font-bold px-8 shadow-md">
                        Actualizar Trabajo
                    </el-button>
                </template>
            </WorkForm>

        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import WorkForm from './Partials/WorkForm.vue';
import { ElNotification } from 'element-plus';
import { ArrowLeft, Check, Clock, Document } from '@element-plus/icons-vue';

const props = defineProps({
    work: Object,
    date: String,
});

const form = useForm({
    town: props.work.town,
    ejido: props.work.ejido,
    work_type: props.work.work_type,
    parcel_number: props.work.parcel_number,
    customer_name: props.work.customer_name,
    area: props.work.area,
    price: props.work.price,
    start_date: props.date,
    description: props.work.description,
    payments: props.work.payments?.length ? props.work.payments : [{ concept: null, amount: null }],
});

const calcTotalPaid = computed(() => {
    if (!form.payments?.length) return 0;
    return form.payments.reduce((acc, item) => acc + Number(item.amount), 0);
});

const workIsPaid = computed(() => {
    return calcTotalPaid.value >= Number(form.price || 0);
});

const update = () => {
    // Evitar guardar pagos vacíos o a medias
    if (form.payments.some(item => item.concept == null || item.amount == null)) {
        form.payments = null;
    }
    
    form.put(route('works.update', props.work.id), {
        onSuccess: () => {
            ElNotification({
                title: 'Correcto',
                message: 'Se actualizó el trabajo exitosamente',
                type: 'success'
            });
            // Si pagos se limpiaron, restaurar el state visual para evitar errores de frontend
            if (!form.payments) {
                form.payments = [{ concept: null, amount: null }];
            }
        }
    });
};
</script>