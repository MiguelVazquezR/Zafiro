<template>
    <Head title="Bienvenido" />
    <div class="relative">

        <div v-if="showPreview" class="fixed inset-0 bg-black opacity-90 z-20"></div>

        <!-- Imagen centrada sin opacidad -->
        <div v-if="showPreview" @click="showPreview = false; currentServiceIndex = null;"
            class="fixed inset-0 flex justify-center items-center z-30">
            <div class="relative">
                <h1 class="text-[#FFD700] my-9 text-3xl text-center">{{ services[currentServiceIndex].title }}</h1>
                <img class="w-[600px] mx-auto opacity-100" :src="services[currentServiceIndex].image">
                <button @click="goToContact"
                    class="mx-auto mt-11 text-2xl rounded-full border-2 border-[#FFD700] text-black dark:text-black flex justify-between items-center py-1 px-5 bg-[#FFD700] transition-all">
                    Contáctanos
                </button>
            </div>
        </div>

        <!-- whatsapp button -->
        <a class="md:hidden z-50 w-14 h-14 lg:w-20 lg:h-20 rounded-full bg-green-600 shadow-md shadow-green-800/100 flex items-center justify-center fixed bottom-3 right-3 hover:scale-105"
            href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página%20,%20me%20interesa%20su%20servicio!"
            target="_blank" rel="noopener noreferrer">
            <i class="fa-brands fa-beat fa-whatsapp text-2xl lg:text-4xl text-gray-100"></i>
        </a>

        <!-- mobile menu (hamburger) -->
        <div v-if="showMobileMenu"
            class="flex flex-col z-30 w-2/3 bg-[#262626] rounded-xl fixed top-24 right-5 border-white border py-1 text-white">
            <button class="mx-1 py-2 bg-[#FFD700] rounded-lg">LOTES</button>
            <button class="mx-1 py-2 hover:bg-[#FFD700] rounded-lg" @click="$inertia.visit('/otros-servicios')">OTROS SERVICIOS</button>
            <!-- <button class="mx-1 py-2 hover:bg-[#FFD700] rounded-lg" @click="scrollToSection('InicioM')">Inicio</button>
            <button class="mx-1 py-2 hover:bg-[#FFD700] rounded-lg" @click="scrollToSection('Servicios')">Servicios</button>
            <button class="mx-1 py-2 hover:bg-[#FFD700] rounded-lg" @click="scrollToSection('Contacto')">Proyectos</button>
            <button class="mx-1 py-2 hover:bg-[#FFD700] rounded-lg" @click="scrollToSection('Contacto')">Contacto</button> -->
        </div>

        <!-- navbar -->
        <nav :class="['navbar', { 'fixed-navbar': isNavbarFixed }]"
            class="flex items-center justify-between py-4 lg:px-10 px-6 w-full dark:bg-white">
            <div class="flex space-x-2 items-center">
                <img src="../../../public/images/logo_dark.png" class="h-14" alt="logo" />
                <span class="font-bold text-[#FFD700] text-xl">INGENIERÍA ZAFIRO</span>
            </div>
            <button @click="showMobileMenu = !showMobileMenu" class="lg:hidden">
                <i class="fa-solid fa-bars text-xl text-[#FFD700]"></i>
            </button>
            <div class="mr-12 hidden lg:inline">        
                <button class="mx-2 rounded-sm px-1 py-px bg-[#FFD700]">LOTES</button>
                <button class="mx-2 rounded-sm px-1 py-px hover:bg-[#FFD700] transition-colors ease-linear duration-200" @click="$inertia.visit('/otros-servicios')">OTROS SERVICIOS</button>
                <a href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página%20,%20me%20interesa%20su%20servicio!" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp text-xl ml-2"></i>
                </a>
                <!-- <button class="mx-2 rounded-[10px] px-1 py-px hover:bg-[#FFD700] hover:text-white"
                    @click="scrollToSection('Inicio')">Inicio</button>
                <button class="mx-2 rounded-[10px] px-1 py-px hover:bg-[#FFD700] hover:text-white"
                    @click="scrollToSection('Servicios')">Servicios</button>
                <button class="mx-2 rounded-[10px] px-1 py-px hover:bg-[#FFD700] hover:text-white"
                    @click="scrollToSection('Contacto')">Proyectos</button>
                <button class="mx-2 rounded-[10px] px-1 py-px hover:bg-[#FFD700] hover:text-white"
                    @click="scrollToSection('Contacto')">Contacto</button> -->
            </div>
        </nav>

        <main class="pt-20">
            <!-- home desktop-->
            <section class="hidden md:block lg:mx-24 mx-1 mt-9 relative mb-28" id="Inicio">
                <div class="rounded-[20px] h-[500px] relative">
                    <img class="mx-auto w-[90%] h-[120%]" src="../../../public/images/home_lotes.png">

                    <div class="absolute bottom-3 left-28 h-1/2 w-[500px]">
                        <p class="text-white text-3xl">EL SITIO IDEAL PARA ENCONTRAR EL  TERRERNO PERFECTO PARA TI </p>
                        <p class="text-[#D9D9D9] text-3xl mt-5">TERRENOS EN VENTA </p>
                        <button @click="goToContact"
                            class="ml-auto mr-2 mt-14 text-xl rounded-full border-2 border-[#FFD700] text-black flex justify-between items-center py-1 px-5 bg-[#FFD700] transition-all">
                            Contáctanos
                        </button>
                    </div>
                </div>
            </section>

            <!-- home mobile-->
            <section class="md:hidden mx-4 mt-9 relative mb-16" id="InicioM">
                <div class="rounded-[20px] h-[300px] relative">
                    <img class="absolute right-0 top-0 h-[100%]" src="../../../public/images/home_lotes.png">
                    <div class="absolute bottom-16 left-1 h-1/2 w-[230px]">
                        <p class="text-white text-base">EL SITIO IDEAL PARA ENCONTRAR EL  TERRERNO PERFECTO PARA TI </p>
                        <p class="text-[#D9D9D9] text-base mt-5">TERRENOS EN VENTA </p>
                        <button @click="goToContact"
                            class="ml-auto mr-16 mt-14 text-sm rounded-full border-2 border-[#FFD700] text-black flex justify-between items-center py-1 px-5 bg-[#FFD700] transition-all">
                            Contáctanos
                        </button>
                    </div>
                </div>
            </section>

            <!-- Services -->
            <section class="lg:mx-24 mx-1 relative mb-32" id="Servicios">
                <h2 class="font-bold mb-10 text-3xl text-center">Explora nuestros fraccionamientos y encuentra tu lote perfecto.</h2>

                <div class="grid xs:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-44">
                    <!-- componente de lote de fraccionamiento ------------------- -->
                    <div class="rounded-[20px] border border-[#D9D9D9] shadow-md min-h-[530px] text-2xl relative cursor-pointer">
                        <figure class="h-1/2 bg-slate-500 rounded-t-[20px]">
                            <!-- <img src="" alt=""> -->
                        </figure>
                        <div class="p-5">
                            <p class="text-gray-500 text-right text-base">- {{ 'Lotes residenciales' }}</p>
                            <p class="text-[#4D4D4D] text-xl mt-4"> {{ 'Fraccionameinto “Los Arrallanes”' }}</p>
                            <p class="text-[#4D4D4D] text-base mt-1"> {{ 'Lotes desde' }}</p>
                            <p class="text-black text-3xl font-bold mt-1"> ${{ '   390,000' }}</p>
                            <p class="text-[#4D4D4D] text-base mt-1"><i class="fa-solid fa-location-dot mr-2"></i>{{ 'Tizapán el Alto, Jalisco.' }}</p>
                            <p class="text-[#4D4D4D] text-base mt-1"><i class="fa-solid fa-ruler-horizontal mr-2"></i>{{ 'Desde 900 m2' }}</p>
                            <p class="text-[#4D4D4D] text-base mt-1">{{ 'Disponibles: 64 lotes' }}</p>
                        </div>
                    </div>  
                </div>
            </section>
            
            <section class="bg-[#EDEDED] rounded-t-[70px] px-32 py-14">
                <h2 class="mb-9 text-3xl ml-3 font-bold">Descubre los diferentes tamaños de terreno que tenemos para ti. </h2>
                <!-- componente de terreno individual --------- -->
                <div class="bg-white rounded-xl border shadow-md p-3 w-[550px] h-[220px] flex space-x-4 cursor-pointer">
                    <figure class="rounded-md w-[235px] bg-gray-500">
                        <!-- <img src="" alt=""> -->
                    </figure>

                    <div class="w-[315px]">
                        <p class="text-base text-right">{{'Frac. Los Arrallanes'}}</p>
                        <p class="text-base text-[#4D4D4D]">Venta de terreno/lote</p>
                        <p class="text-2xl font-bold">{{ '$74,000' }}</p>
                        <p class="text-xl">{{ '2.7 hectáreas' }}</p>
                        <p class="text-[#4D4D4D] text-base mt-1"><i class="fa-solid fa-location-dot mr-2"></i>{{ 'Tizapán el Alto, Jalisco.' }}</p>
                        <p class="text-[#4D4D4D] text-base mt-1"><i class="fa-solid fa-ruler-horizontal mr-2"></i>{{ '32 x 34 m' }}</p>
                    </div>
                </div>
                <div class="text-center mt-7">
                    <i class="fa-solid fa-angle-down text-white bg-black rounded-full p-2 cursor-pointer"></i>
                </div>
            </section>
            <footer class="p-4 md:grid grid-cols-4 gap-3 text-white bg-[#1A1A1A] md:relative">
                <figure class="h-full md:h-auto flex md:flex-col items-center space-y-2">
                    <img class="h-16 md:h-auto" src="../../../public/images/logo_light.png" />
                    <span class="font-bold text-[#FFD700] text-xl">INGENIERÍA ZAFIRO</span>
                </figure>
                <div class="flex flex-col">
                    <h2 class="text-xl text-[#FFD700] font-bold mb-5">Venta de terrenos</h2>
                    <li v-for="(lote, index) in lotes" :key="index">{{ lote.title }}</li>
                </div>
                <div class="flex flex-col">
                    <h2 class="text-xl text-[#FFD700] font-bold mb-5">Servicios</h2>
                    <li v-for="(service, index) in services" :key="index">{{ service.title }}</li>
                </div>
                <div class="flex flex-col mb-5">
                    <h2 class="text-xl text-[#FFD700] font-bold mb-5">Contacto</h2>
                    <p>
                        <i class="fa-solid fa-envelope mr-3"></i>
                        jose.rod@ingenieriazafiro.dtw.com.mx
                    </p>
                    <p>
                        <i class="fa-solid fa-phone mr-3"></i>
                        3312517732
                    </p>
                </div>
                <p class="col-span-full">
                    <small>Copyrigth &copy; 2023 </small>
                    <small class="block md:inline mb-5"> Ingeniería Zafiro. Todos los derechos reservados.</small>
                </p>
                <a href="https://dtw.com.mx" target="_blank"
                    class="col-span-full flex justify-end items-center space-x-2 mr-24">
                    <small>by Digtital Tech Work</small>
                    <img class="w-6" src="../../../public/images/dtw_logo.png">
                </a>
            </footer>
        </main>
    </div>
