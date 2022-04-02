<?php

namespace presetshare\yii2\likes\traits;

use Yii;
use presetshare\yii2\likes\helpers\EntityLikeHelper;
use presetshare\yii2\likes\models\EntityLike;
use presetshare\yii2\likes\models\EntityLikeCounter;
use yii\db\Expression;

trait EntityLikeQueries
{
    /**
     * @var bool
     */
    protected $selectAdded = false;

    public function withLikesCount()
    {
        $alias = EntityLikeHelper::getAlias($this->modelClass);
        $model = new $this->modelClass();
        $counterTable = EntityLikeCounter::tableName();
        $this->initSelect($model);

        $this
            ->leftJoin(['elc' => $counterTable], [
                "elc.entity_id" => new Expression("`{$model->tableSchema->name}`.`{$model->primaryKey()[0]}`"),
                "elc.entity_alias" => $alias
            ])
            ->addSelect([
                new Expression("COALESCE(elc.value, 0) as likes_count"),
            ]);

        return $this;
    }

    public function onlyLikedByMe()
    {
        $alias = EntityLikeHelper::getAlias($this->modelClass);
        $model = new $this->modelClass();
        $likeTable = EntityLike::tableName();
        $this->initSelect($model);

        $this
            ->innerJoin(['el' => $likeTable], [
                "el.entity_alias" => $alias,
                "el.entity_id" => new Expression("{$model->tableSchema->name}.{$model->primaryKey()[0]}"),
                "el.user_id" => Yii::$app->user->id,
                "el.active" => 1,
            ]);

        return $this;
    }

    public function withHasMyLike()
    {
        if(!Yii::$app->user->isGuest) {
            $alias = EntityLikeHelper::getAlias($this->modelClass);
            $model = new $this->modelClass();
            $likeTable = EntityLike::tableName();
            $this->initSelect($model);

            $this
                ->leftJoin(['lc2' => $likeTable], [
                    "lc2.entity_alias" => $alias,
                    "lc2.entity_id" => new Expression("{$model->tableSchema->name}.{$model->primaryKey()[0]}"),
                    "lc2.user_id" => Yii::$app->user->id,
                    "lc2.active" => 1,
                ])->addSelect([
                    new Expression("COALESCE(lc2.active, 0) as has_my_like"),
                ]);
        }

        return $this;
    }

    protected function initSelect($model)
    {
        if (!$this->selectAdded && (is_array($this->select) && !array_search('*', $this->select)) ||
            !isset($this->select)) {
            $this->addSelect("{$model->tableSchema->name}.*");
            $this->selectAdded = true;
        }
    }
}
