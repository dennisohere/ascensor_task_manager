import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type {Category, Task, TaskData} from "@/utils/types";
import useAxios from "@/composables/axios.ts";

export const useTasksStore = defineStore('tasks', () => {
  const { axios } = useAxios();

  const categories = ref<Category[]>([])
  const tasks = ref<Task[]>([])
  const loadingCategories = ref(false)
  const loading = ref(false)
  const error = ref(null)

  const completedTasks = computed(() => tasks.value.filter(task => task.completed))
  const pendingTasks = computed(() => tasks.value.filter(task => !task.completed))

  const fetchCategories = async () => {

    loadingCategories.value = true
    error.value = null

    try {
      const response = await axios.get('/api/categories')
      categories.value = [...response.data]

    }catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch tasks'
      console.debug('error:fetchCategories', error.value, err)
    } finally {
      loadingCategories.value = false
      console.log('fetchCategories:tasks', categories.value)
    }
  }

  const fetchTasks = async () => {

    loading.value = true
    error.value = null
    try {
      const response = await axios.get('/api/me/tasks')
      tasks.value = [...response.data.data]
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Failed to fetch tasks'
      console.debug('error:fetchTasks', error.value, err)
    } finally {
      loading.value = false
      console.log('fetchTasks:tasks', tasks.value)
    }
  }

  const createTask = async (taskData: TaskData) => {

    loading.value = true
    error.value = null
    try {
      const response = await axios.post('/api/me/tasks', taskData)
      tasks.value.push(response.data.data)
      return { success: true, task: response.data.data }
    } catch (err: any) {
      error.value = err?.response?.data?.message || 'Failed to create task'
      return { success: false, message: error.value }
    } finally {
      loading.value = false
    }
  }

  const updateTask = async (id: string, taskData: TaskData) => {

    loading.value = true
    error.value = null
    try {
      const response = await axios.put(`/api/me/tasks/${id}`, taskData)
      const index = tasks.value.findIndex(task => task.id === id)
      if (index !== -1) {
        tasks.value[index] = response.data.data
      }
      return { success: true, task: response.data.data }
    } catch (err: any) {
      error.value = err?.response?.data?.message || 'Failed to update task'
      return { success: false, message: error.value }
    } finally {
      loading.value = false
    }
  }

  const deleteTask = async (id: string) => {

    loading.value = true
    error.value = null
    try {
      await axios.delete(`/api/me/tasks/${id}`)
      tasks.value = tasks.value.filter(task => task.id !== id)
      return { success: true }
    } catch (err: any) {
      error.value = err?.response?.data?.message || 'Failed to delete task'
      return { success: false, message: error.value }
    } finally {
      loading.value = false
    }
  }

  const toggleTaskCompletion = async (id: string) => {
    const task = tasks.value.find(t => t.id === id)
    if (!task) return { success: false, message: 'Task not found' }
    
    return await updateTask(id, {
      completed: !task.completed,
      name: task.name,
      description: task.description,
      due_date: task.due_date.toString(),
      category_name: task.category.name,
    })
  }

  const getTasksByCategory = (category: string) => {
    return tasks.value.filter(task => task.category.name === category)
  }

  const getTasksByStatus = (completed: boolean) => {
    return tasks.value.filter(task => task.completed === completed)
  }

  return {
    categories,
    tasks,
    loading,
    error,
    completedTasks,
    pendingTasks,
    // tasksByCategory,
    fetchCategories,
    fetchTasks,
    createTask,
    updateTask,
    deleteTask,
    toggleTaskCompletion,
    getTasksByCategory,
    getTasksByStatus
  }
})
