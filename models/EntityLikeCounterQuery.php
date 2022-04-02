<?php

namespace presetshare\yii2\likes\models;

/**
 * This is the ActiveQuery class for [[EntityLikeCounter]].
 *
 * @see EntityLikeCounter
 */
class EntityLikeCounterQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return EntityLikeCounter[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EntityLikeCounter|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
