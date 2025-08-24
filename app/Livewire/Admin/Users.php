<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class Users extends Component
{
    use WithPagination;
    public $perPage = 5;
    public $showModal = false;
    public $showConfirm = false;
    public $deleteUserId = null;
    public $editUserId = null;
    public $form = [
        'name' => '',
        'email' => '',
        'password' => '12345678',
    ];
    public function confirmDelete($id)
    {
        $this->deleteUserId = $id;
        $this->showConfirm = true;
    }

    public function deleteConfirmed()
    {
        if (!Auth::user()->hasRole('super-admin')) {
            abort(403);
        }
        $user = User::findOrFail($this->deleteUserId);
        $user->roles()->detach();
        $user->delete();
        $this->showConfirm = false;
        $this->deleteUserId = null;
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editUserId = null;
    }

    public function edit($id)
    {
        if (!Auth::user()->hasRole('super-admin')) {
            abort(403);
        }
        $user = User::findOrFail($id);
        $this->form = [
            'name' => $user->name,
            'email' => $user->email,
        ];
        $this->editUserId = $id;
        $this->showModal = true;
    }

    public function save()
    {
        if (!Auth::user()->hasRole('super-admin')) {
            abort(403);
        }
        $data = $this->form;
        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        if ($this->editUserId) {
            User::findOrFail($this->editUserId)->update($data);
        } else {
            $user = User::create($data);
            $user->assignRole('user');
        }
        $this->showModal = false;
    }



    public function resetForm()
    {
        $this->form = [
            'name' => '',
            'email' => '',
            'password' => '12345678',
        ];
    }

    public function render()
    {
        $users = User::where('name', '!=', 'Admin')->paginate($this->perPage);
    return view('livewire.admin.users', compact('users'));
    }
}
