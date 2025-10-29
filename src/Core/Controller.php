<?php
namespace Src\Core;

abstract class Controller {
    protected Container $container;
    protected App $app;

    public function __construct(Container $container,App $app) {
        $this->container = $container;
        $this->app = $app;
    }

    protected function view(string $template, array $data = []) {
        extract($data);
        require __DIR__ . '/../../app/Views/' . $template . '.php';
    }
}
