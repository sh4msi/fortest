<form wire:submit.prevent="register"  class="space-y-8">
    {{ $this->form }}

    <x-filament::button type="submit" form="register" class="w-full">
        Register
    </x-filament::button>
</form>
