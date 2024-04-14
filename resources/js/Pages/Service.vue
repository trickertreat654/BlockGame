<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import {  ref } from 'vue';



const isTrue = true == [];
const isFalse = true == ![];
console.log(isTrue)
console.log(isFalse)

console.log(2-true*'2'+'2')






const props = defineProps({
    service: {
        type: Object,
    }
});


// const dark = ref(false);
// console.log(dark.value)
const form = useForm({
    content: props.service ? props.service.content : '' 
})


Echo.private(`service-channel.${props.service ? props.service.content.id : 1}`)
    .listen('ServiceFinished', (e) => {
        console.log(e);
    });

</script>

<template>
    <Head title="Service" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Service</h2>
        </template>

        <div class="py-12 ">
          
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <form @submit.prevent="form.post(route('service.store'))">
                        <input v-model="form.content">
                        <button type="submit">submit</button>
                    </form>
                
                    <Link method="post" :href="route('service.preform')">Preform Service</Link>
                    <div class="p-6 text-gray-900 dark:text-gray-100">{{ props.service ? props.service.content : '' }}</div>
                    <div class="p-6 text-gray-900 dark:text-gray-100">{{ props.service ? props.service.content_changed : '' }}</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
