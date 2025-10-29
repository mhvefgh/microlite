<?php
namespace Src\Core;

class View
{
    public static function render(string $template, array $data = []): string
    {
        $template = str_replace('.', '/', $template); // convert admin.index -> admin/index
        $viewPath = dirname(__DIR__, 2) . "/app/views/{$template}.php";

        if (!file_exists($viewPath)) {
            return "View not found: {$template}";
        }

        extract($data);

        ob_start();
        require $viewPath;
        return ob_get_clean();
    }
}
