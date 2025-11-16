<?php
namespace Src\Core;

use ReflectionClass;
use RuntimeException;

class Container
{
    protected array $instances = [];

    public function set(string $id, mixed $value, bool $singleton = true): void
    {
        $this->instances[$id] = [
            'factory' => $value,
            'singleton' => $singleton,
            'instance' => null,
        ];
    }

    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            $entry = &$this->instances[$id];

            if (!is_array($entry)) {
                return $entry;
            }

            if ($entry['singleton'] && $entry['instance']) {
                return $entry['instance'];
            }

            $service = $entry['factory'];
            $resolved = is_callable($service) ? $service($this) : $service;

            if ($entry['singleton']) {
                $entry['instance'] = $resolved;
            }

            return $resolved;
        }

        if (class_exists($id)) {
            $reflector = new \ReflectionClass($id);

            if (!$reflector->isInstantiable()) {
                throw new \RuntimeException("Class $id is not instantiable.");
            }

            $constructor = $reflector->getConstructor();
            $dependencies = [];

            if ($constructor) {
                foreach ($constructor->getParameters() as $param) {
                    $paramType = $param->getType()?->getName();
                    $dependencies[] = $paramType && class_exists($paramType) ? $this->get($paramType) : ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
                }
            }

            $object = $reflector->newInstanceArgs($dependencies);
            $this->instances[$id] = $object;
            return $object;
        }

        throw new \RuntimeException("No service found for $id");
    }
}
