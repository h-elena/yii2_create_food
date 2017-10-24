<?php
namespace app\modules\createFoods\controllers;

use Yii,
    yii\web\Controller,
    yii\web\Response,
    yii\helpers\Url,
    yii\helpers\ArrayHelper,
    yii\filters\AccessControl,
    app\modules\createFoods\models\Food,
    app\modules\createFoods\models\Ingredients,
    yii\data\ActiveDataProvider;

class FoodController extends Controller
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
     * Displays list of foods.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Food::find()
        ]);

        return $this->render('index', [
            'listDataProvider' => $dataProvider
        ]);
    }

    /**
     * @return string
     */
    public function actionUpdate()
    {
        $model = new Food();
        $checkboxList = ArrayHelper::map(Ingredients::find()
            ->select(['id', 'name'])
            ->andWhere('active='.Ingredients::statusActive)
            ->all(), 'id', 'name');

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->ingredientsCheckbox = Yii::$app->request->post('Food')['ingredientsCheckbox'];
            if(empty($model->ingredientsCheckbox)){
                Yii::$app->session->setFlash('error', 'Не выбраны ингредиенты.');
                return $this->render('update', [
                    'model' => $model,
                    'title' => (!empty(Yii::$app->request->get('id')) ? 'Редактировать блюдо' : 'Добавить блюдо'),
                    'checkboxList' => $checkboxList
                ]);
            }
            else{
                if ($model->validate()) {
                    if (Yii::$app->request->get('id') && is_numeric(Yii::$app->request->get('id'))) {
                        if ($food = Food::findById((int)Yii::$app->request->get('id'))) {
                            $food->load(Yii::$app->request->post());
                            $food->ingredientsCheckbox = Yii::$app->request->post('Food')['ingredientsCheckbox'];
                            if($food->update()){
                                if($food->updateLinks()){
                                    Yii::$app->session->setFlash('success', 'Блюдо обновлено');
                                    return $this->redirect(Url::toRoute('/createFoods/food'));
                                }
                            }
                            else{
                                if($food->updateLinks()){
                                    Yii::$app->session->setFlash('success', 'Блюдо обновлено');
                                    return $this->redirect(Url::toRoute('/createFoods/food'));
                                }
                                return $this->render('update', [
                                    'model' => $food,
                                    'errors' => $food->errors,
                                    'title' => (!empty(Yii::$app->request->get('id')) ? 'Редактировать блюдо' : 'Добавить блюдо'),
                                    'checkboxList' => $checkboxList
                                ]);
                            }
                        }
                    }
                    if(!$model::findByName($model->name)) {
                        if ($model->saveAll()) {
                            Yii::$app->session->setFlash('success', 'Блюдо добавлено');
                            return $this->redirect(Url::toRoute('/createFoods/food'));
                        }
                        else{
                            return $this->render('update', [
                                'model' => $model,
                                'errors' => $model->errors,
                                'title' => (!empty(Yii::$app->request->get('id')) ? 'Редактировать блюдо' : 'Добавить блюдо'),
                                'checkboxList' => $checkboxList
                            ]);
                        }
                    }
                    else{
                        Yii::$app->session->setFlash('error', 'Такое блюдо уже есть.');
                    }
                }
            }
        }

        if (Yii::$app->request->get('id') && is_numeric(Yii::$app->request->get('id'))) {
            if ($food = Food::findById((int)Yii::$app->request->get('id'))) {
                $model = $food;
                $model->ingredientsCheckbox = $food->ingredients;
                if(!empty(Yii::$app->request->post())){
                    $model->load(Yii::$app->request->post());
                    $model->ingredientsCheckbox = Yii::$app->request->post('Food')['ingredientsCheckbox'];
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'title' => (!empty(Yii::$app->request->get('id')) ? 'Редактировать блюдо' : 'Добавить блюдо'),
            'checkboxList' => $checkboxList
        ]);
    }

    /**
     * @return Response
     */
    public function actionDelete()
    {
        if (Yii::$app->request->get('id') && is_numeric(Yii::$app->request->get('id'))) {
            $model = new Food();
            if($model->deleteFood()){
                return $this->redirect(Url::toRoute('/createFoods/food'));
            }
        }
        return $this->redirect(Url::toRoute('/createFoods/food'));
    }
}