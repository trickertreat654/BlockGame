<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PhaserWindow from '@/Components/PhaserWindow.vue';
</script>

<template>
    <AuthenticatedLayout>
        <Head>
            <title>Phaser Game</title>
        </Head>
                <PhaserWindow />
    </AuthenticatedLayout>
</template>
  