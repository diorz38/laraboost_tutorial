<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Skp;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can show SKP table for logged-in user', function () {
    $user = User::factory()->create();
    Skp::factory()->count(3)->create(['user_id' => $user->id]);

    $this->actingAs($user);
    Livewire::test(\App\Livewire\Skp\Crud::class)
        ->assertSee('SKP Table')
        ->assertSee($user->name);
});

it('can open edit modal for SKP', function () {
    $user = User::factory()->create();
    $skp = Skp::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);
    Livewire::test(\App\Livewire\Skp\Crud::class)
        ->call('edit', $skp->id)
        ->assertSet('editSkpId', $skp->id)
        ->assertSet('showModal', true)
        ->assertSee('Edit SKP');
});

it('can open delete confirmation modal for SKP', function () {
    $user = User::factory()->create();
    $skp = Skp::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);
    Livewire::test(\App\Livewire\Skp\Crud::class)
        ->call('confirmDelete', $skp->id)
        ->assertSet('editSkpId', $skp->id)
        ->assertSet('showConfirm', true);
});

it('shows users without SKP for selected bulan/tahun', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    Skp::factory()->create([
        'user_id' => $user->id,
        'bulan' => '01',
        'tahun' => '2025',
        'jenis' => 'SKP Bulanan',
    ]);

    Livewire::test(\App\Livewire\Skp\Crud::class)
        ->set('filterBulan', '01')
        ->set('filterTahun', '2025')
        ->assertSee($other->name)
        ->assertDontSee($user->name);
});
it('can add new SKP', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    Livewire::test(\App\Livewire\Skp\Crud::class)
        ->set('addForm.jenis', 'SKP Bulanan')
        ->set('addForm.kode', 'SKP001')
        ->set('addForm.nama', 'Test SKP')
        ->set('addForm.bulan', '08')
        ->set('addForm.tahun', '2025')
        ->call('addSkp')
        ->assertHasNoErrors();

    expect(Skp::where('nama', 'Test SKP')->where('user_id', $user->id)->exists())->toBeTrue();
});
it('shows add SKP modal when button is clicked', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    Livewire::test(\App\Livewire\Skp\Crud::class)
        ->call('showAddModal')
        ->assertSet('showAddModal', true)
        ->assertSee('Tambah SKP');
});
