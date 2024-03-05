<template>

    <Head :title="subdivision.data.name" />
    <div class="relative">

        <div v-if="showPreview" class="fixed inset-0 bg-black opacity-90 z-20"></div>

        <!-- Imagenenes -->
        <div v-if="showPreview" @click="showPreview = false"
            class="fixed inset-0 flex justify-center items-center border z-30">
            <!-- Change image -->
            <div class="absolute top-1/2 lg:top-1/2 w-4/5 lg:w-2/3 flex justify-between items-center">
                <i @click.stop="handleMinusImage"
                    class="fa-solid fa-angle-left text-white text-lg px-[19px] py-3 rounded-full bg-gray-400/60 hover:scale-105"></i>
                <i @click.stop="handlePlusImage"
                    class="fa-solid fa-angle-right text-white text-lg px-[19px] py-3 rounded-full bg-gray-400/60 hover:scale-105"></i>
            </div>
            <div>
                <img class="w-[700px] rounded-lg mx-auto" :src="subdivision.data.images[currentImage].original_url">
            </div>
        </div>

        <!-- whatsapp button -->
        <a class="lg:hidden z-50 w-14 h-14 lg:w-20 lg:h-20 rounded-full bg-green-600 shadow-md shadow-green-800/100 flex items-center justify-center fixed bottom-3 right-3 hover:scale-105"
            href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página,%20me%20interesa%20comparar%20un%20terreno"
            target="_blank" rel="noopener noreferrer">
            <i class="fa-brands fa-beat fa-whatsapp text-2xl lg:text-4xl text-gray-100"></i>
        </a>

        <!-- mobile menu (hamburger) -->
        <div v-if="showMobileMenu"
            class="flex flex-col z-50 bg-[#262626] rounded-xl w-2/3 md:w-1/3 fixed top-24 md:top-20 right-5 border-white border py-1 text-white">
            <button class="mx-1 py-2 hover:bg-primary rounded-lg" @click="$inertia.visit('/')">LOTES</button>
            <button class="mx-1 py-2 hover:bg-primary rounded-lg" @click="$inertia.visit('/otros-servicios')">OTROS
                SERVICIOS</button>
        </div>

        <!-- navbar -->
        <nav :class="['navbar', { 'fixed-navbar': isNavbarFixed }]"
            class="flex items-center justify-between py-4 lg:px-10 px-6 w-full dark:bg-white">
            <div class="flex space-x-2 items-center">
                <img src="../../../../public/images/logo_dark.png" class="h-14" alt="logo" />
                <span class="font-bold text-primary text-xl">INGENIERÍA ZAFIRO</span>
            </div>
            <button @click="showMobileMenu = !showMobileMenu" class="lg:hidden">
                <i class="fa-solid fa-bars text-xl text-primary"></i>
            </button>
            <div class="mr-12 hidden lg:inline">
                <button class="mx-2 rounded-sm px-1 py-px hover:bg-primarylight"
                    @click="$inertia.visit('/')">LOTES</button>
                <button
                    class="mx-2 rounded-sm px-1 py-px hover:bg-primarylight transition-colors ease-linear duration-200"
                    @click="$inertia.visit('/otros-servicios')">OTROS SERVICIOS</button>
                <a href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página,%20me%20interesa%20comparar%20un%20terreno"
                    target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp text-xl ml-2"></i>
                </a>
            </div>
        </nav>

        <main class="pt-20">

            <section class="lg:p-20 md:p-4 lg:mx-12 mx-2">
                <h1 class="font-bold text-3xl">Lotes {{ subdivision.data.type + ' en fraccionamiento "' +
        subdivision.data.name + '"' }}</h1>
                <p class="text-sm text-[#4D4D4D] underline"><i class="fa-solid fa-location-dot mr-2"></i> Terrenos en {{
        subdivision.data.address }}</p>

                <!-- imagenes -->
                <div class="lg:grid grid-cols-2 gap-9 mt-5 relative space-y-3 lg:space-y-0">
                    <figure class="w-full h-[600px] flex justify-center">
                        <img class="object-cover rounded-xl w-full" :src="subdivision.data.images[0]?.original_url"
                            alt="">
                    </figure>
                    <div class="grid grid-cols-2 gap-7 pt-4 lg:pt-0">
                        <figure v-if="subdivision.data.images?.length > 1" class="w-full">
                            <img class="object-cover rounded-xl w-full h-[286px]"
                                :src="subdivision.data.images[1]?.original_url" alt="">
                        </figure>
                        <figure v-if="subdivision.data.images?.length > 2" class="w-full">
                            <img class="object-cover rounded-xl w-full h-[286px]"
                                :src="subdivision.data.images[2]?.original_url" alt="">
                        </figure>
                        <figure v-if="subdivision.data.images?.length > 3" class="w-full hidden lg:block">
                            <img class="object-cover rounded-xl w-full h-[286px]"
                                :src="subdivision.data.images[3]?.original_url" alt="">
                        </figure>
                        <figure v-if="subdivision.data.images?.length > 4" class="w-full hidden lg:block">
                            <img class="object-cover rounded-xl w-full h-[286px]"
                                :src="subdivision.data.images[4]?.original_url" alt="">
                        </figure>
                    </div>
                    <button @click="showPreview = true" v-if="subdivision.data.images?.length > 5 || true"
                        class="absolute -bottom-5 right-20 rounded-[10px] bg-white border border-[#D9D9D9] py-2 px-3">
                        Mostrar todas las fotos
                        <i class="fa-regular fa-images ml-2 text-xs"></i>
                    </button>
                </div>

                <!-- Informacion -->
                <div class="mt-10 lg:grid grid-cols-4 gap-x-8">
                    <div class="col-span-3">
                        <p class="flex items-center text-sm text-[#4A4A4A] border-b pb-2">
                            <Link href="/" class="flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            <span>Inicio</span>
                            </Link>
                            <i class="fa-solid fa-chevron-right mx-2"></i>
                            <span class="text-black">
                                {{ subdivision.data.name }}
                            </span>
                        </p>

                        <div class="my-7">
                            <p class="font-bold text-lg mb-4 pl-4">Características</p>

                            <div
                                class="flex flex-wrap items-center border-b border-[#D9D9D9] pb-7 space-x-3 pl-4 gap-y-2">

                                <!-- Precio desde -->
                                <div
                                    class="rounded-md border border-[#D9D9D9] flex items-center px-2 py-1 ml-3 md:ml-0">
                                    <i class="fa-solid fa-dollar-sign border-r border-[#D9D9D9] pr-1 text-lg"></i>
                                    <div class="flex flex-col justify-center px-3">
                                        <p class="text-sm text-[#4D4D4D]">Precio desde</p>
                                        <p class="text-base font-bold">{{ subdivision.data.lowest_price.numberFormat }}
                                            MXN
                                        </p>
                                    </div>
                                </div>

                                <!-- Superficie desde -->
                                <div class="rounded-md border border-[#D9D9D9] flex items-center px-2 py-1">
                                    <i class="fa-solid fa-ruler-horizontal border-r border-[#D9D9D9] pr-1 text-lg"></i>
                                    <div class="flex flex-col justify-center px-3">
                                        <p class="text-sm text-[#4D4D4D]">Superficie desde</p>
                                        <p class="text-base font-bold">{{ subdivision.data.lowest_surface }} m2</p>
                                    </div>
                                </div>

                                <!-- Ubicación -->
                                <div class="rounded-md border border-[#D9D9D9] flex items-center px-2 py-1">
                                    <i class="fa-solid fa-location-dot border-r border-[#D9D9D9] pr-1 text-lg"></i>
                                    <div class="flex flex-col justify-center px-3">
                                        <p class="text-sm text-[#4D4D4D]">Ubicación</p>
                                        <p class="text-base font-bold">{{ subdivision.data.address }}</p>
                                    </div>
                                </div>

                                <!-- Vista previa -->
                                <a v-if="subdivision.data.planos?.length > 0"
                                    :href="subdivision.data.planos[0]?.original_url" target="_blank"
                                    class="rounded-md border border-[#D9D9D9] flex items-center px-2 py-1 cursor-pointer">
                                    <i class="fa-solid fa-file-lines border-r border-[#D9D9D9] pr-1 text-lg"></i>
                                    <div class="flex flex-col justify-center px-3">
                                        <p class="text-sm text-[#4D4D4D]">Vista previa</p>
                                        <p class="text-base font-bold">{{ subdivision.data.planos[0]?.file_name }}</p>
                                    </div>
                                </a>

                                <!-- Lotes disponibles -->
                                <div class="rounded-md border border-[#D9D9D9] flex items-center px-2 py-1">
                                    <i class="fa-solid fa-check border-r border-[#D9D9D9] pr-1 text-lg"></i>
                                    <div class="flex flex-col justify-center px-3">
                                        <p class="text-sm text-[#4D4D4D]">Lotes disponibles</p>
                                        <p class="text-base font-bold">{{ subdivision.data.batches_availables }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="my-4 pl-4 border-b border-[#D9D9D9] pb-7">
                                <p class="font-bold text-lg">Descripción</p>
                                <p class="text-[#4D4D4D] mt-4">{{ subdivision.data.description }}</p>
                            </div>

                            <!-- Amenidades -->
                            <div v-if="subdivision.data.amenities" class="my-4 pl-4 border-b border-[#D9D9D9] pb-7">
                                <p class="font-bold text-lg">Amenidades</p>
                                <div class="flex items-center flex-wrap space-y-2 space-x-3 mt-4">
                                    <p v-for="amenity in subdivision.data.amenities" :key="amenity"
                                        class="text-[#4D4D4D] py-2 px-3 rounded-lg border border-[#D9D9D9]"><i
                                            class="mr-3" :class="getIcon(amenity)"></i>{{ amenity }}</p>
                                </div>
                            </div>

                            <!-- mapa -->
                            <div v-if="subdivision.data.lon && subdivision.data.lat" class="my-4 pl-4">
                                <p class="font-bold text-lg">Ubicación</p>
                                <div ref="map" style="height: 400px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- contactar por whatsApp -->
                    <div class="rounded-lg border border-[#D9D9D9DD] shadow-sm self-start py-3 px-6 mt-7">
                        <p>Contacta con el vendedor para resolver tus dudas </p>
                        <div class="flex justify-center mt-5">
                            <PrimaryButton>
                                <a href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página%20,%20me%20interesa%20su%20servicio!"
                                    target="_blank" rel="noopener noreferrer">Contactar por whatsapp</a>
                            </PrimaryButton>
                        </div>
                    </div>

                    <!-- lotes del fraccionamiento  -->
                    <div class="my-9 col-span-full">
                        <p class="text-lg font-bold">Lotes de {{ subdivision.data.name }} </p>
                        <div v-if="subdivision.data.batches?.length > 0"
                            class="grid xs:grid-cols-1 justify-center lg:grid-cols-2 gap-x-4 gap-y-4 mt-7 lg:mx-24">
                            <BatchCard v-for="batch in subdivision.data.batches" :key="batch" :batch="batch" />
                        </div>
                        <p class="text-sm text-gray-400 text-center" v-else>No hay lotes disponibles dentro de este
                            fraccionamiento</p>
                    </div>

                </div>
            </section>

            <!-- footer  -->
            <footer class="p-4 md:grid md:grid-cols-3 lg:grid-cols-4 gap-3 text-white bg-[#1A1A1A] md:relative text-sm">
                <figure class="h-full md:h-auto flex space-y-2">
                    <img class="h-16 md:h-1/4" src="../../../../public/images/logo_light.png" />
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
                    <a href="https://dtw.com.mx" target="_blank"
                        class="flex justify-end items-center space-x-2 md:mr-24">
                        <small>by Digtital Tech Work</small>
                        <img class="w-6" src="../../../../public/images/dtw_logo.png">
                    </a>
                </div>
            </footer>
        </main>
    </div>
</template>

<script>
import { useForm, Link, Head } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
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
            // mapa
            map: null,
            marker: null,
            coordinates: { lat: parseFloat(this.subdivision.data.lat), lng: parseFloat(this.subdivision.data.lon) },
            // fraccionamieto
            form,
            isNavbarFixed: false,
            showMobileMenu: false,
            showPreview: false,
            currentImage: 0,
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
        subdivision: Object,
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
        getIcon(amenity) {
            if (amenity == 'Espacio para niños') {
                return 'fa-solid fa-fan';
            } else if (amenity == 'Piscina') {
                return 'fa-solid fa-person-swimming';
            } else if (amenity == 'Salón de eventos') {
                return 'fa-solid fa-people-roof';
            } else if (amenity == 'Áreas verdes') {
                return 'fa-solid fa-leaf';
            } else if (amenity == 'Parque') {
                return 'fa-solid fa-fan';
            } else if (amenity == 'Gimnasio') {
                return 'fa-solid fa-dumbbell';
            }
        },
        handlePlusImage() {
            this.currentImage === (this.subdivision.data.images?.length - 1) ? this.currentImage = 0 : this.currentImage += 1
        },
        handleMinusImage() {
            this.currentImage === 0 ? this.currentImage = this.subdivision.data.images?.length - 1 : this.currentImage -= 1
        },
        initMap() {
            this.map = new google.maps.Map(this.$refs.map, {
                center: this.coordinates,
                zoom: 15,
            });

            this.marker = new google.maps.Marker({
                position: this.coordinates,
                map: this.map,
                title: 'Ubicación',
            });
        },
    },
    components: {
        PrimaryButton,
        BatchCard,
        Link,
        Head,
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
        if (this.$refs.map) {
            this.initMap();
        } else {
            console.error('Map element not found.');
        }
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
