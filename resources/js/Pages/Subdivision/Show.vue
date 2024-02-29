<template>
    <Head :title="subdivision.data.name" />
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
                <img class="w-[700px] rounded-lg mx-auto" :src="subdivision.data.images[currentImage].original_url">
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
                <h1 class="font-bold text-3xl">Lotes {{ subdivision.data.type + ' ' + subdivision.data.name }}</h1>
                <p class="text-sm text-[#4D4D4D] underline"><i class="fa-solid fa-location-dot mr-2"></i> Terrenos en {{ subdivision.data.address }}</p>

                <!-- imagenes -->
                <div class="lg:grid grid-cols-2 gap-9 mt-5 relative space-y-3 md:space-y-0">
                    <figure class="w-full h-[600px] flex justify-center">
                        <img class="object-cover rounded-xl w-full" :src="subdivision.data.images[0]?.original_url" alt="">
                    </figure>
                    <div class="grid grid-cols-2 gap-7 sm:mt-7 lg:mt-0">
                        <figure v-if="subdivision.data.images?.length > 1" class="w-full">
                            <img class="bject-cover rounded-xl w-full h-[270px]" :src="subdivision.data.images[1]?.original_url" alt="">
                        </figure>
                        <figure v-if="subdivision.data.images?.length > 2" class="w-full">
                            <img class="bject-cover rounded-xl w-full h-[270px]" :src="subdivision.data.images[2]?.original_url" alt="">
                        </figure>
                        <figure v-if="subdivision.data.images?.length > 3" class="w-full">
                            <img class="bject-cover rounded-xl w-full h-[270px]" :src="subdivision.data.images[3]?.original_url" alt="">
                        </figure>
                        <figure v-if="subdivision.data.images?.length > 4" class="w-full">
                            <img class="bject-cover rounded-xl w-full h-[270px]" :src="subdivision.data.images[4]?.original_url" alt="">
                        </figure>
                    </div>
                    <button @click="showPreview = true" v-if="subdivision.data.images?.length > 5" class="absolute bottom-0 right-16 rounded-full bg-white border border-[#D9D9D9] p-2">Mostrar todas las fotos<i class="fa-solid fa-photo-film ml-2 text-xs"></i></button>
                </div>

                <!-- Informacion -->
                <div class="mt-10 lg:grid grid-cols-4 gap-x-8">
                    <div class="col-span-3">
                        <p class="text-sm text-[#4A4A4A] border-b pb-2"><i class="fa-solid fa-house mr-2 ml-4"></i> <i class="fa-solid fa-chevron-right"></i> <span class="text-black ml-2">{{ subdivision.data.name }}</span></p>

                        <div class="my-7">
                            <p class="font-bold text-lg mb-4 pl-4">Características</p>

                            <div class="flex flex-wrap items-center border-b border-[#D9D9D9] pb-7 space-x-3 pl-4 gap-y-2">

                                <!-- Precio desde -->
                                <div class="rounded-md border border-[#D9D9D9] flex items-center px-2 py-1 ml-3 md:ml-0">
                                    <i class="fa-solid fa-dollar-sign border-r border-[#D9D9D9] pr-1 text-lg"></i>
                                    <div class="flex flex-col justify-center px-3">
                                        <p class="text-sm text-[#4D4D4D]">Precio desde</p>
                                        <p class="text-base font-bold">{{ subdivision.data.lowest_price.numberFormat }} MXN</p>
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
                                <a v-if="subdivision.data.planos?.length > 0" :href="subdivision.data.planos[0]?.original_url" target="_blank" class="rounded-md border border-[#D9D9D9] flex items-center px-2 py-1 cursor-pointer">
                                    <i class="fa-solid fa-photo-film border-r border-[#D9D9D9] pr-1 text-lg"></i>
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
                            <div class="my-4 pl-4 border-b border-[#D9D9D9] pb-7">
                                <p class="font-bold text-lg">Amenidades</p>
                                <div class="flex items-center space-x-3 mt-4">
                                    <p v-for="amenity in subdivision.data.amenities" :key="amenity" class="text-[#4D4D4D] py-2 px-3 rounded-lg border border-[#D9D9D9]"><i class="mr-3" :class="getIcon(amenity)"></i>{{ amenity }}</p>
                                </div>
                            </div>

                            <!-- Ubicación -->
                            <div class="my-4 pl-4">
                                <p class="font-bold text-lg">Ubicación</p>
                                
                                <iframe class="mt-5 rounded-lg"
                                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA0s1a7phLN0iaD6-UE7m4qP-z21pH0eSc&q=Eiffel+Tower+Paris+France"
                                    width="600" height="380" frameborder="0" style="border:0; width: 100%;" allowfullscreen></iframe>                                    
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

                    <!-- lotes del fraccionamiento  -->
                    <div class="my-9 col-span-full">
                        <p class="text-lg font-bold">Lotes de {{ subdivision.data.name }} </p>
                        <div class="grid xs:grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4 mt-7 lg:mx-24">
                            <BatchCard v-for="batch in subdivision.data.batches" :key="batch" :batch="batch" />
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
import BatchCard from "@/Components/MyComponents/Batch/BatchCard.vue";

// services images
import s1 from "../../../../public/images/services1.png";
import s2 from "../../../../public/images/services2.png";
import s3 from "../../../../public/images/services3.png";
import s4 from "../../../../public/images/services4.png";
import s5 from "../../../../public/images/services5.png";
import s6 from "../../../../public/images/services6.png";

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
            showPreview: false,
            currentImage: 0,
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
        getIcon(amenity) {
            if ( amenity == 'Espacio para niños' ) {
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
        handlePlusImage(){
            this.currentImage === (this.subdivision.data.images?.length - 1) ? this.currentImage = 0 : this.currentImage += 1
        },
        handleMinusImage(){
            this.currentImage === 0 ? this.currentImage = this.subdivision.data.images?.length - 1 : this.currentImage -= 1
        },
    },
    components: {
        PrimaryButton,
        BatchCard,
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
