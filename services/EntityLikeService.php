<?php

namespace presetshare\yii2\likes\services;

use Yii;
use presetshare\yii2\likes\events\EntityLikeEvent;
use presetshare\yii2\likes\models\EntityLike;
use presetshare\yii2\likes\models\EntityLikeCounter;
use presetshare\yii2\likes\Module;
use yii\base\UnknownPropertyException;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class EntityLikeService
{

    protected function getModel($entity_alias, $entity_id)
    {
        /**
         * @var $module Module
         */
        $module = Yii::$app->getModule('entityLike');

        if(!$module->isDefinedAlias($entity_alias)) {
            throw new ForbiddenHttpException("Entity \"{$entity_alias}\" is not defined.");
        }

        $entityClass = $module->getEntityClass($entity_alias);

        /**
         * @var ActiveRecord $entity
         */
        $entity = Yii::createObject($entityClass);
        $model = $entity->findOne($entity_id);

        if(!$model) {
            throw new NotFoundHttpException('Entity not found');
        }

        return $model;
    }

    protected function resolveLike($entity_alias, $entity_id, $model)
    {
        $user = Yii::$app->user->identity;
        $action = 'like';

        $like = EntityLike::find()->where(['entity_alias' => $entity_alias, 'entity_id' => $entity_id, 'user_id' => $user->id])->one();
        if($like) {
            if ($like->active) {
                $action = 'unlike';
            }
            $like->active = $like->active == 0 ? 1 : 0;
        } else {
            $like = new EntityLike();
            $like->entity_alias = $entity_alias;
            $like->entity_id = $entity_id;
            $like->user_id = $user->id;
            $like->active = 1;
        }
        if($like->isNewRecord) {
            $like->trigger(EntityLike::EVENT_AFTER_FIRST_LIKE_TOGGLE, new EntityLikeEvent([
                'entityAuthorId' => $this->getAuthorId($model),
                'entityClass' => get_class($model),
                'likerId' => $user->id,
                'action' => $action
            ]));
        }
        $like->save();
        $like->trigger(EntityLike::EVENT_AFTER_LIKE_TOGGLE, new EntityLikeEvent([
            'entityAuthorId' => $this->getAuthorId($model),
            'entityClass' => get_class($model),
            'likerId' => $user->id,
            'action' => $action
        ]));

        return $action;
    }

    protected function getAuthorId($model)
    {
        try {
            $authorAttribute = $model->authorAttribute;
            $authorId = $model->{$authorAttribute};
        } catch (UnknownPropertyException $e) {
            $authorId = null;
        }

        return $authorId;
    }

    protected function resolveCounter($model, $action, $entity_alias, $entity_id)
    {
        $user = Yii::$app->user->identity;
        $authorId = $this->getAuthorId($model);

        if($authorId == $user->id) {
            return null;
        }

        $counter = EntityLikeCounter::find()->where(['entity_alias' => $entity_alias, 'entity_id' => $entity_id])->one();
        if(!$counter) {
            $counter = new EntityLikeCounter();
            $counter->entity_alias = $entity_alias;
            $counter->entity_id = $entity_id;
            $counter->value = $authorId != $user->id ? 1 : 0;
            $counter->save();
        } else {
            if($authorId != $user->id) {
                $counter->value = $action == 'like' ? $counter->value + 1 : $counter->value - 1;
                $counter->save();
            }
        }

        return $counter;
    }

    /**
     * @param string $entity_alias
     * @param integer $entity_id
     * @return array
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function toggleLike($entity_alias, $entity_id)
    {
        $model = $this->getModel($entity_alias, $entity_id);
        $action = $this->resolveLike($entity_alias, $entity_id, $model);
        $counter = $this->resolveCounter($model, $action, $entity_alias, $entity_id);

        $response = ['action' => $action];
        if($counter) {
            $response['newCount'] = $counter->value;
        }
        return $response;
    }
}
