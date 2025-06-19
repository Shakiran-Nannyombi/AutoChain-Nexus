<x-ui.dialog id="add-vendor-modal">
    <x-ui.dialog-content class="sm:max-w-[425px]">
        <x-ui.dialog-header>
            <x-ui.dialog-title>Add New Vendor</x-ui.dialog-title>
            <x-ui.dialog-description>
                Fill in the details for the new vendor.
            </x-ui.dialog-description>
        </x-ui.dialog-header>
        <form method="POST" action="{{ route('vendors.store') }}">
            @csrf
            <div class="grid gap-4 py-4">
                <div class="grid grid-cols-4 items-center gap-4">
                    <x-ui.label for="name" class="text-right">Vendor Name</x-ui.label>
                    <x-ui.input id="name" name="name" type="text" class="col-span-3" required />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <x-ui.label for="category" class="text-right">Category</x-ui.label>
                    <x-ui.input id="category" name="category" type="text" class="col-span-3" required />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <x-ui.label for="contact_name" class="text-right">Contact Person</x-ui.label>
                    <x-ui.input id="contact_name" name="contact_name" type="text" class="col-span-3" required />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <x-ui.label for="contact_email" class="text-right">Contact Email</x-ui.label>
                    <x-ui.input id="contact_email" name="contact_email" type="email" class="col-span-3" required />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <x-ui.label for="contact_phone" class="text-right">Contact Phone</x-ui.label>
                    <x-ui.input id="contact_phone" name="contact_phone" type="text" class="col-span-3" />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <x-ui.label for="address" class="text-right">Address</x-ui.label>
                    <x-ui.input id="address" name="address" type="text" class="col-span-3" />
                </div>
                <div class="grid grid-cols-4 items-center gap-4">
                    <x-ui.label for="status" class="text-right">Status</x-ui.label>
                    <select id="status" name="status" class="col-span-3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <x-ui.dialog-footer>
                <x-ui.dialog-close as-child>
                    <x-ui.button type="button" variant="outline">Cancel</x-ui.button>
                </x-ui.dialog-close>
                <x-ui.button type="submit">Add Vendor</x-ui.button>
            </x-ui.dialog-footer>
        </form>
    </x-ui.dialog-content>
</x-ui.dialog> 