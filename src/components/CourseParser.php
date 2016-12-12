<?php
/**
 * Created by PhpStorm.
 * User: rosl
 * Date: 08.12.16
 * Time: 8:02
 */

namespace app\components;

use app\models\Currency;
use app\models\ExchangeDirection;
use Yii;
use yii\base\Object;

class CourseParser extends Object
{

    public $usdId = 'R01235';
    public $cbrDaily = 'http://www.cbr.ru/scripts/XML_daily.asp';

    public function init() {

        if(!$this->check('RUR', 'USD')){
            $this->usd();
        }

        if(!$this->check('USD', 'RUR')){
            $this->usd('USD', 'RUR');
        }


        parent::init();
    }

    protected function usd($from = 'RUR', $to = 'USD'){
        $xml = file_get_contents($this->cbrDaily);
        $array=json_decode(json_encode(simplexml_load_string($xml)),true);
        $index = array_search(['ID'=>$this->usdId], array_column($array['Valute'], '@attributes'));

        $course = (float)str_replace(',', '.', $array['Valute'][$index]['Value']);

        if($from == 'RUR' && $to == 'USD') {
            $course = 1/$course;
        }

        $model = \app\models\CourseParser::findOne(['from'=>$from, 'to'=>$to]);
        if(!$model){
            $model = new \app\models\CourseParser();
            $model->from = $from;
            $model->to = $to;
        }
        $model->value = $course;
        $model->updated = date('Y-m-d');
        $model->save();

        $directions = ExchangeDirection::find()
            ->joinWith([
                'from' => function ($query) {
                    $query->from(Currency::tableName() . ' c1');
                },
                'to' => function ($query) {
                    $query->from(Currency::tableName() . ' c2');
                },
            ])->where(['c1.type'=>$from])->andWhere(['c2.type'=>$to])->all();

        foreach($directions as $direction) {
            // if($direction)
            $direction->course = $course;
            $direction->save();
        }

        return true;

    }

    protected function coin($from = 'BITCOIN', $to = 'USD'){

    }

    protected function check($from, $to){
        return (bool)\app\models\CourseParser::findOne(['from'=>$from, 'to'=>$to, 'updated'=>date('Y-m-d')]);
    }

}