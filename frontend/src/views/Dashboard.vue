<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">My Tasks</h1>
        <p class="mt-1 text-sm text-gray-500">
          Manage your tasks and stay organized
        </p>
      </div>
      <button
        @click="showCreateModal = true"
        class="mt-4 sm:mt-0 btn btn-primary flex items-center gap-x-2"
      >
        <PlusIcon class="size-5" />
        Add Task
      </button>
    </div>

    <!-- Filters -->
    <div class="card p-4">
      <div class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
          <select v-model="filters.category" class="input">
            <option value="">All Categories</option>
            <option v-for="category in tasksStore.categories" :value="category.name">{{category.name}}</option>
          </select>
        </div>
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
          <select v-model="filters.status" class="input">
            <option value="">All Tasks</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="tasksStore.loading" class="flex justify-center py-8">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="tasksStore.error" class="card p-4">
      <div class="text-red-600 text-center">
        {{ tasksStore.error }}
      </div>
    </div>

    <!-- Tasks List -->
    <div v-else-if="filteredTasks.length > 0" class="space-y-4">
      <div
        v-for="task in filteredTasks"
        :key="task.id"
        class="card p-4 hover:shadow-md transition-shadow"
      >
        <div class="flex items-start justify-between">
          <div class="flex-1 min-w-0">
            <div class="flex items-center space-x-3 cursor-pointer" @click="toggleTask(task.id)">
              <div
                  :class="[
                    'w-5 h-5 rounded-full border-2 flex items-center justify-center',
                    task.completed
                      ? 'bg-green-500 border-green-500'
                      : 'border-gray-300 hover:border-gray-400'
                  ]"
              >
                <CheckIcon
                    v-if="task.completed"
                    class="w-3 h-3 text-white"
                />
              </div>

              <div class="flex-1 min-w-0">
                <h3
                  :class="[
                    'text-lg font-medium truncate',
                    task.completed ? 'text-gray-500 line-through' : 'text-gray-900'
                  ]"
                >
                  {{ task.name }}
                </h3>
                <p
                  :class="[
                    'text-sm mt-1',
                    task.completed ? 'text-gray-400' : 'text-gray-600'
                  ]"
                >
                  {{ task.description }}
                </p>
                <div class="flex items-center space-x-4 mt-2">
                  <span
                    :class="[
                      'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                      getCategoryColor(task.category.name)
                    ]"
                  >
                    {{ task.category.name }}

                  </span>
                  <span v-if="task.due_date" class="text-sm text-gray-500">
                    Due: {{ formatDate(task.due_date) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="flex items-center space-x-2 ml-4">
            <button
              @click="editTask(task)"
              class="p-1 text-gray-400 hover:text-gray-600"
            >
              <PencilIcon class="w-4 h-4" />
            </button>
            <button
              @click="deleteTask(task.id)"
              class="p-1 text-gray-400 hover:text-red-600"
            >
              <TrashIcon class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="card p-8 text-center">
      <div class="text-gray-400 mb-4">
        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
      </div>
      <h3 class="text-lg font-medium text-gray-900 mb-2">No tasks found</h3>
      <p class="text-gray-500 mb-4">
        {{ filters.category || filters.status ? 'Try adjusting your filters.' : 'Get started by creating your first task.' }}
      </p>
      <button
        v-if="!filters.category && !filters.status"
        @click="showCreateModal = true"
        class="btn btn-primary"
      >
        Create your first task
      </button>
    </div>

    <!-- Create/Edit Task Modal -->
    <TaskModal
      v-if="showCreateModal || editingTask"
      :task="editingTask"
      @close="closeModal"
      @save="saveTask"
    />
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useTasksStore } from '@/stores/tasks'
import { PlusIcon, CheckIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'
import TaskModal from '@/components/TaskModal.vue'

const tasksStore = useTasksStore()

const showCreateModal = ref(false)
const editingTask = ref(null)
const filters = reactive({
  category: '',
  status: ''
})

const filteredTasks = computed(() => {
  let tasks = tasksStore.tasks

  if (!!filters.category.length) {
    tasks = tasks.filter(task => task.category === filters.category)
  }

  if (filters.status === 'completed') {
    tasks = tasksStore.completedTasks
  } else if (filters.status === 'pending') {
    tasks = tasksStore.pendingTasks
  }

  console.log('filtered tasks', tasks, tasksStore.tasks)

  return tasks.sort((a, b) => {
    if (a.completed !== b.completed) {
      return a.completed ? 1 : -1
    }
    return new Date(a.due_date || 0) - new Date(b.due_date || 0)
  })
})

onMounted(async () => {
  await tasksStore.fetchCategories()
  await tasksStore.fetchTasks()
})

const toggleTask = async (id) => {
  await tasksStore.toggleTaskCompletion(id)
}

const editTask = (task) => {
  editingTask.value = { ...task }
}

const deleteTask = async (id) => {
  if (confirm('Are you sure you want to delete this task?')) {
    await tasksStore.deleteTask(id)
  }
}

const closeModal = () => {
  showCreateModal.value = false
  editingTask.value = null
}

const saveTask = async (taskData) => {
  if (editingTask.value) {
    await tasksStore.updateTask(editingTask.value.id, taskData)
  } else {
    await tasksStore.createTask(taskData)
  }
  closeModal()
}

const getCategoryColor = (category) => {
  const colors = {
    work: 'bg-blue-100 text-blue-800',
    personal: 'bg-green-100 text-green-800',
    urgent: 'bg-red-100 text-red-800'
  }
  return colors[category] || 'bg-gray-100 text-gray-800'
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}
</script>
