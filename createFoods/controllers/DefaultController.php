<?php
namespace app\modules\createFoods\controllers;

use Yii,
    yii\web\Controller,
    yii\web\Response,
    yii\helpers\Url,
    app\modules\createFoods\models\Food,
    app\modules\createFoods\models\Ingredients,
    yii\data\ActiveDataProvider;

class DefaultController extends Controller
{
    /**
     * Displays lists of ingredients and foods.
     *
     * @return string
     */
    public function actionIndex()
    {
        $admin = false;
        if (Yii::$app->user->identity && Yii::$app->user->identity->username == 'admin') {
            $admin = true;
        }
        $search = [];
        if (Yii::$app->request->post()) {
            if (!empty(Yii::$app->request->post('selection'))) {
                $checkboxList = Yii::$app->request->post('selection');
                if(count($checkboxList) == 0){
                    Yii::$app->session->setFlash('success', 'Не выбрано ни одного ингредиента.');
                }
                if(count($checkboxList) < 2){
                    Yii::$app->session->setFlash('success', 'Выберите больше ингредиентов.');
                }
                elseif(count($checkboxList) > 5){
                    Yii::$app->session->setFlash('success', 'Выбрано больше 5 ингредиентов.');
                }
                else{
                    $food = Food::find()->with('ingredients')->all();
                    $allMatches = false;
                    foreach ($food as $f){
                        $ingredients = $f->ingredients;
                        $searchIngredients = [];
                        $activeIngredients = [];
                        foreach ($ingredients as $ingredient){
                            if(in_array($ingredient->id, $checkboxList) !== false && $ingredient->active == Ingredients::statusActive){
                                $searchIngredients[] = $ingredient->name;
                            }
                            if($ingredient->active == Ingredients::statusActive){
                                $activeIngredients[] = $ingredient->name;
                            }
                        }
                        if(!empty($searchIngredients) && count($activeIngredients) == count($ingredients)){
                            if(count($ingredients) == count($searchIngredients)){
                                if(!$allMatches){
                                    $search = [];
                                }
                                $allMatches = true;
                                $search[] = [
                                    'id' => $f->id,
                                    'name' => $f->name
                                ];
                            }
                            elseif(!$allMatches && count($searchIngredients) > 1){
                                $search[] = [
                                    'id' => $f->id,
                                    'name' => $f->name,
                                    'ingredientsAllCount' => count($ingredients),
                                    'ingredientsSelected' => count($searchIngredients)
                                ];
                            }
                        }
                    }
                    if(!$allMatches){
                        usort($search, function($a, $b){
                            return ($a['ingredientsSelected'] - $b['ingredientsSelected']);
                        });
                    }
                }
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Ingredients::find()
                ->where(['active' => 1])
        ]);

        return $this->render('index', [
            'admin' => $admin,
            'listDataProvider' => $dataProvider,
            'search' => $search
        ]);
    }
}