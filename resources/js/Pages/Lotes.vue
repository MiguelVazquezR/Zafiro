<template>
    <Head>
        <title>Ingeniería Zafiro | Lotes y Terrenos</title>
        <meta name="description" content="Descubre soluciones inmobiliarias integrales con ingeniería zafiro. Expertos en venta de terrenos, topografía, obra civil, diseño arquitectónico y acabados de lujo.">
        <meta name="keywords" content="ingeniería, construcción, materiales, terreno, lote, fraccionamientos, obras, casas, residencias, industria, medicion, topografía, cimientos, drenaje, calles, gps, deslinde">
        <meta name="robots" content="index">
        <meta name="author" content="DTW">
    </Head>

    <div class="relative bg-slate-50 min-h-screen font-sans selection:bg-primary selection:text-black">
        
        <!-- Botón Flotante de WhatsApp -->
        <a class="z-50 w-14 h-14 lg:w-16 lg:h-16 rounded-full bg-green-500 shadow-lg shadow-green-500/30 flex items-center justify-center fixed bottom-6 right-6 hover:scale-110 hover:bg-green-400 transition-all duration-300"
            href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página,%20me%20interesa%20comprar%20un%20terreno"
            target="_blank" rel="noopener noreferrer" aria-label="Contactar por WhatsApp">
            <i class="fa-brands fa-whatsapp text-3xl lg:text-4xl text-white"></i>
        </a>

        <!-- Navegación Superior -->
        <nav :class="['fixed top-0 w-full z-40 transition-all duration-300', isNavbarFixed ? 'bg-white/90 backdrop-blur-md shadow-sm py-2' : 'bg-transparent py-4']">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 flex items-center justify-between">
                <!-- Logo -->
                <div class="flex space-x-3 items-center cursor-pointer" @click="$inertia.visit('/')">
                    <img src="../../../public/images/logo_dark.png" class="h-10 lg:h-12 drop-shadow-sm transition-transform hover:scale-105" alt="Ingeniería Zafiro Logo" />
                    <span class="font-black text-primary text-xl tracking-tight hidden sm:block">INGENIERÍA ZAFIRO</span>
                </div>

                <!-- Botón Menú Móvil -->
                <button @click="showMobileMenu = !showMobileMenu" class="lg:hidden text-2xl text-slate-800 p-2 focus:outline-none">
                    <i :class="showMobileMenu ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"></i>
                </button>

                <!-- Enlaces Escritorio -->
                <div class="hidden lg:flex items-center space-x-2">
                    <button class="px-5 py-2 rounded-xl bg-primary/10 text-primary font-bold transition-colors">
                        LOTES EN VENTA
                    </button>
                    <button @click="$inertia.visit('/otros-servicios')" class="px-5 py-2 rounded-xl text-slate-600 font-semibold hover:bg-slate-100 transition-colors">
                        OTROS SERVICIOS
                    </button>
                    <a href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página%20,%20me%20interesa%20su%20servicio!"
                        target="_blank" rel="noopener noreferrer" class="ml-4 w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 hover:bg-green-50 text-slate-600 hover:text-green-500 transition-colors">
                        <i class="fa-brands fa-whatsapp text-xl"></i>
                    </a>
                </div>
            </div>

            <!-- Menú Móvil Desplegable -->
            <transition name="fade-slide">
                <div v-if="showMobileMenu" class="absolute top-full left-0 w-full bg-white/95 backdrop-blur-md shadow-lg lg:hidden flex flex-col p-4 space-y-2 border-t border-slate-100">
                    <button class="w-full text-left px-4 py-3 bg-primary/10 text-primary rounded-xl font-bold">LOTES EN VENTA</button>
                    <button @click="$inertia.visit('/otros-servicios')" class="w-full text-left px-4 py-3 text-slate-700 font-bold hover:bg-slate-50 rounded-xl">OTROS SERVICIOS</button>
                </div>
            </transition>
        </nav>

        <main class="pt-24 lg:pt-32 pb-16 space-y-16 lg:space-y-24">
            
            <!-- Hero Section (Unificado y Modernizado) -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative h-[60vh] min-h-[500px] lg:h-[600px] rounded-[32px] overflow-hidden shadow-xl group">
                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" src="../../../public/images/home_lotes.png" alt="Familia admirando su nuevo terreno">
                    
                    <!-- Capa de gradiente para legibilidad -->
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/50 to-transparent"></div>
                    
                    <div class="absolute inset-0 flex flex-col justify-center p-8 lg:p-16 max-w-2xl">
                        <span class="px-4 py-1.5 bg-primary/20 text-primary border border-primary/30 rounded-full text-xs font-bold tracking-widest uppercase mb-6 inline-block w-max backdrop-blur-sm">
                            Terrenos en Venta
                        </span>
                        <h1 class="text-4xl lg:text-6xl font-black text-white leading-[1.1] tracking-tight mb-6">
                            El sitio ideal para encontrar el <span class="text-primary">terreno perfecto</span> para ti.
                        </h1>
                        <p class="text-slate-300 text-lg mb-10 max-w-lg">
                            Desarrollos exclusivos, ubicaciones estratégicas y la certeza jurídica que tu patrimonio necesita.
                        </p>
                        
                        <a href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página,%20me%20interesa%20comprar%20un%20terreno"
                           class="bg-primary text-slate-900 px-8 py-4 rounded-full font-black text-lg hover:bg-primary/90 hover:scale-105 transition-all shadow-lg shadow-primary/20 w-max flex items-center gap-3">
                            Contactar Asesor <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Sección de Fraccionamientos -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <h2 class="text-3xl lg:text-4xl font-black text-slate-900 tracking-tight mb-4">
                        Explora nuestros desarrollos
                    </h2>
                    <p class="text-slate-500 text-lg">
                        Encuentra la ubicación que mejor se adapte a tu estilo de vida o proyecto de inversión.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <SubdivisionCard v-for="subdivision in subdivisions.data" :key="subdivision.id" :subdivision="subdivision" />
                </div>
            </section>

            <!-- Sección de Lotes Específicos -->
            <section class="bg-white rounded-[40px] lg:rounded-[64px] shadow-sm border border-slate-100 py-16 lg:py-24 px-4 sm:px-6 lg:px-8 max-w-[1400px] mx-auto">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <h2 class="text-3xl lg:text-4xl font-black text-slate-900 tracking-tight mb-4">
                        Lotes disponibles por tamaño
                    </h2>
                    <p class="text-slate-500 text-lg">
                        Descubre las diferentes opciones y medidas de terreno que tenemos listas para escriturar.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 justify-center">
                    <BatchCard v-for="batch in batchesLocal" :key="batch.id" :batch="batch" />
                </div>
                
                <!-- Botón Cargar Más -->
                <div class="text-center mt-12">
                    <p v-if="loadingItems" class="text-primary font-bold flex items-center justify-center gap-3">
                        Cargando catálogo <i class="fa-solid fa-circle-notch fa-spin"></i>
                    </p>
                    <button v-else-if="batchesLocal.length < total_batches" 
                            @click="fetchItemsByPage" 
                            class="bg-slate-900 hover:bg-slate-800 text-white px-8 py-3.5 rounded-full font-bold transition-all shadow-md flex items-center gap-3 mx-auto">
                        Ver más terrenos <i class="fa-solid fa-angle-down"></i>
                    </button>
                </div>
            </section>
        </main>

        <!-- Footer Premium -->
        <footer class="bg-slate-900 pt-16 pb-8 px-6 lg:px-12 rounded-t-[40px] lg:rounded-t-[64px] text-slate-300">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 mb-12">
                
                <!-- Columna 1: Marca -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <img class="h-12" src="../../../public/images/logo_light.png" alt="Ingeniería Zafiro Logo Light" />
                        <span class="font-black text-primary text-xl">INGENIERÍA ZAFIRO</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Construimos confianza y patrimonio. Expertos en venta de terrenos, topografía, y diseño arquitectónico con acabados de lujo.
                    </p>
                </div>

                <!-- Columna 2: Desarrollos -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-6 uppercase tracking-wider">Desarrollos</h3>
                    <ul class="space-y-3">
                        <li v-for="(lote, index) in lotes" :key="index" class="text-sm hover:text-primary cursor-pointer transition-colors flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-primary/50"></div>
                            {{ lote.title }}
                        </li>
                    </ul>
                </div>

                <!-- Columna 3: Servicios -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-6 uppercase tracking-wider">Servicios Especializados</h3>
                    <ul class="space-y-3">
                        <li v-for="(service, index) in services" :key="index" class="text-sm hover:text-primary cursor-pointer transition-colors flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-primary/50"></div>
                            {{ service.title }}
                        </li>
                    </ul>
                </div>

                <!-- Columna 4: Contacto -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-6 uppercase tracking-wider">Contáctanos</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center shrink-0 text-primary">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <span class="text-sm mt-1 break-all">jose.rod@ingenieriazafiro.dtw.com.mx</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center shrink-0 text-primary">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <span class="text-sm mt-1">3312517732</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="max-w-7xl mx-auto border-t border-slate-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-slate-500">
                    &copy; {{ currentYear }} Ingeniería Zafiro. Todos los derechos reservados.
                </p>
                <a href="https://dtw.com.mx/" target="_blank" class="flex items-center gap-2 opacity-60 hover:opacity-100 transition-opacity">
                    <span class="text-xs font-bold tracking-widest">POWERED BY</span>
                    <img class="h-6" src="@/../../public/images/DTW_logo_blanco.png" alt="DTW Logo">
                </a>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Head, Link } from "@inertiajs/vue3";
