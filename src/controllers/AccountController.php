<?php
/**
 * Created by PhpStorm.
 * User: rosl
 * Date: 13.12.16
 * Time: 2:45
 */

namespace app\controllers;


use app\models\Banner;
use app\models\Currency;
use app\models\Order;
use app\models\Referal;
use app\models\UserWallet;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class AccountController extends Controller
{

	public $layout = 'account';

	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'rules' => [
								['allow' => true, 'actions' => ['index','security','autofill','referer','materials','forms'], 'roles' => ['@']],
								[
										'allow' => true,
										'actions' => ['order-history'],
										'roles' => ['@'],
								],
						],
				],
		];
	}

	public function actionIndex(){
		$orders = \Yii::$app->user->identity->getOrders()->where(['history'=>1])->all();

		return $this->render('index',[
				'orders'=>$orders
		]);
	}

	public function actionOrderHistory(){
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$orderId = \Yii::$app->request->post('id');
		$order = Order::findOne(['id'=>$orderId]);
		$order->history = 0;
		$order->save();
		return $order;
	}

	public function actionSecurity(){
		return $this->render('security');
	}

	public function actionAutofill(){

		if(\Yii::$app->request->post()){

			foreach(\Yii::$app->request->post()['currency'] as $id => $wallet){
				if($wallet){
					$model = UserWallet::findOne(['currency_id'=>$id, 'user_id'=>\Yii::$app->user->id]);
					if(!$model){
						$model = new UserWallet();
						$model->user_id = \Yii::$app->user->id;
						$model->currency_id = $id;
					}
					$model->wallet = $wallet;
					$model->save();
				}
			}

		}

		return $this->render('autofill');
	}

	public function actionReferer(){

		$referal = Referal::findOne(['user_id'=>\Yii::$app->user->id]);
		//var_dump($referal, \Yii::$app->user->id);die;

		$exchanges = \Yii::$app->user->identity->getCountRefExchanges();
		$incoming = $referal->statistic->incoming;

		return $this->render('referer',[
				'exchanges'=>$exchanges,
				'incoming'=>$incoming
		]);
	}

	public function actionMaterials(){

		$banners = Banner::find()->all();

		return $this->render('materials',[
				'banners'=>$banners
		]);
	}

	public function ranger($url){
		return getimagesize($url);
		return [$width, $height];
	}

	public function actionForms(){
		return $this->render('forms');
	}

}