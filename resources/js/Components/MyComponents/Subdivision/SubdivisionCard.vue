<template>
    <section v-if="!subdivision.soon" @click="$inertia.get(route('subdivisions.show', subdivision.id))"
        class="rounded-[20px] border border-[#D9D9D9] shadow-md h-[420px] text-xl relative cursor-pointer">
        <figure class="h-1/2 bg-gray-300 rounded-t-[20px] relative">
            <img class="object-contain h-full w-full rounded-t-[20px]"
                :src="subdivision.images[currentImage]?.original_url" alt="">
            <!-- Change image -->
            <div v-if="subdivision.images?.length > 1"
                class="absolute top-1/2 -right-[4px] w-[90%] flex justify-between items-center mx-6">
                <i @click.stop="handleMinusImage"
                    class="fa-solid fa-angle-left text-white text-sm px-[13px] py-2 rounded-full bg-black/60 hover:scale-105"></i>
                <i @click.stop="handlePlusImage"
                    class="fa-solid fa-angle-right text-white text-sm px-[13px] py-2 rounded-full bg-black/60 hover:scale-105"></i>
            </div>
            <!-- Actions -->
            <div v-if="$page.props.auth?.user?.id" class="flex items-center space-x-2 absolute top-2 right-2">
                <i @click.stop="$inertia.get(route('subdivisions.edit', subdivision.id))"
                    class="fa-solid fa-pencil text-white text-sm pl-[9px] pt-[6px] w-8 h-8 rounded-full bg-black/60 hover:scale-105"></i>
                <el-popconfirm confirm-button-text="Si" cancel-button-text="No" icon-color="#C30303" title="¿Continuar?"
                    @confirm="deleteItem()">
                    <template #reference>
                        <i @click.stop=""
                            class="fa-regular fa-trash-can text-white text-sm pl-[10px] pt-[6px] w-8 h-8 rounded-full bg-black/60 hover:scale-105"></i>
                    </template>
                </el-popconfirm>
            </div>
        </figure>
        <div class="px-4 py-3">
            <p class="text-gray-500 text-right text-sm">- Lotes {{ subdivision.type }}</p>
            <p class="text-[#4D4D4D] text-lg mt-1"> {{ subdivision.name }}</p>
            <p class="text-[#4D4D4D] text-sm mt-px">Lotes desde</p>
            <p class="text-black text-2xl font-bold mt-px"> ${{ subdivision.lowest_price.numberFormat }}</p>
            <p class="text-[#4D4D4D] text-sm mt-px"><i class="fa-solid fa-location-dot mr-2"></i>{{ subdivision.address
                }}</p>
            <p class="text-[#4D4D4D] text-sm mt-px"><i class="fa-solid fa-ruler-horizontal mr-2"></i>Desde {{
        subdivision.lowest_surface }} m2</p>
            <p class="text-[#4D4D4D] text-sm mt-px">Disponibles: {{ subdivision.batches_availables }}</p>
        </div>
    </section>
    <section v-else class="rounded-[20px] border shadow-md h-[420px] text-xl cursor-pointer bg-gray1A relative">
        <div class="flex flex-col text-center items-center justify-center h-[90%]">
            <h1 class="text-primary font-bold">PROXIMAMENTE</h1>
            <p class="text-white">Fraccionamiento "{{ subdivision.name }}"</p>
        </div>
        <p class="text-white text-sm text-center">Espera los detalles exclusivos sobre los terrenos</p>
        <div v-if="$page.props.auth?.user?.id" class="flex items-center space-x-2 absolute top-2 right-2">
            <i @click.stop="$inertia.get(route('subdivisions.edit', subdivision.id))"
                class="fa-solid fa-pencil text-white text-sm pl-[9px] pt-[6px] w-8 h-8 rounded-full bg-black/60 hover:scale-105"></i>
            <el-popconfirm confirm-button-text="Si" cancel-button-text="No" icon-color="#C30303" title="¿Continuar?"
                @confirm="deleteItem()">

                <template #reference>
                    <i @click.stop=""
                        class="fa-regular fa-trash-can text-white text-sm pl-[10px] pt-[6px] w-8 h-8 rounded-full bg-black/60 hover:scale-105"></i>
                </template>
            </el-popconfirm>
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            currentImage: 0,
        }
    },
    props: {
        subdivision: Object
    },
    emits: ['delete-subdivision'],
    methods: {
        async deleteItem() {
            try {
                const response = await axios.delete(route('subdivisions.destroy', this.subdivision.id));
                if (response.status === 200) {
                    this.$notify({
                        title: "Correcto",
                        message: "Se ha eliminado el fraccionamiento",
                        type: "success",
                    });
                    this.$emit('delete-subdivision', this.subdivision.id);
                }
            } catch (error) {
                console.log(error);
                this.$notify({
                    title: "Hubo un problema",
                    message: "No se pudo eliminar el fraccionamiento",
                    type: "error",
                });
            }
        },
        handlePlusImage() {
            this.currentImage === (this.subdivision.images?.length - 1) ? this.currentImage = 0 : this.currentImage += 1
        },
        handleMinusImage() {
            this.currentImage === 0 ? this.currentImage = this.subdivision.images?.length - 1 : this.currentImage -= 1
        },
    }
}
</script>