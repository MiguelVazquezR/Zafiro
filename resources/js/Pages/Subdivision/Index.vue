<template>
<AppLayout title="Fraccionamientos">
    <div class="grid xs:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-4 mx-4 mt-8 lg:mt-0 lg:mx-10">
        <SubdivisionCard @delete-subdivision="deleteSubdivision" v-for="subdivision in subdivisions.data" :key="subdivision" :subdivision="subdivision" />
    </div>
</AppLayout>

</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import SubdivisionCard from "@/Components/MyComponents/Subdivision/SubdivisionCard.vue";

export default {
data() {
    return {

    }
},
components:{
AppLayout,
SubdivisionCard
},
props:{
subdivisions: Object
},
methods:{
    deleteSubdivision(subdivisionId) {
        // encuentra el indexx del fraccionamiento eliminado
        const indexToDelete = this.subdivisions.data.findIndex(sub => sub.id === subdivisionId);
        // si encuentra el index en el arreglo de subdivisions lo elimina
        if (indexToDelete != -1) {
            this.subdivisions.data.splice(indexToDelete, 1);
        }
    } 
}
}
</script>
