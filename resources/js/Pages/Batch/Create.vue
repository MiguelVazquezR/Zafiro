<template>
    <AppLayout title="Nuevo lote">
        <div class="w-5/6 lg:w-[80%] mx-auto rounded-md lg:border border-secondary p-5">
            <h1 class="font-bold text-lg text-center lg:text-left">Agregar Lote</h1>
            <div class="lg:grid grid-cols-3 space-x-3 mt-3">
                <div>
                    <InputFilePreview v-show="currentImage == 1" @imagen="this.form.image_cover1 = $event;" />
                    <InputFilePreview v-show="currentImage == 2" @imagen="this.form.image_cover2 = $event;" />
                    <InputFilePreview v-show="currentImage == 3" @imagen="this.form.image_cover3 = $event;" />
                    <InputFilePreview v-show="currentImage == 4" @imagen="this.form.image_cover4 = $event;" />
                    <InputFilePreview v-show="currentImage == 5" @imagen="this.form.image_cover5 = $event;" />
                    <InputFilePreview v-show="currentImage == 6" @imagen="this.form.image_cover6 = $event;" />
                    <InputFilePreview v-show="currentImage == 7" @imagen="this.form.image_cover7 = $event;" />
                    <InputFilePreview v-show="currentImage == 8" @imagen="this.form.image_cover8 = $event;" />
                    <p class="text-left ml-20 mt-2">
                        <i @click="currentImage = currentImage - 1" v-if="currentImage > 1" class="fa-solid fa-angle-left text-xs mr-2 cursor-pointer p-1"></i>
                        Imagen {{ currentImage }} de 8
                        <i @click="currentImage = currentImage + 1" v-if="currentImage < 8" class="fa-solid fa-angle-right text-xs ml-2 cursor-pointer p-1"></i>
                    </p>
                </div>

                <div class="col-span-2">
                    <div class="mt-3">
                        <InputLabel value="Nombre del lote*" class="ml-3 mb-1" />
                        <el-input v-model="form.name" placeholder="Escribe el nombre del lote" :maxlength="100" clearable />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="flex space-x-3 mt-3">
                        <div class="w-full">
                            <InputLabel value="Fraccionamiento" class="ml-3 mb-1" />
                            <el-select class="w-full" v-model="form.subdivision_id" clearable placeholder="Seleccione"
                            no-data-text="No hay opciones disponibles" no-match-text="No se encontraron coincidencias">
                            <el-option v-for="subdivision in subdivisions" :key="subdivision" :label="subdivision.name" :value="subdivision.id" />
                            </el-select>
                            <InputError :message="form.errors.subdivision_id" />
                        </div>
                    </div>
                    <div class="flex space-x-3 mt-3">
                        <div class="w-1/2">
                            <InputLabel value="Precio" class="ml-3 mb-1 text-sm" />
                            <el-input v-model="form.price" placeholder="0"
                            :formatter="(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                            :parser="(value) => value.replace(/\$\s?|(,*)/g, '')">
                                <template #prefix>
                                    <i class="fa-solid fa-dollar-sign pr-2 border-r-2"></i>
                                </template>
                            </el-input>
                            <InputError :message="form.errors.price" />
                        </div>
                        <div class="w-1/2">
                            <InputLabel value="Superficie" class="ml-3 mb-1 text-sm" />
                            <el-input v-model="form.surface" placeholder="0"
                            :formatter="(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                            :parser="(value) => value.replace(/\$\s?|(,*)/g, '')">
                                <template #prefix>
                                    <p class="pr-2 border-r-2 text-sm">m2</p>
                                </template>
                            </el-input>
                            <InputError :message="form.errors.surface" />
                        </div>
                    </div>
                    <div>
                        <InputLabel value="Planos" class="ml-3 mb-1 text-sm" />
                        <FileUploader @files-selected="this.form.planos = $event" :multiple="false" />
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Dirección*" class="ml-3 mb-1" />
                        <el-input v-model="form.address" placeholder="Escribe la dirección del lote" :maxlength="100" clearable />
                        <InputError :message="form.errors.address" />
                    </div>
                    <div class="grid grid-cols-2 gap-2 mt-3">
                        <div>
                            <InputLabel value="Latitud" class="ml-3 mb-1" />
                            <el-input v-model="form.lat" placeholder="Latitud" :maxlength="20" clearable />
                            <InputError :message="form.errors.lat" />
                        </div>
                        <div>
                            <InputLabel value="Longitud" class="ml-3 mb-1" />
                            <el-input v-model="form.lon" placeholder="Longitud" :maxlength="20" clearable />
                            <InputError :message="form.errors.lon" />
                        </div>
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Descripción*" class="ml-3 mb-1" />
                        <el-input v-model="form.description" :autosize="{ minRows: 3, maxRows: 5 }" type="textarea"
                                    placeholder="Escribe una descripción" :maxlength="800" show-word-limit clearable />
                        <InputError :message="form.errors.description" />
                    </div> 
                </div>
            </div>
            <div class="text-right mt-5 col-span-full">
                <PrimaryButton :disabled="form.processing" @click="store">Publicar</PrimaryButton>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import InputFilePreview from '@/Components/MyComponents/InputFilePreview.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import FileUploader from "@/Components/MyComponents/FileUploader.vue";
import { useForm } from "@inertiajs/vue3";

export default {
data() {
    const form = useForm({
        name: null,
        subdivision_id: null,
        price: null,
        surface: null,
        address: null,
        lon: null,
        lat: null,
        description: null,
        image_cover1: null,
        image_cover2: null,
        image_cover3: null,
        image_cover4: null,
        image_cover5: null,
        image_cover6: null,
        image_cover7: null,
        image_cover8: null,
        planos: null,
    });
    return {
        form,
        currentImage: 1,
    }
},
components:{
AppLayout,
InputFilePreview,
PrimaryButton,
FileUploader,
InputLabel,
InputError
},
props:{
subdivisions: Array
},
methods:{
    store() {
    this.form.post(route("batches.store"), {
      onSuccess: () => {
        this.$notify({
          title: "Correcto",
          message: "Se ha agregado un nuevo lote",
          type: "success",
        });
      },
    });
  },
}
}
</script>
