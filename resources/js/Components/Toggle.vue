<script setup>
import { rand } from '@vueuse/shared'
import { ref } from 'vue'
import { TransitionPresets, useTransition } from '@vueuse/core'


import { useDark, useToggle } from '@vueuse/core'

const isDark = useDark();

defineModel({
    isDark: Boolean,
})

const toggleDark = useToggle(isDark)

const duration = 800

const baseNumber = ref(0)

const baseVector = ref([0, 0])

function easeOutElastic(n) {
  return n === 0
    ? 0
    : n === 1
      ? 1
      : (2 ** (-10 * n)) * Math.sin((n * 10 - 0.75) * ((2 * Math.PI) / 3)) + 1 ;
}

const cubicBezierNumber = useTransition(baseNumber, {
  duration,
  transition: [0.75, 0, 0.25, 1],
  onFinished() {
    toggleDark()
  },
})

const customFnNumber = useTransition(baseNumber, {
  duration,
  transition: easeOutElastic,
})

const vector = useTransition(baseVector, {
  duration,
  transition: TransitionPresets.easeOutExpo,
})

function toggle() {
  baseNumber.value = baseNumber.value === 1 ? 0 : 1
  baseVector.value = [rand(0, 100), rand(0, 100)]

 

}
</script>

<template>
  <div>
    <button @click="toggleDark()">
      Transition
    </button>

    <p  class="mt-2 ">
      Cubic bezier curve: <b>{{ cubicBezierNumber.toFixed(2) }}</b>
    </p>

    <div class="track number">
      <div class="relative">
        <button  @click="toggle()" class="translate-x-[-25%] dark:translate-x-[-25%] sled"  :style="{ left: `${cubicBezierNumber}%` }" />
      </div>
    </div>

    <p class="mt-2">
      Custom function: <b>{{ customFnNumber.toFixed(2) }}</b>
    </p>

    <div class="track number">
      <div class="relative">
        <div class="sled" :style="{ left: `${customFnNumber}%` }" />
      </div>
    </div>

    <p class="mt-2">
      Vector: <b>[{{ vector[0].toFixed(2) }}, {{ vector[1].toFixed(2) }}]</b>
    </p>

    <div class="track vector">
      <div class="relative">
        <div class="sled" :style="{ left: `${vector[0]}%`, top: `${vector[1]}%` }" />
      </div>
    </div>
  </div>
</template>

<style scoped>
.track {
  background: rgba(221, 20, 20, 0.3);
  border-radius: 0.5rem;
  max-width: 6rem;
  height: 4rem;
  width: 100%;
}

.sled {
  background:rgba(49, 143, 12, 0.3);
  border-radius: 50%;
  height: 2rem;
  position: absolute;
  width: 2rem;

}

.number.track {
  height: 4rem;
  margin: 0.5rem 0;
  padding: 0 0.5rem;
}
/* 
.number.track .sled {
  transform: translateX(-50%);
  
} */


.vector.track {
  padding: 0.5rem;
}

.vector.track .relative {
  padding-bottom: 30%;
}

.vector.track .sled {
  transform: translateX(-50%) translateY(-50%);
}
</style>