<?php

namespace backend\modules\adminx\controllers;

use backend\modules\adminx\components\AccessControl;
use backend\modules\adminx\models\Menu;
use backend\modules\adminx\models\Route;
use yii\web\Controller;

/**
 * Class MenuxController
 * Редактирование меню
 * @package app\modules\adminxx\controllers
 */
class MenuxController extends MainController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow'      => true,
                    'actions'    => [
                        'menu', 'get-menux'
                    ],
                    'roles'      => ['systemAdminxx', ],
                ],
            ],
        ];
        return $behaviors;
    }


    public function actionMenu()
    {
        $rout = new Route();
        $routes = $rout->getAppRoutes();
        return $this->render('menuEdit');
    }

    /**
     * AJAX Возвращает вид _menuxInfo для показа информации по выбранному
     * @return string
     */
    public function actionGetMenux($id = 0)
    {

        $model = Menu::findOne($id);
        if (isset($model)){
            return $this->renderAjax('@app/modules/adminx/views/menux/_menuxInfo', [
                'model' => $model,
            ]);
        } else {
            return 'Not found';
        }
    }



}