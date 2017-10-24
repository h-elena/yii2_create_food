<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Url,
    yii\helpers\Html,
    yii\bootstrap\ActiveForm;

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => 'Блюда', 'url' => Url::to(['/createFoods/default'])];
$this->params['breadcrumbs'][] = ['label' => 'Список блюд', 'url' => Url::to(['/createFoods/food'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $session = Yii::$app->session;
    if (Yii::$app->session->hasFlash('success')) {
        echo '<div class="alert alert-success alert-dismissible" role="alert">'
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span>'
            . '</button>'
            . Yii::$app->session->getFlash('success') . '</div>';
    }
    if (Yii::$app->session->hasFlash('error')) {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">'
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'
            . '<span aria-hidden="true">&times;</span>'
            . '</button>'
            . Yii::$app->session->getFlash('error') . '</div>';
    }?>
    <?php if (!empty($errors)) {?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php
            if(is_array($errors)){
                foreach ($errors as $value) {
                    if(is_array($value)){
                        foreach ($value as $v) {
                            echo $v . '<br>';
                        }
                    }
                    else{
                        echo $value . '<br>';
                    }
                }
            }
            else{
                echo $errors;
            }
            ?>
        </div>
    <?php }?>
    <?php $form = ActiveForm::begin([
        'id' => 'ingredient-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-2\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>
    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'ingredientsCheckbox')->checkboxList($checkboxList)->label('Ингредиенты'); ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
