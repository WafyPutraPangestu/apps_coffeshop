<?php

namespace App\Livewire\Admin\Meja;

use App\Models\Table;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Manajemen Meja')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $confirmingDelete = false;
    public ?int $deletingId = null;

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->confirmingDelete = true;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = false;
        $this->deletingId = null;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            Table::findOrFail($this->deletingId)->delete();
            $this->confirmingDelete = false;
            $this->deletingId = null;
            session()->flash('success', 'Meja berhasil dihapus.');
        }
    }

    public function render()
    {
        $tables = Table::query()
            ->when($this->search, fn($q) => $q->where('table_number', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.meja.index', compact('tables'));
    }
}
