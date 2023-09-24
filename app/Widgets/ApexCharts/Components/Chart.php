<?php

namespace App\Widgets\ApexCharts\Components;

use Illuminate\View\Component;

class Chart extends Component
{
    /**
     * Renders a view for the chart component.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('apex.components.chart');
    }
}
