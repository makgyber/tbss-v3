<?php

namespace App\Widgets\ApexCharts\Widgets;

use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\Concerns\CanPoll;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use App\Widgets\ApexCharts\Concerns\CanDeferLoading;
use App\Widgets\ApexCharts\Concerns\CanFilter;
use App\Widgets\ApexCharts\Concerns\HasContentHeight;
use App\Widgets\ApexCharts\Concerns\HasDarkMode;
use App\Widgets\ApexCharts\Concerns\HasFooter;
use App\Widgets\ApexCharts\Concerns\HasHeader;
use App\Widgets\ApexCharts\Concerns\HasLoadingIndicator;

class ApexChartWidget extends Widget implements HasForms
{
    use CanPoll;
    use CanDeferLoading;
    use CanFilter;
    use HasHeader;
    use HasFooter;
    use HasLoadingIndicator;
    use HasDarkMode;
    use HasContentHeight;

    protected static string $chartId = 'apexChart';

    protected static string $view = 'apex.apex-chart-widget';

    public ?array $options = null;

    /**
     * Initializes the options for the object.
     */
    public function mount(): void
    {
        $this->form->fill();

        $this->options = $this->getOptions();

        if (!$this->getDeferLoading()) {
            $this->readyToLoad = true;
        }
    }

    public function render(): View
    {
        return view(static::$view, []);
    }

    /**
     * Retrieves the chart id.
     *
     * @return string|null The chart id.
     */
    protected function getChartId(): ?string
    {
        return static::$chartId ?? 'apexChart_' . Str::random(10);
    }

    /**
     * Returns an array of chart options for displaying a line chart of customer data.
     *
     * @return array Array of chart options
     */
    protected function getOptions(): array
    {
        return [];
    }

    /**
     * Updates the options of the class and emits an event if the options have changed.
     */
    public function updateOptions(): void
    {
        if ($this->options !== $this->getOptions()) {

            $this->options = $this->getOptions();

            if (!$this->dropdownOpen) {
                $this->emitSelf('updateOptions', [
                    'options' => $this->options,
                ]);
            }
        }
    }
}
