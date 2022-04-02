<?php

namespace presetshare\yii2\likes\behaviors;

use Yii;
use presetshare\yii2\likes\helpers\EntityLikeHelper;
use presetshare\yii2\likes\models\EntityLike;
use presetshare\yii2\likes\models\EntityLikeCounter;
use yii\base\Behavior;
use yii\base\ModelEvent;
use yii\db\ActiveRecord;

class LikeableEntityBehavior extends Behavior
{
    public $authorAttribute = 'user_id';
    
    public $likes_count;
    public $has_my_like = 0;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'flushLikes',
        ];
    }

    /**
     * @param ModelEvent $event
     * @return void
     * @throws \yii\db\Exception
     */
    public function flushLikes($event)
    {
        foreach ([EntityLikeCounter::tableName(), EntityLike::tableName()] as $table) {
            \Yii::$app
                ->db
                ->createCommand()
                ->delete($table, ['entity_alias' => EntityLikeHelper::getAlias($event->sender), 'entity_id' => $event->sender->id])
                ->execute();
        }
    }
}

