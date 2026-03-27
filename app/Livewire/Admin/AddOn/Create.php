<?php

namespace App\Livewire\Admin\AddOn;

use App\Models\AddOn;
use Livewire\Component;


class Create extends Component
{
    public string $name        = '';
    public string $price       = '';
    public bool   $is_available = true;

    protected function rules(): array
    {
        return [
            'name'         => 'required|string|max:100|unique:add_ons,name',
            'price'        => 'required|integer|min:0',
            'is_available' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required'  => 'Add-on name is required.',
        'name.unique'    => 'An add-on with this name already exists.',
        'price.required' => 'Price is required.',
        'price.integer'  => 'Price must be a whole number.',
        'price.min'      => 'Price cannot be negative.',
    ];

    public function save(): void
    {
        $this->validate();

        AddOn::create([
            'name'         => trim($this->name),
            'price'        => (int) $this->price,
            'is_available' => $this->is_available,
        ]);

        session()->flash('success', "Add-on \"{$this->name}\" created successfully.");

        $this->redirect(route('add-on.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.add-on.create')
            ->with(['title' => 'Create Add-On']);
    }
}
