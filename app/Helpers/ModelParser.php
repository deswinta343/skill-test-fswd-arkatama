<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;

class ModelParser extends Collection
{
    public function __construct($class)
    {
        if (is_array($class)) {
            parent::__construct($class);
        } else {
            $namespace = null;
            if ($class instanceof Model) {
                $namespace = get_class($class);
            } else {
                if (class_exists($class)) {
                    $namespace = $class;
                }
            }

            if ($namespace) {
                $reflectionClass = new ReflectionClass($namespace);
                $constants = $reflectionClass->getConstants();
                try {
                    $prop = $reflectionClass->getProperty('withVendorRelation');
                } catch (\Exception $ignore) {
                    $prop = null;
                }

                $with_vendor_relation = false;

                if (isset($constants['WITH_VENDOR_RELATION']) && $constants['WITH_VENDOR_RELATION']) {
                    $with_vendor_relation = true;
                }

                $instance = new $namespace;

                if ($prop && isset($instance->{'withVendorRelation'}) && $instance->{'withVendorRelation'}) {
                    $with_vendor_relation = true;
                }

                foreach ($reflectionClass->getMethods(
                    ReflectionMethod::IS_PUBLIC
                ) as $_ => $reflectionMethod) {
                    try {
                        if ($reflectionMethod->class === $reflectionClass->getName()) {
                            if (! count($reflectionMethod->getParameters())) {
                                if (! $reflectionMethod->isStatic()) {
                                    $methodReturn = $instance->{$reflectionMethod->getName()}();
                                    if ($methodReturn instanceof Relation) {
                                        $model = get_class($methodReturn->getRelated());
                                        if ($with_vendor_relation) {
                                            $this->push(
                                                [
                                                    'name' => $reflectionMethod->getShortName(),
                                                    'relation' => get_class($methodReturn),
                                                    'model' => $model,
                                                    'from_model' => $namespace,
                                                    'is_vendor' => ! strpos('-'.$model, 'App\\'),
                                                ]
                                            );
                                        } else {
                                            if ((bool) strpos('-'.$model, 'App\\')) {
                                                $this->push(
                                                    [
                                                        'name' => $reflectionMethod->getShortName(),
                                                        'relation' => get_class($methodReturn),
                                                        'model' => $model,
                                                        'from_model' => $namespace,
                                                    ]
                                                );
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } catch (\Exception $ignore) {
                    }
                }
            }
        }
    }
}
