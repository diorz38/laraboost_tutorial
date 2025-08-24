<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $file;
    public $note;
    public $newFile;
    public $documentToEdit;
    public $showCreateModal = false;
    public $showEditModal = false;
    
    protected function rules()
    {
        return [
            'file' => 'required|file|max:12288', // Max 12MB when creating
            'newFile' => 'nullable|file|max:12288', // Max 12MB when updating
            'note' => 'nullable|string|max:1000',
        ];
    }

    public function save()
    {
        $this->validate();

        $path = $this->file->store('documents', 'public');

        auth()->user()->documents()->create([
            'file' => $path,
            'note' => $this->note,
        ]);

        $this->reset(['file', 'note', 'showCreateModal']);
        session()->flash('message', 'Document uploaded successfully.');
    }

    public function edit(Document $document)
    {
        $this->documentToEdit = $document;
        $this->note = $document->note;
        $this->showEditModal = true;
    }

    public function update()
    {
        $this->validate([
            'note' => 'nullable|string|max:1000',
            'newFile' => 'nullable|file|max:12288',
        ]);

        $data = ['note' => $this->note];

        if ($this->newFile) {
            // Delete old file
            Storage::disk('public')->delete($this->documentToEdit->file);
            
            // Store new file
            $path = $this->newFile->store('documents', 'public');
            $data['file'] = $path;
        }

        $this->documentToEdit->update($data);

        $this->reset(['documentToEdit', 'note', 'newFile', 'showEditModal']);
        session()->flash('message', 'Document updated successfully.');
    }

    public function delete(Document $document)
    {
        Storage::disk('public')->delete($document->file);
        $document->delete();
        session()->flash('message', 'Document deleted successfully.');
    }

    public function render()
    {
        return view('livewire.documents.index', [
            'documents' => auth()->user()->documents()->latest()->paginate(10),
        ]);
    }
}
