<?php

use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\View;

/** @var View $this */
/** @var ActiveRecord $model */
/** @var string $buttonText */
/** @var string $customClass */
/** @var bool $registerJS */
/** @var bool $registerCSS */

use presetshare\yii2\likes\helpers\EntityLikeHelper;
$url = Url::to('/entityLike/default/toggle');
?>

<button data-id="<?= EntityLikeHelper::getAlias($model); ?>_<?= $model->id; ?>"
        class="entity-like-button <?php echo $model->has_my_like ? 'active' : ''; ?> <?= $customClass; ?>">
    <span class="entity-like-button-text"><?= $buttonText; ?></span> <span class="entity-like-count"><?= $model->likes_count; ?></span>
</button>

<?php
$script = <<< JS
$('.entity-like-button').click(function () {
    var base = $(this);
    var id = $(this).attr('data-id');
    var entityAlias = id.substring(0, id.lastIndexOf("_"));
    var entityId = id.substring(id.lastIndexOf("_") + 1);
    
    $(this).addClass('entity-like-loading');
    $.ajax({
        type: 'POST',
        url: '{$url}',
        data: {
            'entity_alias': entityAlias,
            'entity_id': entityId
        },
        success: function (response) {
            if (response.newCount !== undefined) {
                $('.entity-like-count', base).text(response.newCount);
            }
            base.removeClass('entity-like-loading');
            base.toggleClass('active', response.action === 'like');
        },
        error: function (error) {
            base.removeClass('entity-like-loading');
        }
    });
});
JS;
$css = <<< CSS
.entity-like-button {
    cursor: pointer;
    background-color: transparent;
    display: inline-block;
    vertical-align: middle;
    user-select: none;
    padding: 0;
    font-size: 1rem;
    line-height: 1.5;
    border: 1px solid transparent;
    transition: ease .15s;
}

.entity-like-loading {
    pointer-events: none;
    opacity: .35;
}

.entity-like-button-text {
    filter: saturate(0);
    transition: ease .15s;
}
.entity-like-button.active .entity-like-button-text {
    filter: saturate(100%);
}
CSS;

if($registerJS) $this->registerJs($script, View::POS_READY, 'EntityLikeJS');
if($registerCSS) $this->registerCss($css, [], 'EntityLikeCSS');
?>