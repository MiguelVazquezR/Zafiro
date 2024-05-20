<template>
    <AppLayout title="Editar trabajo">
        <div class="lg:w-2/3 mx-auto mt-6">
            <div class="flex justify-between">
                <p @click="$inertia.get(route('works.index'))" class="flex items-center cursor-pointer">
                    <i class="fa-solid fa-chevron-left mr-5"></i>
                    Regresar
                </p>
                <el-tag v-if="workIsPaid()" type="success" size="large" class="!text-lg">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span>Liquidado</span>
                    </div>
                </el-tag>
                <el-tag v-else type="warning" size="large" class="!text-lg">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-amber-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>Esperando liquidación</span>
                    </div>
                </el-tag>
                <h1 class="text-lg">Editar trabajo</h1>
            </div>

            <!-- form -->
            <div class="bg-white rounded-xl shadow-lg px-5 py-3 my-6">
                <form @submit.prevent="update()" class="lg:grid grid-cols-2 gap-4 space-y-3 lg:space-y-0">
                    <div>
                        <label class="text-sm">Nombre de cliente</label>
                        <input v-model="form.customer_name" type="text"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.customer_name" />
                    </div>
                    <div>
                        <label class="text-sm">Municipio</label>
                        <input v-model="form.town" type="text" name="town" autocomplete="town"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.town" />
                    </div>
                    <div>
                        <label class="text-sm">Ejido</label>
                        <input v-model="form.ejido" type="text" name="ejido" autocomplete="ejido"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.ejido" />
                    </div>
                    <div>
                        <label class="text-sm">Tipo de trabajo</label>
                        <input v-model="form.work_type" type="text"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <!-- <select v-model="form.work_type"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                            <option value="Levantamiento" selected>Levantamiento</option>
                            <option value="Lotificacion">Lotificacion</option>
                            <option value="Subdivisión">Subdivisión</option>
                            <option value="Deslinde">Deslinde</option>
                        </select> -->
                        <InputError :message="form.errors.ejido" />
                    </div>
                    <div>
                        <label class="text-sm">Número de parcela</label>
                        <input v-model="form.parcel_number" type="text"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.parcel_number" />
                    </div>
                    <div>
                        <label class="text-sm">Área (m2)</label>
                        <input v-model="form.area" type="number" step="0.01"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.area" />
                    </div>
                    <div>
                        <label class="text-sm">Precio ($MXN)</label>
                        <input v-model="form.price" type="number" step="0.01"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.price" />
                    </div>
                    <div>
                        <label class="text-sm">Fecha de inicio</label>
                        <input v-model="form.start_date" type="date"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px]">
                        <InputError :message="form.errors.start_date" />
                    </div>
                    <div class="col-span-full">
                        <label class="text-sm">Descripción</label>
                        <textarea v-model="form.description" rows="4"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px]">
                        </textarea>
                        <InputError :message="form.errors.description" />
                    </div>
                    <h2 class="font-bold text-base pt-3 col-span-full">Pagos</h2>
                    <section class="col-span-full">
                        <div v-for="(item, index) in form.payments" :key="index"
                            class="col-span-full flex items-center space-x-2">
                            <div class="w-[75%]">
                                <label class="text-sm">Concepto</label>
                                <input v-model="form.payments[index].concept" type="text"
                                    class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                            </div>
                            <div class="w-[20%]">
                                <label class="text-sm">Monto</label>
                                <input v-model="form.payments[index].amount" type="number"
                                    class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                            </div>
                            <el-popconfirm v-if="form.payments.length > 1" confirm-button-text="Si"
                                cancel-button-text="No" icon-color="#FD8827" title="¿Remover?"
                                @confirm="deletePayment(index)" class="w-[5%]">
                                <template #reference>
                                    <button type="button"
                                        class="text-primary text-sm w-6 h-6 self-end mb-1 hover:bg-gray-100 rounded-full">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </template>
                            </el-popconfirm>
                        </div>
                        <div class="flex justify-center mt-4">
                            <button @click="addPayment()" type="button" class="text-xs text-black underline">
                                + Agregar otro pago
                            </button>
                        </div>
                    </section>
                    <div class="flex justify-end space-x-2 mt-3 col-span-full">
                        <PrimaryButton @click="$inertia.get(route('works.invoice', work.id))" type="button">Ver recibo
                        </PrimaryButton>
                        <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>
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
            town: this.work.town,
            ejido: this.work.ejido,
            work_type: this.work.work_type,
            parcel_number: this.work.parcel_number,
            customer_name: this.work.customer_name,
            area: this.work.area,
            price: this.work.price,
            start_date: this.date,
            description: this.work.description,
            payments: this.work.payments ?? [{ concept: null, amount: null }],
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
    props: {
        work: Object,
        date: String,
    },
    methods: {
        workIsPaid() {
            return this.calcTotalPaid() >= this.work.price;
        },
        calcTotalPaid() {
            if (!this.work.payments?.length) return 0;

            return this.work.payments?.reduce((acc, item) => {
                return acc += item.amount;
            }, 0);
        },
        deletePayment(index) {
            this.form.payments.splice(index, 1);
        },
        addPayment() {
            const newPayment = { concept: null, amount: null };
            this.form.payments.push(newPayment);
        },
        update() {
            if (this.form.payments.some(item => item.conept == null || item.amount == null)) {
                this.form.payments = null;
            }
            this.form.put(route('works.update', this.work), {
                onSuccess: () => {
                    this.$notify({
                        title: 'Correcto',
                        message: 'Se actualizó un nuevo trabajo',
                        type: 'success'
                    });
                }
            })
        },
    }
}
</script>
