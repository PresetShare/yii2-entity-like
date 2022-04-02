<?php

namespace presetshare\yii2\likes\models;

/**
 * This is the ActiveQuery class for [[EntityLike]].
 *
 * @see EntityLike
 */
class EntityLikeQuery extends \yii\db\ActiveQuery
{
    /**
     * @return EntityLikeQuery
     */
    public function active()
    {
        return $this->andWhere('[[active]]=1');
    }

    /**
     * {@inheritdoc}
     * @return EntityLike[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return EntityLike|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
