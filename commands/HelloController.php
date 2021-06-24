<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Contract;
use app\models\User;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\httpclient\Client;
use yii\httpclient\XmlParser;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
        return ExitCode::OK;
    }
    public function actionUpdate($message = 'hello world')
    {
        echo time() . "\n";
        $users = User::find()->all();
        foreach ($users as $user) {
            $contracts = new Client();
            $response = $contracts->createRequest()
                ->setMethod('GET')
                ->setUrl('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts')
                ->setData([
                    'id' => $user->id_db
                ])
                ->send();
            echo time() . "\n";
            if ($response->isOk) {
                $xml = new XmlParser();
                $result = $xml->parse($response);
                if ($result['Contract']) {
                    Contract::updateAllContract($user, $result['Contract']);
                    $user->with_date = $result['Withdate'];
                    $user->by_date = $result['Bydate'];
                    $user->full_name = $result['Name'];
                    $user->save();
                } else {
                    Contract::removeAllUserContract($user->id);
                }
            } else {
                \Yii::error('Не удалось связаться БД - повторите попытку позже.uid-'.$user->id_db.'\n');
                echo 'Не удалось связаться БД - повторите попытку позже.uid-'.$user->id_db;
            }


        }
        echo time() . "\n";
        return ExitCode::OK;
    }
}
