<?php

namespace App\Livewire\Admin\Meja;

use App\Models\Table;
use Livewire\Component;
use Illuminate\Support\Str;

class Edit extends Component
{
    public Table $table;
    public string $table_number = '';

    // Mount dipanggil dengan param {id} dari route
    public function mount(int $id): void
    {
        $this->table = Table::findOrFail($id);
        $this->table_number = $this->table->table_number;
    }

    protected function rules(): array
    {
        return [
            'table_number' => "required|string|max:50|unique:tables,table_number,{$this->table->id}",
        ];
    }

    protected $messages = [
        'table_number.required' => 'Nomor meja wajib diisi.',
        'table_number.unique'   => 'Nomor meja ini sudah terdaftar.',
        'table_number.max'      => 'Nomor meja maksimal 50 karakter.',
    ];

    public function regenerateQr(): void
    {
        $slug = Str::slug($this->table->table_number);
        $this->table->update([
            'qr_code_link' => url("/order/{$slug}?regen=" . now()->timestamp),
        ]);

        session()->flash('success', 'QR Code berhasil di-regenerate.');
    }

    public function save(): void
    {
        $this->validate();

        $slug = Str::slug($this->table_number);
        $qrLink = url("/order/{$slug}");

        $this->table->update([
            'table_number' => $this->table_number,
            'qr_code_link' => $qrLink,
        ]);

        session()->flash('success', "Meja {$this->table_number} berhasil diperbarui.");
        $this->redirect(route('meja.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.meja.edit', ['tableModel' => $this->table])
            ->with(['title' => 'Edit Meja']);
    }
}
