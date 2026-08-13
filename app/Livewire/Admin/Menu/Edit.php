<?php

namespace App\Livewire\Admin\Menu;

use App\Models\AddOn;
use App\Models\Category;
use App\Models\Menu;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Edit Menu')]
class Edit extends Component
{
    use WithFileUploads;

    public int    $menuId;
    public string $name        = '';
    public string $description = '';
    public string $price       = '';
    public string $stock       = '';
    public string $category_id = '';
    public bool   $is_available = true;
    public $image;
    public ?string $existingImage = null;

    // ── Add-ons ──
    public array $selectedAddOns = []; // [add_on_id => true/false]

    public function mount(int $id): void
    {
        $menu = Menu::with('addOns')->findOrFail($id);

        $this->menuId        = $menu->id;
        $this->name          = $menu->name;
        $this->description   = $menu->description ?? '';
        $this->price         = (string) $menu->price;
        $this->stock         = (string) $menu->stock;
        $this->category_id   = (string) $menu->category_id;
        $this->is_available  = (bool) $menu->is_available;
        $this->existingImage = $menu->image;

        // Pre-select add-on yang sudah ter-assign ke menu ini
        $this->selectedAddOns = $menu->addOns
            ->pluck('id')
            ->mapWithKeys(fn($id) => [$id => true])
            ->toArray();
    }

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_available' => 'boolean',
            'image'       => 'nullable|image|max:2048',
        ];
    }

    public function removeImage(): void
    {
        $this->existingImage = null;
    }

    // ── Toggle add-on checkbox ──
    public function toggleAddOn(int $addOnId): void
    {
        if (isset($this->selectedAddOns[$addOnId])) {
            unset($this->selectedAddOns[$addOnId]);
        } else {
            $this->selectedAddOns[$addOnId] = true;
        }
    }

    public function save(): void
    {
        $this->validate();

        $menu = Menu::findOrFail($this->menuId);

        $imagePath = $this->existingImage; // pertahankan gambar lama
        if ($this->image) {
            $imagePath = $this->image->store('menus', 'public');
        }

        $menu->update([
            'name'         => $this->name,
            'description'  => $this->description,
            'price'        => (int) $this->price,
            'stock'        => (int) $this->stock,
            'category_id'  => $this->category_id,
            'is_available' => $this->is_available,
            'image'        => $imagePath,
        ]);

        // Sync ulang relasi add-on (otomatis tambah/hapus sesuai centang terbaru)
        $menu->addOns()->sync(array_keys($this->selectedAddOns));

        $this->dispatch('toast', type: 'success', message: "Menu berhasil diperbarui.");
        $this->redirect(route('menu.index'), navigate: true);
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        $addOns     = AddOn::where('is_available', true)->orderBy('name')->get();

        return view('livewire.admin.menu.edit', compact('categories', 'addOns'));
    }
}
