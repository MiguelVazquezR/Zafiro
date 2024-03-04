<template>
    <AppLayout title="Trabajos">
        <div class="flex justify-between mx-4 lg:mx-20">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fa-solid fa-envelope text-gray-500 text-lg mr-2"></i>Trabajos
            </h2>
            <PrimaryButton @click="$inertia.get(route('works.create'))">+ Nuevo</PrimaryButton>
        </div>
        <div class="lg:w-5/6 mx-auto mt-6">
            <div class="flex space-x-2 justify-end">
                <el-popconfirm confirm-button-text="Si" cancel-button-text="No" icon-color="#FF0000"
                    title="¿Continuar con la eliminación?" @confirm="deleteSelections">
                    <template #reference>
                        <el-button type="danger" plain class="mb-3"
                            :disabled="disableMassiveActions">Eliminar</el-button>
                    </template>
                </el-popconfirm>
            </div>
            <el-table :data="works.data" @row-click="handleRowClick" max-height="450" style="width: 100%"
                @selection-change="handleSelectionChange" ref="multipleTableRef" :row-class-name="tableRowClassName"
                class="cursor-pointer">
                <el-table-column type="selection" width="55" />
                <el-table-column prop="folio" label="Folio" width="180" />
                <el-table-column prop="town" label="Municipio" />
                <el-table-column prop="ejido" label="Ejido" />
                <el-table-column prop="work_type" label="Trabajo" />
                <el-table-column prop="start_date" label="Fecha de inicio" />
                <el-table-column prop="created_at" label="Registrado el" />
            </el-table>
        </div>
    </AppLayout>
</template>

<script>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from 'axios';

export default {
    data() {
        return {
            disableMassiveActions: true,
            toast: null,
        };
    },
    components: {
        ApplicationLogo,
        AppLayout,
        PrimaryButton,
    },
    props: {
        works: {
            type: Object,
            default: []
        },
    },
    methods: {
        handleRowClick(row) {
            this.$inertia.get(route('works.edit', row));
        },
        handleSelectionChange(val) {
            this.$refs.multipleTableRef.value = val;

            if (!this.$refs.multipleTableRef.value.length) {
                this.disableMassiveActions = true;
            } else {
                this.disableMassiveActions = false;
            }
        },
        async deleteSelections() {
            try {
                const response = await axios.post(route('works.massive-delete', {
                    works: this.$refs.multipleTableRef.value
                }));

                if (response.status == 200) {
                    // let indexes = [];
                    this.toast.success(response.data.message);

                    // update list of works
                    let deletedIndexes = [];
                    this.works.data.forEach((message, index) => {
                        if (this.$refs.multipleTableRef.value.includes(message)) {
                            deletedIndexes.push(index);
                        }
                    });

                    // Ordenar los índices de forma descendente para evitar problemas de desplazamiento al eliminar elementos
                    deletedIndexes.sort((a, b) => b - a);

                    // Eliminar mensajes por índice
                    for (const index of deletedIndexes) {
                        this.works.data.splice(index, 1);
                    }

                } else {
                    this.toast.error(response.data.message);
                }

            } catch (err) {
                this.toast.error(err);
                console.log(err);
            }
        },
        tableRowClassName({ row, rowIndex }) {
            if (row.status === 1) {
                return 'text-green-600';
            }

            return '';
        },
    },
};

</script>
