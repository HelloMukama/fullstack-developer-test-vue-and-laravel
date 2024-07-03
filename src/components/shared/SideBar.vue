<template>
  <div class="w-1/4 border-r h-full px-7 pt-7 pb-44 flex flex-col space-y-5 overflow-auto">
    <div class="flex items-center space-x-4 pb-20">
      <UserGroupIcon class="h-6 w-6 text-black" />
      <div class="font-bold text-lg text-black">Flows list</div>
    </div>

    <Button label="Add new task" icon="PlusIcon" color="bg-lime-300" size="xl" />

    <div class="space-y-4">
      <NavList :items="navItems" />
    </div>

    <div class="fixed bottom-0 w-1/4 h-auto left-0 p-7 bg-lime-50 border-t border-r">
      <div class="flex items-center space-x-2 font-bold">
        <WalletIcon class="h-6 stroke-2 text-black" />
        <span class="text-sm text-black">Free plan</span>
      </div>
      <div class="flex items-center justify-between space-x-2 text-xs mt-4">
        <span class="font-bold text-black">Projects</span>
        <span class="text-slate-500">2/10</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-1.5 mb-4 my-2">
        <div class="bg-lime-500 h-1.5 rounded-full" style="width: 85%' }"></div>
      </div>
      <div class="flex items-center justify-between space-x-2 text-xs mt-2">
        <span class="font-bold text-black">Tasks</span>
        <span class="text-slate-500">Unlimited</span>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import { WalletIcon, UsersIcon, UserGroupIcon } from '@heroicons/vue/24/outline';
import Button from './Button.vue';
import NavList from './NavItems.vue';

const navItems = ref([
  { label: 'Plan', icon: 'CalendarIcon', subList: [] },
  {
    label: 'Task List', name: 'task', icon: 'ClipboardDocumentListIcon', subList: [
      { name: 'Meridian', color: 'bg-rose-600' },
      { name: 'Risen', color: 'bg-blue-600' },
      { name: 'SkillBox', color: 'bg-yellow-400' },
      { name: 'Statra Insurance', color: 'bg-green-600' },
    ]
  },
  {
    label: 'Projects', name: 'project', icon: 'FolderIcon', subList: [
      { name: 'Prototype', color: 'bg-purple-600' },
      { name: 'Research', color: 'bg-green-600' },
      { name: 'Testing', color: 'bg-yellow-400' },
    ]
  },
  {
    label: 'Tags', name: 'tag', icon: 'TagIcon', subList: [
      { name: 'Prototype', color: 'bg-purple-600' },
      { name: 'Research', color: 'bg-green-600' },
      { name: 'Testing', color: 'bg-yellow-400' },
    ]
  },
  {
    label: 'Members', name: 'member', icon: 'UserGroupIcon', subList: [
      { name: 'Prototype', color: 'bg-purple-600' },
      { name: 'Research', color: 'bg-green-600' },
      { name: 'Testing', color: 'bg-yellow-400' },
    ]
  },
]);

</script>


<style scoped>
/* Scoped styles for your Sidebar component */
</style>
