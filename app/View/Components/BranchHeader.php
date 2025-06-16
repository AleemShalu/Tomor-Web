<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BranchHeader extends Component
{
    public $branch;

    /**
     * Create a new component instance.
     *
     * @param $branch
     * @return void
     */
    public function __construct($branch)
    {
        $this->branch = $branch;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.branch-header');
    }
}