</template>

<script>
import { useForm, Link, Head } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import InputError from "@/Components/InputError.vue";

// services images
import s1 from "../../../public/images/services1.png";
import s2 from "../../../public/images/services2.png";
import s3 from "../../../public/images/services3.png";
import s4 from "../../../public/images/services4.png";
import s5 from "../../../public/images/services5.png";
import s6 from "../../../public/images/services6.png";

// home images
import h1 from "../../../public/images/h1.png";
import h2 from "../../../public/images/h2.png";

// projects images
import p1 from "../../../public/images/p1.png";
import p2 from "../../../public/images/p2.png";
import p3 from "../../../public/images/p3.png";
import p4 from "../../../public/images/p4.png";
import p5 from "../../../public/images/p5.png";
import p6 from "../../../public/images/p6.png";
import p7 from "../../../public/images/p7.png";
import p8 from "../../../public/images/p8.png";
import p9 from "../../../public/images/p9.png";
import p10 from "../../../public/images/p10.png";
import p11 from "../../../public/images/p11.png";
import p12 from "../../../public/images/p12.png";
import p13 from "../../../public/images/p13.png";
import p14 from "../../../public/images/p14.png";
import p15 from "../../../public/images/p15.png";
import p16 from "../../../public/images/p16.png";
import p17 from "../../../public/images/p17.png";
import p18 from "../../../public/images/p18.png";
import p19 from "../../../public/images/p19.png";
import p20 from "../../../public/images/p20.png";

