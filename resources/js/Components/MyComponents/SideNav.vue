<template>
  <div class="sidenav-container" :class="{ 'is-collapsed': isCollapsed && !isMobile, 'is-mobile': isMobile }">
    
    <!-- Botón para colapsar/expandir el menú (Oculto en móvil) -->
    <div v-if="!isMobile" class="toggle-container">
      <div class="toggle-btn" @click="toggleMenu" :title="isCollapsed ? 'Expandir menú' : 'Ocultar menú'">
        <el-icon v-if="!isCollapsed"><Fold /></el-icon>
        <el-icon v-else><Expand /></el-icon>
      </div>
    </div>

    <el-menu
      :default-active="currentRoute"
      :collapse="isCollapsed && !isMobile"
      class="one-ui-menu"
      unique-opened
    >
      <!-- Módulos Principales -->
      <el-menu-item index="dashboard" @click="navigate('dashboard')">
        <el-icon><Odometer /></el-icon>
        <template #title>Dashboard</template>
      </el-menu-item>

      <el-menu-item index="messages.index" @click="navigate('messages.index')">
        <el-icon><ChatDotRound /></el-icon>
        <template #title>Mensajes</template>
      </el-menu-item>

      <el-menu-item index="works.index" @click="navigate('works.index')">
        <el-icon><Briefcase /></el-icon>
        <template #title>Trabajos</template>
      </el-menu-item>

      <el-menu-item index="quotes.index" @click="navigate('quotes.index')">
        <el-icon><Document /></el-icon>
        <template #title>Cotizaciones</template>
      </el-menu-item>

      <!-- Módulos de Terrenos (Ahora separados) -->
      <el-menu-item index="subdivisions.index" @click="navigate('subdivisions.index')">
        <el-icon><MapLocation /></el-icon>
        <template #title>Fraccionamientos</template>
      </el-menu-item>

      <el-menu-item index="batches.index" @click="navigate('batches.index')">
        <el-icon><Grid /></el-icon>
        <template #title>Lotes</template>
      </el-menu-item>

      <!-- Opción de Finanzas -->
      <el-menu-item index="finanzas.index" @click="goToFinanzas">
        <el-icon><Money /></el-icon>
        <template #title>Gestor de finanzas</template>
      </el-menu-item>

    </el-menu>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { 
  Odometer,
  ChatDotRound,
  Briefcase,
  Document,
  MapLocation, 
  Grid, 
  Money,
  Fold,    
  Expand   
} from '@element-plus/icons-vue';

// Definimos si el componente está siendo renderizado en la vista móvil (Drawer)
const props = defineProps({
  isMobile: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close-drawer']);

// Estado reactivo para controlar si el menú está oculto en escritorio
const isCollapsed = ref(false);

const toggleMenu = () => {
  if (!props.isMobile) {
    isCollapsed.value = !isCollapsed.value;
  }
};

// Computed property para determinar la ruta activa
const currentRoute = computed(() => {
  if (route().current('dashboard')) return 'dashboard';
  if (route().current('messages.*')) return 'messages.index';
  if (route().current('works.*')) return 'works.index';
  if (route().current('quotes.*')) return 'quotes.index';
  // Actualizado: Todo lo que sea subdivisiones resaltará "subdivisions.index"
  if (route().current('subdivisions.*')) return 'subdivisions.index';
  // Actualizado: Todo lo que sea lotes resaltará "batches.index"
  if (route().current('batches.*')) return 'batches.index';
  if (route().current('finanzas.*')) return 'finanzas.index';
  return '';
});

// Función de navegación con Inertia y emitimos evento para cerrar el drawer
const navigate = (routeName) => {
  router.get(route(routeName));
  emit('close-drawer');
};

// Función de navegación clásica para Finanzas
const goToFinanzas = () => {
  window.location.href = route('finanzas.index');
};
</script>

<style scoped>
/* Contenedor principal: Transición suave de ancho */
.sidenav-container {
  width: 280px;
  min-height: 100vh;
  padding: 24px 16px;
  background-color: #fcfdff; 
  border-right: 1px solid #f1f3f5;
  box-shadow: 4px 0 24px rgba(0, 0, 0, 0.02);
  border-radius: 0 32px 32px 0;
  transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  overflow-x: hidden;
}

/* Ancho colapsado (Escritorio) */
.sidenav-container.is-collapsed {
  width: 88px;
}

/* Estilos específicos cuando está en un Drawer (Móvil) */
.sidenav-container.is-mobile {
  width: 100%;
  min-height: auto;
  padding: 0;
  border-right: none;
  border-radius: 0;
  box-shadow: none;
  background-color: transparent;
}

/* Área del botón para colapsar */
.toggle-container {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 16px;
  padding-right: 8px;
}

.sidenav-container.is-collapsed .toggle-container {
  justify-content: center;
  padding-right: 0;
}

.toggle-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 12px;
  cursor: pointer;
  color: #495057;
  transition: all 0.2s ease;
}

.toggle-btn:hover {
  background-color: #f1f3f5;
  color: #212529;
}

.toggle-btn .el-icon {
  font-size: 20px;
}

/* Sobrescribir estilos de Element Plus */
.one-ui-menu {
  border-right: none !important;
  background-color: transparent !important;
}

:deep(.el-menu-item) {
  border-radius: 16px;
  margin-bottom: 6px;
  height: 52px;
  line-height: 52px;
  color: #495057;
  font-weight: 500;
  font-size: 0.95rem;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

:deep(.el-menu-item:hover) {
  background-color: #f1f3f5 !important;
  color: #212529 !important;
  transform: translateX(4px);
}

.sidenav-container.is-collapsed :deep(.el-menu-item:hover),
.sidenav-container.is-mobile :deep(.el-menu-item:hover) {
  transform: none; /* Deshabilitamos el efecto en colapsado o móvil */
}

:deep(.el-menu-item.is-active) {
  background-color: #e0e7ff !important;
  color: #4f46e5 !important;
  font-weight: 700;
}

:deep(.el-icon) {
  font-size: 20px;
  margin-right: 14px;
  transition: color 0.3s ease;
}

.menu-text {
  font-weight: 600;
  font-size: 1rem;
}

:deep(.el-menu--collapse .el-menu-item) {
  padding: 0 !important;
  display: flex;
  justify-content: center;
}
:deep(.el-menu--collapse .el-icon) {
  margin-right: 0;
}
</style>