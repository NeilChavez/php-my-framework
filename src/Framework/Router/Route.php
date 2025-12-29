<?php

namespace Framework\Router;

class Route
{
    public function __construct(private string $name, private $callback, private array $params)
    {
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
