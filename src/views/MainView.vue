<template>
  <div class="h-screen w-screen bg-lime-50 flex">
    <SideBar />
    <div class="flex flex-col flex-1 overflow-hidden">
      <div class="w-full h-24 flex items-center justify-between px-4">
        <div class="text-2xl font-bold">Statra Insurance</div>
        <div class="flex space-x-2 items-center">
          <div class="flex -space-x-4">
            <div class="h-10 w-10 rounded-full border-2 border-slate-500 bg-black"></div>
            <div class="h-10 w-10 rounded-full border-2 border-slate-500 bg-black"></div>
            <div class="h-10 w-10 rounded-full border-2 border-slate-500 bg-black"></div>
            <div class="h-10 w-10 rounded-full border-2 border-slate-500 bg-black text-white center font-bold">
              +8
            </div>
          </div>
          <Button class="font-bold" label="Apps" icon="Squares2X2Icon" color="bg-white" size="lg" dropDown />
          <Button label="Add new task" icon="PlusIcon" color="bg-black" text-color="text-white" size="lg" />
          <Button label="Account" icon="UserIcon" color="bg-white" size="lg" dropDown>
            <template #dropDownContent>
              <ul class="bg-white shadow rounded">
                <li @click="goToProfileHandler" class="px-4 py-2 cursor-pointer hover:bg-gray-100">My Profile</li>
                <li @click="logoutHandler" class="px-4 py-2 cursor-pointer hover:bg-gray-100">Logout</li>
              </ul>
            </template>
          </Button>
        </div>
      </div>
      <div class="w-full flex space-x-10 px-4 font-bold flex-shrink-0 border-b">
        <div
          @click="changeTab('backlog')"
          :class="{ 'border-b-4 border-lime-300 duration-300': activeTab === 'backlog' }"
          class="pb-4 flex space-x-2 items-center cursor-pointer"
        >
          <Square3Stack3DIcon class="w-5 h-5 flex" />
          <span>Backlog</span>
        </div>
        <div
          @click="changeTab('priority-chart')" 
          :class="{ 'border-b-4 border-lime-300 duration-300': activeTab === 'priority-chart' }"
          class="pb-4 flex space-x-2 items-center cursor-pointer"
        >
          <ArrowsPointingInIcon class="w-5 h-5" />
          <span>Priority Chart</span>
        </div>
        <div
          @click="changeTab('related-task')" 
          :class="{ 'border-b-4 border-lime-300 duration-300': activeTab === 'related-task' }"
          class="pb-4 flex space-x-2 items-center cursor-pointer"
        >
          <BriefcaseIcon class="w-5 h-5" />
          <span>Related Tasks</span>
        </div>
        <div
          @click="changeTab('kanban-workflow')" 
          :class="{ 'border-b-4 border-lime-300 duration-300': activeTab === 'kanban-workflow' }"
          class="pb-4 flex space-x-2 items-center cursor-pointer"
        >
          <ChartBarIcon class="w-5 h-5" />
          <span>Kanban Workflow</span>
        </div>
      </div>
      <div class="flex-1 overflow-auto px-4">
        <KanbanWorkFlow v-if="activeTab === 'kanban-workflow'" />
        <RelatedTask v-if="activeTab === 'related-task'" />
        <PriorityChart v-if="activeTab === 'priority-chart'" />
        <BackLog v-if="activeTab === 'backlog'" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import {
  ArrowsPointingInIcon,
  ChartBarIcon,
  Square3Stack3DIcon,
  BriefcaseIcon
} from '@heroicons/vue/24/outline'
import SideBar from '../components/shared/SideBar.vue'
import KanbanWorkFlow from '../components/sections/KanbanWorkFlow.vue'
import RelatedTask from '../components/sections/RelatedTask.vue'
import PriorityChart from '../components/sections/PriorityChart.vue'
import BackLog from '../components/sections/BackLog.vue'
import Button from '../components/shared/Button.vue'
import { useAuthStore } from '../stores/authStore';

const activeTab = ref('kanban-workflow')
const router = useRouter()

const changeTab = (tab) => activeTab.value = tab;

const authStore = useAuthStore();
const { logout, goToProfile } = authStore;

const goToProfileHandler = () => {
  goToProfile();
  router.push({ name: 'profile' });
};

const logoutHandler = async () => {
  try {
    await logout();
    router.push({ name: 'login' });
  } catch (error) {
    console.error('Error logging out:', error);
  }
};

</script>

<style scoped>
input {
  appearance: none;
}

progress[value]::-webkit-progress-value {
  background-image:
	   -webkit-linear-gradient(-45deg, 
	                           transparent 33%, rgba(0, 0, 0, .1) 33%, 
	                           rgba(0,0, 0, .1) 66%, transparent 66%),
	   -webkit-linear-gradient(top, 
	                           rgba(255, 255, 255, .25), 
	                           rgba(0, 0, 0, .25)),
	   -webkit-linear-gradient(left, #09c, #f44);

    border-radius: 2px; 
    background-size: 35px 20px, 100% 100%, 100% 100%;
}

.dropdown-content {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.hidden {
  display: none;
}
</style>
