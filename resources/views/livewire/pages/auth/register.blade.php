<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new [Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $telephone = '';
    public string $adresse = '';

    /**
     * Handle an incoming registration request.
     */
    public bool $conditions_acceptees = false;

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'telephone' => ['required', 'string', 'min:10'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'conditions_acceptees' => ['accepted'],

        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $previousUrl = url()->previous();

        if (str_contains($previousUrl, 'panier')) {
            $this->redirect($previousUrl, navigate: true);
        } else {
            $this->redirect(route('dashboard', absolute: false), navigate: true);
        }
    }
}; ?>

<div>
    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <!-- Telephone -->
        <div class="mt-4">
            <x-input-label for="telephone" :value="__('Téléphone')" />
            <x-text-input wire:model="telephone" id="telephone" class="block mt-1 w-full" type="text"
                name="telephone" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
        </div>

        <!-- Adresse -->
        <div class="mt-4">
            <x-input-label for="adresse" :value="__('Adresse')" />
            <x-text-input wire:model="adresse" id="adresse" class="block mt-1 w-full" type="text" name="adresse"
                autocomplete="street-address" />
            <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                type="password" name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>
            <!-- Acceptation des conditions -->
            <div class="mt-4">
                <label class="flex items-start space-x-2 text-sm text-gray-700">
                    <input wire:model.defer="conditions_acceptees" type="checkbox"
                        class="mt-1 border-gray-300 rounded shadow-sm">
                    <span>
                        En vous inscrivant, vous acceptez nos <a href="{{ route('conditions') }}"
                            class="text-[#05335E] underline hover:text-[#C19B2C]">conditions générales</a>
                        et confirmez avoir lu la <strong>loi 18-07</strong> sur la protection des données personnelles.
                    </span>
                </label>
                @error('conditions_acceptees')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>


            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
