<?php
namespace app\modules\createFoods\controllers;

use Yii,
    yii\web\Controller,
    yii\web\Response,
    yii\helpers\Url,
    yii\filters\AccessControl,
    app\modules\createFoods\models\Ingredients,
    yii\data\ActiveDataProvider;

class IngredientsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return (Yii::$app->user->identity->username == 'admin');
                        }
                    ]
                ]
            ],
        ];
    }

    /**
     * Displays list of ingredients.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity && Yii::$app->user->identity->username != 'admin') {
            //return $this->redirect(Url::toRoute('/createFoods/default/index'));
        }

        if (Yii::$app->request->post()) {
            if(!empty(Yii::$app->request->post('selection'))){
                $checkboxList = Yii::$app->request->post('selection');
                $ingredients = Ingredients::find()
                    ->asArray()
                    ->all();
                $ingredientsIdsActive = [];
                $ingredientsIdsNoActive = [];
                foreach ($ingredients as $ingredient){
                    if(in_array($ingredient['id'], $checkboxList)){
                        if($ingredient['active'] == Ingredients::statusDeactive){
                            $ingredientsIdsActive[] = $ingredient['id'];
                        }
                    }
                    else{
                        if($ingredient['active'] == Ingredients::statusActive){
                            $ingredientsIdsNoActive[] = $ingredient['id'];
                        }
                    }
                }
                if(count($ingredientsIdsActive) > 0) {
                    Ingredients::updateAll(['active' => Ingredients::statusActive], ['in', 'id', $ingredientsIdsActive]);
                }
                if(count($ingredientsIdsNoActive) > 0) {
                    Ingredients::updateAll(['active' => Ingredients::statusDeactive], ['in', 'id', $ingredientsIdsNoActive]);
                }
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Ingredients::find()
        ]);

        return $this->render('index', [
            'listDataProvider' => $dataProvider
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionUpdate()
    {
        $model = new Ingredients();
        if (Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if (Yii::$app->request->get('id') && is_numeric(Yii::$app->request->get('id'))) {
                    if ($ingredients = Ingredients::findById((int)Yii::$app->request->get('id'))) {
                        $ingredients->load(Yii::$app->request->post());
                        if($ingredients->update()){
                            Yii::$app->session->setFlash('success', 'Ингредиент обновлен');
                            return $this->redirect(Url::toRoute('/createFoods/ingredients'));
                        }
                        else{
                            return $this->render('update', [
                                'model' => $ingredients,
                                'errors' => $ingredients->errors,
                                'title' => (!empty(Yii::$app->request->get('id')) ? 'Редактировать ингредиент' : 'Добавить ингредиент')
                            ]);
                        }
                    }
                }
                if(!$model::findByName($model->name)) {
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Ингредиент добавлен');
                        return $this->redirect(Url::toRoute('/createFoods/ingredients'));
                    }
                    else{
                        return $this->render('update', [
                            'model' => $model,
                            'errors' => $model->errors,
                            'title' => (!empty(Yii::$app->request->get('id')) ? 'Редактировать ингредиент' : 'Добавить ингредиент')
                        ]);
                    }
                }
                else{
                    Yii::$app->session->setFlash('error', 'Такой ингредиент уже есть.');
                }
            }
            else{
                return $this->render('update', [
                    'model' => $model,
                    'errors' => $model->errors,
                    'title' => (!empty(Yii::$app->request->get('id')) ? 'Редактировать ингредиент' : 'Добавить ингредиент')
                ]);
            }
        }

        if (Yii::$app->request->get('id') && is_numeric(Yii::$app->request->get('id'))) {
            if ($ingredients = Ingredients::findById((int)Yii::$app->request->get('id'))) {
                $model = $ingredients;
                if(!empty(Yii::$app->request->post())){
                    $model->load(Yii::$app->request->post());
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'title' => (!empty(Yii::$app->request->get('id')) ? 'Редактировать ингредиент' : 'Добавить ингредиент')
        ]);
    }

    /**
     * @return Response
     */
    public function actionDelete()
    {
        if (Yii::$app->request->get('id') && is_numeric(Yii::$app->request->get('id'))) {
            if($ingredients = Ingredients::findById((int)Yii::$app->request->get('id'))) {
                Yii::$app->session->setFlash('success', 'Ингредиент удален');
                $ingredients->delete();
            }
        }
        return $this->redirect(Url::toRoute('/createFoods/ingredients'));
    }
}