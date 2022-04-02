<?php

namespace presetshare\yii2\likes\controllers;

use Yii;
use presetshare\yii2\likes\services\EntityLikeService;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;

class DefaultController extends Controller
{
    private $entityLikeService;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['post'],
                ],
            ],
        ];
    }

    public function __construct($id, $module, EntityLikeService $entityLikeService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->entityLikeService = $entityLikeService;
    }

    public function actionToggle()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->request->getIsAjax()) {
            throw new MethodNotAllowedHttpException('Only ajax post requests allowed.');
        }

        return $this->entityLikeService->toggleLike(
            Yii::$app->request->post('entity_alias'),
            Yii::$app->request->post('entity_id')
        );
    }
}
