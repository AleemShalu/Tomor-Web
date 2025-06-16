<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StoreCreateForm extends Component
{
    public $businesses;
    public $countries;
    public $errors;

    /**
     * Create a new component instance.
     *
     * @param array $businesses
     * @param array $countries
     * @param \Illuminate\Support\MessageBag $errors
     * @return void
     */
    public function __construct($businesses, $countries, $errors)
    {
        $this->businesses = $businesses;
        $this->countries = $countries;
        $this->errors = $errors;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.store-create-form');
    }
}