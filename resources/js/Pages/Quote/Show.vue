<template>
    <div class="h-[800px] relative p-3 md:p-16 text-base md:text-lg">
        <!-- Decoracion header -->
        <figure class="absolute right-0 top-7">
            <img class="h-5 w-40 md:w-72 lg:w-[550px]" src="@/../../public/images/quote_decoration.png" alt="logo">
        </figure>
        <div class="flex items-center space-x-4 my-5">
            <ApplicationMark class="w-24 md:w-40" />
            <p class="font-bold">INGENIERÍA ZAFIRO</p>
        </div>

        <p class="text-[#373737] text-right">Fecha: <strong class="ml-2">{{ quote.data.created_at?.split('-')[0] }}</strong></p>

        <h1 class="text-center font-bold">Cotización {{ quote.data.name }}</h1>

        <!-- body -->
        <div class="my-4">
            <p>{{ quote.data.client }}</p>

            <p class="font-bold mt-7 mb-4">Descriipcón de los servicios</p>
            <p>{{ quote.data.description }}</p>

            <p class="font-bold mt-7 mb-2">Costo</p>
            <p>{{ quote.data.price }}</p>

            <p class="mt-7 mb-2">Esta cotización no incluye costos adicionales que puedan surgir debido a cambios significativos en el alcance del trabajo.</p>
        </div>
    </div>

        <!-- Decoracion footer -->
        <figure class="absolute left-0 botton-0 rotate-180">
            <img class="h-5 w-40 md:w-72 lg:w-[550px]" src="@/../../public/images/quote_decoration.png" alt="logo">
        </figure>
</template>

<script>
import ApplicationMark from '@/Components/ApplicationMark.vue';

export default {
data() {
    return {

    }
},
components:{
ApplicationMark,
},
props:{
quote: Object
},
methods:{

}
}
</script>
