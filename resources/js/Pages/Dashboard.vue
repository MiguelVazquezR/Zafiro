<template>
    <AppLayout title="Dashboard">
        <div class="max-w-7xl mx-auto space-y-8 px-2 sm:px-0">
            
            <!-- Encabezado de Bienvenida -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white rounded-[32px] p-8 md:p-10 shadow-sm border border-gray-100 relative overflow-hidden">
                <!-- Decoración de fondo sutil -->
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
                
                <div class="relative z-10">
                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight mb-2">
                        Hola, {{ $page.props.auth.user.name.split(' ')[0] }} 👋
                    </h1>
                    <p class="text-gray-500 font-medium text-lg">
                        Bienvenido al panel de control de Ingeniería Zafiro.
                    </p>
                </div>

                <div class="relative z-10 flex gap-3 w-full md:w-auto">
                    <el-button @click="router.get(route('works.create'))" type="primary" size="large" class="!rounded-2xl !bg-indigo-600 hover:!bg-indigo-700 !border-none font-bold shadow-md w-full md:w-auto px-6">
                        <el-icon class="mr-2"><Plus /></el-icon> Nuevo Trabajo
                    </el-button>
                </div>
            </div>

            <!-- Grid de Estadísticas Rápidas (Widgets One UI) -->
            <div>
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 ml-2">Resumen General</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    
                    <!-- Widget: Trabajos -->
                    <div @click="router.get(route('works.index'))" class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100 cursor-pointer hover:-translate-y-1 hover:shadow-md transition-all group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <el-icon size="24"><Briefcase /></el-icon>
                            </div>
                            <el-icon class="text-gray-300"><ArrowRight /></el-icon>
                        </div>
                        <div>
                            <h4 class="text-3xl font-black text-gray-900">{{ worksCount }}</h4>
                            <p class="text-sm font-semibold text-gray-500 mt-1">Trabajos Activos</p>
                        </div>
                    </div>

                    <!-- Widget: Cotizaciones -->
                    <div @click="router.get(route('quotes.index'))" class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100 cursor-pointer hover:-translate-y-1 hover:shadow-md transition-all group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl group-hover:bg-amber-500 group-hover:text-white transition-colors">
                                <el-icon size="24"><Document /></el-icon>
                            </div>
                            <el-icon class="text-gray-300"><ArrowRight /></el-icon>
                        </div>
                        <div>
                            <h4 class="text-3xl font-black text-gray-900">{{ quotesCount }}</h4>
                            <p class="text-sm font-semibold text-gray-500 mt-1">Cotizaciones</p>
                        </div>
                    </div>

                    <!-- Widget: Mensajes -->
                    <div @click="router.get(route('messages.index'))" class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100 cursor-pointer hover:-translate-y-1 hover:shadow-md transition-all group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                <el-icon size="24"><ChatDotRound /></el-icon>
                            </div>
                            <el-icon class="text-gray-300"><ArrowRight /></el-icon>
                        </div>
                        <div>
                            <h4 class="text-3xl font-black text-gray-900">{{ messagesCount }}</h4>
                            <p class="text-sm font-semibold text-gray-500 mt-1">Mensajes Nuevos</p>
                        </div>
                    </div>

                    <!-- Widget: Lotes -->
                    <div @click="router.get(route('batches.index'))" class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100 cursor-pointer hover:-translate-y-1 hover:shadow-md transition-all group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-purple-50 text-purple-600 rounded-2xl group-hover:bg-purple-500 group-hover:text-white transition-colors">
                                <el-icon size="24"><Grid /></el-icon>
                            </div>
                            <el-icon class="text-gray-300"><ArrowRight /></el-icon>
                        </div>
                        <div>
                            <h4 class="text-3xl font-black text-gray-900">{{ batchesCount }}</h4>
                            <p class="text-sm font-semibold text-gray-500 mt-1">Lotes Registrados</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Accesos Rápidos (Módulos principales) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Gestor de Finanzas Destacado -->
                <div class="lg:col-span-2 bg-gradient-to-br from-slate-800 to-slate-900 rounded-[32px] p-8 md:p-10 shadow-lg text-white relative overflow-hidden flex flex-col justify-between min-h-[250px]">
                    <div class="absolute right-0 top-0 w-64 h-full bg-white/5 skew-x-12 translate-x-20"></div>
                    
                    <div class="relative z-10 mb-6">
                        <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md mb-6 border border-white/10">
                            <el-icon size="28" class="text-emerald-400"><Money /></el-icon>
                        </div>
                        <h2 class="text-2xl font-black mb-2">Gestor de Finanzas e IVA</h2>
                        <p class="text-slate-400 font-medium max-w-md">Lleva el control exacto de tus honorarios, facturación, declaraciones al SAT y pagos pendientes de clientes.</p>
                    </div>

                    <div class="relative z-10 flex gap-3">
                        <a :href="route('finanzas.index')" class="inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold transition-colors">
                            Abrir Gestor <el-icon class="ml-2"><ArrowRight /></el-icon>
                        </a>
                    </div>
                </div>

                <!-- Gestión de Terrenos -->
                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                            <el-icon size="24"><MapLocation /></el-icon>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Terrenos y Lotes</h3>
                        <p class="text-sm text-gray-500 font-medium mb-6">Administra los fraccionamientos, subdivisiones y el estado de cada lote.</p>
                    </div>

                    <div class="space-y-3">
                        <el-button @click="router.get(route('subdivisions.index'))" class="w-full !justify-start !rounded-xl !bg-gray-50 hover:!bg-gray-100 !border-none !text-gray-700 font-bold" size="large">
                            <el-icon class="mr-2 text-indigo-500"><Menu /></el-icon> Fraccionamientos
                        </el-button>
                        <el-button @click="router.get(route('batches.create'))" class="w-full !justify-start !rounded-xl !bg-gray-50 hover:!bg-gray-100 !border-none !text-gray-700 font-bold" size="large">
                            <el-icon class="mr-2 text-indigo-500"><Plus /></el-icon> Añadir Lote
                        </el-button>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Iconos de Element Plus
import { 
    Briefcase, 
    Document, 
    ChatDotRound, 
    Grid, 
    Plus, 
    ArrowRight, 
    Money, 
    MapLocation,
    Menu
} from '@element-plus/icons-vue';

// Props para recibir conteos reales desde el backend en el futuro.
// Por ahora, mostrarán 0 hasta que actualices tu web.php o DashboardController.
const props = defineProps({
    worksCount: {
        type: Number,
        default: 0
    },
    quotesCount: {
        type: Number,
        default: 0
    },
    messagesCount: {
        type: Number,
        default: 0
    },
    batchesCount: {
        type: Number,
        default: 0
    }
});
</script>

<style scoped>
/* Transiciones suaves para los widgets interactivos */
.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>