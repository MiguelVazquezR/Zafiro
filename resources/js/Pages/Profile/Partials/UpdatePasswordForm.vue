<template>
    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Cabecera -->
        <div class="px-6 md:px-8 py-6 border-b border-gray-100 bg-slate-50/50">
            <h3 class="text-lg font-bold text-gray-900">Actualizar Contraseña</h3>
            <p class="mt-1 text-sm text-gray-500">Asegúrate de que tu cuenta esté usando una contraseña larga y aleatoria para mantenerte seguro.</p>
        </div>

        <!-- Formulario -->
        <form @submit.prevent="updatePassword" class="p-6 md:p-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Contraseña Actual -->
                <div class="md:col-span-2">
                    <label for="current_password" class="block text-sm font-bold text-slate-700 mb-2">Contraseña actual</label>
                    <input id="current_password" ref="currentPasswordInput" v-model="form.current_password" type="password" autocomplete="current-password"
                        class="w-full md:w-1/2 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all" />
                    <InputError :message="form.errors.current_password" class="mt-2" />
                </div>

                <!-- Nueva Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Nueva contraseña</label>
                    <input id="password" ref="passwordInput" v-model="form.password" type="password" autocomplete="new-password"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all" />
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Confirmar nueva contraseña</label>
                    <input id="password_confirmation" v-model="form.password_confirmation" type="password" autocomplete="new-password"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all" />
                    <InputError :message="form.errors.password_confirmation" class="mt-2" />
                </div>
            </div>

            <!-- Acciones -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100 mt-8">
                <ActionMessage :on="form.recentlySuccessful" class="mr-3 text-emerald-600 font-bold">
                    ¡Contraseña actualizada!
                </ActionMessage>

                <button type="submit" :class="{ 'opacity-50 cursor-not-allowed': form.processing }" :disabled="form.processing"
                    class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition-all shadow-md">
                    Guardar Nueva Contraseña
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('user-password.update'), {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>