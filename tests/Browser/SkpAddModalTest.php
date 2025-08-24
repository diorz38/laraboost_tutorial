<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SkpAddModalTest extends DuskTestCase
{
    public function test_shows_add_skp_modal_when_tambah_skp_button_is_clicked()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        // Create 'default' role if it does not exist and assign to user
        if (class_exists('Spatie\\Permission\\Models\\Role')) {
            $roleClass = \Spatie\Permission\Models\Role::class;
            if (!$roleClass::where('name', 'default')->where('guard_name', 'web')->exists()) {
                $roleClass::create(['name' => 'default', 'guard_name' => 'web']);
            }
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('default');
            }
        } elseif (property_exists($user, 'role')) {
            $user->role = 'default';
            $user->save();
        }

        // Create a SKP record for the user to ensure the SKP Table renders
        \App\Models\Skp::factory()->create([
            'user_id' => $user->id,
            'jenis' => 'Test Jenis',
            'kode' => 'TST001',
            'nama' => 'Test SKP',
            'bulan' => str_pad(now()->month, 2, '0', STR_PAD_LEFT),
            'tahun' => now()->year,
            'link' => 'https://example.com',
            'konten' => 'Test Konten',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('http://127.0.0.1:8000/skp')
                ->screenshot('skp-page')
                ->assertSee('SKP Table')
                ->click('@tambah-skp')
                ->waitFor('.flux-modal, [wire\\:model=showAddModal]', 1000)
                ->screenshot('skp-modal')
                ->assertSee('Tambah SKP');

            // Fallback: check if modal is visible, else fail with a message
            $browser->assertVisible('.flux-modal');
        });
    }
}
