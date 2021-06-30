<?php

require_once 'classes/Controller.php';

class Export
{
    private $controller;
    private $type;
    private $format;
    private $args;

    public function __construct()
    {
        $this->args = collect($_REQUEST);
        $this->controller = new Controller($this->args);
        $this->type = $this->pullFromArgs('type');
        $this->format = $this->pullFromArgs('format', 'html');
    }

    private function pullFromArgs(String $qryStr, $default = null)
    {
        $value = $this->args->pull($qryStr);
        return $value ? $value : $default;
    }

    public function render()
    {
        return $this->type ?
        $this->controller->export($this->type, $this->format)
        : exit('Please specify a type');
    }
}