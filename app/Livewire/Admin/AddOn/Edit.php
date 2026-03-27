<?php

namespace App\Livewire\Admin\AddOn;

use App\Models\AddOn;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Edit extends Component
{
    public AddOn  $addOn;
    public string $name        = '';
    public string $price       = '';
    public bool   $is_available = true;

    public function mount(int $id): void
    {
        $this->addOn        = AddOn::findOrFail($id);
        $this->name         = $this->addOn->name;
        $this->price        = (string) $this->addOn->price;
        $this->is_available = (bool)  $this->addOn->is_available;
    }

    protected function rules(): array
    {
        return [
            'name'         => "required|string|max:100|unique:add_ons,name,{$this->addOn->id}",
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

    public function update(): void
    {
        $this->validate();

        $this->addOn->update([
            'name'         => trim($this->name),
            'price'        => (int) $this->price,
            'is_available' => $this->is_available,
        ]);

        session()->flash('success', "Add-on \"{$this->name}\" updated successfully.");

        $this->redirect(route('add-on.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.add-on.edit');
    }
}
