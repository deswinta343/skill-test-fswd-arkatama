<?php

namespace App\Traits;

use App\Exceptions\RestrictDeleteException;
use App\Helpers\ModelParser;

trait RestrictOnDelete
{
    public static function relations()
    {
        return new ModelParser(new static);
    }

    private function getClassName($model)
    {
        $tableName = explode('\\', get_class($model));
        $tableName = end($tableName);
        $tableName = preg_replace('/(?<!\ )[A-Z]/', ' $0', $tableName);
        $tableName = trim($tableName);
        $tableName = strtolower($tableName);

        return $tableName;
    }

    public static function bootRestrictOnDelete()
    {
        static::deleting(function ($model) {

            $class = new static;
            $relations = $class::relations();

            if (isset($class->tableName)) {
                $tableName = $class->tableName;
            } else {
                $tableName = $class->getClassName($model);
            }

            foreach ($relations as $relation) {
                // ignore createdBy and updatedBy relation
                if ($relation['name'] == 'createdBy' || $relation['name'] == 'updatedBy') {
                    continue;
                }

                $ignoredRelations = $class->ignoreOnDelete ?? [];
                // ignore relation that is set to be ignored
                if (in_array($relation['name'], $ignoredRelations)) {
                    continue;
                }

                $count = $model->{$relation['name']}()->count();
                if ($count > 0) {

                    $modelName = $relation['model'];
                    $modelName = $class->getClassName(new $modelName);
                    $message = 'Tidak dapat menghapus data '.$tableName.' karena terdapat data '.$modelName.' yang terkait';
                    throw new RestrictDeleteException($message);
                }
            }
        });
    }
}
