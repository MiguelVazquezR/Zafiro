<template>
    <Head :title="'Cotización ' + quote.data.name" />

    <!-- Contenedor principal: print:bg-white elimina el fondo gris al imprimir -->
    <div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8 font-sans print:bg-white print:py-0">
        
        <!-- Barra de acciones (Oculta al imprimir gracias a print:hidden) -->
        <div class="max-w-4xl mx-auto flex flex-col sm:flex-row justify-between items-center mb-6 print:hidden gap-4">
            <el-button @click="router.get(route('quotes.index'))" class="!rounded-xl !border-none !bg-white hover:!bg-gray-100 !text-gray-700 font-bold px-5 shadow-sm">
                <el-icon class="mr-2"><ArrowLeft /></el-icon> Volver a Cotizaciones
            </el-button>

            <el-button @click="printDocument" type="primary" class="!rounded-xl !bg-indigo-600 hover:!bg-indigo-700 !border-none font-bold px-6 shadow-md">
                <el-icon class="mr-2"><Printer /></el-icon> Imprimir / Guardar PDF
            </el-button>
        </div>

        <!-- Tarjeta del documento (Se quitan sombras y bordes al imprimir) -->
        <div class="max-w-4xl mx-auto bg-white rounded-[32px] shadow-sm border border-gray-100 relative overflow-hidden transition-all print:shadow-none print:border-none print:p-0 min-h-[800px] flex flex-col">

            <!-- Decoracion header -->
            <figure class="absolute right-0 top-7 print:top-0">
                <img class="h-5 w-40 md:w-72 lg:w-[550px] opacity-90" src="@/../../public/images/quote_decoration.png" alt="decoración">
            </figure>

            <!-- Contenido principal del documento -->
            <div class="p-8 md:p-16 flex-1 flex flex-col relative z-10 mt-8 md:mt-0">
                
                <!-- Cabecera de la empresa -->
                <div class="flex items-center space-x-5 mb-8">
                    <ApplicationMark class="w-20 md:w-28 drop-shadow-sm print:drop-shadow-none" />
                    <div class="border-l-2 border-[#FFD700] pl-4">
                        <p class="font-black text-xl md:text-2xl tracking-tight text-gray-900">INGENIERÍA ZAFIRO</p>
                        <p class="text-xs text-gray-500 font-semibold tracking-widest uppercase mt-0.5">Presupuesto de Servicios</p>
                    </div>
                </div>

                <!-- Título de la cotización y fecha -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-12 border-b border-gray-100 pb-6 print:border-gray-300 gap-4">
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Cotización</p>
                        <h1 class="text-3xl font-black text-gray-900">{{ quote.data.name }}</h1>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Fecha de emisión</p>
                        <p class="text-lg font-mono font-bold text-gray-800">{{ quote.data.created_at?.split('-')[0] }}</p>
                    </div>
                </div>

                <!-- Cuerpo del presupuesto -->
                <div class="flex-1 space-y-8">
                    <!-- Cliente -->
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Preparado para:</p>
                        <p class="text-xl font-bold text-gray-800">{{ quote.data.client }}</p>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Descripción de los servicios</p>
                        <div class="bg-gray-50 rounded-[20px] p-6 print:bg-transparent print:p-0 print:border-none border border-gray-100">
                            <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ quote.data.description }}</p>
                        </div>
                    </div>

                    <!-- Costo Total -->
                    <div class="flex justify-end pt-4">
                        <div class="bg-[#FFFDF5] border border-[#FFECA8] rounded-[24px] p-6 min-w-[300px] text-right print:bg-transparent print:border-gray-300">
                            <p class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-1">Costo Total Estimado</p>
                            <p class="text-4xl font-black text-gray-900">{{ formatCurrency(quote.data.price) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Notas y Disclaimer (Siempre al fondo) -->
                <div class="mt-12 pt-6 text-sm text-gray-500 font-medium text-center border-t border-gray-100 print:border-gray-300">
                    Esta cotización no incluye costos adicionales que puedan surgir debido a cambios significativos en el alcance del trabajo.
                </div>
            </div>

            <!-- Decoracion footer -->
            <figure class="absolute left-0 bottom-7 rotate-180 print:bottom-0">
                <img class="h-5 w-40 md:w-72 lg:w-[550px] opacity-90" src="@/../../public/images/quote_decoration.png" alt="decoración">
            </figure>
        </div>
    </div>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import { Printer, ArrowLeft } from '@element-plus/icons-vue';

const props = defineProps({
    quote: {
        type: Object,
        required: true
    }
});

// Función para asegurar que el precio se muestre como moneda incluso si el backend envía solo números
const formatCurrency = (amount) => {
    // Si la cadena ya incluye un signo de dólar, la devolvemos tal cual
    if (typeof amount === 'string' && amount.includes('$')) return amount;
    
    const num = Number(amount || 0);
    if (isNaN(num)) return amount; 
    
    return '$' + num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

// Función nativa para llamar al diálogo de impresión/PDF del sistema
const printDocument = () => {
    window.print();
};
</script>