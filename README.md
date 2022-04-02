yii2-entity-like
==========
[![Total Downloads](https://poser.pugx.org/presetshare/yii2-entity-like/downloads.svg)](https://packagist.org/packages/presetshare/yii2-entity-like)
[![License](https://poser.pugx.org/presetshare/yii2-entity-like/license.svg)](https://packagist.org/packages/presetshare/yii2-entity-like)

❤️ User-like features for Yii2 Applications.

![How yii2-entity-like works](https://raw.githubusercontent.com/presetshare/yii2-entity-like/master/docs/showcase.gif)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer require --prefer-dist presetshare/yii2-entity-like "@dev"
```

or add

```
"presetshare/yii2-entity-like": "@dev"
```

to the require section of your `composer.json` file.


Usage
-----
Migrations
```bash
php yii migrate/up --migrationPath=@vendor/presetshare/yii2-entity-like/migrations
```
Module config
```php
'modules' => [
    'entityLike' => [
        'class' => \presetshare\yii2\likes\Module::class,
        'entities' => [
            \app\models\Post::class
        ],
    ],
],
```
Model behavior
```php
class Post extends \yii\db\ActiveRecord
{
    ...
    public function behaviors()
    {
        return [
            [
                'class' => \presetshare\yii2\likes\behaviors\LikeableEntityBehavior::class,
                'authorAttribute' => 'user_id'
            ]
        ];
    }
    ...
}
```
Query trait
```php
class PostQuery extends \yii\db\ActiveQuery
{
    use \presetshare\yii2\likes\traits\EntityLikeQueries;
    ...
}
```
Queries
```php
$dataProvider = new ActiveDataProvider([
    'query' => Post::find()
        ->withLikesCount()
        ->withHasMyLike()
        //->onlyLikedByMe()

        ->orderBy(['likes_count' => SORT_DESC, 'post.id' => SORT_DESC]),
    'pagination' => [
        'pageSize' => 15,
        'defaultPageSize' => 15,
    ],
]);

$posts = $dataProvider->getModels();

$post = Post::find()->where(['post.id' => $id])->withLikesCount()->withHasMyLike()->one();

$likesCount = $post->likes_count;
$hasMyLike = $post->has_my_like;
```
Widget
```php
<?= \presetshare\yii2\likes\widgets\EntityLikeWidget::widget([
    'model' => $post,
    'customClass' => 'my-entity-like-button', // default null
    'buttonText' => '❤️', // default 👍
    'registerJS' => true, // default true
    'registerCSS' => true // default false
]); ?>
```
Events
```php
<?php
// EntityLike::EVENT_AFTER_FIRST_LIKE_TOGGLE
// EntityLike::EVENT_AFTER_LIKE_TOGGLE

\yii\base\Event::on(
    \presetshare\yii2\likes\models\EntityLike::class,
    \presetshare\yii2\likes\models\EntityLike::EVENT_AFTER_FIRST_LIKE_TOGGLE,
    function (\presetshare\yii2\likes\events\EntityLikeEvent $event) {

        $entityLike = $event->sender;

        $likerId = $entityLike->user_id;
        $entityAuthorId = $event->entityAuthorId;
        $entityId = $entityLike->entity_id;
        $action = $event->action; // like/unlike
        $entityClass = $event->entityClass; // e.g. \app\models\Post

        if ($likerId == $entityAuthorId) return;

        $liker = \app\models\User::findOne($likerId);
        $entityAuthor = \app\models\User::findOne($entityAuthorId);

        if ($entityClass == \app\models\Post::class) {
            $post = \app\models\Post::findOne($entityId);

            \Yii::info(
                'First ' . $action . ' for post ' . $post->name .
                ' from user ' . $liker->username . ' to user ' . $entityAuthor->username);
        }
    }
);

\yii\base\Event::on(
    \presetshare\yii2\likes\models\EntityLike::class,
    \presetshare\yii2\likes\models\EntityLike::EVENT_AFTER_LIKE_TOGGLE,
    function (\presetshare\yii2\likes\events\EntityLikeEvent $event) {

        $entityLike = $event->sender;

        $likerId = $entityLike->user_id;
        $entityAuthorId = $event->entityAuthorId;
        $entityId = $entityLike->entity_id;
        $action = $event->action; // like/unlike
        $entityClass = $event->entityClass; // e.g. \app\models\Post

        if ($likerId == $entityAuthorId) return;

        $liker = \app\models\User::findOne($likerId);
        $entityAuthor = \app\models\User::findOne($entityAuthorId);

        if ($entityClass == \app\models\Post::class) {
            $post = \app\models\Post::findOne($entityId);

            \Yii::info(
                $action . ' for post ' . $post->name .
                ' from user ' . $liker->username . ' to user ' . $entityAuthor->username);
        }
    }
);
```
Support
-------
[!["Buy Me A Coffee"](https://www.buymeacoffee.com/assets/img/custom_images/yellow_img.png)](https://www.buymeacoffee.com/dsgdnb)