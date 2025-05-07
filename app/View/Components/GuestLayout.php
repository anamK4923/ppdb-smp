<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public $title;

    public function __construct($title = 'PPDB SMP Al-Irsyad')
    {
        $this->title = $title;
    }

    public function render(): View
    {
        return view('layouts.guest');
    }
}
