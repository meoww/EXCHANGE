<?php

namespace app\modules\admin;

use nullref\admin\interfaces\IMenuBuilder;
use nullref\core\components\Module as BaseModule;
use nullref\core\interfaces\IAdminModule;
use Yii;
use yii\base\InvalidConfigException;

class Module extends \nullref\admin\Module implements IAdminModule
{
    public static function getAdminMenu()
    {
        return [
            'label' => 'Панель управления',
            'items'=> [
								[
										'label' => 'Управление заявками',
										'url' => ['/admin/main'],
								],
                [
                    'label' => 'Администраторы',
                    'url' => ['/admin/user'],
                ],
                [
                    'label' => 'Валюта',
                    'url' => ['/admin/currency'],
                ],
								[
										'label' => 'Направления обмена',
										'url' => ['/admin/direction'],
								],
								[
										'label' => 'Баннеры',
										'url' => ['/admin/banner'],
								],
								[
										'label' => 'Рефералы',
										'url' => ['/admin/referal'],
								],
								[
										'label' => 'Новости',
										'url' => ['/admin/news'],
								],
								[
										'label' => 'Блог',
										'url' => ['/admin/article'],
								],
								[
										'label' => 'Страницы',
										'url' => ['/admin/page'],
								],
                [
                    'label' => 'Отзывы',
                    'url' => ['/admin/testimonial'],
                ],
								[
										'label' => 'Пользователи',
										'url' => ['/admin/site-user'],
								],
								[
										'label' => 'Система скидок',
										'url' => ['/admin/bonus'],
								],
                [
                    'label' => 'Настройки',
                    'url' => ['/admin/settings'],
                ],
								[
										'label' => 'Контакты',
										'items'=>[
												[
														'label' => 'Email',
														'url' => ['/admin/credential/update?id=1'],
												],
												[
														'label' => 'Jabber',
														'url' => ['/admin/credential/update?id=2'],
												],
												[
														'label' => 'ICQ',
														'url' => ['/admin/credential/update?id=3'],
												],
												[
														'label' => 'Phone',
														'url' => ['/admin/credential/update?id=4'],
												],
										]
								],
								[
										'label' => 'Почта',
										'url' => ['/#'],
									'items'=>[
											[
													'label' => 'При регистрации',
													'url' => ['/admin/settings/update?id=2'],
											],
											[
													'label' => 'При новой заявке',
													'url' => ['/admin/settings/update?id=3'],
											],
											[
													'label' => 'При изменении статуса заявки',
													'url' => ['/admin/settings/update?id=4'],
											],
									]
								],

            ]
        ];
    }
}
