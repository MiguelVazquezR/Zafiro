<template>
<AppLayout title="Cotizaciones">
    <section class="flex items-center justify-between p-3 md:p-9">
        <div class="flex items-center space-x-3">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <mask id="mask0_10395_184" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="20" height="20">
                    <rect width="20" height="20" fill="#D9D9D9"/>
                </mask>
                <g mask="url(#mask0_10395_184)">
                    <path d="M2.5 15.8307L6.25 9.9974L2.5 4.16406H12.5C12.7778 4.16406 13.0382 4.22656 13.2812 4.35156C13.5243 4.47656 13.7222 4.65017 13.875 4.8724L17.5 9.9974L13.875 15.1224C13.7222 15.3446 13.5243 15.5182 13.2812 15.6432C13.0382 15.7682 12.7778 15.8307 12.5 15.8307H2.5ZM5.54167 14.1641H12.5L15.4583 9.9974L12.5 5.83073H5.54167L8.25 9.9974L5.54167 14.1641Z" fill="#1C1B1F"/>
                </g>
            </svg>
            <h1>Cotizaciones</h1>
        </div>

        <PrimaryButton @click="$inertia.get(route('quotes.create'))"><i class="fa-solid fa-plus"></i> Nuevo</PrimaryButton>
    </section>

    <div class="lg:w-5/6 mx-auto mt-1">
        <div class="flex justify-between">
            <!-- pagination -->
            <div>
                <el-pagination @current-change="handlePagination" layout="prev, pager, next"
                    :total="quotes.data.length" />
            </div>
            <!-- buttons -->
            <div class="flex space-x-2 justify-end">
                <el-popconfirm confirm-button-text="Si" cancel-button-text="No" icon-color="#FF0000"
                    title="¿Continuar con la eliminación?" @confirm="deleteSelections">
                    <template #reference>
                        <el-button type="danger" plain class="mb-3"
                            :disabled="disableMassiveActions">Eliminar</el-button>
                    </template>
                </el-popconfirm>
            </div>
        </div>

        <el-table :data="quotes.data" @row-click="handleRowClick" max-height="450" style="width: 100%"
            @selection-change="handleSelectionChange" ref="multipleTableRef" :row-class-name="tableRowClassName"
            class="cursor-pointer">
            <el-table-column type="selection" width="55" />
            <el-table-column prop="folio" label="Folio" width="100" />
            <el-table-column prop="name" label="Nombre" width="250">
                <template #default="scope">
                    <div class="flex items-center">
                        <p class="mr-2 mt-px">
                            <el-tooltip v-if="scope.row.status === 'Esperando autorización del cliente'" content="Esperando autorización del cliente" placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-amber-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </el-tooltip>
                            <el-tooltip v-else-if="scope.row.status === 'Autorizada'" content="Autorizada" placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-green-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </el-tooltip>
                            <el-tooltip v-else-if="scope.row.status === 'Rechazada'" content="Rechazada" placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </el-tooltip>
                            <el-tooltip v-else content="Estado desconocido" placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.75v14.5m7-7H5" />
                                </svg>
                            </el-tooltip>
                        </p>
                        <span>{{ scope.row.name }}</span>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="client" label="Cliente" width="250" />
            <el-table-column prop="price" label="Costo" />
            <el-table-column prop="created_at" label="Registrado el" width="200" />
            <el-table-column align="right">
                <template #default="scope">
                    <el-dropdown trigger="click" @command="handleCommand">
                        <button @click.stop
                            class="el-dropdown-link mr-3 justify-center items-center size-8 rounded-full text-black hover:bg-gray2 transition-all duration-200 ease-in-out">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <template #dropdown>
                            <el-dropdown-menu>
                                <el-dropdown-item :command="'show-' + scope.row.id">Ver</el-dropdown-item>
                                <el-dropdown-item :command="'edit-' + scope.row.id">Editar</el-dropdown-item>
                                <el-dropdown-item :command="'pendent-' + scope.row.id">Esperando autorización del cliente</el-dropdown-item>
                                <el-dropdown-item :command="'authorized-' + scope.row.id">Autorizada</el-dropdown-item>
                                <el-dropdown-item :command="'rejected-' + scope.row.id">Rechazada</el-dropdown-item>
                            </el-dropdown-menu>
                        </template>
                    </el-dropdown>
                </template>
            </el-table-column>
        </el-table>
    </div>
</AppLayout>

</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import axios from 'axios';

export default {
data() {
    return {
        disableMassiveActions: true,
        toast: null,
    }
},
components:{
AppLayout,
PrimaryButton,
},
props:{
    quotes: Object
},
methods:{
    handleCommand(command) {
        const commandName = command.split('-')[0];
        const rowId = command.split('-')[1];

        if ( commandName == 'pendent' || commandName == 'authorized' || commandName == 'rejected' ) {
            this.updateStatus(commandName, rowId);
        } else {
            this.$inertia.get(route('quotes.' + commandName, rowId));
        }
    },
    handleRowClick(row) {
        this.$inertia.get(route('quotes.show', row));
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
            const response = await axios.post(route('quotes.massive-delete', {
                quotes: this.$refs.multipleTableRef.value
            }));

            if (response.status == 200) {
                this.$notify({
                    title: 'Éxito',
                    message: response.data.message,
                    type: 'success'
                });

                // update list of companies
                let deletedIndexes = [];
                this.quotes.data.forEach((quote, index) => {
                    if (this.$refs.multipleTableRef.value.includes(quote)) {
                        deletedIndexes.push(index);
                    }
                });

                // Ordenar los índices de forma descendente para evitar problemas de desplazamiento al eliminar elementos
                deletedIndexes.sort((a, b) => b - a);

                // Eliminar clientes por índice
                for (const index of deletedIndexes) {
                    this.quotes.data.splice(index, 1);
                }

            } else {
                this.$notify({
                    title: 'Algo salió mal',
                    message: response.data.message,
                    type: 'error'
                });
            }

        } catch (err) {
            this.$notify({
                title: 'Algo salió mal',
                message: err.message,
                type: 'error'
            });
            console.log(err);
        }
    },
    async updateStatus(status, quoteId) {
        try {
            const response = await axios.put(route('quotes.update-status', quoteId), {status: status});
            if ( response.status === 200 ) {

                //buscaa la cotización modificada
                const indexQuote = this.quotes.data.findIndex(item => item.id == quoteId);

                //en caso de encontrarla la actualiza
                if ( indexQuote != -1 ) {
                    this.quotes.data[indexQuote].status = response.data.item;
                }
            }
        } catch (error) {
            console.log(error);
        }
    }
}
}
</script>
