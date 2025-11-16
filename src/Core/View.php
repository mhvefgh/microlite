<?php

namespace Src\Core;

class View
{
    public static function make(string $template, array $data = [], ?string $layout = null): string
    {
        $template = str_replace('.', '/', $template);
        $viewPath = BASE_PATH . "/app/views/{$template}.php";

        if (!file_exists($viewPath)) {
            return "View not found: {$template}";
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        if ($layout) {
            $layoutPath = BASE_PATH . "/app/views/" . str_replace('.', '/', $layout) . ".php";
            if (file_exists($layoutPath)) {
                ob_start();
                include $layoutPath;
                return ob_get_clean();
            }
        }

        return $content;
    }

    public static function partial(string $partial, array $data = []): void
    {
        $partial = str_replace('.', '/', $partial);
        $path = BASE_PATH . "/app/views/{$partial}.php";

        if (file_exists($path)) {
            extract($data, EXTR_SKIP);
            include $path;
        }
    }
}
