<?php

namespace presetshare\yii2\likes\models;

use Yii;

/**
 * This is the model class for table "{{%entity_like_counter}}".
 *
 * @property string $entity_alias
 * @property int $entity_id
 * @property int $value
 */
class EntityLikeCounter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%entity_like_counter}}';
    }

    /**
     * {@inheritdoc}
     * @return EntityLikeCounterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EntityLikeCounterQuery(get_called_class());
    }
}
