<?php

namespace App\Livewire\Admin\Menu;

use App\Models\AddOn;
use App\Models\Category;
use App\Models\Menu;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Manajemen Menu')]
class Index extends Component
{
    use WithPagination;

    // ── Tab aktif ──────────────────────────────────────────────
    public string $activeTab = 'menu'; // 'menu' | 'addon'

    // ── Menu state ─────────────────────────────────────────────
    public string $search         = '';
    public string $filterCategory = '';
    public string $filterStatus   = '';
    public bool   $showArchived   = false;

    // ── Add-On state ───────────────────────────────────────────
    public string $addonSearch       = '';
    public string $addonFilterStatus = '';
    public ?int   $addonDeleteId     = null;

    protected $listeners = ['deleteMenu'];

    // ── Resetter ───────────────────────────────────────────────
    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // ── Menu actions ───────────────────────────────────────────
    public function updatingSearch(): void          { $this->resetPage(); }
    public function updatingFilterCategory(): void  { $this->resetPage(); }
    public function updatingFilterStatus(): void    { $this->resetPage(); }

    public function toggleAvailable(int $id): void
    {
        $menu = Menu::findOrFail($id);
        $menu->update(['is_available' => !$menu->is_available]);
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
        $menu->update(['is_active' => false]);
        $this->dispatch('toast', type: 'success', message: "Menu \"{$name}\" berhasil dihapus dari katalog.");
    }

    public function toggleArchiveView(): void
    {
        $this->showArchived = !$this->showArchived;
        $this->resetPage();
    }

    public function restoreMenu(int $id): void
    {
        $menu = Menu::findOrFail($id);
        $menu->update(['is_active' => true]);
        $this->dispatch('toast', type: 'success', message: "Menu \"{$menu->name}\" berhasil dikembalikan ke katalog.");
    }

    // ── Add-On actions ─────────────────────────────────────────
    public function updatingAddonSearch(): void       { $this->resetPage(); }
    public function updatingAddonFilterStatus(): void { $this->resetPage(); }

    public function toggleAddonAvailability(int $id): void
    {
        $addon = AddOn::findOrFail($id);
        $addon->update(['is_available' => !$addon->is_available]);
        $status = $addon->is_available ? 'tersedia' : 'tidak tersedia';
        $this->dispatch('toast', type: 'success', message: "Add-On \"{$addon->name}\" diset {$status}.");
    }

    public function confirmAddonDelete(int $id): void
    {
        $this->addonDeleteId = $id;
    }

    public function deleteAddon(): void
    {
        if (!$this->addonDeleteId) return;
        $addon = AddOn::findOrFail($this->addonDeleteId);
        $name  = $addon->name;
        $addon->delete();
        $this->addonDeleteId = null;
        $this->dispatch('toast', type: 'success', message: "Add-On \"{$name}\" berhasil dihapus.");
    }

    // ── Render ─────────────────────────────────────────────────
    public function render()
    {
        $menus = Menu::query()
            ->with('category')
            ->where('is_active', !$this->showArchived)
            ->when($this->search,         fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->filterCategory, fn($q) => $q->where('category_id', $this->filterCategory))
            ->when($this->filterStatus !== '', fn($q) => $q->where('is_available', $this->filterStatus === '1'))
            ->orderBy('name')
            ->paginate(10);

        $categories = Category::orderBy('name')->get();

        $addOns = AddOn::query()
            ->when($this->addonSearch,         fn($q) => $q->where('name', 'like', "%{$this->addonSearch}%"))
            ->when($this->addonFilterStatus !== '', fn($q) => $q->where('is_available', (bool) $this->addonFilterStatus))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.menu.index', compact('menus', 'categories', 'addOns'));
    }
}
