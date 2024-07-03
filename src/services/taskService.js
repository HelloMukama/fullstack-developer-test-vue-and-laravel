import axiosInstance from '../plugins/axios';

export const getAllTasks = () => {
  return axiosInstance.get('/tasks/all');
};

export const createTask = (taskData) => {
  return axiosInstance.post('/tasks/create', taskData);
};

export const getTask = (taskId) => {
  return axiosInstance.get(`/tasks/show/${taskId}`);
};

export const updateTask = (taskId, taskData) => {
  return axiosInstance.put(`/tasks/${taskId}/update`, taskData);
};

export const deleteTask = (taskId) => {
  return axiosInstance.delete(`/tasks/${taskId}/delete`);
};

// Add other task-related API calls as needed
