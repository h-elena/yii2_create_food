<?php

/* @var $this yii\web\View */

use yii\helpers\Url,
    yii\helpers\Html,
    yii\grid\GridView;

$this->title = 'Список блюд';
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
    <a href="<?=Url::to(['/createFoods/food/update'])?>" class="btn btn-default">Добавить блюдо</a>
    <?php echo GridView::widget([
        'dataProvider' => $listDataProvider,
        'columns' => [
            [
                'class' => \yii\grid\SerialColumn::class,
            ],
            [
                'attribute' => 'name',
                'label' => 'Наименование',
            ],
            [
                'label' => 'Ингредиенты',
                'format' => 'raw',
                'value' => function ($model) {
                    $text = $model->getIngredientsOfFood();
                    return $text;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Кнопки действия',
                'headerOptions' => ['width' => '100'],
                'template' => '{update} {delete}',
            ],
        ],
    ]);
    ?>
</div>
