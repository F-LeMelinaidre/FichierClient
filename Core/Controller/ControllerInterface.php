<?php

namespace Core\Controller;

interface ControllerInterface
{

    public function index();
    public function show();
    public function create();
    public function update();
    public function delete();
    public function render(string $view);
}