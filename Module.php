<?php

namespace presetshare\yii2\likes;

use presetshare\yii2\likes\helpers\EntityLikeHelper;

class Module extends \yii\base\Module
{
    /**
     * @var array
     */
    public $entities = [];

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'presetshare\yii2\likes\controllers';

    public function isDefinedAlias($alias)
    {
        foreach ($this->entities as $class) {
            if($alias == EntityLikeHelper::getAlias($class)) {
                return true;
            }
        }
        return false;
    }

    public function getEntityClass($alias)
    {
        foreach ($this->entities as $class) {
            if(EntityLikeHelper::getAlias($class) == $alias) {
                return $class;
            }
        }

        throw new \Exception("Entity {$alias} is not registered.");
    }

    public function init()
    {
        parent::init();
    }
}
