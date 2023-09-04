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
        <a class="z-50 w-14 h-14 lg:w-20 lg:h-20 rounded-full bg-green-600 shadow-md shadow-green-800/100 flex items-center justify-center fixed bottom-3 right-3 hover:scale-105"
            href="https://api.whatsapp.com/send?phone=523312517732&text=Hola!%20vi%20tu%20página%20,%20me%20interesa%20su%20servicio!"
            target="_blank" rel="noopener noreferrer">
            <i class="fa-brands fa-beat fa-whatsapp text-2xl lg:text-4xl text-gray-100"></i>
        </a>

        <!-- mobile menu (hamburger) -->
        <div v-if="showMobileMenu"
            class="flex flex-col z-30 w-2/3 bg-[#262626] rounded-xl fixed top-24 right-5 border-white border py-1 text-white">
            <button class="mx-1 py-2 hover:bg-[#FFD700] rounded-lg" @click="scrollToSection('InicioM')">Inicio</button>
            <button class="mx-1 py-2 hover:bg-[#FFD700] rounded-lg" @click="scrollToSection('Servicios')">Servicios</button>
            <button class="mx-1 py-2 hover:bg-[#FFD700] rounded-lg" @click="scrollToSection('Contacto')">Proyectos</button>
            <button class="mx-1 py-2 hover:bg-[#FFD700] rounded-lg" @click="scrollToSection('Contacto')">Contacto</button>
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
                <button class="mx-2 rounded-[10px] px-1 py-px hover:bg-[#FFD700] hover:text-white"
                    @click="scrollToSection('Inicio')">Inicio</button>
                <button class="mx-2 rounded-[10px] px-1 py-px hover:bg-[#FFD700] hover:text-white"
                    @click="scrollToSection('Servicios')">Servicios</button>
                <button class="mx-2 rounded-[10px] px-1 py-px hover:bg-[#FFD700] hover:text-white"
                    @click="scrollToSection('Contacto')">Proyectos</button>
                <button class="mx-2 rounded-[10px] px-1 py-px hover:bg-[#FFD700] hover:text-white"
                    @click="scrollToSection('Contacto')">Contacto</button>
            </div>
        </nav>

        <main class="pt-20">
            <!-- home desktop-->
            <section class="hidden md:block lg:mx-24 mx-1 mt-9 relative mb-24" id="Inicio">
                <div class="bg-[#1A1A1A] rounded-[20px] h-[400px] relative">
                    <img class="absolute left-0 top-0" src="../../../public/images/decoration_left.png">
                    <img class="absolute right-0 top-0 h-[400px]" src="../../../public/images/decoration_right.png">
                    <div class="flex h-full py-24">
                        <div @click="currentKirbyIndex = currentKirbyIndex == 0 ? (kirby.length - 1) : currentKirbyIndex - 1"
                            class="w-8 h-full mr-2 flex justify-center items-center cursor-pointer">
                            <button class="z-20 text-white text-lg ml-8">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                        </div>
                        <figure class="md:w-1/2 lg:w-1/3 h-2/3 z-20">
                            <img class="h-[450px] mx-auto" :src="kirby[currentKirbyIndex].image">
                        </figure>
                        <div class="w-2/3 flex flex-col ml-5 pr-32 text-3xl">
                            <h1 class="text-center text-[#FFD700] mb-8">{{ kirby[currentKirbyIndex].title }}</h1>
                            <p class="text-white text-justify">{{ kirby[currentKirbyIndex].description }}</p>
                        </div>
                        <div @click="currentKirbyIndex = currentKirbyIndex == (kirby.length - 1) ? 0 : currentKirbyIndex + 1"
                            class="w-8 h-full ml-2 flex justify-center items-center cursor-pointer">
                            <button class="z-20 text-white text-lg mr-8">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button @click="goToContact"
                    class="ml-auto mr-14 mt-11 text-2xl rounded-full border-2 border-[#FFD700] text-black flex justify-between items-center py-1 px-5 bg-[#FFD700] transition-all">
                    Contáctanos
                </button>
            </section>

            <!-- home mobile-->
            <section class="md:hidden mx-4 mt-9 relative mb-16" id="InicioM">
                <div class="bg-[#1A1A1A] rounded-[20px] h-[450px] relative">
                    <img class="absolute -left-0 bottom-10 h-[160px]" src="../../../public/images/decoration_left.png">
                    <img class="absolute right-0 top-0 h-[300px]" src="../../../public/images/decoration_right.png">
                    <div class="flex flex-col items-center text-xl px-7 pt-7 h-1/2">
                        <h1 class="text-center text-[#FFD700] mb-8">{{ kirby[currentKirbyIndex].title }}</h1>
                        <p class="text-white text-justify">{{ kirby[currentKirbyIndex].description }}</p>
                    </div>
                    <div class="flex h-1/2">
                        <div @click="currentKirbyIndex = currentKirbyIndex == 0 ? (kirby.length - 1) : currentKirbyIndex - 1"
                            class="w-8 h-full mr-2 flex justify-center items-center cursor-pointer">
                            <button class="z-20 text-white text-lg ml-8">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                        </div>
                        <figure class="w-full z-20 rounded-2xl">
                            <img class="h-[300px] rounded-2xl mx-auto" :src="kirby[currentKirbyIndex].image">
                        </figure>
                        <div @click="currentKirbyIndex = currentKirbyIndex == (kirby.length - 1) ? 0 : currentKirbyIndex + 1"
                            class="w-8 h-full ml-2 flex justify-center items-center cursor-pointer">
                            <button class="z-20 text-white text-lg mr-8">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button @click="goToContact"
                    class=" ml-auto mr-5 mt-20 text-2xl rounded-full border-2 border-[#FFD700] dark:text-black text-black flex justify-between items-center py-1 px-5 bg-[#FFD700] transition-all">
                    Contáctanos
                </button>
            </section>

            <!-- Services -->
            <section class="lg:mx-24 mx-1 mt-9 relative mb-32" id="Servicios">
                <h2 class="font-bold mb-10 text-3xl text-center">Nuestros servicios</h2>

                <div class="grid xs:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-44">
                    <div v-for="(service, index) in services" :key="index"
                        class="bg-[#1A1A1A] rounded-[20px] px-5 pt-6  min-h-[326px] text-2xl relative">
                        <h1 class="text-[#FFD700] text-center mb-3">{{ service.title }}</h1>
                        <p class="text-white text-justify">{{ service.description }}</p>
                        <figure @click="showPreview = true; currentServiceIndex = index;"
                            class="w-3/4 h-48 rounded-[20px] absolute -bottom-[104px] left-[12.5%] cursor-pointer">
                            <img class="h-48 w-full rounded-[20px]" :src="service.image">
                            <div
                                class="w-6 h-6 rounded-full bg-black opacity-50 flex items-center justify-center absolute bottom-3 right-4">
                                <i class="fa-solid fa-magnifying-glass-plus text-white text-xs"></i>
                            </div>
                        </figure>
                    </div>  
                </div>
            </section>

            <!-- form -->
            <section class="lg:mx-24 md:mx-20 mx-1 mt-44 relative" id="Contacto">
                <div class="lg:grid grid-cols-5 gap-9 mb-6">
                    <div class="col-span-3 border-2 border-[#FFD700] rounded-[20px] py-10 px-5">
                        <div class="flex h-full">
                            <div @click="currentProyectIndex = currentProyectIndex == 0 ? (projects.length - 1) : currentProyectIndex - 1"
                                class="w-8 h-11/12 lg:h-full mr-2 flex justify-center items-center cursor-pointer">
                                <button>
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>
                            </div>
                            <figure class="w-1/2">
                                <img :src="projects[currentProyectIndex].image">
                            </figure>
                            <div class="w-1/2 flex flex-col ml-5 justify-start text-3xl">
                                <h1 class="text-center font-bold mb-12 lg:mb-32">PROYECTOS</h1>
                                <p class="text-center text-xl">{{ projects[currentProyectIndex].description }}</p>
                            </div>
                            <div @click="currentProyectIndex = currentProyectIndex == (projects.length - 1) ? 0 : currentProyectIndex + 1"
                                class="w-8 h-11/12 lg:h-full ml-2 flex justify-center items-center cursor-pointer">
                                <button>
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <form @submit.prevent="store" class="col-span-2 md:mt-12 lg:mt-0 mt-8 mx-8">
                        <h2 class="font-bold mb-3 text-xl text-[#FFD700]">CONTÁCTANOS</h2>
                        <div class="mb-5">
                            <label for="name">Nombre <span class="text-[#FFD700]">*</span></label>
                            <input v-model="form.name" type="text" id="name" required ref="nameInput"
                                class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9"
                                placeholder="Escribe tu nombre" />
                            <p class="text-red-600 text-xs" v-if="form.errors?.name">{{ form.errors?.name }}</p>
                        </div>
                        <div class="mb-5">
                            <label for="phone">Teléfono <span class="text-[#FFD700]">*</span></label>
                            <input v-model="form.phone" type="text" id="phone" required
                                class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9"
                                placeholder="Escribe tu teléfono" />
                            <p class="text-red-600 text-xs" v-if="form.errors?.phone">{{ form.errors?.phone }}</p>
                        </div>
                        <div class="mb-5">
                            <label for="service">Servicio</label>
                            <select v-model="form.service" id="service"
                                class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px]">
                                <option v-for="(service, index) in services" :key="index" :value="service.title">{{
                                    service.title }}</option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="address">Dirección</label>
                            <input v-model="form.address" type="text" id="address"
                                class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px] h-9"
                                placeholder="Escribe la dirección donde solicita el servicio" />
                            <p class="text-red-600 text-xs" v-if="form.errors?.address">{{ form.errors?.address }}</p>
                        </div>
                        <div class="mb-5">
                            <label for="address">Mensaje</label>
                            <textarea v-model="form.message" rows="5" required
                                class="active:ring-0 focus:ring-0 border-none outline-none bg-[#D9D9D9] block w-full text-[#808080] rounded-[10px]"
                                placeholder="Dejános un mensaje "></textarea>
                            <p class="text-red-600 text-xs" v-if="form.errors?.message">{{ form.errors?.message }}</p>
                        </div>
                        <button type="submit"
                            class="mx-auto mt-5 rounded-full border-2 border-[#FFD700] text-black flex justify-between items-center py-1 px-5 hover:bg-[#FFD700] transition-all">
                            Enviar
                            <i class="fa-solid fa-chevron-right ml-6"></i>
                        </button>
                    </form>
                    <!-- <figure class="rounded-[20px] hidden lg:block">
                        <img class="rounded-[20px]" src="../../../public/assets/images/contact.png" />
                    </figure> -->
                </div>
            </section>

            <footer class="p-4 md:grid grid-cols-3 gap-3 text-white bg-[#1A1A1A] mt-24 md:relative">
                <figure class="h-full md:h-auto flex md:flex-col items-center space-y-2">
                    <img class="h-16 md:h-auto" src="../../../public/images/logo_light.png" />
                    <span class="font-bold text-[#FFD700] text-xl">INGENIERÍA ZAFIRO</span>
                </figure>
                <div class="flex flex-col">
                    <h2 class="text-xl text-[#FFD700] font-bold mb-5">Servicios</h2>
                    <li v-for="(service, index) in services" :key="index">{{ service.title }}</li>
                </div>
                <div class="flex flex-col mb-5">
                    <h2 class="text-xl text-[#FFD700] font-bold mb-5">Contacto</h2>
                    <p>
                        <i class="fa-solid fa-envelope mr-3"></i>
                        jose.rg@ingenieriazafiro.com
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
                    <small>by Digtital TW</small>
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
                    description: "Armando de losa",
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
            ]
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