export default {
    data() {
        const form = useForm({
            name: null,
            address: null,
            phone: null,
            service: null,
            message: null,
        });
        return {
            form,
            isNavbarFixed: false,
            currentTestimony: 0,
            lastScrollY: 0,
            showMobileMenu: false,
            currentProyectIndex: 0,
            currentServiceIndex: null,
            currentKirbyIndex: 0,
            showPreview: false,
            services: [
                {
                    title: "Deslinde de parcelas",
                    description: "Definimos de manera precisa las fronteras de tu parcela para garantizar la propiedad y el uso adecuado de la tierra.",
                    image: s1,
                },
                {
                    title: "Planos topográficos",
                    description: "Brindamos una visión detallada y precisa del terreno. Ya sea para proyectos de construcción, planificación urbana o análisis del terreno.",
                    image: s2,
                },
                {
                    title: "Obra civil",
                    description: "Desde el diseño hasta la construcción, gestionamos cada etapa del proceso con precisión y profesionalismo.",
                    image: s3,
                },
                {
                    title: "Lotificaciones",
                    description: "Convertimos terrenos en comunidades planificadas, creamos espacios funcionales y atractivos ",
                    image: s4,
                },
                {
                    title: "Diseño arquitectónico",
                    description: "Desde residencias hasta espacios comerciales, cada diseño es una expresión de tus necesidades y estilos. ",
                    image: s5,
                },
                {
                    title: "Experiencia con acabados de lujo",
                    description: "Desde selecciones de materiales exclusivos hasta ejecución impecable, creamos ambientes que reflejan tu gusto refinado.",
                    image: s6,
                },
            ],
            projects: [
                {
                    description: "Pulido de losa",
                    image: p1,
                },
                {
                    description: "Cimentación y compactación de material de banco",
                    image: p2,
                },
                {
                    description: "Mecanica de suelos",
                    image: p4,
                },
                {
                    description: "Demolición y retiro de escombro ",
                    image: p5,
                },
                {
                    description: "Replanteo de puntos con gps topografico",
                    image: p7,
                },
                {
                    description: " Cimbrado, armado y colado de losa Estructural",
                    image: p8,
                },
                {
                    description: "Mecanica de suelos prueba spt",
                    image: p9,
                },
                {
                    description: "Carga y acarreo de escombro",
                    image: p10,
                },
                {
                    description: "Cimentación de mampostería ",
                    image: p11,
                },
                {
                    description: "Experiencia en proyectos verticales",
                    image: p13,
                },
                {
                    description: "Armado de losa",
                    image: p14,
                },
                {
                    description: "Construction de carcamárcamo pluvial",
                    image: p17,
                },
                {
                    description: "Estructura de acero",
                    image: p18,
                },
                {
                    description: "Muro con ladrillo aparente",
                    image: p19,
                },
            ],
            kirby: [
                {
                    title: "Nosotros",
                    image: h1,
                    description: "Contamos con amplia experiencia en el ramo de la construcción."
                },
                {
                    title: "Tecnología avanzada ",
                    image: h2,
                    description: "Nuestro compromiso con tecnología de vanguardia nos lleva a emplear el potente receptor GNSS Reach RS+ de Emlid."
                },
            ],
            lotes: [
                {
                    title: 'Fraccionamiento "Los Arrayanes"',
                },
                {
                    title: 'Fraccionamiento "El Crucero"',
                },
            ],
        };
    },
    components: {
        InputError,
    },  
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
        this.toast = useToast();
    },
    beforeDestroy() {
        window.removeEventListener('scroll', this.handleScroll);
    },
    methods: {
        handleScroll() {
            const currentScrollY = window.scrollY;

            if (currentScrollY > this.lastScrollY && currentScrollY > window.innerHeight) {
                // Si se hace scroll hacia abajo y se ha pasado el alto de la pantalla
                this.isNavbarFixed = false;
            } else {
                // Si se hace scroll hacia arriba
                this.isNavbarFixed = true;
            }

            this.lastScrollY = currentScrollY;
        },
        scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            section.scrollIntoView({ behavior: 'smooth' });
            this.showMobileMenu = false;
        },
        store() {
            this.form.post(route("messages.store"), {
                onSuccess: () => {
                    this.toast.success("Mensaje enviado correctamente", {
                        timeout: 5000
                    });

                    this.form.reset();
                    this.goToContact();
                },
                onError: () => {
                    this.toast.error("Completar correctamente formulario", {
                        timeout: 5000
                    });

                    this.goToContact();
                }
            });
        },
        goToContact() {
            this.showPreview = false;
            this.scrollToSection('Contacto');
            this.$refs.nameInput.focus();
        }
    },
    components: {
        Link,
        Head,
    }
};
</script>

<style>
/* Estilos para la barra de navegación */
.navbar {
    position: absolute;
    top: 0;
    background-color: #ffffff;
    opacity: 0.9;
}

.fixed-navbar {
    position: fixed;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 100;
}

html {
    scroll-behavior: smooth;
}
</style>
