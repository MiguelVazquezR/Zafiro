<template>
    <Head>
        <title>{{ subdivision.data.name }} | Ingeniería Zafiro</title>
        <meta name="description" content="Descubre soluciones inmobiliarias integrales con ingeniería zafiro. Expertos en venta de terrenos, topografía, obra civil, diseño arquitectónico y acabados de lujo.">
        <meta name="keywords" content="ingeniería, construcción, materiales, terreno, lote, fraccionamientos, obras, casas, residencias, industria, medicion, topografía, cimientos, drenaje, calles, gps, deslinde, parcelas, hectáreas, lotificación, cimentación, demolición">
        <meta name="robots" content="index">
        <meta name="author" content="DTW">
    </Head>

    <div class="relative bg-slate-50 min-h-screen font-sans selection:bg-primary selection:text-black">

        <!-- MODAL GALERÍA DE IMÁGENES -->
        <transition name="fade">
            <div v-if="showPreview" class="fixed inset-0 z-[60] flex justify-center items-center">
                <!-- Fondo oscuro difuminado -->
                <div @click="closePreview" class="absolute inset-0 bg-slate-900/90 backdrop-blur-md cursor-pointer"></div>
                
                <!-- Controles -->
                <button @click="closePreview" class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors z-20">
                    <i class="fa-solid fa-xmark text-4xl"></i>
                </button>
                
                <button v-if="subdivision.data.images?.length > 1" @click.stop="prevImage" class="absolute left-4 sm:left-10 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center backdrop-blur-sm transition-all z-20">
                    <i class="fa-solid fa-chevron-left text-xl"></i>
                </button>
                
                <button v-if="subdivision.data.images?.length > 1" @click.stop="nextImage" class="absolute right-4 sm:right-10 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center backdrop-blur-sm transition-all z-20">
                    <i class="fa-solid fa-chevron-right text-xl"></i>
                </button>

                <!-- Imagen Activa -->
                <div class="relative z-10 w-full max-w-5xl px-4 flex flex-col items-center">
                    <img class="w-full max-h-[80vh] object-contain rounded-xl shadow-2xl" 
                         :src="subdivision.data.images[currentImage]?.original_url" 
                         alt="Vista del Fraccionamiento">
                    <p class="text-white mt-4 font-medium tracking-widest text-sm">
                        {{ currentImage + 1 }} / {{ subdivision.data.images?.length || 1 }}
                    </p>
                </div>
            </div>
        </transition>

        <!-- BOTÓN FLOTANTE WHATSAPP -->
        <a class="z-50 w-14 h-14 lg:w-16 lg:h-16 rounded-full bg-green-500 shadow-lg shadow-green-500/30 flex items-center justify-center fixed bottom-6 right-6 hover:scale-110 hover:bg-green-400 transition-all duration-300"
            :href="whatsappLink" target="_blank" rel="noopener noreferrer" aria-label="Contactar por WhatsApp">
            <i class="fa-brands fa-whatsapp text-3xl lg:text-4xl text-white"></i>
        </a>

        <!-- NAVEGACIÓN SUPERIOR -->
        <nav :class="['fixed top-0 w-full z-40 transition-all duration-300', isNavbarFixed ? 'bg-white/90 backdrop-blur-md shadow-sm py-2' : 'bg-transparent py-4']">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 flex items-center justify-between">
                <!-- Logo -->
                <div class="flex space-x-3 items-center cursor-pointer" @click="$inertia.visit('/')">
                    <img src="../../../../public/images/logo_dark.png" class="h-10 lg:h-12 drop-shadow-sm transition-transform hover:scale-105" alt="Ingeniería Zafiro Logo" />
                    <span class="font-black text-primary text-xl tracking-tight hidden sm:block">INGENIERÍA ZAFIRO</span>
                </div>

                <!-- Botón Menú Móvil -->
                <button @click="showMobileMenu = !showMobileMenu" class="lg:hidden text-2xl text-slate-800 p-2 focus:outline-none">
                    <i :class="showMobileMenu ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"></i>
                </button>

                <!-- Enlaces Escritorio -->
                <div class="hidden lg:flex items-center space-x-2">
                    <button @click="$inertia.visit('/')" class="px-5 py-2 rounded-xl text-slate-600 font-semibold hover:bg-slate-100 transition-colors">
                        LOTES EN VENTA
                    </button>
                    <button @click="$inertia.visit('/otros-servicios')" class="px-5 py-2 rounded-xl text-slate-600 font-semibold hover:bg-slate-100 transition-colors">
                        OTROS SERVICIOS
                    </button>
                    <a href="https://api.whatsapp.com/send?phone=523329281702" target="_blank" rel="noopener noreferrer" 
                       class="ml-4 w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 hover:bg-green-50 text-slate-600 hover:text-green-500 transition-colors">
                        <i class="fa-brands fa-whatsapp text-xl"></i>
                    </a>
                </div>
            </div>

            <!-- Menú Móvil Desplegable -->
            <transition name="fade-slide">
                <div v-if="showMobileMenu" class="absolute top-full left-0 w-full bg-white/95 backdrop-blur-md shadow-lg lg:hidden flex flex-col p-4 space-y-2 border-t border-slate-100">
                    <button @click="$inertia.visit('/')" class="w-full text-left px-4 py-3 text-slate-700 font-bold hover:bg-slate-50 rounded-xl">LOTES EN VENTA</button>
                    <button @click="$inertia.visit('/otros-servicios')" class="w-full text-left px-4 py-3 text-slate-700 font-bold hover:bg-slate-50 rounded-xl">OTROS SERVICIOS</button>
                </div>
            </transition>
        </nav>

        <main class="pt-24 lg:pt-32 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb Moderno -->
            <div class="flex flex-wrap items-center gap-2 text-sm text-slate-500 font-medium mb-8 bg-white px-5 py-3 rounded-full w-max shadow-sm border border-slate-100">
                <Link href="/" class="hover:text-primary transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-house text-xs"></i> Inicio
                </Link>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                <span class="text-slate-800 font-bold">{{ subdivision.data.name }}</span>
            </div>

            <!-- Título y Encabezado -->
            <div class="mb-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-full text-[10px] font-black tracking-widest uppercase mb-3 inline-block">
                            Desarrollo en Venta
                        </span>
                        <h1 class="text-3xl lg:text-5xl font-black text-slate-900 tracking-tight">{{ subdivision.data.name }}</h1>
                        <p class="text-lg text-slate-500 mt-3 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-primary"></i> {{ subdivision.data.address }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Galería de Imágenes (Mosaico Moderno) -->
            <div class="relative grid grid-cols-1 lg:grid-cols-4 gap-3 lg:gap-4 h-[400px] lg:h-[500px] mb-12 rounded-[32px] overflow-hidden group">
                <!-- Imagen Principal -->
                <div class="lg:col-span-2 h-full cursor-pointer overflow-hidden" @click="openGallery(0)">
                    <img class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" 
                         :src="subdivision.data.images[0]?.original_url" alt="Imagen principal del desarrollo">
                </div>
                
                <!-- Grid de imágenes secundarias (Solo Desktop) -->
                <div class="hidden lg:grid grid-cols-2 grid-rows-2 col-span-2 gap-4 h-full">
                    <div v-for="i in 4" :key="i" class="h-full w-full relative overflow-hidden cursor-pointer" @click="openGallery(i)">
                        <img v-if="subdivision.data.images[i]" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" 
                             :src="subdivision.data.images[i]?.original_url" alt="Imagen secundaria">
                        <div v-else class="w-full h-full bg-slate-200"></div>
                        
                        <!-- Overlay "Ver todas" en la última imagen -->
                        <div v-if="i === 4 && subdivision.data.images?.length > 5" 
                             class="absolute inset-0 bg-slate-900/60 hover:bg-slate-900/80 transition-colors flex items-center justify-center text-white font-bold text-lg backdrop-blur-sm">
                            + Ver todas
                        </div>
                    </div>
                </div>
                
                <!-- Botón flotante para ver fotos (Móvil y Escritorio) -->
                <button @click="openGallery(0)" class="absolute bottom-6 right-6 bg-white/90 backdrop-blur-md text-slate-900 px-6 py-3 rounded-full font-bold shadow-lg hover:bg-white transition-all flex items-center gap-2">
                    <i class="fa-regular fa-images"></i> Mostrar {{ subdivision.data.images?.length || 0 }} fotos
                </button>
            </div>

            <!-- Contenido Principal Dividido (Left: Info, Right: Price Card) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                
                <!-- Columna Izquierda (Características, Descripción y Lotes) -->
                <div class="lg:col-span-2 space-y-12">
                    
                    <!-- Resumen de Características Rápidas -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="bg-white p-5 rounded-[24px] border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                            <i class="fa-solid fa-ruler-horizontal text-2xl text-primary mb-3"></i>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Superficie (Desde)</p>
                            <p class="text-lg font-black text-slate-800">{{ subdivision.data.lowest_surface }} m²</p>
                        </div>
                        <div class="bg-white p-5 rounded-[24px] border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                            <i class="fa-solid fa-check-circle text-2xl text-primary mb-3"></i>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Disponibles</p>
                            <p class="text-lg font-black text-slate-800">{{ subdivision.data.batches_availables }} Lotes</p>
                        </div>
                        <div class="bg-white p-5 rounded-[24px] border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                            <i class="fa-solid fa-layer-group text-2xl text-primary mb-3"></i>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tipo</p>
                            <p class="text-sm font-black text-slate-800">{{ subdivision.data.type || 'N/A' }}</p>
                        </div>
                        <!-- Planos (Si existen) -->
                        <a v-if="subdivision.data.planos?.length > 0" :href="subdivision.data.planos[0]?.original_url" target="_blank"
                           class="bg-indigo-50 p-5 rounded-[24px] border border-indigo-100 shadow-sm flex flex-col items-center justify-center text-center hover:bg-indigo-100 transition-colors cursor-pointer group">
                            <i class="fa-solid fa-file-pdf text-2xl text-indigo-500 mb-3 group-hover:scale-110 transition-transform"></i>
                            <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-1">Planos</p>
                            <p class="text-sm font-black text-indigo-700">Ver Archivo</p>
                        </a>
                        <div v-else class="bg-slate-50 p-5 rounded-[24px] border border-slate-100 flex flex-col items-center justify-center text-center opacity-60">
                            <i class="fa-solid fa-file-pdf text-2xl text-slate-400 mb-3"></i>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Planos</p>
                            <p class="text-sm font-bold text-slate-500">No disp.</p>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="bg-white p-8 sm:p-10 rounded-[32px] shadow-sm border border-slate-100">
                        <h2 class="text-2xl font-black text-slate-900 mb-6">Acerca del Desarrollo</h2>
                        <p class="text-slate-600 leading-relaxed text-lg whitespace-pre-line">{{ subdivision.data.description }}</p>
                    </div>

                    <!-- Amenidades -->
                    <div v-if="subdivision.data.amenities?.length" class="bg-white p-8 sm:p-10 rounded-[32px] shadow-sm border border-slate-100">
                        <h2 class="text-2xl font-black text-slate-900 mb-6">Amenidades Exclusivas</h2>
                        <div class="flex flex-wrap items-center gap-3">
                            <div v-for="amenity in subdivision.data.amenities" :key="amenity" 
                                 class="flex items-center gap-3 px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-slate-700 font-medium">
                                <i :class="getAmenityIcon(amenity)" class="text-primary text-lg"></i>
                                {{ amenity }}
                            </div>
                        </div>
                    </div>

                    <!-- Ubicación en Mapa -->
                    <div v-if="subdivision.data.lon && subdivision.data.lat" class="bg-white p-8 sm:p-10 rounded-[32px] shadow-sm border border-slate-100">
                        <h2 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                            Ubicación <i class="fa-solid fa-location-arrow text-primary"></i>
                        </h2>
                        <div class="rounded-[24px] overflow-hidden border border-slate-200 shadow-inner h-[400px]">
                            <div ref="mapContainer" class="w-full h-full"></div>
                        </div>
                    </div>

                </div>

                <!-- Columna Derecha (Tarjeta Sticky de Contacto) -->
                <div class="lg:col-span-1">
                    <div class="sticky top-32 bg-white rounded-[32px] shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Lotes desde</p>
                        <p class="text-4xl font-black text-slate-900 mb-6">${{ formatCurrency(subdivision.data.lowest_price) }} <span class="text-lg font-bold text-slate-400">MXN</span></p>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-center py-3 border-b border-slate-100">
                                <span class="text-slate-500 font-medium">Lotes disponibles</span>
                                <span class="text-slate-900 font-bold text-right">{{ subdivision.data.batches_availables }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-slate-100">
                                <span class="text-slate-500 font-medium">Dimensiones desde</span>
                                <span class="text-slate-900 font-bold text-right">{{ subdivision.data.lowest_surface }} m²</span>
                            </div>
                        </div>

                        <a :href="whatsappLink" target="_blank" rel="noopener noreferrer" 
                           class="w-full bg-slate-900 text-white px-8 py-4 rounded-full font-black text-lg hover:bg-slate-800 hover:scale-[1.02] transition-all flex items-center justify-center gap-3 shadow-lg">
                            Agendar Visita <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        
                        <p class="text-center text-xs text-slate-400 font-medium mt-4">
                            Recibe asesoría personalizada sin costo.
                        </p>
                    </div>
                </div>

            </div>
            
            <!-- Listado de Lotes del Fraccionamiento -->
            <div class="mt-20 pt-16 border-t border-slate-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Lotes disponibles</h2>
                        <p class="text-slate-500 text-lg mt-1">Explora los terrenos dentro de {{ subdivision.data.name }}</p>
                    </div>
                </div>

                <div v-if="subdivision.data.batches?.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-6 justify-center">
                    <BatchCard v-for="batch in subdivision.data.batches" :key="batch.id" :batch="batch" />
                </div>
                
                <div v-else class="bg-white rounded-[32px] shadow-sm border border-slate-100 p-16 text-center flex flex-col items-center">
                    <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-tags text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">No hay lotes publicados aún</h3>
                    <p class="text-slate-500 max-w-sm mx-auto">Pronto agregaremos los lotes individuales para este fraccionamiento.</p>
                </div>
            </div>
            
        </main>

        <!-- FOOTER PREMIUM -->
        <footer class="bg-slate-900 pt-16 pb-8 px-6 lg:px-12 rounded-t-[40px] lg:rounded-t-[64px] text-slate-300 mt-20">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 mb-12">
                
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <img class="h-12" src="../../../../public/images/logo_light.png" alt="Ingeniería Zafiro Logo Light" />
                        <span class="font-black text-primary text-xl">INGENIERÍA ZAFIRO</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Construimos confianza y patrimonio. Expertos en venta de terrenos, topografía, y diseño arquitectónico con acabados de lujo.
                    </p>
                </div>

                <div>
                    <h3 class="text-white font-bold text-lg mb-6 uppercase tracking-wider">Desarrollos</h3>
                    <ul class="space-y-3">
                        <li v-for="(lote, index) in lotes" :key="index" class="text-sm hover:text-primary cursor-pointer transition-colors flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-primary/50"></div>
                            {{ lote.title }}
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-bold text-lg mb-6 uppercase tracking-wider">Servicios Especializados</h3>
                    <ul class="space-y-3">
                        <li v-for="(service, index) in services" :key="index" class="text-sm hover:text-primary cursor-pointer transition-colors flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-primary/50"></div>
                            {{ service.title }}
                        </li>
                    </ul>
                </div>

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
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Head, Link } from "@inertiajs/vue3";
import BatchCard from "@/Pages/Batch/Partials/BatchCard.vue";

const props = defineProps({
    subdivision: {
        type: Object,
        required: true
    }
});

// ESTADO Y VARIABLES LOCALES
const isNavbarFixed = ref(true);
const showMobileMenu = ref(false);
const showPreview = ref(false);
const currentImage = ref(0);
const lastScrollY = ref(0);
const currentYear = ref(new Date().getFullYear());
const mapContainer = ref(null);

// Lógica pre-armada de enlace WhatsApp para evitar cadenas gigantes
const whatsappLink = computed(() => {
    const text = encodeURIComponent(`Hola! vi la página, me interesa información sobre el desarrollo: ${props.subdivision.data.name}`);
    return `https://api.whatsapp.com/send?phone=523329281702&text=${text}`;
});

// DATOS ESTÁTICOS PARA FOOTER
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

// FUNCIONES UTILITARIAS Y EVENTOS
const handleScroll = () => {
    const currentScrollY = window.scrollY;
    if (currentScrollY > lastScrollY.value && currentScrollY > 100) {
        isNavbarFixed.value = false;
    } else {
        isNavbarFixed.value = true;
    }
    lastScrollY.value = currentScrollY;
};

const formatCurrency = (amount) => {
    const num = amount?.numberFormat ?? amount;
    if (!num) return '0.00';
    if (typeof num === 'string' && num.includes(',')) return num; 
    return Number(num).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

const getAmenityIcon = (amenity) => {
    const icons = {
        'Espacio para niños': 'fa-solid fa-child-reaching',
        'Piscina': 'fa-solid fa-person-swimming',
        'Salón de eventos': 'fa-solid fa-people-roof',
        'Áreas verdes': 'fa-solid fa-leaf',
        'Parque': 'fa-solid fa-tree',
        'Gimnasio': 'fa-solid fa-dumbbell'
    };
    return icons[amenity] || 'fa-solid fa-check';
};

// Funciones Galería Modal
const openGallery = (index) => {
    if (props.subdivision.data.images && props.subdivision.data.images[index]) {
        currentImage.value = index;
        showPreview.value = true;
    }
};

const closePreview = () => {
    showPreview.value = false;
};

const nextImage = () => {
    const total = props.subdivision.data.images?.length || 1;
    currentImage.value = currentImage.value === (total - 1) ? 0 : currentImage.value + 1;
};

const prevImage = () => {
    const total = props.subdivision.data.images?.length || 1;
    currentImage.value = currentImage.value === 0 ? (total - 1) : currentImage.value - 1;
};

// Integración Google Maps
const initMap = () => {
    if (window.google && props.subdivision.data.lat && props.subdivision.data.lon) {
        const coords = { 
            lat: parseFloat(props.subdivision.data.lat), 
            lng: parseFloat(props.subdivision.data.lon) 
        };
        
        const map = new google.maps.Map(mapContainer.value, {
            center: coords,
            zoom: 15,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
        });

        new google.maps.Marker({
            position: coords,
            map: map,
            title: props.subdivision.data.name,
            animation: google.maps.Animation.DROP
        });
    }
};

// CICLO DE VIDA
onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    if (mapContainer.value) {
        initMap();
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
/* Transiciones Suaves para Modales y Menús */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.4s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

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