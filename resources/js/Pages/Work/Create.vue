<template>
    <AppLayout title="Crear trabajo">
        <div class="max-w-4xl mx-auto space-y-6">
            
            <!-- Alertas de Conexión y Sincronización (One UI Style) -->
            <div class="fixed top-20 right-6 z-50 flex flex-col gap-3 max-w-sm w-full">
                <transition name="el-fade-in-linear">
                    <el-alert v-if="!isOnline" title="Sin conexión a Internet" type="warning" show-icon class="!rounded-2xl shadow-lg border border-orange-100">
                        <template #default>
                            <p class="text-xs text-orange-700 mt-1">Los registros se guardarán localmente. Sincroniza al recuperar conexión.</p>
                        </template>
                    </el-alert>
                </transition>

                <transition name="el-fade-in-linear">
                    <el-alert v-if="isOnline && localStorageItems?.length" title="Registros pendientes" type="info" show-icon class="!rounded-2xl shadow-lg border border-blue-100" :closable="false">
                        <template #default>
                            <div class="flex items-center justify-between mt-2 gap-4">
                                <p class="text-xs text-blue-700">Tienes {{ localStorageItems.length }} trabajo(s) guardado(s) offline.</p>
                                <el-button 
                                    @click="syncData" 
                                    :loading="syncingData" 
                                    type="primary" 
                                    size="small" 
                                    class="!rounded-lg !font-bold">
                                    Sincronizar
                                </el-button>
                            </div>
                        </template>
                    </el-alert>
                </transition>
            </div>

            <!-- Encabezado de la página -->
            <div class="flex items-center gap-4 mb-8 px-2">
                <el-button @click="router.get(route('works.index'))" circle class="!border-none !bg-gray-100 hover:!bg-gray-200">
                    <el-icon><ArrowLeft /></el-icon>
                </el-button>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Crear nuevo trabajo</h2>
                    <p class="text-sm text-gray-500 font-medium mt-0.5">Llena los datos para registrar un proyecto</p>
                </div>
            </div>

            <!-- Formulario Reutilizable -->
            <WorkForm :form="form" @submit="store">
                <template #actions>
                    <el-button @click="router.get(route('works.index'))" class="!rounded-xl !border-none !bg-gray-100 hover:!bg-gray-200 !text-gray-700 font-bold px-6">
                        Cancelar
                    </el-button>
                    <el-button type="primary" native-type="submit" :loading="loading" class="!rounded-xl !bg-indigo-600 hover:!bg-indigo-700 !border-none font-bold px-8 shadow-md">
                        Guardar Trabajo
                    </el-button>
                </template>
            </WorkForm>

        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import WorkForm from './Partials/WorkForm.vue';
import axios from 'axios';
import { ElNotification } from 'element-plus';
import { ArrowLeft } from '@element-plus/icons-vue';

const form = useForm({
    town: null,
    ejido: null,
    work_type: null,
    parcel_number: null,
    customer_name: null,
    area: null,
    price: null,
    description: null,
    start_date: null,
    payments: [{ concept: null, amount: null }],
});

const localStorageItems = ref([]);
const loading = ref(false);
const syncingData = ref(false);
const isOnline = ref(navigator.onLine);

const calcItemsInLocalStorage = () => {
    localStorageItems.value = JSON.parse(localStorage.getItem('formData')) || [];
};

const store = () => {
    loading.value = true;
    if (isOnline.value) {
        form.post(route('works.store'), {
            onSuccess: () => {
                ElNotification({
                    title: 'Correcto',
                    message: 'Se registró un nuevo trabajo',
                    type: 'success'
                });
                loading.value = false;
            },
            onError: () => { loading.value = false; }
        });
    } else {
        saveToLocalStorage();
        loading.value = false;
    }
};

const saveToLocalStorage = () => {
    let storedData = JSON.parse(localStorage.getItem('formData')) || [];
    const dataToStore = {
        town: form.town,
        ejido: form.ejido,
        work_type: form.work_type,
        parcel_number: form.parcel_number,
        customer_name: form.customer_name,
        area: form.area,
        price: form.price,
        start_date: form.start_date,
        // Omitimos description y pagos si tu versión anterior no los guardaba offline, 
        // o puedes añadirlos si lo prefieres.
    };

    storedData.push(dataToStore);
    localStorage.setItem('formData', JSON.stringify(storedData));
    
    form.reset();
    calcItemsInLocalStorage();

    ElNotification({
        title: 'Guardado Local',
        message: 'Trabajo guardado localmente. Sincroniza cuando tengas internet.',
        type: 'warning'
    });
};

const syncData = async () => {
    syncingData.value = true;
    try {
        const response = await axios.post(route('works.massive-store'), {
            works: localStorageItems.value,
        });

        if (response.status === 200) {
            ElNotification({
                title: 'Sincronizado',
                message: response.data.message,
                type: 'success'
            });
            localStorage.removeItem('formData');
            calcItemsInLocalStorage();
        }
    } catch (error) {
        console.error(error);
        ElNotification({ title: 'Error', message: 'No se pudo sincronizar.', type: 'error' });
    } finally {
        syncingData.value = false;
    }
};

const handleOnline = () => { isOnline.value = true; };
const handleOffline = () => { isOnline.value = false; };

onMounted(() => {
    window.addEventListener('online', handleOnline);
    window.addEventListener('offline', handleOffline);
    calcItemsInLocalStorage();
});

onBeforeUnmount(() => {
    window.removeEventListener('online', handleOnline);
    window.removeEventListener('offline', handleOffline);
});
</script>