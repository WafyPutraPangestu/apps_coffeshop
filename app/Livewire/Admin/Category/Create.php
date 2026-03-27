<?php

namespace App\Livewire\Admin\Category;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Tambah Kategori')]
class Create extends Component
{
    #[Rule('required|string|max:100|unique:categories,name')]
    public string $name = '';

    public function save(): void
    {
        $this->validate();

        Category::create(['name' => $this->name]);

        $this->dispatch('toast', type: 'success', message: "Kategori \"{$this->name}\" berhasil ditambahkan.");

        $this->redirect(route('categories.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.category.create');
    }
}
