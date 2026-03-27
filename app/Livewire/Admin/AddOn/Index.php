<?php

namespace App\Livewire\Admin\AddOn;

use App\Models\AddOn;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public ?int $deleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function toggleAvailability(int $id): void
    {
        $addOn = AddOn::findOrFail($id);
        $addOn->update(['is_available' => ! $addOn->is_available]);

        session()->flash('success', "Add-on \"{$addOn->name}\" status updated.");
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function delete(): void
    {
        if (! $this->deleteId) return;

        $addOn = AddOn::findOrFail($this->deleteId);
        $name  = $addOn->name;
        $addOn->delete();

        $this->deleteId = null;
        session()->flash('success', "Add-on \"{$name}\" has been deleted.");
    }

    public function render()
    {
        $addOns = AddOn::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->filterStatus !== '', fn($q) => $q->where('is_available', (bool) $this->filterStatus))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.add-on.index', compact('addOns'))
            ->with('title', 'Add-Ons');
    }
}
