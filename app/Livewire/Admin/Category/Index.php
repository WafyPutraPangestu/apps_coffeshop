<?php

namespace App\Livewire\Admin\Category;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Kategori')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Dipanggil dari blade langsung — aman karena ID integer
    public function confirmDelete(int $id): void
    {
        $this->dispatch(
            'confirm',
            message: 'Yakin ingin menghapus kategori ini? Tindakan ini tidak bisa dibatalkan.',
            action: 'deleteCategory',
            payload: $id,
        );
    }

    // Listener dari Notify component — payload dikirim sebagai named arg
    public function deleteCategory(mixed $payload): void
    {
        $category = Category::findOrFail((int) $payload);

        if ($category->menus()->count() > 0) {
            $this->dispatch('toast', type: 'error', message: 'Kategori tidak bisa dihapus karena masih memiliki menu.');
            return;
        }

        $category->delete();
        $this->dispatch('toast', type: 'success', message: 'Kategori berhasil dihapus.');
    }

    protected $listeners = ['deleteCategory'];

    public function render()
    {
        $categories = Category::query()
            ->withCount('menus')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.category.index', compact('categories'));
    }
}
