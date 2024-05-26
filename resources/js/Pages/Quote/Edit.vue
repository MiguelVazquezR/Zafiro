<template>
    <AppLayout title="Editar cotización">
        <div class="w-5/6 lg:w-[80%] mx-auto rounded-md lg:border border-[#D9D9D9] p-5">
            <h1 class="font-bold text-lg text-center lg:text-left">Editar cotización</h1>

            <form @submit.prevent="update" class="mt-3">
                <div class="mt-3">
                    <InputLabel value="Nombre del cliente" class="ml-3 mb-1" />
                    <el-input v-model="form.client" placeholder="Ej. Gerardo Villa" :maxlength="255" clearable />
                    <InputError :message="form.errors.client" />
                </div>

                <div class="mt-3">
                    <InputLabel value="Nombre de la cotización (opcional)" class="ml-3 mb-1" />
                    <el-input v-model="form.name" placeholder="Ej. Levantamiento" :maxlength="255" clearable />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="mt-3">
                    <InputLabel value="Descripción de los servicios*" class="ml-3 mb-1" />
                    <el-input v-model="form.description" :autosize="{ minRows: 3, maxRows: 5 }" type="textarea"
                                placeholder="Describe los servicios que cubre essta cotización" :maxlength="800" show-word-limit clearable />
                    <InputError :message="form.errors.description" />
                </div>

                <div class="w-1/2 mt-3">
                    <InputLabel value="Costo*" class="ml-3 mb-1 text-sm" />
                    <el-input v-model="form.price" placeholder="0"
                    :formatter="(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                    :parser="(value) => value.replace(/\$\s?|(,*)/g, '')">
                        <template #prefix>
                            <i class="fa-solid fa-dollar-sign pr-2 border-r-2"></i>
                        </template>
                    </el-input>
                    <InputError :message="form.errors.price" />
                </div>

                <div class="w-full mt-3">
                    <InputLabel value="Condiciones de pago*" class="ml-3 mb-1" />
                    <el-select class="w-full" v-model="form.payment_conditions" clearable placeholder="Seleccione"
                    no-data-text="No hay opciones disponibles" no-match-text="No se encontraron coincidencias">
                    <el-option v-for="item in paymentConditions" :key="item" :label="item" :value="item" />
                    </el-select>
                    <InputError :message="form.errors.payment_conditions" />
                </div>

                <div class="mt-7 text-right">
                    <PrimaryButton :disabled="form.processing">Guardar cambios</PrimaryButton>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";

export default {
data() {
    const form = useForm({
        client: this.quote.client,
        name: this.quote.name,
        description: this.quote.description,
        price: this.quote.price,
        payment_conditions: this.quote.payment_conditions,
    });

    return {
        form,
        paymentConditions: [
            'Pago a una sola exhibición',
            '2 pagos (50% de anticipo al inicio y 50% al finalizar el trabajo)'
        ]
    }
},
components:{
AppLayout,
PrimaryButton,
InputLabel,
InputError
},
props:{
quote: Object
},
methods:{
    update() {
        this.form.put(route("quotes.update"), {
            onSuccess: () => {
                this.$notify({
                title: "Correcto",
                message: "Se ha creado la cotización",
                type: "success",
                });
            },
        });
    },
}
}
</script>
