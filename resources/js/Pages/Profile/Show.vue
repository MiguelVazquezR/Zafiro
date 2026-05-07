<template>
    <AppLayout title="Mi Perfil">
        <!-- Contenedor Principal (Estilo One UI) -->
        <div class="max-w-4xl mx-auto space-y-8 px-4 sm:px-6 lg:px-8 pb-16 pt-8">
            
            <!-- Encabezado de la página -->
            <div class="flex items-center gap-4 mb-8">
                <div class="p-3 bg-indigo-100 text-indigo-600 rounded-2xl">
                    <el-icon size="24"><User /></el-icon>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Mi Perfil</h2>
                    <p class="text-sm text-gray-500 font-medium mt-0.5">Gestiona tu información personal y ajustes de seguridad</p>
                </div>
            </div>

            <!-- Formularios en formato Tarjeta (Cards) -->
            <div class="space-y-8">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <UpdateProfileInformationForm :user="$page.props.auth.user" />
                </div>

                <div v-if="$page.props.jetstream.canUpdatePassword">
                    <UpdatePasswordForm />
                </div>

                <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
                    <TwoFactorAuthenticationForm :requires-confirmation="confirmsTwoFactorAuthentication" />
                </div>

                <LogoutOtherBrowserSessionsForm :sessions="sessions" />

                <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                    <DeleteUserForm />
                </template>
            </div>
            
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import { User } from '@element-plus/icons-vue';

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>