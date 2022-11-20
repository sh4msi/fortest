<?php

namespace App\Http\Livewire;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Livewire\Component;

//use Laravel\Fortify\Rules\Password;

/**
 * @property ComponentContainer $form
 */
class Register extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;


    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [

            TextInput::make('name')
                ->string()
                ->required(),

            TextInput::make('email')
                ->unique('users')
                ->email()
                ->required(),

            TextInput::make('password')
                ->password()
                ->disableAutocomplete()
                ->rules([
                    'required', 'string',
                    Password::min(8)
                        // ->mixedCase()
                        // ->numbers()
//                        ->symbols()
                        // ->uncompromised(3)
                        ,
                    'confirmed'
                ]),

            TextInput::make('password_confirmation')
                ->password()
                ->rules(['required', 'string', 'min:8']),
        ];
    }

    public function render(): View
    {
        return view('livewire.register')
            ->layout('filament::components.layouts.card', [
                'title' => 'Register'
            ]);
    }


    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        auth()->login($user);
        return redirect('/admin');

    }


}
