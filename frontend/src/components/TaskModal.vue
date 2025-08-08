<template>
  <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900">
            {{ task ? 'Edit Task' : 'Create New Task' }}
          </h3>
          <button
            @click="$emit('close')"
            class="text-gray-400 hover:text-gray-600"
          >
            <XMarkIcon class="w-6 h-6" />
          </button>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
              Title *
            </label>
            <input
              id="title"
              v-model="form.name"
              type="text"
              required
              class="input"
              placeholder="Enter task title"
            />
          </div>

          <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
              Description
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="3"
              class="input"
              placeholder="Enter task description"
            ></textarea>
          </div>

          <div class="space-y-1">
            <label for="category" class="block text-sm font-medium text-gray-700 ">
              Category *
            </label>
            <select
              id="category"
              v-model="form.category"
              required
              class="input"
            >
              <option value="">Select a category</option>
              <option v-for="category in tasksStore.categories" :value="category.name">{{category.name}}</option>
              <option value="other">Other</option>
            </select>
            <template v-if="form.category === 'other'">
              <input
                  id="other-category"
                  v-model="otherCategory"
                  type="text"
                  required
                  class="input"
                  placeholder="Other category"
              />
            </template>
          </div>



          <div>
            <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">
              Deadline
            </label>
            <input
              id="deadline"
              v-model="form.due_date"
              type="date"
              class="input"
            />
          </div>

          <div v-if="task" class="flex items-center">
            <input
              id="completed"
              v-model="form.completed"
              type="checkbox"
              class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
            />
            <label for="completed" class="ml-2 block text-sm text-gray-900">
              Mark as completed
            </label>
          </div>

          <div v-if="error" class="text-red-600 text-sm">
            {{ error }}
          </div>

          <div class="flex justify-end space-x-3 pt-4">
            <button
              type="button"
              @click="$emit('close')"
              class="btn btn-secondary"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="btn btn-primary"
            >
              {{ loading ? 'Saving...' : (task ? 'Update Task' : 'Create Task') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import type {TaskData} from "@/utils/types.js";
import {useTasksStore} from "@/stores/tasks.ts";
const tasksStore = useTasksStore()

const props = defineProps({
  task: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'save'])

const form = reactive({
  name: '',
  description: '',
  category: '',
  due_date: '',
  completed: false
})

const loading = ref(false)
const error = ref('')
const otherCategory = ref('')

onMounted(() => {
  if (props.task) {
    form.name = props.task.name || ''
    form.description = props.task.description || ''
    form.category = props.task.category.name || ''
    form.due_date = props.task.due_date ? new Date(props.task.due_date).toISOString().split('T')[0] : ''
    form.completed = props.task.completed || false
  }
})

const handleSubmit = async () => {
  loading.value = true
  error.value = ''

  try {
    const taskData: TaskData = {
      name: form.name.trim(),
      description: form.description.trim(),
      category_name: otherCategory.value || form.category,
      due_date: form.due_date || null,
      completed: form.completed
    }

    emit('save', taskData)
  } catch (err) {
    error.value = 'Failed to save task'
  } finally {
    loading.value = false
  }
}
</script>
