import { defineStore } from 'pinia';
import axiosInstance from '../plugins/axios';
import { reactive, toRefs } from 'vue';

export const useTaskStore = defineStore('taskStore', () => {
    const state = reactive({
        tasks: [],
    });

    const fetchTasks = async () => {
        try {
            const response = await axiosInstance.get('/api/v1/tasks/all');
            state.tasks = response.data.data;
        } catch (error) {
            console.error('Error fetching tasks:', error);
            throw error;
        }
    };

    const createTask = async (taskData) => {
        try {
          const response = await axiosInstance.post('/api/v1/tasks/create', taskData);
          const newTask = response.data.data;
          state.tasks.push(newTask); // Add new task to local state
        } catch (error) {
          console.error('Error creating task:', error);
          throw error;
        }
      };
      
    const updateTask = async (task) => {
        try {
            const taskId = task.id;
            const response = await axiosInstance.put(`/api/v1/tasks/${taskId}/update`, task);
            const updatedTask = response.data.data;
            const updatedTaskIndex = state.tasks.findIndex(t => t.id === taskId);
            if (updatedTaskIndex !== -1) {
                state.tasks.splice(updatedTaskIndex, 1, updatedTask);
            }
        } catch (error) {
            console.error('Error updating task:', error);
            throw error;
        }
    };

    const deleteTask = async (taskId) => {
        try {
            await axiosInstance.delete(`/api/v1/tasks/${taskId}/delete`);
            state.tasks = state.tasks.filter(task => task.id !== taskId);
        } catch (error) {
            console.error('Error deleting task:', error);
            throw error;
        }
    };

    const restoreTask = async (taskId) => {
        try {
            await axiosInstance.patch(`/api/v1/tasks/${taskId}/restore`);
            const updatedTasks = state.tasks.map(task => {
                if (task.id === taskId) {
                    task.deleted_at = null; // Assuming `deleted_at` is set to null on restore
                }
                return task;
            });
            state.tasks = updatedTasks;
        } catch (error) {
            console.error('Error restoring task:', error);
            throw error;
        }
    };

    return {
        ...toRefs(state),
        fetchTasks,
        updateTask,
        deleteTask,
        restoreTask,
        createTask
    };
});
