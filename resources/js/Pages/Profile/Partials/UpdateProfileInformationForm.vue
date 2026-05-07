<template>
    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Cabecera de la Tarjeta -->
        <div class="px-6 md:px-8 py-6 border-b border-gray-100 bg-slate-50/50">
            <h3 class="text-lg font-bold text-gray-900">Información del Perfil</h3>
            <p class="mt-1 text-sm text-gray-500">Actualiza el nombre de tu cuenta y la dirección de correo electrónico.</p>
        </div>

        <!-- Cuerpo del Formulario -->
        <form @submit.prevent="updateProfileInformation" class="p-6 md:p-8 space-y-6">
            
            <!-- Foto de Perfil -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <input ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">

                <div class="relative shrink-0">
                    <!-- Foto actual -->
                    <div v-show="!photoPreview" class="h-24 w-24 rounded-full overflow-hidden border-4 border-white shadow-md bg-slate-100">
                        <img :src="user.profile_photo_url" :alt="user.name" class="h-full w-full object-cover">
                    </div>

                    <!-- Nueva Foto (Preview) -->
                    <div v-show="photoPreview" class="h-24 w-24 rounded-full overflow-hidden border-4 border-indigo-50 shadow-md">
                        <span class="block w-full h-full bg-cover bg-no-repeat bg-center" :style="'background-image: url(\'' + photoPreview + '\');'"></span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" @click.prevent="selectNewPhoto" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold text-sm hover:bg-indigo-100 transition-colors">
                        Seleccionar nueva foto
                    </button>
                    <button v-if="user.profile_photo_path" type="button" @click.prevent="deletePhoto" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl font-bold text-sm hover:bg-red-100 transition-colors">
                        Eliminar foto
                    </button>
                </div>
                <InputError :message="form.errors.photo" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nombre completo</label>
                    <input id="name" v-model="form.name" type="text" required autocomplete="name"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all" />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Correo electrónico</label>
                    <input id="email" v-model="form.email" type="email" required autocomplete="username"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all" />
                    <InputError :message="form.errors.email" class="mt-2" />

                    <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                        <p class="text-sm mt-3 text-amber-600 bg-amber-50 p-3 rounded-lg border border-amber-100">
                            Tu dirección de correo no está verificada.
                            <Link :href="route('verification.send')" method="post" as="button" class="underline font-bold hover:text-amber-800 ml-1" @click.prevent="sendEmailVerification">
                                Haz clic aquí para reenviar el enlace.
                            </Link>
                        </p>
                        <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                            Se ha enviado un nuevo enlace de verificación a tu correo.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie del Formulario (Acciones) -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 mt-8">
                <ActionMessage :on="form.recentlySuccessful" class="mr-3 text-emerald-600 font-bold">
                    ¡Guardado con éxito!
                </ActionMessage>

                <button type="submit" :class="{ 'opacity-50 cursor-not-allowed': form.processing }" :disabled="form.processing"
                    class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition-all shadow-md">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({ user: Object });

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }
    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => { verificationLinkSent.value = true; };
const selectNewPhoto = () => { photoInput.value.click(); };

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];
    if (!photo) return;
    const reader = new FileReader();
    reader.onload = (e) => { photoPreview.value = e.target.result; };
    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) { photoInput.value.value = null; }
};
</script>