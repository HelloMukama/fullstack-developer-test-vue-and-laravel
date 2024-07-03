import { defineStore } from 'pinia';
import axiosInstance from '../plugins/axios';
import { reactive, toRefs } from 'vue'; // Import toRefs from Vue

export const useProjectStore = defineStore('projectStore', () => {
    const state = reactive({
      projects: [],
    });

    const fetchProjects = async () => {
      try {
        const response = await axiosInstance.get('/api/v1/projects/all');
        state.projects = response.data.data; // Update to access data array from response
      } catch (error) {
        console.error('Error fetching projects:', error);
        throw error;
      }
    };

    return {
      ...toRefs(state),
      fetchProjects,
    };
});
