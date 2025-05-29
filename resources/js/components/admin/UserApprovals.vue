<template>
  <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Pending User Registrations</h1>
        <p class="mt-2 text-sm text-gray-700">
          Review and approve or reject new user registrations
        </p>
      </div>
    </div>

    <div class="mt-8 flex flex-col">
      <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
          <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Company</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Role</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Documents</th>
                  <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                    <span class="sr-only">Actions</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="user in pendingUsers" :key="user.id">
                  <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                    {{ user.name }}
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ user.email }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ user.company_name }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ user.role }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    <button
                      @click="viewDocuments(user)"
                      class="text-indigo-600 hover:text-indigo-900"
                    >
                      View Documents ({{ user.documents.length }})
                    </button>
                  </td>
                  <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                    <button
                      @click="approveUser(user)"
                      class="text-green-600 hover:text-green-900 mr-4"
                    >
                      Approve
                    </button>
                    <button
                      @click="showRejectModal(user)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Reject
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Reject Modal -->
    <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  Reject Registration
                </h3>
                <div class="mt-2">
                  <textarea
                    v-model="rejectionReason"
                    rows="4"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    placeholder="Enter reason for rejection..."
                  ></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="rejectUser"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Reject
            </button>
            <button
              type="button"
              @click="closeModal"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from 'axios'

export default {
  name: 'UserApprovals',
  setup() {
    const pendingUsers = ref([])
    const showModal = ref(false)
    const selectedUser = ref(null)
    const rejectionReason = ref('')

    const fetchPendingUsers = async () => {
      try {
        const response = await axios.get('/api/admin/pending-users')
        pendingUsers.value = response.data
      } catch (error) {
        console.error('Error fetching pending users:', error)
        alert('Failed to fetch pending users')
      }
    }

    const approveUser = async (user) => {
      try {
        await axios.post(`/api/admin/users/${user.id}/approve`, {
          action: 'approve'
        })
        await fetchPendingUsers()
        alert('User approved successfully')
      } catch (error) {
        console.error('Error approving user:', error)
        alert('Failed to approve user')
      }
    }

    const showRejectModal = (user) => {
      selectedUser.value = user
      showModal.value = true
    }

    const closeModal = () => {
      showModal.value = false
      selectedUser.value = null
      rejectionReason.value = ''
    }

    const rejectUser = async () => {
      if (!rejectionReason.value.trim()) {
        alert('Please provide a reason for rejection')
        return
      }

      try {
        await axios.post(`/api/admin/users/${selectedUser.value.id}/approve`, {
          action: 'reject',
          rejection_reason: rejectionReason.value
        })
        await fetchPendingUsers()
        closeModal()
        alert('User rejected successfully')
      } catch (error) {
        console.error('Error rejecting user:', error)
        alert('Failed to reject user')
      }
    }

    const viewDocuments = (user) => {
      // Implement document viewing logic
      console.log('View documents for user:', user)
    }

    onMounted(() => {
      fetchPendingUsers()
    })

    return {
      pendingUsers,
      showModal,
      rejectionReason,
      approveUser,
      showRejectModal,
      closeModal,
      rejectUser,
      viewDocuments
    }
  }
}
</script> 