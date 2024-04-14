<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link, router, usePage  } from '@inertiajs/vue3';
import {  ref, computed } from 'vue';
import { useEventListener } from '@vueuse/core';
import { watch } from 'vue';


const page = usePage();
const gridSize = 10;
const heath = ref(0);

// console.log(page.props.auth.user);
const user = page.props.auth.user;

const playerPosition = ref({ id:page.props.auth.user.id, x: 0, y: 0 });
const enemyPosition = ref([{ id:50 , x: 5, y: 5 }]);
// const enemyPosition = ref();

const grid = ref(Array.from({ length: 10 }, () => Array.from({ length: 10 }, () => ({}))));

const isPlayerCell = (rowIndex, cellIndex) => {
  //  heath.value=heath.value+1;
  console.log('hit');
  return rowIndex === playerPosition.value.y && cellIndex === playerPosition.value.x;
};

const gameOver = computed(() => {
  return playerPosition.value.x === enemyPosition.value.x && playerPosition.value.y === enemyPosition.value.y;
});

// watch(() => playerPosition.value, (newVal, oldVal) => {
//   if (newVal.x === enemyPosition.value.x && newVal.y === enemyPosition.value.y) {
//     heath.value -= 10;
//   }
// });

const isEnemyCell = (rowIndex, cellIndex) => {
  // heath.value++;
  console.log('hit');

  for (let i = 0; i < enemyPosition.value.length; i++) {
    if (rowIndex === enemyPosition.value[i].y && cellIndex === enemyPosition.value[i].x) {
      return enemyPosition.value[i].id;
    }
  }
  // return rowIndex === enemyPosition.value.y && cellIndex === enemyPosition.value.x;

};
const cellCheck = (rowIndex, cellIndex) => {

  for (let i = 0; i < enemyPosition.value.length; i++) {
    if (rowIndex === enemyPosition.value[i].y && cellIndex === enemyPosition.value[i].x) {
      return {style: 'bg-red-500', id: enemyPosition.value[i].id};
    }
    else if (rowIndex === playerPosition.value.y && cellIndex === playerPosition.value.x) {
      return {style: 'bg-blue-500', id: page.props.auth.user.id};
    }
    return {style: '', id: ''};

  }
  // return rowIndex === enemyPosition.value.y && cellIndex === enemyPosition.value.x;

};

useEventListener(window, 'keydown', (event) => {
  switch (event.key) {
    case 'ArrowUp':
      playerPosition.value.y = Math.max(playerPosition.value.y - 1, 0);
      Echo.private(`player-channel`)
    .whisper('move', {
        name: user.name,
        position: {id:page.props.auth.user.id, x:playerPosition.value.x, y:playerPosition.value.y}
    });
      break;
    case 'ArrowDown':
      playerPosition.value.y = Math.min(playerPosition.value.y + 1, grid.value.length - 1);
       Echo.private(`player-channel`)
    .whisper('move', {
        name: user.name,
        position: {id:page.props.auth.user.id, x:playerPosition.value.x, y:playerPosition.value.y}
    });
      break;
    case 'ArrowLeft':
      playerPosition.value.x = Math.max(playerPosition.value.x - 1, 0);
       Echo.private(`player-channel`)
    .whisper('move', {
        name: user.name,
        position: {id:page.props.auth.user.id, x:playerPosition.value.x, y:playerPosition.value.y}
    });
      break;
    case 'ArrowRight':
      playerPosition.value.x = Math.min(playerPosition.value.x + 1, grid.value[0].length - 1);
       Echo.private(`player-channel`)
    .whisper('move', {
        name: user.name,
        position: {id:page.props.auth.user.id, x:playerPosition.value.x, y:playerPosition.value.y}
    });
      break;
  }
});


const flag = ref(true);

Echo.private(`player-channel`)
    .listenForWhisper('move', (e) => {
        // console.log(e);
        // console.log(enemyPosition.value);
        flag.value = true;
    
        for (let i = 0; i < enemyPosition.value.length; i++) {
            if (e.position.id === enemyPosition.value[i].id) {
                enemyPosition.value[i] = {
                    id: e.position.id,
                    x: e.position.x,
                    y: e.position.y,
                };
                flag.value = false;
                // console.log('spot one')
            }           
        }
        if (flag.value)
        {

          // console.log('spot second')

          enemyPosition.value.push({
            id: e.position.id,
            x: e.position.x,
            y: e.position.y,
          });
        }
            
        //
      
      // enemyPosition.value = {
      //       id: e.position.id,
      //       x: e.position.x,
      //       y: e.position.y,
      //   };
    })
    ;


    const temp = ref(true);

</script>

<template>
    <Head title="Game" />
    
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Game
            <span v-if="gameOver" class="text-red-500">You fucking lost!!!!</span>      
          </h2>

          <!-- heath bar -->

          

        </template>
        <div class="py-12 ">       
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- <button @click="move" class="p-2 bg-blue-500">click</button> -->
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="flex flex-col game-container">
    <div 
      v-for="(row, rowIndex) in grid" 
      :key="rowIndex" 
      class="flex grid-row"
    >
    <div
    v-for="(cell, cellIndex) in row"
    :key="cellIndex"
    class="flex items-center justify-center w-12 h-12 border border-gray-300 grid-cell"
    :class="isPlayerCell(rowIndex, cellIndex) ? 'bg-blue-500' : isEnemyCell(rowIndex, cellIndex) ? 'bg-red-500' : ''"
    >   
    <!-- :class="cellCheck(rowIndex, cellIndex)" -->
    {{ isEnemyCell(rowIndex, cellIndex) }} 
    {{ isPlayerCell(rowIndex, cellIndex) ? page.props.auth.user.id : ''}} 





  
    
  </div>

  
</div>








    
  </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
