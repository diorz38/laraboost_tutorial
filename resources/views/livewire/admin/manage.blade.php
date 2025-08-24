<div>
    <div class="mb-6 w-full">
        <flux:heading size="xl" level="1">Manage</flux:heading>
        <flux:subheading size="lg" class="mb-6">Administer users, roles, and permissions</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-8">
        <div class="w-48 shrink-0 flex flex-col items-start">
            <nav class="flex flex-col gap-2 mt-2">
                <button wire:click="setTab('users')" class="px-4 py-2 rounded transition-colors duration-150 text-base font-semibold focus:outline-none {{ $tab === 'users' ? 'bg-primary text-white' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 hover:bg-primary hover:text-white' }}">
                    Users
                </button>
                <button wire:click="setTab('roles')" class="px-4 py-2 rounded transition-colors duration-150 text-base font-semibold focus:outline-none {{ $tab === 'roles' ? 'bg-primary text-white' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 hover:bg-primary hover:text-white' }}">
                    Roles
                </button>
                <button wire:click="setTab('permissions')" class="px-4 py-2 rounded transition-colors duration-150 text-base font-semibold focus:outline-none {{ $tab === 'permissions' ? 'bg-primary text-white' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 hover:bg-primary hover:text-white' }}">
                    Permissions
                </button>
            </nav>
        </div>
        <div class="w-full md:w-3/4">
            @if($tab === 'users')
                <livewire:admin.users />
            @elseif($tab === 'roles')
                <livewire:admin.roles />
            @elseif($tab === 'permissions')
                <livewire:admin.permissions />
            @endif
        </div>
    </div>
</div>
