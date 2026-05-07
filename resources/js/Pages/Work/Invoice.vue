<template>
    <Head title="Nota de Servicio" />
    
    <!-- Contenedor principal: print:bg-white elimina el fondo gris al imprimir -->
    <div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8 font-sans print:bg-white print:py-0">
        
        <!-- Barra de acciones (Oculta al imprimir gracias a print:hidden) -->
        <div class="max-w-3xl mx-auto flex justify-end mb-6 print:hidden">
            <button @click="printDocument" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-md transition-colors focus:ring-4 focus:ring-indigo-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0v-2.94a2.25 2.25 0 012.25-2.25h6a2.25 2.25 0 012.25 2.25v2.94z" />
                </svg>
                Imprimir / Guardar PDF
            </button>
        </div>

        <!-- Tarjeta del documento (Se quitan sombras y bordes al imprimir) -->
        <div class="max-w-3xl mx-auto bg-white rounded-[32px] shadow-sm border border-gray-100 p-8 sm:p-12 transition-all print:shadow-none print:border-none print:p-0">
            
            <!-- ENCABEZADO -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-10 pb-8 border-b border-gray-100 print:mb-6 print:pb-4">
                <div class="flex items-center gap-4">
                    <ApplicationLogo class="w-20 drop-shadow-sm print:drop-shadow-none" />
                    <div>
                        <p class="text-[#FFD700] font-black text-2xl tracking-tight leading-none print:text-black">Ingeniería Zafiro</p>
                        <p class="text-xs text-gray-400 font-medium mt-1 uppercase tracking-widest">Nota de Servicio</p>
                    </div>
                </div>
                
                <div class="text-left sm:text-right bg-gray-50 p-4 rounded-2xl w-full sm:w-auto print:bg-transparent print:p-0">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Folio del Trabajo</p>
                    <p class="text-2xl font-mono font-black text-gray-800">{{ work.data.folio }}</p>
                    <p class="text-sm text-gray-500 mt-2 font-medium">Emisión: <span class="text-gray-800">{{ today }}</span></p>
                </div>
            </div>

            <!-- DATOS DEL SERVICIO -->
            <div class="mb-10 print:mb-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-1.5 h-6 bg-[#FFD700] rounded-full print:bg-black"></div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Detalles del Servicio</h2>
                </div>
                
                <!-- Fondo gris se remueve al imprimir -->
                <div class="bg-gray-50 rounded-[24px] p-6 sm:p-8 print:bg-transparent print:p-0 print:border print:border-gray-200">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                        
                        <div class="sm:col-span-2">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Cliente</p>
                            <p class="text-lg font-bold text-gray-800">{{ work.data.customer_name }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tipo de Trabajo</p>
                            <p class="text-base font-semibold text-gray-700 bg-white px-3 py-1.5 rounded-xl inline-block border border-gray-100 print:border-none print:px-0">{{ work.data.work_type }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Fecha de Inicio</p>
                            <p class="text-base font-medium text-gray-700">{{ work.data.start_date }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Municipio</p>
                            <p class="text-base font-medium text-gray-700">{{ work.data.town }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Ejido</p>
                            <p class="text-base font-medium text-gray-700">{{ work.data.ejido }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Número de Parcela</p>
                            <p class="text-base font-medium text-gray-700">{{ work.data.parcel_number }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Área</p>
                            <p class="text-base font-medium text-gray-700">{{ work.data.area }} <span class="text-gray-400 text-sm">m²</span></p>
                        </div>
                    </div>

                    <!-- Fila de Costo Destacada -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between items-center">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Costo Total Estimado</p>
                        <p class="text-3xl font-black text-gray-900">{{ work.data.price }}</p>
                    </div>
                </div>
            </div>

            <!-- NOTAS ADICIONALES -->
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-1.5 h-6 bg-[#FFD700] rounded-full print:bg-black"></div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Notas y Observaciones</h2>
                </div>
                
                <div class="bg-[#FFFDF5] border border-[#FFECA8] rounded-[20px] p-5 print:border-gray-300 print:bg-transparent">
                    <p class="text-sm font-medium text-amber-800 flex items-start gap-3 print:text-black">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-[#FFD700] shrink-0 mt-0.5 print:text-black">
                          <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                        </svg>
                        <span>Se usa equipo GNSS RTK de una banda con sistema NTRIP.</span>
                    </p>
                </div>
            </div>
            
            <!-- Pie de página para impresión -->
            <div class="mt-12 pt-6 text-center text-xs text-gray-400 font-medium print:mt-6 print:text-gray-600">
                Documento generado automáticamente por el sistema de Ingeniería Zafiro.
            </div>

        </div>
    </div>
</template>

<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head } from '@inertiajs/vue3';

// Props
const props = defineProps({
    work: {
        type: Object,
        required: true
    },
    today: {
        type: String,
        required: true
    }
});

// Función nativa para llamar al diálogo de impresión/PDF del sistema operativo
const printDocument = () => {
    window.print();
};
</script>