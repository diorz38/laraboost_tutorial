<?php

namespace App\Livewire\Skp;

use App\Models\Skp;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;


class Crud extends Component {
    // All wire:model properties are already declared below, no need to redeclare here
    /**
     * Livewire bound properties for SKP page and modals
     */
    use WithPagination;

    public string $bulan;
    public string $tahun;
    public array $editForm = [
        'jenis' => '',
        'kode' => '',
        'nama' => '',
        'bulan' => '',
        'tahun' => '',
        'link' => '',
        'konten' => '',
    ];
    public array $addForm = [
        'jenis' => '',
        'kode' => '',
        'nama' => '',
        'bulan' => '',
        'tahun' => '',
        'link' => '',
        'konten' => '',
    ];
    public bool $showAddModal = false;
    public bool $showModal = false;
    public bool $showConfirm = false;
    public $editSkpId = null;
    public $tahunOptions = [];
    public array $bulanOptions = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];
    public $filterBulan;
    public $filterTahun;

    public function confirmDelete(int $id): void
    {
        $this->editSkpId = $id;
        $this->showConfirm = true;
    }

    public function showAddModal(): void
    {
        $this->reset('addForm');
        $this->showAddModal = true;
    }

    public function addSkp(): void
    {
        $validated = validator($this->addForm, [
            'jenis' => ['required', 'string', 'max:255'],
            'kode' => ['required', 'string', 'max:255'],
            'nama' => ['required', 'string', 'max:255'],
            'bulan' => ['required', 'string', 'max:2'],
            'tahun' => ['required', 'string', 'max:4'],
            'link' => ['nullable', 'string', 'max:255'],
            'konten' => ['nullable', 'string', 'max:1000'],
        ])->validate();

        $validated['user_id'] = auth()->id();
        \App\Models\Skp::create($validated);
        $this->showAddModal = false;
        $this->dispatch('skpAdded');
        $this->reset('addForm');
    }

    public function mount(): void
    {
        $this->filterBulan = str_pad(now()->month, 2, '0', STR_PAD_LEFT);
        $this->filterTahun = now()->year;
        $this->bulan = $this->filterBulan;
        $this->tahun = (string) $this->filterTahun;

        // Ensure Livewire recognizes these as arrays for wire:model binding
        $this->addForm = [
            'jenis' => '',
            'kode' => '',
            'nama' => '',
            'bulan' => '',
            'tahun' => '',
            'link' => '',
            'konten' => '',
        ];
        $this->editForm = [
            'jenis' => '',
            'kode' => '',
            'nama' => '',
            'bulan' => '',
            'tahun' => '',
            'link' => '',
            'konten' => '',
        ];
    }

    public function edit(int $id): void
    {
        $skp = Skp::findOrFail($id);
        $this->editForm = [
            'jenis' => $skp->jenis,
            'kode' => $skp->kode,
            'nama' => $skp->nama,
            'bulan' => $skp->bulan,
            'tahun' => $skp->tahun,
            'link' => $skp->link,
            'konten' => $skp->konten,
        ];
        $this->editSkpId = $id;
        $this->showModal = true;
    }

    public function render()
    {
        $users = User::whereBetween('id', [2, 100])->get();

        $this->tahunOptions = Skp::distinct()->pluck('tahun')->sort()->values()->toArray();

        $usersWithoutSkp = User::whereBetween('id', [2, 100])
            ->whereDoesntHave('skps', function ($query) {
                $query->where('bulan', $this->filterBulan)
                    ->where('tahun', $this->filterTahun)
                    ->where('jenis', 'SKP Bulanan');
            })
            ->get();

        $skps = Skp::with('user')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('livewire.skp.crud', [
            'skps' => $skps,
            'users' => $users,
            'usersWithoutSkp' => $usersWithoutSkp,
            'bulan' => $this->filterBulan,
            'tahun' => $this->filterTahun,
            'tahunOptions' => $this->tahunOptions,
        ]);
    }
}
