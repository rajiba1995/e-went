<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AdminLogin extends Component
{
    public $email = '';
    public $password = '';


    public function login()
    { 
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt(['email' => $this->email, 'password' => $this->password])) {
            return redirect()->route('admin.dashboard');
        } else {
            session()->flash('message', 'Invalid credentials!');
        }
    }
    // Add the logout method
    public function logout()
    {
        Auth::guard('admin')->logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('login');
    }
    public function render()
    {
        return view('livewire.admin-login')->layout('components.layouts.admin');
    }
}
