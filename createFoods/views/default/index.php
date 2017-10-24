<?php

/* @var $this yii\web\View */

use yii\helpers\Url,
    yii\helpers\Html,
    yii\grid\GridView,
    yii\bootstrap\ActiveForm;

$this->title = 'Блюда';
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
    <?php if($admin){?>
        <a href="<?=Url::to(['/createFoods/ingredients'])?>" class="btn btn-default">Список ингредиентов</a>
        <a href="<?=Url::to(['/createFoods/food'])?>" class="btn btn-success">Список блюд</a>
    <?php }?>
    <?php if(!empty($search)) {?>
        <h2><strong>Найдены следующие блюда:</strong></h2>
        <ol>
            <?php foreach ($search as $key => $s) {?>
                <li><?php echo $s['name']?></li>
            <?php } ?>
        </ol>
        <br><br>
    <?php }?>
    <?php $form = ActiveForm::begin([
        'id' => 'ingredients-form',
        'options' => ['class' => 'form-horizontal']
    ]);
    echo '<h4>Для приготовления блюда выберите не более 5 ингредиентов.</h4>';
    echo GridView::widget([
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
                'class' => yii\grid\CheckboxColumn::className(),
                'checkboxOptions' => function($model) {
                    return [
                        'value' => $model->id,
                        'checked' => (!empty(Yii::$app->request->post('selection')) &&  in_array($model->id, Yii::$app->request->post('selection')) ? true : false),
                        'autocomplete' => 'off'
                    ];
                },
            ]
        ],
    ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton('Найти блюда из выбранных ингредиентов', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
