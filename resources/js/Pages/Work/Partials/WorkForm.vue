<template>
    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 md:p-8 transition-all">
        <form @submit.prevent="$emit('submit')" class="space-y-8">
            
            <!-- SECCIÓN: Información Básica -->
            <div>
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Información del Cliente y Ubicación</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Nombre de cliente</label>
                        <el-input v-model="form.customer_name" placeholder="Ej. Juan Pérez" size="large" class="one-ui-input" />
                        <InputError :message="form.errors.customer_name" class="mt-1" />
                    </div>
                    
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Municipio</label>
                        <el-input v-model="form.town" placeholder="Municipio" size="large" class="one-ui-input" />
                        <InputError :message="form.errors.town" class="mt-1" />
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Ejido</label>
                        <el-input v-model="form.ejido" placeholder="Ejido o localidad" size="large" class="one-ui-input" />
                        <InputError :message="form.errors.ejido" class="mt-1" />
                    </div>
                </div>
            </div>

            <el-divider class="!my-0 opacity-50" />

            <!-- SECCIÓN: Detalles Técnicos -->
            <div>
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Detalles Técnicos y Costos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Tipo de trabajo</label>
                        <el-input v-model="form.work_type" placeholder="Ej. Levantamiento, Lotificación..." size="large" class="one-ui-input" />
                        <InputError :message="form.errors.work_type" class="mt-1" />
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Número de parcela</label>
                        <el-input v-model="form.parcel_number" placeholder="Ej. 145-B" size="large" class="one-ui-input" />
                        <InputError :message="form.errors.parcel_number" class="mt-1" />
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Área (m²)</label>
                        <el-input v-model="form.area" type="number" placeholder="0.00" size="large" class="one-ui-input" />
                        <InputError :message="form.errors.area" class="mt-1" />
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Precio ($MXN)</label>
                        <el-input v-model="form.price" type="number" placeholder="0.00" size="large" class="one-ui-input">
                            <template #prefix>$</template>
                        </el-input>
                        <InputError :message="form.errors.price" class="mt-1" />
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Fecha de inicio</label>
                        <el-date-picker 
                            v-model="form.start_date" 
                            type="date" 
                            placeholder="Selecciona una fecha" 
                            format="YYYY/MM/DD"
                            value-format="YYYY-MM-DD"
                            size="large"
                            class="one-ui-input w-full !w-full" 
                        />
                        <InputError :message="form.errors.start_date" class="mt-1" />
                    </div>
                </div>

                <div class="mt-5">
                    <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Descripción detallada</label>
                    <el-input 
                        v-model="form.description" 
                        type="textarea" 
                        :rows="3" 
                        placeholder="Añade notas u observaciones del trabajo..." 
                        class="one-ui-input" 
                    />
                    <InputError :message="form.errors.description" class="mt-1" />
                </div>
            </div>

            <el-divider class="!my-0 opacity-50" />

            <!-- SECCIÓN: Pagos -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Registro de Pagos</h3>
                    <el-button @click="addPayment" type="primary" size="small" class="!rounded-full !bg-indigo-50 !text-indigo-600 !border-none hover:!bg-indigo-100">
                        <el-icon class="mr-1"><Plus /></el-icon> Añadir pago
                    </el-button>
                </div>
                
                <div class="space-y-3">
                    <div v-for="(item, index) in form.payments" :key="index" class="flex items-start gap-3 bg-gray-50/50 p-3 rounded-2xl border border-gray-100">
                        <div class="flex-1">
                            <label class="text-xs font-semibold text-gray-500 mb-1 block">Concepto</label>
                            <el-input v-model="form.payments[index].concept" placeholder="Ej. Anticipo" class="one-ui-input bg-white" />
                        </div>
                        <div class="w-1/3">
                            <label class="text-xs font-semibold text-gray-500 mb-1 block">Monto</label>
                            <el-input v-model="form.payments[index].amount" type="number" placeholder="0.00" class="one-ui-input bg-white">
                                <template #prefix>$</template>
                            </el-input>
                        </div>
                        <div class="pt-6">
                            <el-popconfirm v-if="form.payments.length > 1" title="¿Remover pago?" @confirm="removePayment(index)" confirm-button-text="Sí" cancel-button-text="No">
                                <template #reference>
                                    <el-button type="danger" circle plain class="!border-none !bg-red-50 hover:!bg-red-100">
                                        <el-icon><Delete /></el-icon>
                                    </el-button>
                                </template>
                            </el-popconfirm>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Inyectadas desde la Vista Padre -->
            <div class="pt-4 flex justify-end gap-3 border-t border-gray-100 mt-6 pt-6">
                <slot name="actions"></slot>
            </div>
            
        </form>
    </div>
</template>

<script setup>
import { Plus, Delete } from '@element-plus/icons-vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    form: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['submit']);

const addPayment = () => {
    props.form.payments.push({ concept: null, amount: null });
};

const removePayment = (index) => {
    props.form.payments.splice(index, 1);
};
</script>

<style scoped>
/* Estilos consistentes One UI para Inputs de Element Plus */
:deep(.one-ui-input .el-input__wrapper),
:deep(.one-ui-input .el-textarea__inner) {
    border-radius: 12px;
    background-color: #f1f5f9;
    box-shadow: none !important;
    border: 1px solid transparent;
    transition: all 0.2s ease;
    padding-top: 4px;
    padding-bottom: 4px;
}

:deep(.one-ui-input .el-input__wrapper.is-focus),
:deep(.one-ui-input .el-textarea__inner:focus) {
    background-color: #ffffff;
    border-color: #6366f1;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
}

:deep(.el-input-group__prepend) {
    border-radius: 12px 0 0 12px;
    background-color: #f1f5f9;
    border: none;
}
</style>