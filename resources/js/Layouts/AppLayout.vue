<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import SideNav from '@/Components/MyComponents/SideNav.vue';

// Iconos de Element Plus
import { 
    Menu as MenuIcon,
    ArrowDown,
    User,
    Key,
    SwitchButton,
    OfficeBuilding,
    Plus
} from '@element-plus/icons-vue';

defineProps({
    title: String,
});

// Control del menú lateral móvil
const mobileDrawer = ref(false);

const switchToTeam = (teamId) => {
    router.put(route('current-team.update'), {
        team_id: teamId,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};

// Manejador de acciones del menú de usuario de Element Plus
const handleUserCommand = (command) => {
    if (command === 'logout') {
        logout();
    } else if (command === 'profile') {
        router.get(route('profile.show'));
    } else if (command === 'api') {
        router.get(route('api-tokens.index'));
    }
};

// Manejador de acciones de Equipos
const handleTeamCommand = (command) => {
    if (command === 'create') {
        router.get(route('teams.create'));
    } else if (command === 'settings') {
        // En Inertia a veces necesitamos el ID actual, pero asumiremos que vas a la config general o la config del equipo actual
        // Aquí puedes ajustar la ruta según cómo Jetstream maneje tu setup.
        router.get(route('teams.show', 1)); // Reemplazar '1' con el ID dinámico si es necesario
    } else {
        // Si el comando es un ID de equipo, cambiamos a ese equipo
        switchToTeam(command);
    }
};
</script>

<template>
    <div class="app-wrapper bg-[#fcfdff] min-h-screen flex font-sans">
        <Head :title="title" />
        <Banner />

        <!-- Menú Lateral Escritorio (Fijo a la izquierda) -->
        <div class="hidden md:block sticky top-0 h-screen z-20">
            <SideNav />
        </div>

        <!-- Contenedor Principal -->
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300">
            
            <!-- Topbar (Navegación Superior) -->
            <header class="h-20 px-4 md:px-8 flex items-center justify-between sticky top-0 z-10 bg-white/70 backdrop-blur-xl border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <!-- Botón Hamburguesa (Solo Móvil) -->
                    <el-button class="md:!hidden !border-none !bg-gray-100 hover:!bg-gray-200" circle @click="mobileDrawer = true">
                        <el-icon size="20"><MenuIcon /></el-icon>
                    </el-button>

                    <!-- Logo -->
                    <Link :href="route('dashboard')" class="flex items-center">
                        <ApplicationMark class="block h-10 w-auto" />
                    </Link>
                </div>

                <!-- Acciones de Usuario y Equipos -->
                <div class="flex items-center gap-3 md:gap-5">
                    
                    <!-- Selector de Equipos (Element Plus Dropdown) -->
                    <el-dropdown v-if="$page.props.jetstream.hasTeamFeatures" trigger="click" @command="handleTeamCommand" placement="bottom-end">
                        <div class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-100 cursor-pointer transition-colors border border-gray-100">
                            <span class="text-sm font-semibold text-gray-600 hidden sm:block">
                                {{ $page.props.auth.user.current_team.name }}
                            </span>
                            <el-icon class="text-gray-400"><ArrowDown /></el-icon>
                        </div>
                        <template #dropdown>
                            <el-dropdown-menu class="one-ui-dropdown">
                                <el-dropdown-item disabled class="!text-xs !font-bold !text-gray-400 uppercase tracking-wider">Gestionar Equipo</el-dropdown-item>
                                <el-dropdown-item command="settings"><el-icon><OfficeBuilding /></el-icon> Configuración de equipo</el-dropdown-item>
                                <el-dropdown-item v-if="$page.props.jetstream.canCreateTeams" command="create" divided><el-icon><Plus /></el-icon> Crear Nuevo Equipo</el-dropdown-item>
                                
                                <template v-if="$page.props.auth.user.all_teams.length > 1">
                                    <el-dropdown-item divided disabled class="!text-xs !font-bold !text-gray-400 uppercase tracking-wider">Cambiar de Equipo</el-dropdown-item>
                                    <el-dropdown-item v-for="team in $page.props.auth.user.all_teams" :key="team.id" :command="team.id">
                                        <div class="flex items-center justify-between w-full gap-4">
                                            <span>{{ team.name }}</span>
                                            <div v-if="team.id == $page.props.auth.user.current_team_id" class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                        </div>
                                    </el-dropdown-item>
                                </template>
                            </el-dropdown-menu>
                        </template>
                    </el-dropdown>

                    <!-- Menú de Perfil (Element Plus Dropdown) -->
                    <el-dropdown trigger="click" @command="handleUserCommand" placement="bottom-end">
                        <div class="flex items-center gap-3 cursor-pointer hover:opacity-80 transition-opacity p-1 bg-white rounded-full shadow-sm border border-gray-100">
                            <img v-if="$page.props.jetstream.managesProfilePhotos" 
                                 class="h-9 w-9 rounded-full object-cover" 
                                 :src="$page.props.auth.user.profile_photo_url" 
                                 :alt="$page.props.auth.user.name">
                            <div v-else class="h-9 w-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                {{ $page.props.auth.user.name.charAt(0) }}
                            </div>
                            <span class="font-bold text-gray-700 hidden sm:block pr-2 text-sm">{{ $page.props.auth.user.name }}</span>
                        </div>
                        <template #dropdown>
                            <el-dropdown-menu class="one-ui-dropdown min-w-[200px]">
                                <el-dropdown-item disabled class="!text-xs !font-bold !text-gray-400 uppercase tracking-wider">Cuenta</el-dropdown-item>
                                <el-dropdown-item command="profile"><el-icon><User /></el-icon> Mi Perfil</el-dropdown-item>
                                <el-dropdown-item v-if="$page.props.jetstream.hasApiFeatures" command="api"><el-icon><Key /></el-icon> API Tokens</el-dropdown-item>
                                <el-dropdown-item command="logout" divided class="!text-red-500"><el-icon><SwitchButton /></el-icon> Cerrar sesión</el-dropdown-item>
                            </el-dropdown-menu>
                        </template>
                    </el-dropdown>

                </div>
            </header>

            <!-- Contenido Dinámico de la Página -->
            <main class="flex-1 p-4 md:p-8">
                <!-- Envolvemos el slot principal en un contenedor flotante para ese look "Burbuja" general si se desea,
                     o dejamos que las vistas hijas decidan. Lo dejamos limpio por defecto para máxima compatibilidad. -->
                <slot />
            </main>
        </div>

        <!-- Drawer (Menú Lateral Móvil) -->
        <el-drawer v-model="mobileDrawer" title="Navegación" direction="ltr" size="280px" class="one-ui-drawer md:hidden">
            <!-- Renderizamos TODO el SideNav dentro del Drawer y cerramos el drawer al cambiar de ruta -->
            <SideNav :isMobile="true" @close-drawer="mobileDrawer = false" />
        </el-drawer>

    </div>
</template>

<style scoped>
/* Sobrescribir estilos de Element Plus para los menús desplegables (Dropdowns) para hacerlos "One UI" */
:deep(.one-ui-dropdown) {
    border-radius: 16px !important;
    padding: 8px !important;
    box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1) !important;
    border: 1px solid #f1f3f5 !important;
}

:deep(.one-ui-dropdown .el-dropdown-menu__item) {
    border-radius: 12px;
    margin-bottom: 2px;
    padding: 10px 16px;
    font-weight: 600;
    color: #495057;
    transition: all 0.2s ease;
}

:deep(.one-ui-dropdown .el-dropdown-menu__item:hover) {
    background-color: #f1f3f5;
    color: #212529;
}

:deep(.one-ui-dropdown .el-dropdown-menu__item--divided) {
    margin-top: 6px;
    border-top: 1px solid #f1f3f5;
}

/* Modificaciones para el Drawer (Menú lateral en móvil) */
:deep(.one-ui-drawer .el-drawer__header) {
    margin-bottom: 0;
    padding-bottom: 16px;
    border-bottom: 1px solid #f1f3f5;
    font-weight: 800;
    color: #212529;
}

:deep(.one-ui-drawer) {
    border-radius: 0 24px 24px 0 !important;
}
</style>