import axios from 'axios';
import SubdivisionCard from "@/Pages/Subdivision/Partials/SubdivisionCard.vue";
import BatchCard from "@/Pages/Batch/Partials/BatchCard.vue";

// Props
const props = defineProps({
    subdivisions: { type: Object, default: () => ({ data: [] }) },
    batches: { type: Object, default: () => ({ data: [] }) },
    total_batches: { type: Number, default: 0 },
});

// Estado Reactivo
const isNavbarFixed = ref(true);
const showMobileMenu = ref(false);
const lastScrollY = ref(0);
const currentYear = ref(new Date().getFullYear());

const loadingItems = ref(false);
const batchesLocal = ref([]);

// Datos Estáticos
const lotes = [
    { title: 'Fraccionamiento "Los Arrayanes"' },
    { title: 'Fraccionamiento "El Crucero"' },
];

const services = [
    { title: "Deslinde de parcelas" },
    { title: "Planos topográficos" },
    { title: "Obra civil" },
    { title: "Lotificaciones" },
    { title: "Diseño arquitectónico" },
    { title: "Acabados de lujo" },
];

// Métodos
const handleScroll = () => {
    const currentScrollY = window.scrollY;
    
    // Ocultar Navbar al bajar, Mostrar al subir (o si está hasta arriba)
    if (currentScrollY > lastScrollY.value && currentScrollY > 100) {
        isNavbarFixed.value = false;
    } else {
        isNavbarFixed.value = true;
    }

    lastScrollY.value = currentScrollY;
};

const fetchItemsByPage = async () => {
    try {
        loadingItems.value = true;
        // Asumiendo que el endpoint maneja la lógica de la página a través de la sesión o similar, 
        // o puedes agregar `?page=X` si tu Laravel usa el paginador estándar.
        const response = await axios.get(route('batches.get-by-page'));

        if (response.status === 200 && response.data.items) {
            batchesLocal.value = [...batchesLocal.value, ...response.data.items];
        }
    } catch (error) {
        console.error("Error cargando lotes:", error);
    } finally {
        loadingItems.value = false;
    }
};

// Lifecycle Hooks
onMounted(() => {
    batchesLocal.value = props.batches?.data || [];
    window.addEventListener('scroll', handleScroll);
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
/* Transición suave para el menú móvil */
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.3s ease;
}
.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>