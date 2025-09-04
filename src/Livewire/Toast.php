<?php

namespace App\Livewire;

use Livewire\Component;

class Toast extends Component
{
    protected $listeners = ['notify' => 'showMessage'];

    public string $message = '';
    public bool $show = false;

    public function showMessage($data): void
    {
        $this->message = $data['message'];
        $this->show = true;

        $this->dispatch('toast-hide')->self();
    }

    public function render()
    {
        return view('referral-toaster::livewire.components.toast');
    }
}
