<?php

namespace Framework;

class Renderer
{
    private const DEFAULT_ALIAS = '_MAIN_';
    private array $paths = [];
    private array $globals = [];

    public function addPath(string $alias, ?string $path = null)
    {
        if ($path !== null) {
            $this->paths[$alias] = $path;
        } else {
            $this->paths[self::DEFAULT_ALIAS] = $alias;
        }
    }

    public function render(string $view, array $params = []): string
    {
        $path = '';
        // check if the $view has an alias, like "@blog/demo"
        if ($this->hasAlias($view)) {
            // transforms "@blog/demo" to "/absolute/path/views/demo.php"
            $path = $this->replaceAliasWithPath($view) . '.php';
        } else {
            $path = $this->paths[self::DEFAULT_ALIAS] . '/' . $view . '.php';
        }

        ob_start();
        $renderer = $this;
        foreach ($this->getGlobals() as $key => $value) {
            $$key = $value;
        }
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        include $path;
        $content = ob_get_clean();
        return $content;
    }

    public function addGlobals(string $key, mixed $value)
    {
        $this->globals[$key] = $value;
    }

    public function getGlobals(): array
    {
        return $this->globals;
    }

    private function hasAlias(string $view): bool
    {
        return $view[0] === '@';
    }

    private function getAlias(string $view)
    {
        return substr($view, 1, strpos($view, '/') - 1);
    }

    private function replaceAliasWithPath(string $view)
    {   // from "@blog/demo" to "blog";
        $alias = $this->getAlias($view);
        // loking for "/path/views"
        // in paths array $paths = ["blog" => "/path/views"]
        $path = $this->paths[$alias];

        // replace the "@blog/demo" to "/path/views" . "$view"
        return str_replace('@' . $alias, $path, $view);
    }

    public function getPaths()
    {
        return $this->paths;
    }
}
