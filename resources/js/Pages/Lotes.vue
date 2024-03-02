<template>
    <Head title="Bienvenido" />
    <div class="relative">

        <div v-if="showPreview" class="fixed inset-0 bg-black opacity-90 z-20"></div>

        <!-- Imagen centrada sin opacidad -->
        <div v-if="showPreview" @click="showPreview = false" class="fixed inset-0 flex justify-center items-center z-30">
            <div class="relative">
                <h1 class="text-[#FFD700] my-9 text-3xl text-center">{{ services[currentServiceIndex].title }}</h1>
                <img class="w-[400px] mx-auto opacity-100" :src="services[currentServiceIndex].image">
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
            <button class="mx-1 py-2 hover:bg-[#FFD700] rounded-lg" @click="$inertia.visit('/otros-servicios')">OTROS
                SERVICIOS</button>
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
                <button class="mx-2 rounded-sm px-1 py-px hover:bg-[#FFD700] transition-colors ease-linear duration-200"
                    @click="$inertia.visit('/otros-servicios')">OTROS SERVICIOS</button>
                <a href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página%20,%20me%20interesa%20su%20servicio!"
                    target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp text-xl ml-2"></i>
                </a>
            </div>
        </nav>

        <main class="pt-14">
            <!-- home desktop-->
            <section class="hidden md:block mt-9 relative mb-48" id="Inicio">
                <div class="rounded-[20px] h-[500px] relative">
                    <img class="mx-auto w-full" src="../../../public/images/home_lotes.png">

                    <div class="absolute bottom-3 left-28 h-1/2 w-[500px]">
                        <p class="text-white lg:text-3xl">EL SITIO IDEAL PARA ENCONTRAR EL TERRERNO PERFECTO PARA TI </p>
                        <p class="text-[#D9D9D9] text-3xl mt-5">TERRENOS EN VENTA </p>
                        <a href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página,%20me%20interesa%20comparar%20un%20terreno"
                            class="ml-auto mr-2 mt-14 text-xl rounded-full border-2 border-[#FFD700] text-black flex justify-center items-center py-1 px-5 bg-[#FFD700] transition-all">
                            Contáctanos
                        </a>
                    </div>
                </div>
            </section>

            <!-- home mobile-->
            <section class="md:hidden mt-9 relative mb-16" id="InicioM">
                <div class="rounded-[20px] h-[300px] relative">
                    <img class="absolute right-0 top-0 h-[100%]" src="../../../public/images/home_lotes.png">
                    <div class="absolute bottom-16 left-4 h-1/2 w-[230px]">
                        <p class="text-white text-sm">EL SITIO IDEAL PARA ENCONTRAR EL TERRERNO PERFECTO PARA TI </p>
                        <p class="text-[#D9D9D9] text-sm mt-5">TERRENOS EN VENTA </p>
                        <button @click="goToContact"
                            class="ml-auto mr-20 mt-14 text-sm rounded-full border-2 border-[#FFD700] text-black flex justify-between items-center py-1 px-5 bg-[#FFD700] transition-all">
                            Contáctanos
                        </button>
                    </div>
                </div>
            </section>

            <!-- Services -->
            <section class="lg:mx-72 mx-5 relative mb-16 lg:mb-32 pt-0 lg:pt-10" id="Servicios">
                <h2 class="font-bold my-5 lg:my-10 text-lg lg:text-2xl text-center">Explora nuestros fraccionamientos y
                    encuentra tu lote
                    perfecto.</h2>
                <div class="grid xs:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-4">
                    <SubdivisionCard v-for="subdivision in subdivisions.data" :key="subdivision"
                        :subdivision="subdivision" />
                </div>
            </section>

            <section class="bg-[#EDEDED] rounded-t-[70px] lg:px-32 p-2 lg:py-14">
                <h2 class="py-9 lg:text-3xl ml-3 font-bold">Descubre los diferentes tamaños de terreno que tenemos para ti.
                </h2>
                <div class="md:grid md:grid-cols-2 gap-x-4 gap-y-4 lg:mx-28 space-y-2 md:space-y-0">
                    <BatchCard v-for="batch in batches.data" :key="batch" :batch="batch" />
                </div>
                <p v-if="loadingItems" class="text-xs my-4 text-center">
                    Cargando <i class="fa-sharp fa-solid fa-circle-notch fa-spin ml-2 text-primary"></i>
                </p>
                <div v-else-if="(total_batches > 2) && (batches.data.length < total_batches) && batches.data.length"
                    @click="fetchItemsByPage" class="text-center mt-7">
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
import InputError from "@/Components/InputError.vue";
import SubdivisionCard from "@/Components/MyComponents/Subdivision/SubdivisionCard.vue";
import BatchCard from "@/Components/MyComponents/Batch/BatchCard.vue";


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
            showMobileMenu: false,
            currentServiceIndex: null,
            showPreview: false,
            loadingItems: false,
            currentPage: 1,
            services: [
                {
                    title: "Deslinde de parcelas",
                    description: "Definimos de manera precisa las fronteras de tu parcela para garantizar la propiedad y el uso adecuado de la tierra.",
                },
                {
                    title: "Planos topográficos",
                    description: "Brindamos una visión detallada y precisa del terreno. Ya sea para proyectos de construcción, planificación urbana o análisis del terreno.",
                },
                {
                    title: "Obra civil",
                    description: "Desde el diseño hasta la construcción, gestionamos cada etapa del proceso con precisión y profesionalismo.",
                },
                {
                    title: "Lotificaciones",
                    description: "Convertimos terrenos en comunidades planificadas, creamos espacios funcionales y atractivos ",
                },
                {
                    title: "Diseño arquitectónico",
                    description: "Desde residencias hasta espacios comerciales, cada diseño es una expresión de tus necesidades y estilos. ",
                },
                {
                    title: "Experiencia con acabados de lujo",
                    description: "Desde selecciones de materiales exclusivos hasta ejecución impecable, creamos ambientes que reflejan tu gusto refinado.",
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
    props: {
        subdivisions: Object,
        batches: Object,
        total_batches: Number,
    },
    components: {
        InputError,
        SubdivisionCard,
        BatchCard,
        Link,
        Head,
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
        async fetchItemsByPage() {
            try {
                this.loadingItems = true;
                const response = await axios.get(route('batches.get-by-page', this.currentPage));

                if (response.status === 200) {
                    this.batches.data = [...this.batches.data, ...response.data.items];
                    this.currentPage++;
                }
            } catch (error) {
                console.log(error)
            } finally {
                this.loadingItems = false;
            }
        },
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
    },
    beforeDestroy() {
        window.removeEventListener('scroll', this.handleScroll);
    },
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
