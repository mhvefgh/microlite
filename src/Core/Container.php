<?php
namespace Src\Core;

use ReflectionClass;
use RuntimeException;

class Container
{
    protected array $instances = [];

    public function set(string $id, mixed $value): void
    {
        $this->instances[$id] = $value;
    }

    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            $service = $this->instances[$id];

            if (is_callable($service)) {
                $resolved = $service($this);
                $this->instances[$id] = $resolved;
                return $resolved;
            }

            return $service;
        }

        if (class_exists($id)) {
            $reflector = new ReflectionClass($id);

            if (!$reflector->isInstantiable()) {
                throw new RuntimeException("Class $id is not instantiable.");
            }

            $constructor = $reflector->getConstructor();
            $dependencies = [];

            if ($constructor) {
                foreach ($constructor->getParameters() as $param) {
                    $paramType = $param->getType()?->getName();
                    $dependencies[] = $paramType && class_exists($paramType)
                        ? $this->get($paramType)
                        : ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
                }
            }

            $object = $reflector->newInstanceArgs($dependencies);
            $this->instances[$id] = $object;
            return $object;
        }

        throw new RuntimeException("No service found for $id");
    }
}
