<template>
    <AppLayout title="Crear trabajo">
        <div class="lg:w-2/3 mx-auto mt-6">
            <div class="flex justify-between">
                <p @click="$inertia.get(route('works.index'))" class="flex items-center cursor-pointer">
                    <i class="fa-solid fa-chevron-left mr-5"></i>
                    Regresar
                </p>
                <h1 class="text-lg">Crear nuevo trabajo</h1>
            </div>

            <!-- form -->
            <div class="bg-white rounded-xl shadow-lg px-5 py-3 mt-6">
                <form @submit.prevent="store()" class="lg:grid grid-cols-2 gap-4 space-y-3 lg:space-y-0">
                    <div>
                        <label class="text-sm">Nombre de cliente</label>
                        <input v-model="form.customer_name" type="text" class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.customer_name" />
                    </div>
                    <div>
                        <label class="text-sm">Municipio</label>
                        <input v-model="form.town" type="text" class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.town" />
                    </div>
                    <div>
                        <label class="text-sm">Ejido</label>
                        <input v-model="form.ejido" type="text" class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.ejido" />
                    </div>
                    <div>
                        <label class="text-sm">Tipo de trabajo</label>
                        <select v-model="form.work_type" class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                            <option value="Levantamiento" selected>Levantamiento</option>
                            <option value="Lotificacion">Lotificacion</option>
                            <option value="Subdivisión">Subdivisión</option>
                            <option value="Deslinde">Deslinde</option>
                        </select>
                        <InputError :message="form.errors.ejido" />
                    </div>
                    <div>
                        <label class="text-sm">Número de parcela</label>
                        <input v-model="form.parcel_number" type="text" class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.parcel_number" />
                    </div>
                    <div>
                        <label class="text-sm">Área (m2)</label>
                        <input v-model="form.area" type="number" step="0.01" class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.area" />
                    </div>
                    <div>
                        <label class="text-sm">Precio ($MXN)</label>
                        <input v-model="form.price" type="number" step="0.01" class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.price" />
                    </div>
                    <div>
                        <label class="text-sm">Fecha de inicio</label>
                        <input v-model="form.start_date" type="date" class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px]">
                        <InputError :message="form.errors.start_date" />
                    </div>
                    <div class="flex justify-end mt-3 col-span-full">
                        <PrimaryButton>Guardar</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
<script>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm } from "@inertiajs/vue3";

export default {
    data() {
        const form = useForm({
            town: null,
            ejido: null,
            work_type: null,
            parcel_number: null,
            customer_name: null,
            area: null,
            price: null,
            start_date: null,
        });

        return {
            form,
            disableMassiveActions: true,
        };
    },
    components: {
        AppLayout,
        PrimaryButton,
        InputError,
    },
    methods: {
        store() {
            this.form.post(route('works.store'), {
                onSuccess: () => {
                    this.$notify({
                        title: 'Correcto',
                        message: 'Se registró un nuevo trabajo',
                        type: 'success'
                    });
                }
            })
        },
    }
}
</script>
