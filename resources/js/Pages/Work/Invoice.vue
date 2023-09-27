<template>
    <Head title="Nota" />
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <ApplicationLogo class="w-28" />
            <p class="text-[#FFD700] font-bold text-xl">Ingeniería Zafiro</p>
        </div>
        <div>
            <p>Folio: {{ work.data.folio }}</p>
            <p>Fecha de emisión: {{ today }}</p>
        </div>
    </div>

    <!-- datos del servicio -->
    <p class="py-1 bg-[#FFD700] text-center border-t border-b border-black mt-5">Datos del servicio</p>
    <div class="grid grid-cols-3 gap-x-5 gap-y-2">
        <p>Cliente:</p>
        <p class="col-span-2">{{ work.data.customer_name }}</p>
        <p>Tipo de trabajo:</p>
        <p class="col-span-2">{{ work.data.work_type }}</p>
        <p>Área:</p>
        <p class="col-span-2">{{ work.data.area }} metros cuadrados</p>
        <p>Municipio:</p>
        <p class="col-span-2">{{ work.data.town }}</p>
        <p>Ejido:</p>
        <p class="col-span-2">{{ work.data.ejido }}</p>
        <p>Número de parcela:</p>
        <p class="col-span-2">{{ work.data.parcel_number }}</p>
        <p>Fecha de inicio:</p>
        <p class="col-span-2">{{ work.data.start_date }}</p>
        <p>Precio:</p>
        <p class="col-span-2">${{ work.data.price }}</p>
    </div>

    <!-- Notas -->
    <p class="py-1 bg-[#FFD700] text-center border-t border-b border-black mt-5">Notas</p>
    <div>
        <p>Se usa equipo GNSS RTK de una banda con sistema NTRIP</p>
    </div>
</template>
<script>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        ApplicationLogo,
        Head,
    },
    props: {
        work: Object,
        today: String,
    }
}
</script>
