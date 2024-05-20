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
                <el-table-column prop="folio" label="Folio" width="110" />
                <el-table-column label="Pagos" width="180">
                    <template #default="scope">
                        <div class="flex items-center">
                            <p class="mr-2 mt-px">
                                <el-tooltip v-if="workIsPaid(scope.row)" content="Liquidado" placement="top">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-green-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                </el-tooltip>
                                <el-tooltip v-else content="Esperando liquidación" placement="top">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-amber-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </el-tooltip>
                            </p>
                            <span>{{ scope.row.payments?.length ?? 0 }} pagos (${{
                                calcTotalPaid(scope.row).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") }})</span>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column prop="price" label="Costo" />
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
        workIsPaid(work) {
            return this.calcTotalPaid(work) >= work.raw_price;
        },
        calcTotalPaid(work) {
            if (!work.payments?.length) return 0;

            return work.payments?.reduce((acc, item) => {
                return acc += item.amount;
            }, 0);
        },
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
                const indexesToDelete = this.$refs.multipleTableRef.value.map(item => item.id);
                const response = await axios.post(route('works.massive-delete', {
                    works: indexesToDelete
                }));

                if (response.status === 200) {
                    // remover los trabajos eliminados
                    this.works.data = this.works.data.filter(element => !indexesToDelete.includes(element.id));

                    this.$notify({
                        title: "Correcto",
                        message: "",
                        type: "success",
                    });

                }
            } catch (err) {
                console.log(err);
                this.$notify({
                    title: "Problema al eliminar registro(s)",
                    message: "",
                    type: "error",
                });
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
