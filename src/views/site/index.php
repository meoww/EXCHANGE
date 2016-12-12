<?php
use app\assets\NgAppAsset;
use yii\helpers\Url;
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = Yii::$app->name;
Yii::$app->formatter->locale = 'ru-RU';
NgAppAsset::register($this); ?>
<div class="bid" ng-app="ExchangeApp">
    <div class="container" ng-controller="FormController">
        <div class="bid-block">
            <div class="info-wrapper">
                <div class="info">
                    <div class="mob-info">
                        <div class="capt">1 USD = 62.35 RUR, 1 BTC = 614.846 USD, 1 LTC = 3.8435 USD, 1 ETH = 12.51 USD</div>
                        <label>У вас есть</label>
                        <select>
                            <?php foreach($currency as $item): ?>
                            <option data-image="<?=$item->getImage()?$item->getImage()->getUrl('15x'):''?>"><?=$item->title?></option>
                            <?php endforeach; ?>
                        </select>
                        <label>Можете олучить</label>
                        <select>
                            <option data-image="./img/visa-ico.png">Visa/MasterCard RUB</option>
                        </select>
                        <div class="reserve">Резерв 2 939 111.55 RUR</div>
                        <div class="unit">1 BTC-е USD = 60.9807 QIWI RUR</div>
                    </div>
                    <div class="col-1">
                        <div class="head">У Вас есть</div>
                        <div class="rows">

                            <div ng-repeat="item in currencies" class="row value" ng-class="{active: activeCurrency.id == item.id}" ng-mouseenter="changeCurrency(item)">
                                <div class="image"><div><img ng-src="{{item.ajaxIcon}}" alt=""></div></div>
                                <div class="amount">1.0000 {{item.title}} {{item.type}}</div>
                                <div class="clearfix"></div>
                            </div>

                        </div>
                    </div>

                    <div class="col-2">
                        <div class="head">Вы можете получить</div>
                        <div class="rows">

                            <div class="row" ng-repeat="direction in filteredDirections = (directions | filter:{'currency_from': activeCurrency.id}:true)" ng-class="{active:directionActive.id == direction.id}" ng-mouseenter="changeDirection(direction)">
                                <div class="image"><div><img ng-src="{{direction.ajaxIcon}}" alt=""></div></div>
                                <div class="amount">{{direction.course}} {{direction.currencyTitle}} {{direction.currencyType}}</div>
                                <div class="clearfix"></div>
                            </div>

                        </div>
                    </div>
                        <div class="col-3">
                            <div class="head">Получаете Резерв</div>
                            <div class="rows">
                                <div class="row" ng-repeat="reserve in filteredDirections" ng-class="{active:directionActive.id == reserve.id}" ng-mouseenter="changeDirection(reserve)">
                                    <div class="reserve">{{reserve.currencyReserve}}</div>
                                </div>
                            </div>
                        </div>
                    <div class="clearfix"></div>
                </div>
            </div><!-- /.info -->
            <form class="form order-form" action="<?=Url::to(['site/process-order'])?>">
                <input id="form-token" type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->csrfToken?>"/>
                <div class="head">Оформить заявку</div>
                <div class="fields">
                    <div class="row">
                        <select id="cur_from" name="exchange_from_id">
                            <?php foreach($currency as $item): ?>
                                <option value="<?=$item->id?>" data-image="<?=$item->getImage()?$item->getImage()->getUrl('15x'):''?>"><?=$item->title?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="amount">
                            <input type="text" name="from_value" id="from_value_input" ng-change="exchange_to = countExchangeResult()" ng-model="exchange_from" placeholder="min {{directionActive.min}}" />
                            <div class="currency">{{directionActive.from.type}}</div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="row">
                        <select id="cur_to" name="exchange_to_id">
                            <?php foreach($currency_all as $item): ?>
                                <option value="<?=$item->id?>" data-image="<?=$item->getImage()?$item->getImage()->getUrl('15x'):''?>"><?=$item->title?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="amount">
                            <input type="text" name="to_value" id="to_value_input" ng-model="exchange_to" placeholder="0" />
                            <div class="currency">{{directionActive.to.type}}</div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="hint">По курсу: <span id="form_course"></span> 1.0000 {{directionActive.from.type}} {{directionActive.from.title}} = {{directionActive.course}} {{directionActive.to.type}} {{directionActive.to.title}}</div>
                    <div class="row">
                        <input type="text" name="card" placeholder="Номер карты" class="full" />
                    </div>
                    <div class="row">
                        <input type="text" name="bank" placeholder="Название банка" class="full" />
                    </div>
                    <div class="row">
                        <input type="text" name="fio" placeholder="ФИО отправителя" class="full" />
                    </div>
                    <div class="row">
                        <input type="text" name="wallet" placeholder="Кошелек для получения" class="full" />
                    </div>
                    <div class="row">
                        <input type="text" name="email" placeholder="Ваш Email" class="full" />
                    </div>
                    <input type="hidden" name="ip" value="<?=Yii::$app->request->getUserIP()?>">
                    <div class="agree">
                        <input type="checkbox" id="ch" /> <label for="ch">Я согласен с правилами обмена</label>
                    </div>
                    <div class="control">
                        <button>Перейти к оплате</button>
                    </div>
                </div>
            </form><!-- /.form -->
            <div class="clearfix"></div>
        </div>
        <div class="link">
            <a href="#">&nbsp;</a>
        </div>
    </div>
