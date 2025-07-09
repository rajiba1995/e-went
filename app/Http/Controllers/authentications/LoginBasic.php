<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginBasic extends Controller
{
  public $email;
  public $password;
  public $remember_me = false;

  protected $rules = [
    'email' => 'required|email',
    'password' => 'required|min:6',
];

  
public function login()
{
 
    $this->validate();

    if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
        session()->flash('message', 'Login successful!');
        return redirect()->route('dashboard-analytics'); // Redirect to the dashboard
    } else {
        $this->addError('email', 'Invalid email or password');
    }
}


  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }
}
