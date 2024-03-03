<template>
    <AppLayout title="Nuevo fraccionamiento">
        <div class="lg:w-[80%] mx-auto rounded-md lg:border border-secondary p-5">
            <h1 class="font-bold text-lg text-center lg:text-left">Agregar fraccionamiento</h1>
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
                        <i @click="currentImage = currentImage - 1" v-if="currentImage > 1"
                            class="fa-solid fa-angle-left text-xs mr-2 cursor-pointer p-1"></i>
                        Imagen {{ currentImage }} de 8
                        <i @click="currentImage = currentImage + 1" v-if="currentImage < 8"
                            class="fa-solid fa-angle-right text-xs ml-2 cursor-pointer p-1"></i>
                    </p>
                </div>

                <div class="col-span-2">
                    <div class="mt-3">
                        <InputLabel value="Nombre del fraccionamiento*" class="ml-3 mb-1" />
                        <el-input v-model="form.name" placeholder="Escribe el nombre del fraccionamiento"
                            :maxlength="100" clearable />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="flex space-x-3 mt-3">
                        <div class="w-2/3">
                            <InputLabel value="Tipo de lotes" class="ml-3 mb-1" />
                            <el-select class="w-full" v-model="form.type" clearable placeholder="Seleccione"
                                no-data-text="No hay opciones disponibles"
                                no-match-text="No se encontraron coincidencias">
                                <el-option v-for="item in types" :key="item" :label="item" :value="item" />
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
                        <InputLabel value="Dirección*" class="ml-3 mb-1" />
                        <el-input v-model="form.address" placeholder="Escribe la dirección del fraccionamiento"
                            :maxlength="100" clearable />
                        <InputError :message="form.errors.address" />
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Url de maps" class="ml-3 mb-1" />
                        <el-input v-model="form.maps_url" placeholder="Pega la url de google maps" :maxlength="700"
                            clearable />
                        <InputError :message="form.errors.maps_url" />
                    </div>
                    <div class="mt-3">
                        <InputLabel value="Descripción*" class="ml-3 mb-1" />
                        <el-input v-model="form.description" :autosize="{ minRows: 3, maxRows: 5 }" type="textarea"
                            placeholder="Escribe la descripción" :maxlength="800" show-word-limit clearable />
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
                    <label class="flex items-center mt-3">
                        <Checkbox v-model:checked="form.soon" name="soon" />
                        <span class="ml-2 text-sm text-gray-600">Próximamente</span>
                    </label>
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
import Checkbox from '@/Components/Checkbox.vue';
import InputError from "@/Components/InputError.vue";
import FileUploader from "@/Components/MyComponents/FileUploader.vue";
import { useForm } from "@inertiajs/vue3";

export default {
    data() {
        const form = useForm({
            name: null,
            type: null,
            batches_availables: null,
            lowest_price: null,
            lowest_surface: null,
            address: null,
            maps_url: null,
            description: null,
            amenities: [],
            image_cover1: null,
            image_cover2: null,
            image_cover3: null,
            image_cover4: null,
            image_cover5: null,
            image_cover6: null,
            image_cover7: null,
            image_cover8: null,
            planos: null,
            soon: false,
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
    components: {
        AppLayout,
        InputFilePreview,
        PrimaryButton,
        FileUploader,
        InputLabel,
        InputError,
        Checkbox,
    },
    props: {

    },
    methods: {
        store() {
            this.form.post(route("subdivisions.store"), {
                onSuccess: () => {
                    this.$notify({
                        title: "Correcto",
                        message: "Se ha agregado un nuevo fraccionamiento",
                        type: "success",
                    });
                },
            });
        },
    }
}
</script>