</div>

<div class="info-block">
    <div class="container">
        <div class="block scrollbar">
            <div class="title">Последние обмены</div>
            <div class="last-changes">
                <?php foreach($orders as $order): ?>
                <div class="last-change">
                    <div class="transaction">
                        <div>
                            <div class="image"><?=$order->exchange->from->getImage() ? Html::img($order->exchange->from->getImage()->getUrl()) : ''?></div>
                            <div class="name"><?=$order->exchange->from->title?></div>
                            <div class="clearfix"></div>
                        </div>
                        <div>
                            <div class="image"><?=$order->exchange->to->getImage() ? Html::img($order->exchange->to->getImage()->getUrl()) : ''?></div>
                            <div class="name"><?=$order->exchange->to->title?></div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="info"><?=$order->getLocation('img')?> <?=$order->getLocation('name')?>, 2 часа назад</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="block">
            <div class="title">Статистика</div>
            <div class="stat">
                <div class="counter">0</div>
                <div class="name">Зарегистрировано пользователей</div>
            </div>
        </div>
        <div class="block">
            <div class="title">Отзывы</div>
            <div class="comments owl-carousel">
                <?php foreach($testimonials as $testimonial): ?>
                <div class="item">
                    <div class="avatar"><?=$testimonial->getImage() ? Html::img($testimonial->getImage()->getUrl('92x92')) : ''?></div>
                    <div class="name"><?=$testimonial->name?></div>
                    <div class="date"><?=Yii::$app->formatter->asDate($testimonial->date); ?></div>
                    <div class="text"><?=$testimonial->content?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="link"><a href="#" id="comment_dialog_btn">Оставить отзыв</a></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div><!-- /.info-block -->

<div class="how-work">
    <div class="container">
        <div class="title">Как это работает?</div>
        <div class="descr">Конвертируйте валюту в другую с наименьшими потерями!</div>
        <div class="text scrollbar">
            <div class="text-wrapper">
                <?=\yii\helpers\Html::decode(\app\models\Settings::findOne(['slug'=>'how_it_works'])['content'])?>
            </div>
        </div>
    </div>
</div><!-- /.how-work -->


<div id="comment_dialog">
    <div class="d-title">Оставить отзыв</div>
    <form action="<?=Url::to(['site/testimonial'])?>" method="post" class="ajax-form">
        <p>
            <label for="avatar">Ваше фото</label>
        <input type="file" name="avatar" id="avatar">
        </p>
        <input type="text" placeholder="Как Вас зовут" name="name" />
        <input type="text" placeholder="Email" name="email" />
        <textarea placeholder="Ваш отзыв" name="content"></textarea>
        <input type="hidden" name="enabled" value="1">
        <input type="hidden" name="date" value="<?=date('Y-m-d')?>">
        <input type="submit" value="Отправить" />
    </form>
</div>