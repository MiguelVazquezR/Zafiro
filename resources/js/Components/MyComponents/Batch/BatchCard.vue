<template>
    <div
        class="bg-white rounded-xl border shadow-md p-3 w-full h-[240px] flex space-x-4 cursor-pointer">
        <figure class="bg-gray-300 rounded-[20px] h-full relative">
            <img class="object-cover h-full w-full rounded-[20px]" :src="batch.images[currentImage]?.original_url">
            <!-- Change image -->
            <div v-if="batch.images?.length > 1" class="absolute top-1/2 w-full flex justify-between items-center">
                <i @click.stop="handleMinusImage()"
                    class="fa-solid fa-angle-left text-white text-sm px-[13px] py-2 rounded-full bg-black/60 hover:scale-105"></i>
                <i @click.stop="handlePlusImage()"
                    class="fa-solid fa-angle-right text-white text-sm px-[13px] py-2 rounded-full bg-black/60 hover:scale-105"></i>
            </div>
            <!-- Actions -->
            <div v-if="$page.props.auth?.user?.id" class="flex items-center space-x-2 absolute top-2 right-2">
                <i @click.stop="$inertia.get(route('batches.edit', batch.id))"
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

        <!-- <Link :href="route('batches.show', this.batch.id)"> -->
        <div @click="$inertia.visit(route('batches.show', this.batch.id))" class="w-[315px] text-sm">
            <p class="text-right">{{ batch.subdivision?.name }}</p>
            <p class="text-[#4D4D4D] mt-2 text-base">Venta de terreno/lote</p>
            <p class="text-2xl font-bold">${{ batch.price.numberFormat }}</p>
            <p v-if="batch.subdivision?.type" class="text-[#4D4D4D]">Lotes {{ batch.subdivision?.type }}</p>
            <p class="text-[#4D4D4D] mt-1"><i class="fa-solid fa-location-dot mr-2"></i>{{ batch.address }}</p>
            <p class="text-[#4D4D4D] mt-1"><i class="fa-solid fa-ruler-horizontal mr-2"></i>{{ batch.surface }} m2</p>
        </div>
        <!-- </Link> -->
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";
export default {
    data() {
        return {
            currentImage: 0,
        }
    },
    components: {
        Link
    },
    props: {
        batch: Object
    },
    emits: ['delete-batch'],
    methods: {
        async deleteItem() {
            try {
                const response = await axios.delete(route('batches.destroy', this.batch.id));
                if (response.status === 200) {
                    this.$notify({
                        title: "Correcto",
                        message: "Se ha eliminado el lote",
                        type: "success",
                    });
                    this.$emit('delete-batch', this.batch.id);
                }
            } catch (error) {
                console.log(error);
                this.$notify({
                    title: "Hubo un problema",
                    message: "No se pudo eliminar el lote",
                    type: "error",
                });
            }
        },
        handlePlusImage() {
            this.currentImage === (this.batch.images?.length - 1) ? this.currentImage = 0 : this.currentImage += 1
        },
        handleMinusImage() {
            this.currentImage === 0 ? this.currentImage = this.batch.images?.length - 1 : this.currentImage -= 1
        },
    }
}
</script>
