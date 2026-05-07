<template>
    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 md:p-8 transition-all">
        <form @submit.prevent="$emit('submit')" class="space-y-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- SECCIÓN IZQUIERDA: Multimedia (Imágenes y Planos) -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Galería del Proyecto</h3>
                        <p v-if="isEdit" class="text-xs font-semibold text-rose-500 bg-rose-50 p-2.5 rounded-xl mb-4 border border-rose-100">
                            Si cambias o agregas nuevas imágenes, debes colocarlas todas de nuevo ya que se reemplazarán.
                        </p>
                        
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex flex-col items-center">
                            <!-- Visualizadores de Imagen -->
                            <InputFilePreview v-show="currentImage === 1" :imageUrl="getImgUrl(0)" @cleared="form.cleared_cover1 = true" @imagen="form.image_cover1 = $event" />
                            <InputFilePreview v-show="currentImage === 2" :imageUrl="getImgUrl(1)" @cleared="form.cleared_cover2 = true" @imagen="form.image_cover2 = $event" />
                            <InputFilePreview v-show="currentImage === 3" :imageUrl="getImgUrl(2)" @cleared="form.cleared_cover3 = true" @imagen="form.image_cover3 = $event" />
                            <InputFilePreview v-show="currentImage === 4" :imageUrl="getImgUrl(3)" @cleared="form.cleared_cover4 = true" @imagen="form.image_cover4 = $event" />
                            <InputFilePreview v-show="currentImage === 5" :imageUrl="getImgUrl(4)" @cleared="form.cleared_cover5 = true" @imagen="form.image_cover5 = $event" />
                            <InputFilePreview v-show="currentImage === 6" :imageUrl="getImgUrl(5)" @cleared="form.cleared_cover6 = true" @imagen="form.image_cover6 = $event" />
                            <InputFilePreview v-show="currentImage === 7" :imageUrl="getImgUrl(6)" @cleared="form.cleared_cover7 = true" @imagen="form.image_cover7 = $event" />
                            <InputFilePreview v-show="currentImage === 8" :imageUrl="getImgUrl(7)" @cleared="form.cleared_cover8 = true" @imagen="form.image_cover8 = $event" />

                            <!-- Controles de Paginación de Imágenes -->
                            <div class="flex items-center gap-4 mt-4">
                                <button type="button" @click="currentImage--" :disabled="currentImage <= 1" class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 disabled:opacity-40 hover:bg-gray-100 transition-all">
                                    <el-icon><ArrowLeft /></el-icon>
                                </button>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Imagen {{ currentImage }} de 8</span>
                                <button type="button" @click="currentImage++" :disabled="currentImage >= 8" class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 disabled:opacity-40 hover:bg-gray-100 transition-all">
                                    <el-icon><ArrowRight /></el-icon>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Planos Documentales</h3>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                            <FileUploader @files-selected="form.planos = $event" />
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN DERECHA: Datos del Fraccionamiento -->
                <div class="lg:col-span-2 space-y-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2 border-b border-gray-100 pb-2">Información Principal</h3>
                    
                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Nombre del fraccionamiento *</label>
                        <el-input v-model="form.name" placeholder="Escribe el nombre..." maxlength="100" clearable size="large" class="one-ui-input" />
                        <InputError :message="form.errors.name" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div class="sm:col-span-2">
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Tipo de lotes</label>
                            <el-select v-model="form.type" clearable placeholder="Seleccione" size="large" class="w-full one-ui-input" no-data-text="No hay opciones" no-match-text="Sin coincidencias">
                                <el-option v-for="item in types" :key="item" :label="item" :value="item" />
                            </el-select>
                            <InputError :message="form.errors.type" class="mt-1" />
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Lotes disp.</label>
                            <el-input v-model="form.batches_availables" placeholder="0" maxlength="100" clearable size="large" class="one-ui-input" />
                            <InputError :message="form.errors.batches_availables" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Precio desde</label>
                            <el-input v-model="form.lowest_price" placeholder="0" size="large" class="one-ui-input"
                                :formatter="(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                :parser="(value) => value.replace(/\$\s?|(,*)/g, '')">
                                <template #prefix>
                                    <span class="font-bold text-gray-500">$</span>
                                </template>
                            </el-input>
                            <InputError :message="form.errors.lowest_price" class="mt-1" />
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Superficie desde</label>
                            <el-input v-model="form.lowest_surface" placeholder="0" size="large" class="one-ui-input"
                                :formatter="(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                :parser="(value) => value.replace(/\$\s?|(,*)/g, '')">
                                <template #prefix>
                                    <span class="font-bold text-gray-500 border-r border-gray-300 pr-2">m²</span>
                                </template>
                            </el-input>
                            <InputError :message="form.errors.lowest_surface" class="mt-1" />
                        </div>
                    </div>

                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mt-8 mb-2 border-b border-gray-100 pb-2">Ubicación y Detalles</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Dirección *</label>
                            <el-input v-model="form.address" type="textarea" :autosize="{ minRows: 2, maxRows: 4 }" placeholder="Escribe la dirección..." maxlength="800" show-word-limit class="one-ui-input" />
                            <InputError :message="form.errors.address" class="mt-1" />
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Latitud (Opcional)</label>
                            <el-input v-model="form.lat" placeholder="Ej. 20.659698" maxlength="20" clearable size="large" class="one-ui-input" />
                            <InputError :message="form.errors.lat" class="mt-1" />
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Longitud (Opcional)</label>
                            <el-input v-model="form.lon" placeholder="Ej. -103.349609" maxlength="20" clearable size="large" class="one-ui-input" />
                            <InputError :message="form.errors.lon" class="mt-1" />
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Descripción del Fraccionamiento *</label>
                        <el-input v-model="form.description" type="textarea" :autosize="{ minRows: 3, maxRows: 6 }" placeholder="Escribe una breve descripción del desarrollo..." maxlength="800" show-word-limit class="one-ui-input" />
                        <InputError :message="form.errors.description" class="mt-1" />
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 mb-1.5 block">Amenidades</label>
                        <el-select v-model="form.amenities" multiple placeholder="Selecciona amenidades" class="w-full one-ui-input" size="large" no-data-text="No hay opciones" no-match-text="Sin coincidencias">
                            <el-option v-for="amenity in amenitiesList" :key="amenity.label" :value="amenity.label">
                                <i :class="amenity.icon" class="text-gray-400 w-5"></i>
                                <span class="ml-2 font-medium">{{ amenity.label }}</span>
                            </el-option>
                        </el-select>
                        <InputError :message="form.errors.amenities" class="mt-1" />
                    </div>

                    <!-- Toggle Estilo One UI -->
                    <div class="mt-4 flex items-center gap-3 p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                        <el-switch v-model="form.soon" active-color="#4f46e5" />
                        <div>
                            <p class="text-sm font-bold text-gray-800">Próximamente</p>
                            <p class="text-xs font-medium text-gray-500">Marcar si el desarrollo aún no está disponible.</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Acciones Inyectadas desde la Vista Padre -->
            <div class="pt-6 border-t border-gray-100 mt-8 flex justify-end gap-3">
                <slot name="actions"></slot>
            </div>
            
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import InputFilePreview from '@/Components/MyComponents/InputFilePreview.vue';
import FileUploader from '@/Components/MyComponents/FileUploader.vue';
import InputError from '@/Components/InputError.vue';
import { ArrowLeft, ArrowRight } from '@element-plus/icons-vue';

