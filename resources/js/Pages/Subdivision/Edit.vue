<template>
    <AppLayout title="Editar fraccionamiento">
        <div class="lg:w-2/3 mx-auto rounded-md lg:border border-secondary p-5">
            <h1 class="font-bold text-lg text-center lg:text-left">Editar fraccionamiento <span class="text-primary">"{{ subdivision.name }}"</span></h1>
            <div class="lg:grid grid-cols-3 space-x-3 mt-3">
                <div>
                    <p class="text-xs text-red-600">Si vas a cambiar o agregar nuevas imágenes es necesario ponerlas todas de nuevo ya que se eliminarán</p>
                    <InputFilePreview v-show="currentImage == 1" :imageUrl="subdivision.media[0]?.original_url" @cleared="form.cleared_cover1 = true" @imagen="this.form.image_cover1 = $event;" />
                    <InputFilePreview v-show="currentImage == 2" :imageUrl="subdivision.media[1]?.original_url" @cleared="form.cleared_cover2 = true" @imagen="this.form.image_cover2 = $event;" />
                    <InputFilePreview v-show="currentImage == 3" :imageUrl="subdivision.media[2]?.original_url" @cleared="form.cleared_cover3 = true" @imagen="this.form.image_cover3 = $event;" />
                    <InputFilePreview v-show="currentImage == 4" :imageUrl="subdivision.media[3]?.original_url" @cleared="form.cleared_cover4 = true" @imagen="this.form.image_cover4 = $event;" />
                    <InputFilePreview v-show="currentImage == 5" :imageUrl="subdivision.media[4]?.original_url" @cleared="form.cleared_cover5 = true" @imagen="this.form.image_cover5 = $event;" />
                    <InputFilePreview v-show="currentImage == 6" :imageUrl="subdivision.media[5]?.original_url" @cleared="form.cleared_cover6 = true" @imagen="this.form.image_cover6 = $event;" />
                    <InputFilePreview v-show="currentImage == 7" :imageUrl="subdivision.media[6]?.original_url" @cleared="form.cleared_cover7 = true" @imagen="this.form.image_cover7 = $event;" />
                    <InputFilePreview v-show="currentImage == 8" :imageUrl="subdivision.media[7]?.original_url" @cleared="form.cleared_cover8 = true" @imagen="this.form.image_cover8 = $event;" />
                    <p class="text-left ml-20 mt-2">
                        <i @click="currentImage = currentImage - 1" v-if="currentImage > 1" class="fa-solid fa-angle-left text-xs mr-2 cursor-pointer p-1"></i>
                        Imagen {{ currentImage }} de 8
                        <i @click="currentImage = currentImage + 1" v-if="currentImage < 8" class="fa-solid fa-angle-right text-xs ml-2 cursor-pointer p-1"></i>
                    </p>
                </div>

                <div class="col-span-2">
                    <div class="mt-3">
                        <InputLabel value="Nombre del fraccionamiento*" class="ml-3 mb-1" />
                        <el-input v-model="form.name" placeholder="Escribe el nombre del fraccionamiento" :maxlength="100" clearable />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="flex space-x-3 mt-3">
                        <div class="w-2/3">
                            <InputLabel value="Tipo de lotes" class="ml-3 mb-1" />
                            <el-select class="w-full" v-model="form.type" clearable placeholder="Seleccione"
                            no-data-text="No hay opciones disponibles" no-match-text="No se encontraron coincidencias">
                            <el-option v-for="type in types" :key="type" :label="type" :value="type" />
                            </el-select>
                            <InputError :message="form.errors.type" />
                        </div>
                        <div class="w-1/3">
                            <InputLabel value="Lotes disponibles" class="ml-3 mb-1" />
                            <el-input v-model="form.batches_availables" placeholder="0" :maxlength="100" clearable />
                            <InputError :message="form.errors.batches_availables" />
                        </div>
                    </div>
                    <div class="flex space-x-3 mt-3">
                        <div class="w-1/2">
                            <InputLabel value="Precio desde" class="ml-3 mb-1 text-sm" />
                            <el-input v-model="form.lowest_price" placeholder="0"
                            :formatter="(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                            :parser="(value) => value.replace(/\$\s?|(,*)/g, '')">
                                <template #prefix>
                                    <i class="fa-solid fa-dollar-sign pr-2 border-r-2"></i>
                                </template>
                            </el-input>
                            <InputError :message="form.errors.lowest_price" />
                        </div>
                        <div class="w-1/2">
                            <InputLabel value="Superficie desde" class="ml-3 mb-1 text-sm" />
                            <el-input v-model="form.lowest_surface" placeholder="0"
                            :formatter="(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                            :parser="(value) => value.replace(/\$\s?|(,*)/g, '')">
                                <template #prefix>
                                    <p class="pr-2 border-r-2 text-sm">m2</p>
                                </template>
                            </el-input>
                            <InputError :message="form.errors.lowest_surface" />
                        </div>
                    </div>
                    <div>
                        <InputLabel value="Planos" class="ml-3 mb-1 text-sm" />
                        <FileUploader @files-selected="this.form.planos = $event" />
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Ubicación*" class="ml-3 mb-1" />
                        <el-input v-model="form.address" placeholder="Escribe el ubicación" :maxlength="100" clearable />
                        <InputError :message="form.errors.address" />
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Descripción*" class="ml-3 mb-1" />
                        <el-input v-model="form.description" :autosize="{ minRows: 3, maxRows: 5 }" type="textarea"
                                    placeholder="Escribe una breve descripción del evento" :maxlength="800" show-word-limit clearable />
                        <InputError :message="form.errors.description" />
                    </div>  
                    <div class="w-full mt-3">
                        <InputLabel value="Amenidades" class="ml-3 mb-1" />
                        <el-select v-model="form.amenities" multiple placeholder="Seleccione" class="!w-full"
                            no-data-text="No hay opciones registradas" no-match-text="No se encontraron coincidencias">
                            <el-option v-for="amenity in amenities" :key="amenity" :value="amenity.label">
                                <i :class="amenity.icon"></i>
                                <span class="ml-3">{{ amenity.label }}</span>
                            </el-option>
                        </el-select>
                        <InputError :message="form.errors.amenities" />
                    </div>

                </div>
            </div>
            <div class="text-right mt-5 col-span-full">
                <PrimaryButton :disabled="form.processing" @click="update">Guardar cambios</PrimaryButton>
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
        name: this.subdivision.name,
        type: this.subdivision.type,
        batches_availables: this.subdivision.batches_availables,
        lowest_price: this.subdivision.lowest_price,
        lowest_surface: this.subdivision.lowest_surface,
        address: this.subdivision.address,
        description: this.subdivision.description,
        amenities: this.subdivision.amenities,
        image_cover1: null,
        image_cover2: null,
        image_cover3: null,
        image_cover4: null,
        image_cover5: null,
        image_cover6: null,
        image_cover7: null,
        image_cover8: null,
        cleared_cover1: false,
        cleared_cover2: false,
        cleared_cover3: false,
        cleared_cover4: false,
        cleared_cover5: false,
        cleared_cover6: false,
        cleared_cover7: false,
        cleared_cover8: false,
        planos: null,
    });
    return {
        form,
        currentImage: 1,
        types: [
            'Residenciales',
            'Comerciales',
            'Industriales',
        ],
        amenities: [
            {
                    label: 'Espacio para niños',
                    icon: 'fa-solid fa-fan',
                },
                {
                    label: 'Piscina',
                    icon: 'fa-solid fa-person-swimming',
                },
                {
                    label: 'Salón de eventos',
                    icon: 'fa-solid fa-people-roof',
                },
                {
                    label: 'Áreas verdes',
                    icon: 'fa-solid fa-leaf',
                },
                {
                    label: 'Parque',
                    icon: 'fa-solid fa-fan',
                },
                {
                    label: 'Gimnasio',
                    icon: 'fa-solid fa-dumbbell',
                },
        ]
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
subdivision: Object
},
methods:{
    update() {
        if (this.form.planos != null || this.form.image_cover1 != null) {

            this.form.post(route("subdivisions.update-with-media", this.subdivision.id), {
                onSuccess: () => {
                    this.$notify({
                        title: "Correcto",
                        message: "Se ha editado el fraccionamiento",
                        type: "success",
                    });
                },
            });
        } else {
            this.form.put(route("subdivisions.update", this.subdivision.id), {
                onSuccess: () => {
                    this.$notify({
                        title: "Correcto",
                        message: "Se ha editado el fraccionamiento",
                        type: "success",
                    });
                },
            });
            }
    },
}
}
</script>
