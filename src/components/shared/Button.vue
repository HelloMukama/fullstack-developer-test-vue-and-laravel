<template>
  <div class="relative">
    <button
      @click="toggleDropdown"
      :class="`${props.color} ${sizes[props.size]} ${props.textColor} rounded font-semibold flex items-center space-x-2 shadow`">
      <component class="h-5 w-5 stroke-2" :is="Icons[props.icon]"></component>
      <span>{{ props.label }}</span>
      <component class="h-4 w-4 stroke-2" :is="Icons['ChevronDownIcon']" v-if="props.dropDown"></component>
    </button>
    <div v-if="props.dropDown && isDropdownOpen" class="dropdown-content">
      <slot name="dropDownContent"></slot>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import * as Icons from '@heroicons/vue/24/outline';

const sizes = {
  sm: 'px-1 py-2 text-xs',
  md: 'px-3 py-2 text-sm',
  lg: 'px-5 py-3 text-sm',
  xl: 'px-10 py-4 text-base',
};

const props = defineProps({
  label: [String, Number],
  icon: [String, Number],
  size: [String, Number],
  color: [String, Number],
  textColor: [String, Number],
  dropDown: [Boolean],
});

const isDropdownOpen = ref(false);

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};
</script>

<style scoped>
.dropdown-content {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  z-index: 10;
}
</style>
