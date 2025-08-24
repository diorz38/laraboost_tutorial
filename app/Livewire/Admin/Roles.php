<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class Roles extends Component
{
    public $roles;
    public $showModal = false;
    public $editRoleId = null;
    public $form = [
        'name' => '',
        'guard_name' => 'web',
    ];
    public $permissions = [];
    public $selectedPermissions = [];

    public function toggleSelectAllPermissions()
    {
        if (count($this->selectedPermissions) === count($this->permissions)) {
            $this->selectedPermissions = [];
        } else {
            $this->selectedPermissions = $this->permissions->pluck('id')->toArray();
        }
    }

    public function mount()
    {
        if (!Auth::user() || !Auth::user()->hasRole('super-admin')) {
            abort(403);
        }
        $this->loadRoles();
        $this->permissions = \Spatie\Permission\Models\Permission::all();
    }

    public function loadRoles()
    {
        $this->roles = Role::all();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editRoleId = null;
        $this->selectedPermissions = [];
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->form = $role->only(['name', 'guard_name']);
        $this->editRoleId = $id;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $this->showModal = true;
    }

    public function save()
    {
        $data = $this->form;
        if ($this->editRoleId) {
            $role = Role::findOrFail($this->editRoleId);
            $role->update($data);
            $role->syncPermissions($this->selectedPermissions);
        } else {
            $role = Role::create($data);
            $role->syncPermissions($this->selectedPermissions);
        }
        $this->showModal = false;
        $this->loadRoles();
    }

    public function delete($id)
    {
        Role::findOrFail($id)->delete();
        $this->loadRoles();
    }

    public function resetForm()
    {
        $this->form = [
            'name' => '',
            'guard_name' => 'web',
        ];
        $this->selectedPermissions = [];
    }

    public function render()
    {
        return view('livewire.admin.roles', [
            'permissions' => $this->permissions,
        ]);
    }
}
