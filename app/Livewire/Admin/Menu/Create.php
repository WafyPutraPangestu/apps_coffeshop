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
#[Title('Tambah Menu')]
class Create extends Component
{
    use WithFileUploads;

    public string $name        = '';
    public string $description = '';
    public string $price       = '';
    public string $stock       = '0';
    public string $category_id = '';
    public bool   $is_available = true;
    public $image;

    // ── Add-ons ──
    public array  $selectedAddOns = []; // [add_on_id => true/false]

    // ── Quick-Create Add-On ──
    public bool   $showNewAddOnForm = false;
    public string $newAddOnName     = '';
    public string $newAddOnPrice    = '';

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

    // ── Toggle add-on checkbox ──
    public function toggleAddOn(int $addOnId): void
    {
        if (isset($this->selectedAddOns[$addOnId])) {
            unset($this->selectedAddOns[$addOnId]);
        } else {
            $this->selectedAddOns[$addOnId] = true;
        }
    }

    // ── Quick-create add-on baru ──
    public function saveNewAddOn(): void
    {
        $this->validate([
            'newAddOnName'  => 'required|string|max:100',
            'newAddOnPrice' => 'required|integer|min:0',
        ], [], [
            'newAddOnName'  => 'Nama Add-On',
            'newAddOnPrice' => 'Harga Add-On',
        ]);

        $addon = AddOn::create([
            'name'         => $this->newAddOnName,
            'price'        => (int) $this->newAddOnPrice,
            'is_available' => true,
        ]);

        // Otomatis dipilih setelah dibuat
        $this->selectedAddOns[$addon->id] = true;

        $this->newAddOnName     = '';
        $this->newAddOnPrice    = '';
        $this->showNewAddOnForm = false;

        $this->dispatch('toast', type: 'success', message: "Add-On \"{$addon->name}\" berhasil ditambahkan dan dipilih.");
    }

    public function save(): void
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('menus', 'public');
        }

        $menu = Menu::create([
            'name'         => $this->name,
            'description'  => $this->description,
            'price'        => (int) $this->price,
            'stock'        => (int) $this->stock,
            'category_id'  => $this->category_id,
            'is_available' => $this->is_available,
            'image'        => $imagePath,
        ]);

        // Simpan relasi add-on yang dipilih
        $menu->addOns()->sync(array_keys($this->selectedAddOns));

        $this->dispatch('toast', type: 'success', message: "Menu \"{$this->name}\" berhasil ditambahkan.");
        $this->redirect(route('menu.index'), navigate: true);
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        $addOns     = AddOn::where('is_available', true)->orderBy('name')->get();

        return view('livewire.admin.menu.create', compact('categories', 'addOns'));
    }
}
