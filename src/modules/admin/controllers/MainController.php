<?php

namespace app\modules\admin\controllers;

use app\models\Currency;
use app\models\ExchangeDirection;
use app\models\Order;
use nullref\admin\components\AdminController;
use nullref\admin\models\Admin;
use nullref\admin\models\LoginForm;
use nullref\admin\models\PasswordResetForm;
use Yii;
use nullref\admin\components\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 *
 */
class MainController extends AdminController
{
    public $dashboardPage = ['/admin'];

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'login', 'index', 'error'],
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['index', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }

    public function actionIndex($currency_from = 3)
    {
        $post = Yii::$app->request->post();
        if($post){
            ExchangeDirection::deleteAll(['currency_from'=>$currency_from]);
            foreach($post['directions'] as $direction){
                $direction['currency_from'] = $currency_from;
                $model = new ExchangeDirection();
                $model->load($direction);
                $model->setAttributes($direction);
                $model->save();
            }
        }

        $directions = ExchangeDirection::find()->where(['currency_from'=>$currency_from])->all();
        $currency = Currency::find()->all();

        $current = Currency::findOne(['id'=>$currency_from]);
        $orders = Order::findAll(['status'=>1]);
        
        return $this->render('index', [
            'directions'=>$directions,
            'currency'=>$currency,
            'cur'=>$current,
            'orders'=>$orders,
        ]);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'nullref\admin\actions\ErrorAction',
            ],
        ];
    }

    public function actionLogin()
    {
        $this->layout = 'base';

        $model = new LoginForm();

        if (!Yii::$app->get('admin')->isGuest) {
            return $this->redirect($this->dashboardPage);
        }

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->login();
                return $this->redirect($this->dashboardPage);
            } else {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return $model->errors;
                }
            }
        }
        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->get('admin')->logout();

        return $this->goHome();
    }

    public function actionReset($id, $token = false)
    {
        /** @var Admin $user */
        $user = Admin::findOne($id);
        if (($token !== false) && (isset($user)) && ($user->passwordResetToken === $token) && ($user->passwordResetExpire >= time())) {
            $model = new PasswordResetForm();
            if ($model->load(Yii::$app->getRequest()->post()) && $model->changePassword($user)) {
                Yii::$app->user->login($user);
                return $this->redirect(['index']);
            }
            return $this->render('password-reset', [
                'model' => $model,
            ]);
        }
        throw new NotFoundHttpException();
    }
}