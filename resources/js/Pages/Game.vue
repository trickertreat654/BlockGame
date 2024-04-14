<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GameBox from '@/Components/GameBox.vue';
import { Head, useForm, Link, router, usePage  } from '@inertiajs/vue3';
import {  ref, computed, reactive, onMounted, onUnmounted } from 'vue';
import { useEventListener } from '@vueuse/core';

const page = usePage();
const user = page.props.auth.user;

const gridSize = 10;
const grid = ref(Array.from({ length: 10 }, () => Array.from({ length: 10 }, () => ({}))));

const playerPosition = ref({ id:page.props.auth.user.id, x: 0, y: 0 });
const enemyPosition = ref([{ id:50 , x: 5, y: 5 }]);


const projectilePositions = reactive([]);



  const intervalId = setInterval(updateProjectilePositions, 100); // Update every 100ms

// const animationFrameId = requestAnimationFrame(updateProjectilePositions);



// const gameOver = computed(() => {
//   return playerPosition.value.x === enemyPosition.value.x && playerPosition.value.y === enemyPosition.value.y;
// });
const aim = ref(0);
useEventListener(window, 'keydown', (event) => {
  // console.log(event.key);
  switch (event.key) {
    case 'ArrowUp':
      aim.value = 2;
      playerPosition.value.y = Math.max(playerPosition.value.y - 1, 0);
      Echo.private(`player-channel`)
    .whisper('move', {
        name: user.name,
        position: {id:page.props.auth.user.id, x:playerPosition.value.x, y:playerPosition.value.y}
    });
      break;
    case 'ArrowDown':
      aim.value = 4;
      playerPosition.value.y = Math.min(playerPosition.value.y + 1, grid.value.length - 1);
       Echo.private(`player-channel`)
    .whisper('move', {
        name: user.name,
        position: {id:page.props.auth.user.id, x:playerPosition.value.x, y:playerPosition.value.y}
    });
      break;
    case 'ArrowLeft':
      aim.value = 1;
      playerPosition.value.x = Math.max(playerPosition.value.x - 1, 0);
       Echo.private(`player-channel`)
    .whisper('move', {
        name: user.name,
        position: {id:page.props.auth.user.id, x:playerPosition.value.x, y:playerPosition.value.y}
    });
      break;
    case 'ArrowRight':
      aim.value = 3;
      playerPosition.value.x = Math.min(playerPosition.value.x + 1, grid.value[0].length - 1);
       Echo.private(`player-channel`)
    .whisper('move', {
        name: user.name,
        position: {id:page.props.auth.user.id, x:playerPosition.value.x, y:playerPosition.value.y}
    });
      break;
    case ' ':
      // console.log('space');
      fireProjectile(playerPosition.value.x, playerPosition.value.y, aim.value, page.props.auth.user.id);

       Echo.private(`player-channel`)
    .whisper('fire', {
        name: user.name,
        position: {x:playerPosition.value.x, y:playerPosition.value.y, aim: aim.value, id:page.props.auth.user.id,}
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
       
    }); 

Echo.private(`player-channel`)
    .listenForWhisper('fire', (e) => {
                
      fireProjectile(e.position.x, e.position.y, e.position.aim, e.position.id);
       
    }); 
    
    

    function fireProjectile(initialX, initialY, direction, playerId) {
  projectilePositions.push({
    x: initialX,
    y: initialY,
    direction,
    playerId
  });
}


    function updateProjectilePositions() {
  projectilePositions.forEach(projectile => {
    switch (projectile.direction) {
      case 1: // left
        projectile.x -= 1;
        break;
      case 2: // up
        projectile.y -= 1;
        break;
      case 3: // right
        projectile.x += 1;
        break;
      case 4: // down
        projectile.y += 1;
        break;
    }
  });

  // Optionally remove projectiles that move off the grid
  for (let i = projectilePositions.length - 1; i >= 0; i--) {
    if (projectilePositions[i].x < 0 || projectilePositions[i].x >= grid.length ||
        projectilePositions[i].y < 0 || projectilePositions[i].y >= grid.length) {
      projectilePositions.splice(i, 1);
    }
  }
}












</script>

<template>
    <Head title="Game" />    
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Game
            <!-- <span v-if="gameOver" class="text-red-500">You lost!!!!</span>       -->
          </h2>         
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
    

  <!-- :key="cellIndex" -->
  <GameBox  v-for="(cell, cellIndex) in row"
    :key="cellIndex"
    :player="rowIndex === playerPosition.y && cellIndex === playerPosition.x"
    :playerId="rowIndex === playerPosition.y && cellIndex === playerPosition.x ? page.props.auth.user.id+'' : ' '"
    :enemy="enemyPosition.some(enemy => enemy.x === cellIndex && enemy.y === rowIndex)"
    :enemyId="enemyPosition.find(enemy => enemy.x === cellIndex && enemy.y === rowIndex) ? enemyPosition.find(enemy => enemy.x === cellIndex && enemy.y === rowIndex).id+'' : ' '"
    :projectile="projectilePositions.some(projectile => projectile.x === cellIndex && projectile.y === rowIndex)"
    >
  </GameBox> 
  
</div>    
  </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
