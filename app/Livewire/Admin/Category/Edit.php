<?php

namespace App\Livewire\Admin\Category;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Edit Kategori')]
class Edit extends Component
{
    public int $categoryId;
    public string $name = '';

    public function mount(int $id): void
    {
        $category       = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name       = $category->name;
    }

    protected function rules(): array
    {
        return [
            'name' => "required|string|max:100|unique:categories,name,{$this->categoryId}",
        ];
    }

    public function save(): void
    {
        $this->validate();

        Category::findOrFail($this->categoryId)->update(['name' => $this->name]);

        $this->dispatch('toast', type: 'success', message: "Kategori berhasil diperbarui.");

        $this->redirect(route('categories.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.category.edit');
    }
}
