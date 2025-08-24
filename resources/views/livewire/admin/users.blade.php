<div>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Users</h2>
        @if(auth()->user()->hasRole('super-admin'))
            <flux:button wire:click="create" variant="primary">Add User</flux:button>
        @endif
    </div>

    <div class="overflow-x-auto rounded-lg shadow border border-zinc-200 dark:border-zinc-700">
        <table class="min-w-full bg-white dark:bg-zinc-800">
            <thead>
                <tr class="bg-zinc-50 dark:bg-zinc-900">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b border-zinc-100 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                        <td class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200 font-mono">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200">{{ $user->name }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200">{{ $user->email }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            @if(auth()->user()->hasRole('super-admin'))
                                <flux:button wire:click="edit({{ $user->id }})" size="sm" variant="filled">Edit</flux:button>
                                <flux:button wire:click="confirmDelete({{ $user->id }})" size="sm" variant="danger">Delete</flux:button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <flux:modal wire:model="showModal" dismissible>
        <flux:modal.close />
        <form wire:submit.prevent="save" class="p-4">
            <h3 class="text-lg font-semibold mb-4">{{ $editUserId ? 'Edit User' : 'Create User' }}</h3>
            <flux:field label="Name">
                <flux:input wire:model.defer="form.name" required />
            </flux:field>
            <flux:field label="Email">
                <flux:input wire:model.defer="form.email" type="email" required />
            </flux:field>
            <flux:field label="Password">
                <flux:input wire:model.defer="form.password" type="password" {{ $editUserId ? '' : 'required' }} />
            </flux:field>
            <div class="flex justify-end mt-4 gap-2">
                <flux:button type="button" wire:click="$set('showModal', false)" variant="filled">Cancel</flux:button>
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal wire:model="showConfirm" name="confirm" dismissible>
        <flux:modal.close />
        <div class="p-4">
            <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
            <p class="mb-6">Are you sure you want to delete this user? This action cannot be undone.</p>
            <div class="flex justify-end gap-2">
                <flux:button type="button" wire:click="$set('showConfirm', false)" variant="filled">Cancel</flux:button>
                <flux:button type="button" wire:click="deleteConfirmed" variant="danger">Delete</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
