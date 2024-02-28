<template>
    <div class="rounded-[20px] border border-[#D9D9D9] shadow-md h-[530px] text-2xl relative cursor-pointer">
        <figure class="h-1/2 bg-gray-300 rounded-t-[20px] relative">
            <img class="object-contain h-full w-full rounded-t-[20px]" :src="subdivision.cover1[0]?.original_url" alt="">
            <!-- Options -->
            <div class="flex items-center space-x-2 absolute top-2 right-2">
                <i class="fa-solid fa-pencil text-white text-sm pl-2 pt-1 w-8 h-8 rounded-full bg-black/75"></i>
                <i class="fa-regular fa-trash-can text-white text-sm w-8 h-8 rounded-full bg-black/75"></i>
            </div>
        </figure>
        <div class="p-5">
            <p class="text-gray-500 text-right text-base">- Lotes {{ subdivision.type }}</p>
            <p class="text-[#4D4D4D] text-xl mt-4"> {{ subdivision.name }}</p>
            <p class="text-[#4D4D4D] text-base mt-1">Lotes desde</p>
            <p class="text-black text-3xl font-bold mt-1"> ${{ subdivision.lowest_price }}</p>
            <p class="text-[#4D4D4D] text-base mt-1"><i class="fa-solid fa-location-dot mr-2"></i>{{ subdivision.address }}</p>
            <p class="text-[#4D4D4D] text-base mt-1"><i class="fa-solid fa-ruler-horizontal mr-2"></i>{{ subdivision.lowest_surface }} m2</p>
            <p class="text-[#4D4D4D] text-base mt-1">Disponibles: {{ subdivision.batches_availables }}</p>
        </div>
    </div>  
</template>

<script>
export default {
props:{
    subdivision: Object
}
}
</script>