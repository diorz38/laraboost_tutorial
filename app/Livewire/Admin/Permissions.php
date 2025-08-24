<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class Permissions extends Component
{
    use WithPagination;
    public $perPage = 10;
    public $showModal = false;
    public $showConfirm = false;
    public $editPermissionId = null;
    public $deletePermissionId = null;
    public $form = [
        'name' => '',
        'guard_name' => 'web',
    ];

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->hasRole('super-admin')) {
            abort(403);
        }
    }

    public function create()
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403);
        }
        $this->resetForm();
        $this->showModal = true;
        $this->editPermissionId = null;
    }

    public function edit($id)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403);
        }
        $permission = Permission::findOrFail($id);
        $this->form = [
            'name' => $permission->name,
            'guard_name' => $permission->guard_name,
        ];
        $this->editPermissionId = $id;
        $this->showModal = true;
    }

    public function save()
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403);
        }
        $data = $this->form;
        if ($this->editPermissionId) {
            Permission::findOrFail($this->editPermissionId)->update($data);
        } else {
            Permission::create($data);
        }
        $this->showModal = false;
    }

    public function confirmDelete($id)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403);
        }
        $this->deletePermissionId = $id;
        $this->showConfirm = true;
    }

    public function deleteConfirmed()
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403);
        }
        Permission::findOrFail($this->deletePermissionId)->delete();
        $this->showConfirm = false;
        $this->deletePermissionId = null;
    }

    public function resetForm()
    {
        $this->form = [
            'name' => '',
            'guard_name' => 'web',
        ];
    }

    public function render()
    {
        $permissions = Permission::paginate($this->perPage);
        return view('livewire.admin.permissions', compact('permissions'));
    }
}
