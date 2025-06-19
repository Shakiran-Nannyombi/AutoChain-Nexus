<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-90 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-2xl font-semibold text-gray-900 mb-6">Add New User</h3>
            <div class="p-0">
                <form id="addUserForm" method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="mb-4 text-left">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="name" required
                               class="mt-1 block w-full px-4 py-2.5 bg-white text-gray-900 border-2 border-gray-400 rounded-md shadow-sm outline-none appearance-none focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm">
                    </div>
                    <div class="mb-4 text-left">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" required
                               class="mt-1 block w-full px-4 py-2.5 bg-white text-gray-900 border-2 border-gray-400 rounded-md shadow-sm outline-none appearance-none focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm">
                    </div>
                    <div class="mb-4 text-left">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" required
                               class="mt-1 block w-full px-4 py-2.5 bg-white text-gray-900 border-2 border-gray-400 rounded-md shadow-sm outline-none appearance-none focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm">
                    </div>
                    <div class="mb-4 text-left">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="mt-1 block w-full px-4 py-2.5 bg-white text-gray-900 border-2 border-gray-400 rounded-md shadow-sm outline-none appearance-none focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm">
                    </div>
                    <div class="mb-6 text-left">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" id="role" required
                                class="mt-1 block w-full px-4 py-2.5 bg-white text-gray-900 border-2 border-gray-400 rounded-md shadow-sm outline-none appearance-none focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="items-center px-0 py-3">
                        <button id="submitAddUser" type="submit"
                                class="px-4 py-2 bg-primary text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary">
                            Add User
                        </button>
                        <button id="closeAddUserModal" type="button"
                                class="mt-3 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 