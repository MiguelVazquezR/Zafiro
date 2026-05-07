<template>
    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 md:p-8 transition-all">
        <form @submit.prevent="$emit('submit')" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre del Cliente -->
                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Nombre del cliente *</label>
                    <el-input 
                        v-model="form.client" 
                        placeholder="Ej. Gerardo Villa" 
                        maxlength="255" 
                        clearable 
                        size="large" 
                        class="one-ui-input" 
                    />
                    <InputError :message="form.errors.client" class="mt-1" />
                </div>

                <!-- Nombre de la Cotización -->
                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Nombre de la cotización (opcional)</label>
                    <el-input 
                        v-model="form.name" 
                        placeholder="Ej. Levantamiento Topográfico" 
                        maxlength="255" 
                        clearable 
                        size="large" 
                        class="one-ui-input" 
                    />
                    <InputError :message="form.errors.name" class="mt-1" />
                </div>

                <!-- Descripción de los Servicios -->
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Descripción de los servicios *</label>
                    <el-input 
                        v-model="form.description" 
                        type="textarea" 
                        :autosize="{ minRows: 3, maxRows: 6 }"
                        placeholder="Describe los servicios que cubre esta cotización..." 
                        maxlength="800" 
                        show-word-limit 
                        clearable 
                        class="one-ui-input" 
                    />
                    <InputError :message="form.errors.description" class="mt-1" />
                </div>

                <!-- Costo -->
                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Costo estimado *</label>
                    <el-input 
                        v-model="form.price" 
                        placeholder="0.00" 
                        size="large" 
                        class="one-ui-input"
                        :formatter="(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                        :parser="(value) => value.replace(/\$\s?|(,*)/g, '')"
                    >
                        <template #prefix>
                            <span class="font-bold text-gray-500">$</span>
                        </template>
                    </el-input>
                    <InputError :message="form.errors.price" class="mt-1" />
                </div>

                <!-- Condiciones de Pago -->
                <div>
                    <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Condiciones de pago *</label>
                    <el-select 
                        v-model="form.payment_conditions" 
                        clearable 
                        placeholder="Seleccione una opción"
                        no-data-text="No hay opciones disponibles" 
                        no-match-text="No se encontraron coincidencias"
                        size="large"
                        class="w-full one-ui-input"
                    >
                        <el-option 
                            v-for="item in paymentConditions" 
                            :key="item" 
                            :label="item" 
                            :value="item" 
                        />
                    </el-select>
                    <InputError :message="form.errors.payment_conditions" class="mt-1" />
                </div>
            </div>

            <!-- Acciones Inyectadas desde la Vista Padre -->
            <div class="pt-6 border-t border-gray-100 mt-8 flex justify-end gap-3">
                <slot name="actions"></slot>
            </div>
            
        </form>
    </div>
</template>

<script setup>
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    form: {
        type: Object,
        required: true
    }
});

defineEmits(['submit']);

const paymentConditions = [
    'Pago a una sola exhibición',
    '2 pagos (50% de anticipo al inicio y 50% al finalizar el trabajo)'
];
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
}

:deep(.one-ui-input .el-input__wrapper.is-focus),
:deep(.one-ui-input .el-textarea__inner:focus) {
    background-color: #ffffff;
    border-color: #6366f1;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
}
</style>