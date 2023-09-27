<template>
    <AppLayout title="Crear trabajo">
        <div class="lg:w-2/3 mx-auto mt-6 relative">
            <!-- mensaje de conexion a internet -->
            <div v-if="!isOnline" class="fixed top-16 right-0 w-44 px-4 py-1 bg-gray-500 text-white text-xs">
                <p class="text-sm font-semibold">Sin conexión a Internet</p>
                <p class="mt-2 text-xs">
                    Los registros que guardes se almacenaran localmente y cuando tengas conexión a
                    internet podrás sincronizarlos
                    con la base de datos.
                </p>
            </div>
            <div v-else-if="localStorageItems?.length"
                class="fixed top-16 right-0 w-44 px-4 py-1 text-xs bg-gray-500 text-white">
                <p class="mb-3">Hay {{ localStorageItems?.length }} registros sin sincronizar</p>
                <button v-if="!syncingData" @click="syncData()" class="bg-[#FFD700] text-black rounded-sm px-2 py-px">
                    Sincronizar <i class="fa-solid fa-cloud-arrow-up"></i>
                </button>
                <button v-else disabled
                    class="bg-[#FFD700] text-black rounded-sm px-2 py-px disabled:opacity-50 disabled:cursor-not-allowed">
                    Sincronizando <i class="fa-solid fa-arrows-rotate fa-spin"></i>
                </button>
            </div>

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
                        <input v-model="form.customer_name" type="text"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.customer_name" />
                    </div>
                    <div>
                        <label class="text-sm">Municipio</label>
                        <input v-model="form.town" type="text"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.town" />
                    </div>
                    <div>
                        <label class="text-sm">Ejido</label>
                        <input v-model="form.ejido" type="text"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                        <InputError :message="form.errors.ejido" />
                    </div>
                    <div>
                        <label class="text-sm">Tipo de trabajo</label>
                        <select v-model="form.work_type"
                            class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9">
                            <option value="Levantamiento" selected>Levantamiento</option>
                            <option value="Lotificacion">Lotificacion</option>
                            <option value="Subdivisión">Subdivisión</option>
                            <option value="Deslinde">Deslinde</option>
                        </select>
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
                    <div class="flex justify-end mt-3 col-span-full">
                        <PrimaryButton :disabled="loading">Guardar</PrimaryButton>
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
import axios from 'axios';

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
            localStorageItems: [],
            loading: false,
            syncingData: false,
            disableMassiveActions: true,
            isOnline: navigator.onLine, // Verificar el estado de conexión al cargar el componente
        };
    },
    components: {
        AppLayout,
        PrimaryButton,
        InputError,
    },
    computed: {
        getItemsInLocalStorage() {
            return JSON.parse(localStorage.getItem('formData'));
        }
    },
    methods: {
        calcItemsInLocalStorage() {
            this.localStorageItems = JSON.parse(localStorage.getItem('formData'));
        },
        store() {
            this.loading = true;
            if (this.isOnline) {
                this.form.post(route('works.store'), {
                    onSuccess: () => {
                        this.$notify({
                            title: 'Correcto',
                            message: 'Se registró un nuevo trabajo',
                            type: 'success'
                        });

                        this.loading = false;
                    }
                })
            } else {
                this.saveToLocalStorage();
                this.loading = false;
            }
        },
        saveToLocalStorage() {
            // Obtén los datos actuales almacenados en el Local Storage
            let storedData = JSON.parse(localStorage.getItem('formData')) || [];

            const dataToStore = {
                town: this.form.town,
                ejido: this.form.ejido,
                work_type: this.form.work_type,
                parcel_number: this.form.parcel_number,
                customer_name: this.form.customer_name,
                area: this.form.area,
                price: this.form.price,
                start_date: this.form.start_date,
            };

            // Agrega el nuevo objeto al arreglo
            storedData.push(dataToStore);

            // Vuelve a guardar el arreglo en el Local Storage
            localStorage.setItem('formData', JSON.stringify(storedData));
            this.form.reset();

            this.$notify({
                title: 'Correcto',
                message: 'Se registró un nuevo trabajo en almacenamiento local. Cuando tengas conexión a internet sincroniza tus datos con la nube',
                type: 'success'
            });
        },
        async syncData() {
            this.syncingData = true;
            try {
                const response = await axios.post(route('works.massive-store'), {
                    works: this.localStorageItems,
                });

                if (response.status === 200) {
                    this.$notify({
                        title: 'Correcto',
                        message: response.data.message,
                        type: 'success'
                    });

                    // eliminar datos en almacenamiento local
                    localStorage.removeItem('formData');
                    this.calcItemsInLocalStorage();
                }
            } catch (error) {
                console.log(error);
            } finally {
                this.syncingData = false;
            }
        },
        handleOnline() {
            this.isOnline = true;
        },
        handleOffline() {
            this.isOnline = false;
        },
    },
    mounted() {
        // Agregar escuchadores de eventos online/offline
        window.addEventListener('online', this.handleOnline);
        window.addEventListener('offline', this.handleOffline);
        this.calcItemsInLocalStorage();
    },
    beforeUnmount() {
        // Eliminar los escuchadores de eventos al desmontar el componente
        window.removeEventListener('online', this.handleOnline);
        window.removeEventListener('offline', this.handleOffline);
    },
}
</script>
