<template>

    <Head>
        <title>Lotes / terrenos</title>
        <meta name="description"
            content="Descubre soluciones inmobiliarias integrales con ingeniería zafiro. Expertos en venta de terrenos, topografía, obra civil, diseño arquitectónico y acabados de lujo. Con años de experiencia, garantizamos precisión y satisfacción. ¡Transforma tus proyectos con nosotros!">
        <meta name="keywords"
            content="ingeniería, construcción, materiales, terreno, lote, fraccionamientos, obras, casas, residencias, industria, medicion, topografía, cimientos, drenaje, calles, gps, deslinde, prcelas, hectáreas, lotificación, cimentación, demolición, Mecanica de suelos, cimbrado, losas, estructura">
        <meta name="robots" content="index">
        <meta name="author" content="DTW">
    </Head>

    <div class="relative">
        <!-- whatsapp button -->
        <a class="lg:hidden z-50 w-14 h-14 lg:w-20 lg:h-20 rounded-full bg-green-600 shadow-md shadow-green-800/100 flex items-center justify-center fixed bottom-3 right-3 hover:scale-105"
            href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página,%20me%20interesa%20comprar%20un%20terreno"
            target="_blank" rel="noopener noreferrer">
            <i class="fa-brands fa-beat fa-whatsapp text-2xl lg:text-4xl text-gray-100"></i>
        </a>

        <!-- mobile menu (hamburger) -->
        <div v-if="showMobileMenu"
            class="flex flex-col z-50 bg-[#262626] rounded-xl w-2/3 md:w-1/3 fixed top-24 md:top-20 right-5 border-white border py-1 text-white">
            <button class="mx-1 py-2 bg-primary rounded-lg">LOTES</button>
            <button class="mx-1 py-2 hover:bg-primary rounded-lg" @click="$inertia.visit('/otros-servicios')">OTROS
                SERVICIOS</button>
        </div>

        <!-- navbar -->
        <nav :class="['navbar', { 'fixed-navbar': isNavbarFixed }]"
            class="flex items-center justify-between py-4 lg:px-10 px-6 w-full dark:bg-white">
            <div class="flex space-x-2 items-center">
                <img src="../../../public/images/logo_dark.png" class="h-14" alt="logo" />
                <span class="font-bold text-primary text-xl">INGENIERÍA ZAFIRO</span>
            </div>
            <button @click="showMobileMenu = !showMobileMenu" class="lg:hidden">
                <i class="fa-solid fa-bars text-xl text-primary"></i>
            </button>
            <div class="mr-12 hidden lg:inline">
                <button class="mx-2 rounded-sm px-2 py-px bg-primarylight font-bold">LOTES</button>
                <button
                    class="mx-2 rounded-sm px-1 py-px hover:bg-primarylight transition-colors ease-linear duration-200"
                    @click="$inertia.visit('/otros-servicios')">OTROS SERVICIOS</button>
                <a href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página%20,%20me%20interesa%20su%20servicio!"
                    target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp text-xl ml-2"></i>
                </a>
            </div>
        </nav>

        <main class="pt-14">
            <!-- home desktop-->
            <section class="hidden md:block mt-9 relative md:mb-8 lg:mb-16" id="Inicio">
                <figure class="rounded-[20px] lg:h-[630px] md:h-full relative">
                    <img class="mx-auto w-full h-full object-cover" src="../../../public/images/home_lotes.png" alt="famialia abrazada de espardas mirando una casa">

                    <div class="absolute bottom-20 lg:bottom-36 left-8 lg:left-28 h-1/2 w-1/2 lg:w-1/3">
                        <p class="text-white lg:text-3xl">EL SITIO IDEAL PARA ENCONTRAR EL TERRERNO PERFECTO PARA TI
                        </p>
                        <p class="text-[#D9D9D9] lg:text-3xl mt-5">TERRENOS EN VENTA </p>
                        <a href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página,%20me%20interesa%20comprar%20un%20terreno"
                            class="ml-auto mr-2 mt-14 lg:text-xl rounded-full border-2 border-primary text-black inline-flex justify-center items-center py-1 px-4 lg:px-9 bg-primary transition-all">
                            Contáctanos
                        </a>
                    </div>
                </figure>
            </section>

            <!-- home mobile-->
            <section class="md:hidden mt-9 relative mb-16" id="InicioM">
                <div class="rounded-[20px] h-[300px] relative">
                    <img class="absolute right-0 top-0 h-[100%]" src="../../../public/images/home_lotes.png" alt="famialia abrazada de espardas mirando una casa">
                    <div class="absolute bottom-16 left-4 h-1/2 w-[230px]">
                        <p class="text-white text-sm">EL SITIO IDEAL PARA ENCONTRAR EL TERRERNO PERFECTO PARA TI </p>
                        <p class="text-[#D9D9D9] text-sm mt-5">TERRENOS EN VENTA </p>
                        <button @click="goToContact"
                            class="ml-auto mr-20 mt-14 text-sm rounded-full border-2 border-primary text-black flex justify-between items-center py-1 px-5 bg-primary transition-all">
                            Contáctanos
                        </button>
                    </div>
                </div>
            </section>

            <!-- Services -->
            <section class="lg:mx-36 md:mx-10 mx-5 relative mb-16 lg:mb-32" id="Servicios">
                <h2 class="font-bold my-5 lg:my-10 text-lg lg:text-2xl text-center">
                    Explora nuestros fraccionamientos y encuentra tu lote perfecto.</h2>
                <div class="grid xs:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-4">
                    <SubdivisionCard v-for="subdivision in subdivisions.data" :key="subdivision"
                        :subdivision="subdivision" />
                </div>
            </section>

            <section class="bg-[#EDEDED] rounded-t-[70px] lg:px-5 md:px-10 p-2 lg:py-14">
                <h2 class="py-9 text-lg lg:text-2xl ml-3 font-bold">Descubre los diferentes tamaños de terreno que
                    tenemos para ti.
                </h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:mx-10 justify-center">
                    <BatchCard class="mx-auto" v-for="batch in batchesLocal" :key="batch" :batch="batch" />
                </div>
                <p v-if="loadingItems" class="text-xs my-4 text-center">
                    Cargando <i class="fa-sharp fa-solid fa-circle-notch fa-spin ml-2 text-primary"></i>
                </p>
                <div v-else-if="(total_batches > 2) && (batches.data.length < total_batches) && batches.data.length"
                    @click="fetchItemsByPage" class="text-center mt-7">
                    <button class="flex items-center justify-center text-white bg-black rounded-full w-6 h-6 mx-auto">
                        <i class="fa-solid fa-angle-down"></i>
                    </button>
                </div>
            </section>
            <footer class="p-4 md:grid md:grid-cols-3 lg:grid-cols-4 gap-3 text-white bg-[#1A1A1A] md:relative text-sm">
                <figure class="h-full md:h-auto flex space-y-2">
                    <img class="h-16 md:h-1/4" src="../../../public/images/logo_light.png" alt="logo de ingenieria zafiro. Mapa blanco y un marcador de posición similar al de google maps" />
                    <span class="lg:font-bold text-primary lg:text-xl">INGENIERÍA ZAFIRO</span>
                </figure>
                <div class="flex flex-col lg:text-base">
                    <h2 class="lg:text-xl text-primary lg:font-bold mb-5">Venta de terrenos</h2>
                    <li v-for="(lote, index) in lotes" :key="index">{{ lote.title }}</li>
                    <div class="flex lg:hidden flex-col mb-5 mt-5">
                        <h2 class="lg:text-xl text-primary lg:font-bold mb-5">Contacto</h2>
                        <p class="flex items-center text-xs">
                            <i class="fa-solid fa-envelope mr-3"></i>
                            jose.rod@ingenieriazafiro.dtw.com.mx
                        </p>
                        <p>
                            <i class="fa-solid fa-phone mr-2 text-xs"></i>
                            3312517732
                        </p>
                    </div>
                </div>
                <div class="flex flex-col">
                    <h2 class="lg:text-xl text-primary lg:font-bold mb-5">Servicios</h2>
                    <li v-for="(service, index) in services" :key="index">{{ service.title }}</li>
                </div>
                <div class="hidden lg:flex flex-col mb-5">
                    <h2 class="lg:text-xl text-primary lg:font-bold mb-5">Contacto</h2>
                    <p>
                        <i class="fa-solid fa-envelope mr-3"></i>
                        jose.rod@ingenieriazafiro.dtw.com.mx
                    </p>
                    <p>
                        <i class="fa-solid fa-phone mr-3"></i>
                        3312517732
                    </p>
                </div>
                <div class="col-span-full flex-col justify-center md:flex-row flex items-center md:justify-between">
                    <p class="flex mt-4">
                        <small>Copyrigth &copy; 2023 </small>
                        <small class="block md:inline"> Ingeniería Zafiro. Todos los derechos reservados.</small>
                    </p>
                    <figure class="mt-4 cursor-pointer w-1/2 ">
                        <a class="flex justify-end items-center" href="https://app.dtw.com.mx/" target="_blank">
                            <p class="text-white text-xl">BY</p>
                            <img class="w-20 lg:w-[10%]" src="@/../../public/images/DTW_logo_blanco.png" alt="">
                        </a>
                    </figure>
                </div>
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
            batchesLocal: null,
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
                const response = await axios.get(route('batches.get-by-page'));

                if (response.status === 200) {
                    this.batchesLocal = [...this.batchesLocal, ...response.data.items];
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
        this.batchesLocal = this.batches.data;
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
