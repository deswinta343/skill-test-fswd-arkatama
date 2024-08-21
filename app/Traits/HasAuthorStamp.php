<?php

namespace App\Traits;

trait HasAuthorStamp
{
    protected static function bootHasAuthorStamp()
    {
        static::creating(function ($model) {
            $authorStamp = $model->author_stamp ?? 'created_by';
            $model->$authorStamp = getUserId();
        });
        static::updating(function ($model) {
            $mutatorStamp = $model->mutator_stamp ?? 'updated_by';
            $model->$mutatorStamp = getUserId();
        });
        static::deleting(function ($model) {
            if (in_array('deleted_by', $model->getFillable())) {
                $mutatorStamp = $model->mutator_stamp ?? 'deleted_by';
                $model->$mutatorStamp = getUserId();
            }
        });
    }
}
