<?php

namespace presetshare\yii2\likes\events;

use yii\base\Event;

class EntityLikeEvent extends Event
{
    /**
     * @var int
     */
    public $entityAuthorId;
    
    /**
     * @var int
     */
    public $likerId;

    /**
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $entityClass;
}