const props = defineProps({
    form: { type: Object, required: true },
    isEdit: { type: Boolean, default: false },
    subdivision: { type: Object, default: null }
});

defineEmits(['submit']);

const currentImage = ref(1);

const types = ['Residenciales', 'Comerciales', 'Industriales'];

const amenitiesList = [
    { label: 'Espacio para niños', icon: 'fa-solid fa-child-reaching' }, // Adaptado a un icono FontAwesome más claro si gustas
    { label: 'Piscina', icon: 'fa-solid fa-person-swimming' },
    { label: 'Salón de eventos', icon: 'fa-solid fa-people-roof' },
    { label: 'Áreas verdes', icon: 'fa-solid fa-leaf' },
    { label: 'Parque', icon: 'fa-solid fa-tree' }, // Adaptado
    { label: 'Gimnasio', icon: 'fa-solid fa-dumbbell' }
];

// Utilidad para extraer URL de imágenes si estamos en edición
const getImgUrl = (index) => {
    if (props.isEdit && props.subdivision?.media?.[index]) {
        return props.subdivision.media[index].original_url;
    }
    return null;
};
</script>

<style scoped>
/* Estilos consistentes One UI para Inputs de Element Plus */
:deep(.one-ui-input .el-input__wrapper),
:deep(.one-ui-input .el-textarea__inner) {
    border-radius: 12px;
    background-color: #f8fafc;
    box-shadow: none !important;
    border: 1px solid transparent;
    transition: all 0.2s ease;
}

:deep(.one-ui-input .el-input__wrapper.is-focus),
:deep(.one-ui-input .el-textarea__inner:focus) {
    background-color: #ffffff;
    border-color: #6366f1;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
}

:deep(.el-input-group__prepend) {
    border-radius: 12px 0 0 12px;
    background-color: #f8fafc;
    border: none;
}
</style>