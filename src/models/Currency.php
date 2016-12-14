<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "currency".
 *
 * @property integer $id
 * @property string $title
 * @property string $icon
 * @property string $reserve
 * @property string $type
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency';
    }

    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reserve'], 'number'],
            [['title', 'type', 'wallet'], 'string', 'max' => 255],
            [['icon'], 'file'],
        ];
    }

    public function fields()
		{
			return ArrayHelper::merge(parent::fields(), [
					'ajaxIcon' => function($model){
						return $model->getImage()->getUrl();
					},
					/*'directions' => function($model){
						return $model->getDirections()->asArray()->all();
					}*/
					'directionsCount' => function($model){
						return (int)$model->getDirections()->count();
					},
					'fields'=> function($model){
						return $model->getFields()->asArray()->all();
					}
			]); // TODO: Change the autogenerated stub
		}

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'icon' => 'Иконка',
            'reserve' => 'Резерв',
            'type' => 'Тип',
        ];
    }

    public function getDirections() {
        return $this->hasMany(ExchangeDirection::className(), ['currency_from'=>'id']);
    }

		public function getWallet(){
			return $this->hasOne(UserWallet::className(), [
					'currency_id'=>'id'
			])->where(['user_id'=>Yii::$app->user->id]);
		}

		public function getFields(){
				return $this->hasMany(CurrencyFields::className(), [
						'currency_id'=>'id'
				]);
		}

}
