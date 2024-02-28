<template>
    <div class="border border-secondary rounded-r-md py-4 px-3 text-sm w-56">
        <div class="mb-2">
            <p @click="inicioOptions = !inicioOptions" class="flex items-center cursor-pointer">Inicio
                <i v-if="inicioOptions" class="fa-solid fa-angle-down text-xs ml-3"></i>
                <i v-else class="fa-solid fa-angle-up text-xs ml-3"></i>
            </p>
            <ul v-if="inicioOptions" class="ml-2 *:cursor-pointer *:py-1 *:px-2 *:rounded-md space-y-1">
                <li @click="$inertia.get(route('subdivisions.create'))" :class="route().current('subdivisions.create') ? 'bg-primarylight' : '' " class="hover:bg-primarylight cursor-pointer px-2">Nuevo fraccionamiento</li>
                <li @click="$inertia.get(route('subdivisions.index'))" :class="route().current('subdivisions.index') ? 'bg-primarylight' : '' " class="hover:bg-primarylight cursor-pointer px-2">Lista de fraccionamientos</li>
                <li @click="$inertia.get(route('batches.create'))" :class="route().current('batches.create') ? 'bg-primarylight' : '' " class="hover:bg-primarylight cursor-pointer px-2">Nuevo lote</li>
                <li @click="$inertia.get(route('batches.index'))" :class="route().current('batches.index') ? 'bg-primarylight' : '' " class="hover:bg-primarylight cursor-pointer px-2">Lista de lotes</li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
data(){
    return {
        inicioOptions: true,
    }
},
methods:{

}
}
</script>
