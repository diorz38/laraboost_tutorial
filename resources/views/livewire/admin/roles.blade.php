<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Roles</h2>
        <flux:button wire:click="create" variant="primary">Add Role</flux:button>
    </div>

    <div class="overflow-x-auto rounded-lg shadow border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full bg-white dark:bg-zinc-800">
            <thead>
                <tr class="bg-zinc-50 dark:bg-zinc-900">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Guard</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr class="border-b border-zinc-100 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                        <td class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200 font-mono">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200">{{ $role->name }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200">{{ $role->guard_name }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <flux:button wire:click="edit({{ $role->id }})" size="sm" variant="filled">Edit</flux:button>
                            <flux:button wire:click="delete({{ $role->id }})" size="sm" variant="danger">Delete</flux:button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <flux:modal wire:model="showModal" dismissible>
        <flux:modal.close />
        <form wire:submit.prevent="save" class="p-4">
            <h3 class="text-lg font-semibold mb-4">{{ $editRoleId ? 'Edit Role' : 'Create Role' }}</h3>
            <flux:field label="Name">
                <flux:input wire:model.defer="form.name" required />
            </flux:field>
            <flux:field label="Guard Name">
                <flux:input wire:model.defer="form.guard_name" required />
            </flux:field>
            <flux:field label="Permissions">
                <div class="mb-2">
                    <label class="flex items-center gap-2">
                        <flux:checkbox wire:click="toggleSelectAllPermissions" :checked="count($selectedPermissions) === count($permissions)" />
                        <span class="font-semibold text-sm">Select All</span>
                    </label>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    @foreach ($permissions as $permission)
                        <label class="flex items-center gap-2">
                            <flux:checkbox wire:model.defer="selectedPermissions" value="{{ $permission->id }}" />
                            <span class="text-sm">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </flux:field>
            <div class="flex justify-end mt-4 gap-2">
                <flux:button type="button" wire:click="$set('showModal', false)" variant="filled">Cancel</flux:button>
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
