<template>
    <div class="flex items-center justify-center h-screen bg-gray-100 game-container">
      <div class="grid grid-cols-10 gap-1">
        <div v-for="(cell, index) in 100" :key="index" class="relative flex items-center justify-center w-10 h-10 border border-gray-400">
          <!-- Player -->
          <div v-if="isPlayerCell(index)" class="w-full h-full bg-blue-500"></div>
          <!-- Enemy -->
          <div v-if="isEnemyCell(index)" class="w-full h-full bg-red-500"></div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useEventListener } from '@vueuse/core';
  
  // Assuming a 10x10 grid
  const gridSize = 10;
  const playerPosition = ref({ x: 2, y: 2 });
  const enemyPosition = ref({ x: 5, y: 5 });
  
  // Utility to check if a cell index corresponds to the player's position
  function isPlayerCell(index) {
    const x = index % gridSize;
    const y = Math.floor(index / gridSize);
    return x === playerPosition.value.x && y === playerPosition.value.y;
  }
  
  // Utility to check if a cell index corresponds to the enemy's position
  function isEnemyCell(index) {
    const x = index % gridSize;
    const y = Math.floor(index / gridSize);
    return x === enemyPosition.value.x && y === enemyPosition.value.y;
  }
  
  // Mock function to simulate receiving enemy position updates via websocket
  // In a real app, you'd use Laravel Echo here to subscribe to a channel and listen for updates
  function mockEnemyUpdates() {
    setInterval(() => {
      enemyPosition.value = {
        x: Math.floor(Math.random() * gridSize),
        y: Math.floor(Math.random() * gridSize),
      };
    }, 2000);
  }
  
  // Listen for arrow key presses to move the player
  useEventListener(window, 'keydown', (event) => {
    switch (event.key) {
      case 'ArrowUp':
        playerPosition.value.y = Math.max(playerPosition.value.y - 1, 0);
        break;
      case 'ArrowDown':
        playerPosition.value.y = Math.min(playerPosition.value.y + 1, gridSize - 1);
        break;
      case 'ArrowLeft':
        playerPosition.value.x = Math.max(playerPosition.value.x - 1, 0);
        break;
      case 'ArrowRight':
        playerPosition.value.x = Math.min(playerPosition.value.x + 1, gridSize - 1);
        break;
    }
  });
  
  // Simulate enemy updates
  mockEnemyUpdates();
  </script>
  
  <style scoped>
  /* Tailwind utility classes handle most of our styling needs */
  </style>
  