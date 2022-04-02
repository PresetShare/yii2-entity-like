<?php

namespace presetshare\yii2\likes\widgets;

use Yii;
use presetshare\yii2\likes\helpers\EntityLikeHelper;
use presetshare\yii2\likes\Module;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;

class EntityLikeWidget extends \yii\base\Widget
{
    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $buttonText = '👍';

    /**
     * @var string
     */
    public $customClass;

    /**
     * @var string
     */
    public $registerJS = true;

    /**
     * @var string
     */
    public $registerCSS = false;

    public function init()
    {
        parent::init();

        if (!isset($this->model)) throw new InvalidConfigException('Model must be set.');

        /**
         * @var $module Module
         */
        $module = Yii::$app->getModule('entityLike');

        if(!$module->isDefinedAlias($entity_alias = EntityLikeHelper::getAlias($this->model))) {
            throw new ForbiddenHttpException("Entity \"{$entity_alias}\" is not defined.");
        }
    }

    public function run()
    {
        return $this->render('like', [
            'model' => $this->model,
            'buttonText' => $this->buttonText,
            'customClass' => $this->customClass,
            'registerJS' => $this->registerJS,
            'registerCSS' => $this->registerCSS,
        ]);
    }
}
