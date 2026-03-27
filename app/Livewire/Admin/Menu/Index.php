<?php

namespace App\Livewire\Admin\Menu;

use App\Models\Category;
use App\Models\Menu;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Menu')]
class Index extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $filterCategory = '';
    public string $filterStatus   = '';

    protected $listeners = ['deleteMenu'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingFilterCategory(): void
    {
        $this->resetPage();
    }
    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function toggleAvailable(int $id): void
    {
        $menu = Menu::findOrFail($id);
        $menu->update(['is_available' => ! $menu->is_available]);

        $status = $menu->is_available ? 'tersedia' : 'habis';
        $this->dispatch('toast', type: 'success', message: "Menu \"{$menu->name}\" diset {$status}.");
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch(
            'confirm',
            message: 'Yakin ingin menghapus menu ini? Tindakan ini tidak bisa dibatalkan.',
            action: 'deleteMenu',
            payload: $id,
        );
    }

    public function deleteMenu(mixed $payload): void
    {
        $menu = Menu::findOrFail((int) $payload);
        $name = $menu->name;
        $menu->delete();

        $this->dispatch('toast', type: 'success', message: "Menu \"{$name}\" berhasil dihapus.");
    }

    public function render()
    {
        $menus = Menu::query()
            ->with('category')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->filterCategory, fn($q) => $q->where('category_id', $this->filterCategory))
            ->when($this->filterStatus !== '', fn($q) => $q->where('is_available', $this->filterStatus === '1'))
            ->orderBy('name')
            ->paginate(10);

        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.menu.index', compact('menus', 'categories'));
    }
}
