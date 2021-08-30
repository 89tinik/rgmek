<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'ru',
    'layout' => 'default',
    //'catchAll' => ['ajax/close'],
//    'defaultRoute' => 'main',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'FmFrzpt6h1XjcMViBc3vLOzCOuAnS_BB',
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'authTimeout' => 3600,
            'loginUrl' => ['login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
			//'transport' => [
			//	'class' => 'Swift_SmtpTransport',
			//	'host' => 'smtp.yandex.ru',
			//	'username' => 'prgmek@yandex.ru',
			//	'password' => 'njdnjayngrzxtpwn',
			//	'port' => '465',
			//	'encryption' => 'SSL',
			//],
			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => 'smtp.send.rgmek.ru',
				'username' => 'noreply@send.rgmek.ru',
				'password' => 'r@&hXR$X8G',
				'port' => '587',
			],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
					'maxFileSize'=>5120,
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'pattern' => '',
                    'route' => 'main/index',
                    'suffix' => '',
                ],
                'registration'=>'login/registration',
                'logout'=>'login/logout',
                'verification'=>'login/verification',
                'profile'=>'main/profile',
                'payment'=>'main/payment',
                //'<action:(profile | payment)>'=>'main/<action>',
            ],
        ],

    ],
    'modules' => [
        'sberbank' => [
            'class' => 'pantera\yii2\pay\sberbank\Module',
            'components' => [
                'sberbank' => [
                    'class' => pantera\yii2\pay\sberbank\components\Sberbank::class,

                    // время жизни инвойса в секундах (по умолчанию 20 минут - см. документацию Сбербанка)
                    // в этом примере мы ставим время 1 неделю, т.е. в течение этого времени покупатель может
                    // произвести оплату по выданной ему ссылке
                    'sessionTimeoutSecs' => 60 * 60 * 24 * 7,

                    // логин api мерчанта
                    'login' => 'T597168285295-api',

                    // пароль api мерчанта
                    'password' => 'T597168285295',

                    // использовать тестовый режим (по умолчанию - нет)
                    'testServer' => true,

                    // использовать двухстадийную оплату (по умолчанию - нет)
                    'registerPreAuth' => false
                ],
            ],

            // страница вашего сайта с информацией об успешной оплате
            'successUrl' => '/inner/pay-success',

            // страница вашего сайта с информацией о НЕуспешной оплате
            'failUrl' => '/inner/pay-fail',

            // обработчик, вызываемый по факту успешной оплаты
            'successCallback' => function($invoice) {
                // какая-то ваша логика, например
                $receipt = \app\models\Receipt::findOne($invoice->order_id);
                $receipt->setStatus('pay',$receipt->id);
                $receipt->sendToServer();
                //$client->sendEmail('Зачислена оплата по вашему заказу №' . $order->id);
                // .. и т.д.
            },

            // обработчик, вызываемый по факту НЕуспешной оплаты
            //'failCallback' => function($invoice) {
                // какая-то ваша логика, например
                //$order = \your\models\Order::findOne($invoice->order_id);
                //$client = $order->getClient();
                //$client->sendEmail('Ошибка при оплате по вашему заказу №' . $order->id);
                // .. и т.д.
            //},

            // необязательный callback для генерации uniqid инвойса, необходим
            // в том случае, если по каким-то причинам используемый по умолчанию
            // формат `#invoice_id#-#timestamp#` вам не подходит
            //'idGenerator' => function(Invoice $invoice, int $id) {
                // $id - это uniqid, сгенерированный по умолчанию
                // вместо него используем собственный алгоритм, например такой
                //return '000-AAA-' . $invoice->id;
            //},
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
