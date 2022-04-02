<?php

namespace presetshare\yii2\likes\helpers;

use presetshare\yii2\likes\traits\Likeable;
use yii\db\ActiveRecord;

class EntityLikeHelper
{
    /**
     * @param $entity string|ActiveRecord
     * @return string
     */
    public static function getAlias($entity) {
        $class = $entity instanceof ActiveRecord ? get_class($entity) : $entity;
        $path = explode('\\', $class);
        return lcfirst(array_pop($path));
    }
}
