<template>
<AppLayout title="Lotes">
    <div class="grid xs:grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4 lg:mx-42">
        <BatchCard @delete-batch="deletebatch" v-for="batch in batches.data" :key="batch" :batch="batch" />
    </div>
</AppLayout>

</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import BatchCard from "@/Components/MyComponents/Batch/BatchCard.vue";

export default {
data() {
    return {

    }
},
components:{
AppLayout,
BatchCard
},
props:{
batches: Object
},
methods:{
    deletebatch(batchId) {
        // encuentra el index del lote eliminado
        const indexToDelete = this.batches.data.findIndex(sub => sub.id === batchId);
        // si encuentra el index en el arreglo de batches lo elimina
        if (indexToDelete != -1) {
            this.batches.data.splice(indexToDelete, 1);
        }
    } 
}
}
</script>
