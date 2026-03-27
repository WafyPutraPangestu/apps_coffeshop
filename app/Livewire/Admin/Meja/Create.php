<?php

namespace App\Livewire\Admin\Meja;

use App\Models\Table;
use Livewire\Component;
use Illuminate\Support\Str;

class Create extends Component
{
    public string $table_number = '';

    protected $rules = [
        'table_number' => 'required|string|max:50|unique:tables,table_number',
    ];

    protected $messages = [
        'table_number.required' => 'Nomor meja wajib diisi.',
        'table_number.unique'   => 'Nomor meja ini sudah terdaftar.',
        'table_number.max'      => 'Nomor meja maksimal 50 karakter.',
    ];

    public function save(): void
    {
        $this->validate();

        // Generate QR code link — URL pelanggan akan scan
        $slug = Str::slug($this->table_number);
        $qrLink = url("/order/{$slug}");

        Table::create([
            'table_number' => $this->table_number,
            'qr_code_link' => $qrLink,
        ]);

        session()->flash('success', "Meja {$this->table_number} berhasil ditambahkan.");
        $this->redirect(route('meja.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.meja.create')->with([
            'title' => 'Tambah Meja',
        ]);
    }
}
