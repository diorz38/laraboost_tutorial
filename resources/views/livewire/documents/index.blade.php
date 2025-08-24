<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @if (session()->has('message'))
            <div class="mb-4 px-4 py-2 bg-green-100 text-green-900 rounded">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Documents</h2>
                    <flux:button wire:click="$set('showCreateModal', true)" variant="primary">
                        Upload Document
                    </flux:button>
                </div>

                <!-- Documents Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($documents as $document)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ Storage::url($document->file) }}" 
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-800">
                                            {{ basename($document->file) }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $document->note }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $document->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <flux:button wire:click="edit({{ $document->id }})" variant="filled" size="sm">
                                            Edit
                                        </flux:button>
                                        <flux:button wire:click="delete({{ $document->id }})" variant="danger" size="sm">
                                            Delete
                                        </flux:button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div x-show="$wire.showCreateModal" 
         x-transition
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black opacity-30"></div>

            <!-- Modal -->
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">Upload New Document</h3>
                    <button wire:click="$set('showCreateModal', false)" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <form wire:submit="save">
                        <div class="space-y-4">
                            <div>
                                <flux:input type="file" wire:model="file" />
                                @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <flux:textarea wire:model="note" placeholder="Add a note..." />
                                @error('note') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="flex justify-end space-x-2 p-4 border-t">
                    <flux:button wire:click="$set('showCreateModal', false)" variant="filled">Cancel</flux:button>
                    <flux:button wire:click="save" variant="primary">Upload</flux:button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="$wire.showEditModal"
         x-transition
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black opacity-30"></div>

            <!-- Modal -->
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">Edit Document</h3>
                    <button wire:click="$set('showEditModal', false)" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <flux:input type="file" wire:model="newFile" />
                            <div class="text-sm text-gray-500 mt-1">Leave empty to keep the current file</div>
                            @error('newFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <flux:textarea wire:model="note" class="w-full" />
                            @error('note') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end space-x-2 p-4 border-t">
                    <flux:button wire:click="$set('showEditModal', false)" variant="filled">Cancel</flux:button>
                    <flux:button wire:click="update" variant="primary">Save Changes</flux:button>
                </div>
            </div>
        </div>
    </div>
</div>