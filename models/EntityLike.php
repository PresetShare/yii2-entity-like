<?php

namespace presetshare\yii2\likes\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "entity_like".
 *
 * @property int $id
 * @property string $entity_alias
 * @property int $entity_id
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $active
 */
class EntityLike extends \yii\db\ActiveRecord
{
    const EVENT_AFTER_FIRST_LIKE_TOGGLE = 'afterFirstLikeToggle';
    const EVENT_AFTER_LIKE_TOGGLE = 'afterLikeToggle';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%entity_like}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            ['class' => TimestampBehavior::class]
        ];
    }

    /**
     * {@inheritdoc}
     * @return EntityLikeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EntityLikeQuery(get_called_class());
    }
}
