<?php

namespace presetshare\yii2\likes\events;

use yii\base\Event;

class EntityLikeEvent extends Event
{
    /**
     * @var int|null
     */
    public $entityAuthorId;

    /**
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $entityClass;
}
