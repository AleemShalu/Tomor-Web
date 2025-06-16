<?php

namespace App\View\Components;

use Illuminate\View\Component;


class TabComplaintNavigation extends Component
{
    public $currentRoute;
    public $tabs;

    public function __construct($currentRoute, $tabs)
    {
        $this->currentRoute = $currentRoute;
        $this->tabs = $tabs;
    }

    public function render()
    {
        return view('components.tab-complaint-navigation');
    }
}
