<?php

namespace App\Livewire\Admin\Menu;

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
    public string $category_id = '';
    public bool   $is_available = true;
    public $image;

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'price'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_available' => 'boolean',
            'image'       => 'nullable|image|max:2048',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('menus', 'public');
        }

        Menu::create([
            'name'         => $this->name,
            'description'  => $this->description,
            'price'        => (int) $this->price,
            'category_id'  => $this->category_id,
            'is_available' => $this->is_available,
            'image'        => $imagePath,
        ]);

        $this->dispatch('toast', type: 'success', message: "Menu \"{$this->name}\" berhasil ditambahkan.");
        $this->redirect(route('menu.index'), navigate: true);
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        return view('livewire.admin.menu.create', compact('categories'));
    }
}
