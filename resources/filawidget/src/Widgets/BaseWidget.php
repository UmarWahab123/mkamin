<?php

namespace Filawidget\Widgets;

abstract class BaseWidget
{
    protected $config;

    public function __construct($config = [])
    {
        $this->config = $config;
    }

    public function render()
    {
        $view = $this->getView();

        return view($view, $this->config);
    }

    abstract protected function getView();
}
