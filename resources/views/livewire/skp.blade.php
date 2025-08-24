
<!-- Modal for Add/Edit SKP -->
<div x-data="{ show: @entangle('showModal') }" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="text-lg font-semibold mb-4">{{ $editingSkp ? 'Edit SKP' : 'Add SKP' }}</div>
        <form wire:submit.prevent="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" wire:model.defer="form.nama" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-500" />
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300" wire:click="$set('showModal', false)">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">{{ $editingSkp ? 'Update' : 'Create' }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Delete Confirmation -->
<div x-data="{ show: @entangle('showConfirm') }" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" style="display: none;">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="text-lg font-semibold mb-4">Delete SKP</div>
        <p>Are you sure you want to delete this SKP?</p>
        <div class="flex justify-end gap-2 mt-4">
            <button type="button" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300" wire:click="$set('showConfirm', false)">Cancel</button>
            <button type="button" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700" wire:click="deleteConfirmed">Delete</button>
        </div>
    </div>
</div>

<div class="mb-4 flex justify-end">
    <button type="button" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700" wire:click="openCreateModal">Add SKP</button>
</div>

<livewire:powergrid id="skp-table" :component="\App\Livewire\SkpPowerGrid::class" />
