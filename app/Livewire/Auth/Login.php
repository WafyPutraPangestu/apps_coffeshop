<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.app')]
class Login extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required|min:6')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->dispatch('toast', type: 'error', message: 'Email atau password salah.');
            return;
        }

        session()->regenerate();

        $this->redirect('/dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
