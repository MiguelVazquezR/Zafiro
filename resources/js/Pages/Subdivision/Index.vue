<template>
<AppLayout title="Fraccionamientos">
    <div class="grid xs:grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-44">
        <SubdivisionCard v-for="subdivision in subdivisions.data" :key="subdivision" :subdivision="subdivision" />
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
    
}
}
</script>
