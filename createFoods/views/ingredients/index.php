<?php

/* @var $this yii\web\View */

use yii\helpers\Url,
    yii\helpers\Html,
    yii\grid\GridView,
    yii\bootstrap\ActiveForm;

$this->title = 'Список ингредиентов';
$this->params['breadcrumbs'][] = ['label' => 'Блюда', 'url' => Url::to(['/createFoods/default/index'])];
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
    <a href="<?=Url::to(['/createFoods/ingredients/update'])?>" class="btn btn-default">Добавить ингредиент</a>
    <?php $form = ActiveForm::begin([
        'id' => 'ingredients-form',
        'options' => ['class' => 'form-horizontal']
    ]);
    echo GridView::widget([
        'dataProvider' => $listDataProvider,
        'showOnEmpty'=> false,
        'columns' => [
            [
                'class' => \yii\grid\SerialColumn::class,
                'headerOptions' => ['width' => '50'],
            ],
            [
                'attribute' => 'name',
                'label' => 'Наименование',
            ],
            [
                'attribute' => 'active',
                'label' => 'Активность',
                'format' => 'raw',
                'headerOptions' => ['width' => '100'],
                'filter' => [
                    0 => 'Нет',
                    1 => 'Да',
                ],
                'value' => function ($model, $key, $index, $column) {
                    $active = $model->{$column->attribute} === 1;
                    return \yii\helpers\Html::tag(
                        'span',
                        $active ? 'Да' : 'Нет',
                        [
                            'class' => 'label label-' . ($active ? 'success' : 'danger'),
                        ]
                    );
                },
            ],
            [
                'class' => yii\grid\CheckboxColumn::className(),
                'header'=>'Изменение активности',
                'headerOptions' => ['width' => '100'],
                'checkboxOptions' => function($model, $key, $index, $column) {
                    return ['value' => $model->id, 'checked' => ($model->active == 1 ? true : false), 'autocomplete' => 'off'];
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Кнопки действия',
                'headerOptions' => ['width' => '100'],
                'template' => '{update} {delete}',
            ]
        ]
    ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить изменения', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
