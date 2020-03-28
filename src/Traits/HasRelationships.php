<?php

namespace Devel\Traits;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

trait HasRelationships
{
    /**
     * Get the list of a model's relationships
     *
     * @return array
     */
    public function getRelationships(): array
    {
        $reflector = new \ReflectionClass($this);

        $relationships = [];

        foreach ($reflector->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $acceptedClasses = Str::startsWith($method->class, [
                'App\\',
                'Modules\\',
                'Devel\\',
            ]);

            if (count($method->getParameters()) || !$acceptedClasses || $method->getName() === 'getRelationships') {
                continue;
            }

            try {
                $return = $method->invoke($this);
                $className = get_class($return->getRelated());

                if ($return instanceof Relation && substr($className, 0, 10) !== 'Illuminate') {
                    $relationships[$method->getName()] = [
                        'type' => class_basename($return),
                        'model' => get_class($return->getRelated()),
                        'method' => $method,
                        'relation' => $return,
                    ];
                }
            } catch (\Exception $e) {
                // Do nothing
            }
        }

        return $relationships;
    }
}
