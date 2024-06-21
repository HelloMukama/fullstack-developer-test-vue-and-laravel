import { defineStore } from 'pinia'
import axios from 'axios'

export const useTaskStore = defineStore('taskStore', {
  state: () => ({
    tasks: [],
    projects: [],
    members: [],
    priorities: [],
    categories: [],
  }),
  
  actions: {
    async fetchTasks() {
      this.tasks = (await axios.get('/api/tasks')).data
    },
    async fetchProjects() {
      this.projects = (await axios.get('/api/projects')).data
    },
    async fetchMembers() {
      this.members = (await axios.get('/api/members')).data
    },
    async fetchPriorities() {
      this.priorities = (await axios.get('/api/priorities')).data
    },
    async fetchCategories() {
      this.categories = (await axios.get('/api/categories')).data
    }
  }
})