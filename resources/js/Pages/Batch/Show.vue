<template>
    <Head :title="batch.data.name" />
    <div class="relative">

        <div v-if="showPreview" class="fixed inset-0 bg-black opacity-90 z-20"></div>

        <!-- Imagenenes -->
        <div v-if="showPreview" @click="showPreview = false"
            class="fixed inset-0 flex justify-center items-center border z-30">
                <!-- Change image -->
                <div class="absolute top-1/2 w-2/3 flex justify-between items-center">
                    <i @click.stop="handleMinusImage" class="fa-solid fa-angle-left text-white text-lg px-[19px] py-3 rounded-full bg-gray-400/60 hover:scale-105"></i>
                    <i @click.stop="handlePlusImage" class="fa-solid fa-angle-right text-white text-lg px-[19px] py-3 rounded-full bg-gray-400/60 hover:scale-105"></i>
                </div>
            <div>
                <img class="w-[700px] rounded-lg mx-auto" :src="batch.data.images[currentImage].original_url">
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
            <button class="mx-1 py-2 hover:bg-[#FFD700] rounded-lg" @click="$inertia.visit('/')">LOTES</button>
            <button class="mx-1 py-2 hover:bg-[#FFD700] rounded-lg" @click="$inertia.visit('/otros-servicios')">OTROS SERVICIOS</button>
        </div>

        <!-- navbar -->
        <nav :class="['navbar', { 'fixed-navbar': isNavbarFixed }]"
            class="flex items-center justify-between py-4 lg:px-10 px-6 w-full dark:bg-white">
            <div class="flex space-x-2 items-center">
                <img src="../../../../public/images/logo_dark.png" class="h-14" alt="logo" />
                <span class="font-bold text-[#FFD700] text-xl">INGENIERÍA ZAFIRO</span>
            </div>
            <button @click="showMobileMenu = !showMobileMenu" class="lg:hidden">
                <i class="fa-solid fa-bars text-xl text-[#FFD700]"></i>
            </button>
            <div class="mr-12 hidden lg:inline">        
                <button class="mx-2 rounded-sm px-1 py-px hover:bg-[#FFD700]" @click="$inertia.visit('/')">LOTES</button>
                <button class="mx-2 rounded-sm px-1 py-px hover:bg-[#FFD700] transition-colors ease-linear duration-200" @click="$inertia.visit('/otros-servicios')">OTROS SERVICIOS</button>
                <a href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página%20,%20me%20interesa%20su%20servicio!" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp text-xl ml-2"></i>
                </a>
            </div>
        </nav>

        <main class="pt-20">

            <section class="lg:p-20 lg:mx-12 mx-2">
                <h1 class="font-bold text-3xl">{{ batch.data.name }}. Venta de terrenos en {{ batch.data.address }}</h1>
                <p class="text-sm text-[#4D4D4D] underline"><i class="fa-solid fa-location-dot mr-2"></i> Terreno en {{ batch.data.subdivision.address }}</p>

                <!-- imagenes -->
                <div class="lg:grid grid-cols-2 gap-9 mt-5 relative space-y-3 md:space-y-0">
                    <figure class="w-full h-[600px] flex justify-center">
                        <img class="object-cover rounded-xl w-full" :src="batch.data.images[0]?.original_url" alt="">
                    </figure>
                    <div class="grid grid-cols-2 gap-7 sm:mt-7 lg:mt-0">
                        <figure v-if="batch.data.images?.length > 1" class="w-full">
                            <img class="bject-cover rounded-xl w-full h-[270px]" :src="batch.data.images[1]?.original_url" alt="">
                        </figure>
                        <figure v-if="batch.data.images?.length > 2" class="w-full">
                            <img class="bject-cover rounded-xl w-full h-[270px]" :src="batch.data.images[2]?.original_url" alt="">
                        </figure>
                        <figure v-if="batch.data.images?.length > 3" class="w-full">
                            <img class="bject-cover rounded-xl w-full h-[270px]" :src="batch.data.images[3]?.original_url" alt="">
                        </figure>
                        <figure v-if="batch.data.images?.length > 4" class="w-full">
                            <img class="bject-cover rounded-xl w-full h-[270px]" :src="batch.data.images[4]?.original_url" alt="">
                        </figure>
                    </div>
                    <button @click="showPreview = true" v-if="batch.data.images?.length > 5" class="absolute bottom-0 right-16 rounded-full bg-white border border-[#D9D9D9] p-2">Mostrar todas las fotos<i class="fa-solid fa-photo-film ml-2 text-xs"></i></button>
                </div>

                <!-- Informacion -->
                <div class="mt-10 lg:grid grid-cols-4 gap-x-8">
                    <div class="col-span-3">
                        <p class="text-sm text-[#4A4A4A] border-b pb-2">
                            <i class="fa-solid fa-house mr-2 ml-4"></i>
                            <i class="fa-solid fa-chevron-right"></i>
                            <span class="text-black ml-2">{{ batch.data.subdivision.name }}</span>
                            <i class="fa-solid fa-chevron-right ml-2"></i>
                            <span class="text-black ml-2">{{ batch.data.name }}</span>
                        </p>

                        <div class="my-7">
                            <p class="font-bold text-lg mb-4 pl-4">Características</p>

                            <div class="flex flex-wrap items-center border-b border-[#D9D9D9] pb-7 space-x-3 pl-4 gap-y-2">

                                <!-- Precio desde -->
                                <div class="rounded-md border border-[#D9D9D9] flex items-center px-2 py-1 ml-3 md:ml-0">
                                    <i class="fa-solid fa-dollar-sign border-r border-[#D9D9D9] pr-1 text-lg"></i>
                                    <div class="flex flex-col justify-center px-3">
                                        <p class="text-sm text-[#4D4D4D]">Precio</p>
                                        <p class="text-base font-bold">{{ batch.data.price.numberFormat }} MXN</p>
                                    </div>
                                </div>

                                <!-- Superficie desde -->
                                <div class="rounded-md border border-[#D9D9D9] flex items-center px-2 py-1">
                                    <i class="fa-solid fa-ruler-horizontal border-r border-[#D9D9D9] pr-1 text-lg"></i>
                                    <div class="flex flex-col justify-center px-3">
                                        <p class="text-sm text-[#4D4D4D]">Superficie</p>
                                        <p class="text-base font-bold">{{ batch.data.surface }} m2</p>
                                    </div>
                                </div>

                                <!-- Ubicación -->
                                <div class="rounded-md border border-[#D9D9D9] flex items-center px-2 py-1">
                                    <i class="fa-solid fa-location-dot border-r border-[#D9D9D9] pr-1 text-lg"></i>
                                    <div class="flex flex-col justify-center px-3">
                                        <p class="text-sm text-[#4D4D4D]">Ubicación</p>
                                        <p class="text-base font-bold">{{ batch.data.address }}</p>
                                    </div>
                                </div>

                                <!-- Vista previa -->
                                <a v-if="batch.data.planos?.length > 0" :href="batch.data.planos[0]?.original_url" target="_blank" class="rounded-md border border-[#D9D9D9] flex items-center px-2 py-1 cursor-pointer">
                                    <i class="fa-solid fa-photo-film border-r border-[#D9D9D9] pr-1 text-lg"></i>
                                    <div class="flex flex-col justify-center px-3">
                                        <p class="text-sm text-[#4D4D4D]">Vista previa</p>
                                        <p class="text-base font-bold">{{ batch.data.planos[0]?.file_name }}</p>
                                    </div>
                                </a>

                            </div>

                            <!-- Descripción -->
                            <div class="my-4 pl-4 border-b border-[#D9D9D9] pb-7">
                                <p class="font-bold text-lg">Descripción</p>
                                <p class="text-[#4D4D4D] mt-4">{{ batch.data.description }}</p>
                            </div>

                            <!-- Ubicación -->
                            <div v-if="batch.data.maps_url" class="my-4 pl-4">
                                <p class="font-bold text-lg">Ubicación</p>
                                <iframe
                                    class="mt-5 rounded-lg"
                                    :src="getEmbedMapUrl(batch.data.maps_url)"
                                    width="600"
                                    height="380"
                                    frameborder="0"
                                    style="border: 0; width: 100%;"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        </div>
                    </div>

                    <!-- contactar por whatsApp -->
                    <div class="rounded-lg border border-[#D9D9D9DD] shadow-sm self-start py-3 px-6 mt-7">
                        <p>Contacta con el vendedor para resolver tus dudas </p>
                        <div class="flex justify-center mt-5">
                            <PrimaryButton> 
                                <a href="https://api.whatsapp.com/send?phone=523329281702&text=Hola!%20vi%20tu%20página%20,%20me%20interesa%20su%20servicio!" target="_blank" rel="noopener noreferrer">Contactar por whatsapp</a>
                            </PrimaryButton>
                        </div>
                    </div>

                </div>
            </section>

            <!-- footer  -->
            <footer class="p-4 md:grid grid-cols-4 gap-3 text-white bg-[#1A1A1A] md:relative">
                <figure class="h-full md:h-auto flex md:flex-col items-center space-y-2">
                    <img class="h-16 md:h-auto" src="../../../../public/images/logo_light.png" />
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
                    <img class="w-6" src="../../../../public/images/dtw_logo.png">
                </a>
            </footer>
        </main>
    </div>
</template>

<script>
import { useForm, Link, Head } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

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
            currentImage: 0,
            showMobileMenu: false,
            showPreview: false,
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
        batch: Object,
    },  
    methods: {
        handlePlusImage(){
            this.currentImage === (this.batch.data.images?.length - 1) ? this.currentImage = 0 : this.currentImage += 1
        },
        handleMinusImage(){
            this.currentImage === 0 ? this.currentImage = this.batch.data.images?.length - 1 : this.currentImage -= 1
        },
        getEmbedMapUrl(mapsUrl) {
        // Asegurarte de que mapsUrl tenga el formato correcto
        const formattedUrl = mapsUrl.replace('https://www.google.com/maps/place/', '');

        // Agregar "/embed/v1/" y la key al inicio de la URL
        return `https://www.google.com/maps/embed/v1/place?key=AIzaSyA0s1a7phLN0iaD6-UE7m4qP-z21pH0eSc&q=${formattedUrl}`;
        },
    },
    components: {
        PrimaryButton,
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
