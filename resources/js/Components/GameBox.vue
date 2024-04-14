<script setup>
import { onUpdated } from 'vue';

defineProps({
    player: Boolean,
    enemy: Boolean,
    projectile: Boolean,
    playerId: String,
    enemyId: String,


    

})

onUpdated(() => {
    console.log('updated')
})
</script>
<template>

<div       
    class="flex items-center justify-center w-12 h-12 border border-gray-300 grid-cell"
    :class="player ? 'bg-blue-500' : enemy  ? 'bg-red-500' : 
    projectile ? 'bg-green-500' : ''"
    >   
    {{ playerId }}
    {{ enemyId }}
    
  </div>

</template>