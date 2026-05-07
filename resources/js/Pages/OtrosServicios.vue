<template>
    <Head>
        <title>Ingeniería Zafiro | Otros Servicios</title>
        <meta name="description" content="Descubre soluciones inmobiliarias integrales con ingeniería zafiro. Expertos en venta de terrenos, topografía, obra civil, diseño arquitectónico y acabados de lujo.">
        <meta name="keywords" content="ingeniería, construcción, materiales, terreno, lote, fraccionamientos, obras, casas, residencias, industria, medicion, topografía, cimientos, drenaje, calles, gps, deslinde, parcelas, hectáreas, lotificación, cimentación, demolición">
        <meta name="robots" content="index">
        <meta name="author" content="DTW">
    </Head>

    <div class="relative bg-slate-50 min-h-screen font-sans selection:bg-primary selection:text-black">

        <!-- MODAL DE VISTA PREVIA (Servicios) -->
        <transition name="fade">
            <div v-if="showPreview" class="fixed inset-0 z-[60] flex justify-center items-center">
                <!-- Fondo oscuro difuminado -->
                <div @click="closePreview" class="absolute inset-0 bg-slate-900/80 backdrop-blur-md cursor-pointer"></div>
                
                <!-- Contenido del modal -->
                <div class="relative z-10 w-full max-w-4xl px-4 flex flex-col items-center">
                    <button @click="closePreview" class="absolute -top-12 right-4 text-white hover:text-primary transition-colors">
                        <i class="fa-solid fa-xmark text-4xl"></i>
                    </button>
                    <h2 class="text-primary font-black text-3xl md:text-4xl text-center mb-8 drop-shadow-md">
                        {{ services[currentServiceIndex].title }}
                    </h2>
                    <img class="w-full max-h-[60vh] object-contain rounded-2xl shadow-2xl" :src="services[currentServiceIndex].image" alt="Detalle del servicio">
                    
                    <button @click="goToContact" class="mt-10 bg-primary text-slate-900 px-8 py-3.5 rounded-full font-black text-lg hover:bg-primary/90 hover:scale-105 transition-all shadow-lg shadow-primary/20 flex items-center gap-3">
                        Solicitar Presupuesto <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </transition>

        <!-- Botón Flotante de WhatsApp -->
        <a class="z-50 w-14 h-14 lg:w-16 lg:h-16 rounded-full bg-green-500 shadow-lg shadow-green-500/30 flex items-center justify-center fixed bottom-6 right-6 hover:scale-110 hover:bg-green-400 transition-all duration-300"
            href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página%20,%20me%20interesa%20su%20servicio!"
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
                    <button @click="$inertia.visit('/')" class="px-5 py-2 rounded-xl text-slate-600 font-semibold hover:bg-slate-100 transition-colors">
                        LOTES EN VENTA
                    </button>
                    <button class="px-5 py-2 rounded-xl bg-primary/10 text-primary font-bold transition-colors">
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
                    <button @click="$inertia.visit('/')" class="w-full text-left px-4 py-3 text-slate-700 font-bold hover:bg-slate-50 rounded-xl">LOTES EN VENTA</button>
                    <button class="w-full text-left px-4 py-3 bg-primary/10 text-primary rounded-xl font-bold">OTROS SERVICIOS</button>
                </div>
            </transition>
        </nav>

        <main class="pt-24 lg:pt-32 pb-16 space-y-16 lg:space-y-24">
            
            <!-- Hero Section: Nosotros & Tecnología -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-slate-900 rounded-[32px] lg:rounded-[48px] overflow-hidden shadow-xl relative min-h-[500px] flex items-center">
                    
                    <!-- Fondos decorativos -->
                    <div class="absolute -left-32 -top-32 w-96 h-96 bg-primary/20 rounded-full blur-3xl opacity-50"></div>
                    <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl opacity-50"></div>

                    <div class="relative z-10 flex flex-col lg:flex-row items-stretch w-full h-full">
                        
                        <!-- Texto (Lado Izquierdo) -->
                        <div class="flex-1 p-8 lg:p-16 flex flex-col justify-center text-center lg:text-left">
                            <span class="px-4 py-1.5 bg-primary/20 text-primary border border-primary/30 rounded-full text-xs font-bold tracking-widest uppercase mb-6 inline-block w-max mx-auto lg:mx-0">
                                ¿Por qué elegirnos?
                            </span>
                            <transition name="fade" mode="out-in">
                                <div :key="currentKirbyIndex">
                                    <h1 class="text-4xl lg:text-5xl font-black text-primary leading-tight tracking-tight mb-6">
                                        {{ kirby[currentKirbyIndex].title }}
                                    </h1>
                                    <p class="text-slate-300 text-lg lg:text-xl leading-relaxed max-w-lg mx-auto lg:mx-0">
                                        {{ kirby[currentKirbyIndex].description }}
                                    </p>
                                </div>
                            </transition>
                            
                            <div class="mt-10 flex items-center justify-center lg:justify-start gap-4">
                                <button @click="prevKirby" class="w-12 h-12 rounded-full border border-slate-700 text-slate-300 hover:bg-slate-800 hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>
                                <div class="flex gap-2">
                                    <span v-for="(k, idx) in kirby" :key="idx" 
                                          class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                                          :class="currentKirbyIndex === idx ? 'bg-primary w-6' : 'bg-slate-700'">
                                    </span>
                                </div>
                                <button @click="nextKirby" class="w-12 h-12 rounded-full border border-slate-700 text-slate-300 hover:bg-slate-800 hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Imagen (Lado Derecho) -->
                        <div class="flex-1 relative min-h-[300px] lg:min-h-full p-4 lg:p-8 flex items-center justify-center">
                            <transition name="fade" mode="out-in">
                                <img :key="currentKirbyIndex" 
                                     class="w-full h-full object-cover rounded-2xl lg:rounded-[32px] shadow-2xl" 
                                     :src="kirby[currentKirbyIndex].image" 
                                     alt="Imagen representativa de la empresa">
                            </transition>
                        </div>

                    </div>
                </div>
            </section>

            <!-- Sección de Servicios -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
                    <h2 class="text-3xl lg:text-4xl font-black text-slate-900 tracking-tight mb-4">
                        Nuestros Servicios
                    </h2>
                    <p class="text-slate-500 text-lg">
                        Soluciones integrales de ingeniería, topografía y construcción con los más altos estándares de calidad.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="(service, index) in services" :key="index"
                         class="group bg-white rounded-[32px] overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col">
                        
                        <!-- Imagen superior -->
                        <div class="h-56 relative overflow-hidden bg-slate-100 cursor-pointer" @click="openPreview(index)">
                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                 :src="service.image" 
                                 alt="Representación del servicio">
                            <!-- Overlay Hover -->
                            <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/30 transition-colors flex items-center justify-center">
                                <div class="w-12 h-12 bg-primary text-slate-900 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all scale-75 group-hover:scale-100">
                                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Texto Inferior -->
                        <div class="p-8 flex-1 flex flex-col">
                            <h3 class="text-xl font-bold text-slate-900 mb-3">{{ service.title }}</h3>
                            <p class="text-slate-500 leading-relaxed mb-6 flex-1">{{ service.description }}</p>
                            <button @click="goToContact" class="text-primary font-bold hover:text-slate-900 transition-colors flex items-center gap-2 mt-auto w-max">
                                Solicitar servicio <i class="fa-solid fa-arrow-right text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sección Proyectos & Contacto -->
            <section id="Contacto" class="bg-white rounded-[40px] lg:rounded-[64px] shadow-sm border border-slate-100 py-16 lg:py-24 px-4 sm:px-6 lg:px-8 max-w-[1400px] mx-auto">
                <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                    
                    <!-- Lado Izquierdo: Carrusel de Proyectos -->
                    <div class="relative bg-slate-50 rounded-[32px] p-6 sm:p-10 border border-slate-100 text-center flex flex-col items-center">
                        <span class="text-primary font-black uppercase tracking-widest text-sm mb-2">Galería de Obras</span>
                        <h2 class="text-3xl font-black text-slate-900 mb-8">Nuestros Proyectos</h2>
                        
                        <div class="relative w-full aspect-square sm:aspect-video lg:aspect-square overflow-hidden rounded-2xl shadow-lg mb-8">
                            <transition name="fade" mode="out-in">
                                <img :key="currentProyectIndex" 
                                     class="w-full h-full object-cover" 
                                     :src="projects[currentProyectIndex].image" 
                                     alt="Imagen del proyecto">
                            </transition>
                            <!-- Etiqueta sobre la imagen -->
                            <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/80 to-transparent p-6">
                                <p class="text-white text-lg font-medium drop-shadow-md">
                                    {{ projects[currentProyectIndex].description }}
                                </p>
                            </div>
                        </div>

                        <!-- Controles -->
                        <div class="flex items-center justify-center gap-6 w-full">
                            <button @click="prevProject" class="w-12 h-12 rounded-full bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 hover:text-primary flex items-center justify-center transition-all shadow-sm">
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                            <span class="text-sm font-bold text-slate-400">
                                {{ currentProyectIndex + 1 }} / {{ projects.length }}
                            </span>
                            <button @click="nextProject" class="w-12 h-12 rounded-full bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 hover:text-primary flex items-center justify-center transition-all shadow-sm">
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Lado Derecho: Formulario de Contacto -->
                    <div class="flex flex-col justify-center">
                        <h2 class="text-3xl lg:text-4xl font-black text-slate-900 tracking-tight mb-2">
                            Ponte en contacto
                        </h2>
                        <p class="text-slate-500 mb-10 text-lg">
                            Déjanos tus datos y nos comunicaremos contigo a la brevedad para asesorarte.
                        </p>

                        <form @submit.prevent="store" class="space-y-6">
                            <!-- Nombre -->
                            <div>
                                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nombre completo <span class="text-primary">*</span></label>
                                <input v-model="form.name" type="text" id="name" required ref="nameInput"
                                    class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all"
                                    placeholder="Ej. Juan Pérez" />
                                <InputError :message="form.errors?.name" class="mt-1" />
                            </div>

                            <!-- Teléfono y Servicio -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone" class="block text-sm font-bold text-slate-700 mb-2">Teléfono <span class="text-primary">*</span></label>
                                    <input v-model="form.phone" type="tel" id="phone" required
                                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all"
                                        placeholder="10 dígitos" />
                                    <InputError :message="form.errors?.phone" class="mt-1" />
                                </div>
                                <div>
                                    <label for="service" class="block text-sm font-bold text-slate-700 mb-2">Servicio de interés</label>
                                    <select v-model="form.service" id="service"
                                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all appearance-none">
                                        <option value="" disabled selected>Selecciona una opción</option>
                                        <option v-for="(service, index) in services" :key="index" :value="service.title">{{ service.title }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Dirección -->
                            <div>
                                <label for="address" class="block text-sm font-bold text-slate-700 mb-2">Dirección del proyecto</label>
                                <input v-model="form.address" type="text" id="address"
                                    class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all"
                                    placeholder="Ubicación aproximada" />
                                <InputError :message="form.errors?.address" class="mt-1" />
                            </div>

                            <!-- Mensaje -->
                            <div>
                                <label for="message" class="block text-sm font-bold text-slate-700 mb-2">Mensaje <span class="text-primary">*</span></label>
                                <textarea v-model="form.message" id="message" rows="4" required
                                    class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all resize-none"
                                    placeholder="Cuéntanos más sobre lo que necesitas..."></textarea>
                                <InputError :message="form.errors?.message" class="mt-1" />
                            </div>

                            <!-- Botón Enviar -->
                            <button type="submit" :disabled="form.processing"
                                class="w-full sm:w-auto bg-slate-900 text-white px-10 py-4 rounded-full font-black text-lg hover:bg-slate-800 hover:scale-[1.02] disabled:opacity-70 disabled:hover:scale-100 transition-all flex items-center justify-center gap-3 shadow-lg">
                                Enviar Mensaje <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>

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
import { Head, Link, useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import InputError from "@/Components/InputError.vue";

// Services images
import s1 from "../../../public/images/services1.png";
import s2 from "../../../public/images/services2.png";
import s3 from "../../../public/images/services3.png";
import s4 from "../../../public/images/services4.png";
import s5 from "../../../public/images/services5.png";
import s6 from "../../../public/images/services6.png";

// Home images
import h1 from "../../../public/images/h1.png";
import h2 from "../../../public/images/h2.png";

// Projects images
import p1 from "../../../public/images/p1.png";
import p2 from "../../../public/images/p2.png";
import p4 from "../../../public/images/p4.png";
import p5 from "../../../public/images/p5.png";
import p7 from "../../../public/images/p7.png";
import p8 from "../../../public/images/p8.png";
import p9 from "../../../public/images/p9.png";
import p10 from "../../../public/images/p10.png";
import p11 from "../../../public/images/p11.png";
import p13 from "../../../public/images/p13.png";
import p14 from "../../../public/images/p14.png";
import p17 from "../../../public/images/p17.png";
import p18 from "../../../public/images/p18.png";
import p19 from "../../../public/images/p19.png";

// Estado Reactivo
const isNavbarFixed = ref(true);
const showMobileMenu = ref(false);
const lastScrollY = ref(0);
const currentYear = ref(new Date().getFullYear());

const showPreview = ref(false);
const currentServiceIndex = ref(0);
const currentKirbyIndex = ref(0);
const currentProyectIndex = ref(0);
const nameInput = ref(null);

const toast = useToast();

const form = useForm({
    name: null,
    address: null,
    phone: null,
    service: "",
    message: null,
});

// Datos Estáticos
const lotes = [
    { title: 'Fraccionamiento "Los Arrayanes"' },
    { title: 'Fraccionamiento "El Crucero"' },
];

const services = [
    { title: "Deslinde de parcelas", description: "Definimos de manera precisa las fronteras de tu parcela para garantizar la propiedad y el uso adecuado de la tierra.", image: s1 },
    { title: "Planos topográficos", description: "Brindamos una visión detallada y precisa del terreno. Ya sea para proyectos de construcción, planificación urbana o análisis del terreno.", image: s2 },
    { title: "Obra civil", description: "Desde el diseño hasta la construcción, gestionamos cada etapa del proceso con precisión y profesionalismo.", image: s3 },
    { title: "Lotificaciones", description: "Convertimos terrenos en comunidades planificadas, creamos espacios funcionales y atractivos.", image: s4 },
    { title: "Diseño arquitectónico", description: "Desde residencias hasta espacios comerciales, cada diseño es una expresión de tus necesidades y estilos.", image: s5 },
    { title: "Experiencia con acabados de lujo", description: "Desde selecciones de materiales exclusivos hasta ejecución impecable, creamos ambientes que reflejan tu gusto refinado.", image: s6 },
];

const kirby = [
    { title: "Nosotros", description: "Contamos con amplia experiencia en el ramo de la construcción e ingeniería. Tu proyecto en las mejores manos.", image: h1 },
    { title: "Tecnología avanzada", description: "Nuestro compromiso con tecnología de vanguardia nos lleva a emplear el potente receptor GNSS Reach RS+ de Emlid.", image: h2 },
];

const projects = [
    { description: "Pulido de losa", image: p1 },
    { description: "Cimentación y compactación de material", image: p2 },
    { description: "Mecánica de suelos", image: p4 },
    { description: "Demolición y retiro de escombro", image: p5 },
    { description: "Replanteo de puntos con GPS", image: p7 },
    { description: "Cimbrado, armado y colado estructural", image: p8 },
    { description: "Mecánica de suelos (Prueba SPT)", image: p9 },
    { description: "Carga y acarreo de escombro", image: p10 },
    { description: "Cimentación de mampostería", image: p11 },
    { description: "Experiencia en proyectos verticales", image: p13 },
    { description: "Armado de losa", image: p14 },
    { description: "Construcción de cárcamo pluvial", image: p17 },
    { description: "Estructura de acero", image: p18 },
    { description: "Muro con ladrillo aparente", image: p19 },
];

// Métodos
const handleScroll = () => {
    const currentScrollY = window.scrollY;
    if (currentScrollY > lastScrollY.value && currentScrollY > 100) {
        isNavbarFixed.value = false;
    } else {
        isNavbarFixed.value = true;
    }
    lastScrollY.value = currentScrollY;
};

// Navegación Carruseles
const nextKirby = () => {
    currentKirbyIndex.value = currentKirbyIndex.value === kirby.length - 1 ? 0 : currentKirbyIndex.value + 1;
};
const prevKirby = () => {
    currentKirbyIndex.value = currentKirbyIndex.value === 0 ? kirby.length - 1 : currentKirbyIndex.value - 1;
};

const nextProject = () => {
    currentProyectIndex.value = currentProyectIndex.value === projects.length - 1 ? 0 : currentProyectIndex.value + 1;
};
const prevProject = () => {
    currentProyectIndex.value = currentProyectIndex.value === 0 ? projects.length - 1 : currentProyectIndex.value - 1;
};

// Modal de Servicios
const openPreview = (index) => {
    currentServiceIndex.value = index;
    showPreview.value = true;
};
const closePreview = () => {
    showPreview.value = false;
};

// Formulario
const goToContact = () => {
    showPreview.value = false;
    const section = document.getElementById('Contacto');
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
        setTimeout(() => {
            if (nameInput.value) nameInput.value.focus();
        }, 500);
    }
};

const store = () => {
    form.post(route("messages.store"), {
        onSuccess: () => {
            toast.success("Mensaje enviado correctamente", { timeout: 5000 });
            form.reset();
        },
        onError: () => {
            toast.error("Por favor completa correctamente el formulario", { timeout: 5000 });
            goToContact();
        }
    });
};

// Lifecycle Hooks
onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
/* Transiciones Suaves (Fade y Slides) */
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