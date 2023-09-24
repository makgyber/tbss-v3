<?php

namespace App\Widgets\ApexCharts\Components;

use Illuminate\View\Component;

class Header extends Component
{
    /**
     * Renders the view for the header component.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('apex.components.header');
    }
}
