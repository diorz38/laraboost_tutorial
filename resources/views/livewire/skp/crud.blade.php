<div class="max-w-6xl mx-auto p-6">
    <!-- Card: Users without SKP for selected bulan/tahun -->
        <div class="mb-6 flex">
            <div class="rounded-lg shadow border border-blue-200 dark:border-blue-700 bg-blue-50 dark:bg-blue-900 p-4 w-full md:w-1/2">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-bold text-blue-800 dark:text-blue-100">
                        Users without SKP
                        <span class="ml-2 text-sm font-normal text-blue-600 dark:text-blue-300">({{ $usersWithoutSkp->count() }})</span>
                    </h3>
                    <div class="flex gap-4 items-center">
                        <div>
                            <label for="filter-bulan" class="block text-xs font-medium text-blue-700 dark:text-blue-200 mb-1">Bulan</label>
                            <select id="filter-bulan" wire:model.live="filterBulan" class="rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-blue-700 dark:text-blue-200 px-2 py-1 text-xs">
                                @foreach($bulanOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="filter-tahun" class="block text-xs font-medium text-blue-700 dark:text-blue-200 mb-1">Tahun</label>
                            <select id="filter-tahun" wire:model.live="filterTahun" class="rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-blue-700 dark:text-blue-200 px-2 py-1 text-xs">
                                <option value="{{ $filterTahun }}">{{ $filterTahun }}</option>
                                @foreach($tahunOptions as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @if($usersWithoutSkp->isEmpty())
                    <p class="text-blue-700 dark:text-blue-200">All users have SKP for this period.</p>
                @else
                    <div class="text-blue-700 dark:text-blue-200">
                        {{ $usersWithoutSkp->pluck('name')->implode(', ') }}
                    </div>
                @endif
                <h3 class="text-lg font-bold text-blue-800 dark:text-blue-100 mt-2">Bulan: {{ $bulan }}, Tahun: {{ $tahun }}</h3>
            </div>
        </div>
    <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100">SKP Table</h2>
    <flux:button wire:click="showAddModal" variant="primary" class="ml-auto" dusk="tambah-skp">Tambah SKP</flux:button>
    </div>
    <div class="overflow-x-auto rounded-lg shadow border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
            <thead>
                <tr class="bg-zinc-50 dark:bg-zinc-800">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">User</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Jenis</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Kode</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Bulan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Tahun</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($skps as $skp)
                    <tr class="odd:bg-white even:bg-zinc-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800 border-b border-zinc-100 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition">
                        <td class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200 font-mono">{{ ($skps->currentPage() - 1) * $skps->perPage() + $loop->iteration }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200">{{ $skp->user->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200">{{ $skp->jenis }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200">{{ $skp->kode }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200">{{ $skp->nama }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200">{{ $skp->bulan }}</td>
                        <td class="px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200">{{ $skp->tahun }}</td>
                        <td class="px-4 py-2 flex gap-2">
                                <flux:button wire:click="edit({{ $skp->id }})" size="sm" variant="filled">Edit</flux:button>
                                <flux:button wire:click="confirmDelete({{ $skp->id }})" size="sm" variant="danger">Delete</flux:button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex justify-end">
        {{ $skps->links() }}
    </div>

    <!-- Add Modal -->
    @if ($showAddModal)
        <flux:modal wire:model="showAddModal" dismissible class="max-w-3xl w-full">
            <flux:modal.close />
            <form wire:submit.prevent="addSkp" class="p-4">
                <h3 class="text-lg font-semibold mb-4">Tambah SKP</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field label="Jenis">
                        <flux:input wire:model.defer="addForm.jenis" required />
                    </flux:field>
                    <flux:field label="Kode">
                        <flux:input wire:model.defer="addForm.kode" required />
                    </flux:field>
                    <flux:field label="Nama">
                        <flux:input wire:model.defer="addForm.nama" required />
                    </flux:field>
                    <flux:field label="Bulan">
                        <flux:input wire:model.defer="addForm.bulan" />
                    </flux:field>
                    <flux:field label="Tahun">
                        <flux:input wire:model.defer="addForm.tahun" required />
                    </flux:field>
                    <flux:field label="Link">
                        <flux:input wire:model.defer="addForm.link" />
                    </flux:field>
                    <flux:field label="Konten" class="md:col-span-2">
                        <flux:textarea wire:model.defer="addForm.konten" />
                    </flux:field>
                </div>
                <div class="flex justify-end mt-6 gap-2">
                    <flux:button type="button" wire:click="$set('showAddModal', false)" variant="filled">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Simpan</flux:button>
                </div>
            </form>
        </flux:modal>
    @endif

    <!-- Edit Modal -->
    @if ($showModal)
        <flux:modal wire:model="showModal" dismissible class="max-w-3xl w-full">
            <flux:modal.close />
            <form wire:submit.prevent="updateSkp" class="p-4">
                <h3 class="text-lg font-semibold mb-4">Edit SKP</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field label="Jenis">
                        <flux:input wire:model.defer="editForm.jenis" required />
                    </flux:field>
                    <flux:field label="Kode">
                        <flux:input wire:model.defer="editForm.kode" required />
                    </flux:field>
                    <flux:field label="Nama">
                        <flux:input wire:model.defer="editForm.nama" required />
                    </flux:field>
                    <flux:field label="Bulan">
                        <flux:input wire:model.defer="editForm.bulan" />
                    </flux:field>
                    <flux:field label="Tahun">
                        <flux:input wire:model.defer="editForm.tahun" required />
                    </flux:field>
                    <flux:field label="Link">
                        <flux:input wire:model.defer="editForm.link" />
                    </flux:field>
                    <flux:field label="Konten" class="md:col-span-2">
                        <flux:textarea wire:model.defer="editForm.konten" />
                    </flux:field>
                </div>
                <div class="flex justify-end mt-6 gap-2">
                    <flux:button type="button" wire:click="$set('showModal', false)" variant="filled">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Update</flux:button>
                </div>
            </form>
        </flux:modal>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showConfirm)
        <flux:modal wire:model="showConfirm" name="confirm" dismissible>
            <flux:modal.close />
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-4">Confirm Delete</h3>
                <p class="mb-6">Are you sure you want to delete this SKP? This action cannot be undone.</p>
                <div class="flex justify-end gap-2">
                    <flux:button type="button" wire:click="$set('showConfirm', false)" variant="filled">Cancel</flux:button>
                    <flux:button type="button" wire:click="deleteConfirmed" variant="danger">Delete</flux:button>
                </div>
            </div>
        </flux:modal>
    @endif
</div>