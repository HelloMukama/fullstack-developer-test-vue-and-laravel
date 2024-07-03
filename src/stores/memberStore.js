import { defineStore } from 'pinia';
import axiosInstance from '../plugins/axios';
import { reactive, toRefs } from 'vue'; // Import toRefs from Vue

export const useMemberStore = defineStore('memberStore', () => {
    const state = reactive({
      members: [],
    });

    const fetchMembers = async () => {
      try {
        const response = await axiosInstance.get('/api/v1/profiles');
        state.members = response.data.data; // Update to access data array from response
      } catch (error) {
        console.error('Error fetching members:', error);
        throw error;
      }
    };

    return {
      ...toRefs(state),
      fetchMembers,
    };
});
