<template>
    <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Cabecera -->
        <div class="px-6 md:px-8 py-6 border-b border-gray-100 bg-slate-50/50">
            <h3 class="text-lg font-bold text-gray-900">Sesiones Activas</h3>
            <p class="mt-1 text-sm text-gray-500">Gestiona y cierra la sesión de tu cuenta en otros navegadores y dispositivos.</p>
        </div>

        <!-- Contenido -->
        <div class="p-6 md:p-8">
            <div class="max-w-2xl text-sm text-gray-600 mb-6">
                Si es necesario, puedes cerrar la sesión de todos los demás navegadores en todos tus dispositivos. A continuación se enumeran algunas de tus sesiones recientes; sin embargo, esta lista puede no ser exhaustiva.
            </div>

            <!-- Lista de Dispositivos -->
            <div v-if="sessions.length > 0" class="mt-5 space-y-4 max-w-2xl">
                <div v-for="(session, i) in sessions" :key="i" class="flex items-center gap-4 bg-slate-50 border border-slate-100 p-4 rounded-2xl">
                    <div class="p-3 bg-white rounded-xl shadow-sm">
                        <!-- Icono Escritorio -->
                        <svg v-if="session.agent.is_desktop" class="w-6 h-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                        </svg>
                        <!-- Icono Móvil -->
                        <svg v-else class="w-6 h-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                    </div>

                    <div class="flex-1">
                        <div class="text-sm font-bold text-gray-800">
                            {{ session.agent.platform ? session.agent.platform : 'Desconocido' }} - {{ session.agent.browser ? session.agent.browser : 'Desconocido' }}
                        </div>
                        <div class="text-xs text-gray-500 font-medium flex items-center gap-2 mt-1">
                            <span>{{ session.ip_address }}</span>
                            <span class="text-gray-300">•</span>
                            <span v-if="session.is_current_device" class="text-emerald-500 font-bold bg-emerald-50 px-2 py-0.5 rounded-md">Este dispositivo</span>
                            <span v-else>Última vez activo {{ session.last_active }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center mt-8">
                <button @click="confirmLogout" class="bg-slate-800 text-white px-6 py-3 rounded-xl font-bold hover:bg-slate-900 transition-all shadow-md">
                    Cerrar sesión en otros navegadores
                </button>

                <ActionMessage :on="form.recentlySuccessful" class="ml-4 text-emerald-600 font-bold">
                    ¡Hecho!
                </ActionMessage>
            </div>

            <!-- Modal de Confirmación de Contraseña de Jetstream -->
            <DialogModal :show="confirmingLogout" @close="closeModal">
                <template #title>
                    <span class="font-black text-xl">Confirmar Cierre de Sesión</span>
                </template>

                <template #content>
                    Por favor ingresa tu contraseña para confirmar que deseas cerrar sesión en el resto de tus dispositivos.
                    <div class="mt-4">
                        <input
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-2 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                            placeholder="Tu contraseña"
                            autocomplete="current-password"
                            @keyup.enter="logoutOtherBrowserSessions"
                        />
                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <div class="flex gap-3 justify-end">
                        <button @click="closeModal" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-colors">
                            Cancelar
                        </button>
                        <button :class="{ 'opacity-50': form.processing }" :disabled="form.processing" @click="logoutOtherBrowserSessions" 
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition-all">
                            Confirmar
                        </button>
                    </div>
                </template>
            </DialogModal>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';

defineProps({
    sessions: Array,
});

const confirmingLogout = ref(false);
const passwordInput = ref(null);

const form = useForm({ password: '' });

const confirmLogout = () => {
    confirmingLogout.value = true;
    setTimeout(() => passwordInput.value.focus(), 250);
};

const logoutOtherBrowserSessions = () => {
    form.delete(route('other-browser-sessions.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingLogout.value = false;
    form.reset();
};
</script>