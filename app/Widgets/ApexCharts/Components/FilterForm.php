<?php

namespace App\Widgets\ApexCharts\Components;

use Illuminate\View\Component;

class FilterForm extends Component
{
    /**
     * Renders the view for the filter-form component.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('apex.components.filter-form');
    }
}
