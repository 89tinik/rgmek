<?php

namespace app\controllers;

use app\models\Contract;
use app\models\Messages;
use SimpleXMLElement;
use Yii;
use app\models\DraftContractChange;
use app\models\DraftContractChangeForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * DraftContractChangeController implements the CRUD actions for DraftContractChange model.
 */
class DraftContractChangeController extends BaseController
{


    /**
     * Creates a new DraftContractChange model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param string $contract
     * @return mixed
     */
    public function actionCreate($contract='')
    {
        if ($draftContract = DraftContractChange::findOne(['user_id' => \Yii::$app->user->id, 'contract_id' => $contract])) {
            return $this->redirect(['update', 'id' => $draftContract->id]);
        }
        $model = new DraftContractChange();
        $model->user_id = \Yii::$app->user->id;
        if (!empty($contract)){
            $model->contract_id = $contract;
        }
        if ($model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

    }

    /**
     * Updates an existing DraftContractChange model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->markLast();
        if (!Yii::$app->request->isAjax) {
            $data = ['id' => \Yii::$app->user->identity->id_db];
            if (!empty($model->contract_id)) {
                $currentContract = Contract::findOne(['number' => $model->contract_id]);
                $data['contract'] = $currentContract->uid;
            }
            $contractsInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/pricechanging/draft', $data);

        }

        if (empty($model->contract_id)){
            $index = array_search($contractsInfo['success']['ContractNumber'], array_column($contractsInfo['success']['ContractNumberList']['item'], 'id'));
            $model->contract_id = $contractsInfo['success']['ContractNumberList']['item'][$index]['description'];
            $model->save();
        }

        $modelForm = new DraftContractChangeForm();
        $modelForm->attributes = $model->attributes;

        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            $fileChange = false;
            $model->attributes = $modelForm->attributes;
            $modelForm->filesUpload = UploadedFile::getInstances($modelForm, 'filesUpload');
            $model->contract_price = preg_replace('/[\s\xC2\xA0]+/u', '', $model->contract_price);
            $model->contract_volume = preg_replace('/[\s\xC2\xA0]+/u', '', $model->contract_volume);
            $model->contract_price_new = preg_replace('/[\s\xC2\xA0]+/u', '', $model->contract_price_new);
            $model->contract_volume_new = preg_replace('/[\s\xC2\xA0]+/u', '', $model->contract_volume_new);

            if ($modelForm->filesUpload) {
                $fileChange = true;
                $folderId = $model->id;
                $uploadDirectory = DraftContractChange::UPLOAD_FILES_FOLDER_PATH . $folderId;

                if (!is_dir($uploadDirectory)) {
                    mkdir($uploadDirectory, 0775, true);
                }

                $oldFilesArr = json_decode($model->files, true) ?? [];
                $allFilesArr = $modelForm->uploadFiles($folderId, count($oldFilesArr));

                if ($oldFilesArr) {
                    $allFilesArr = array_merge($oldFilesArr, $allFilesArr);
                }
                if ($allFilesArr !== false) {
                    $model->files = json_encode($allFilesArr);
                }

            }

            if ($model->save()) {
                if (Yii::$app->request->isAjax) {
                    if ($fileChange) {
                        return $this->renderPartial('_uploaded-files', ['files' => $model->files, 'draft' => $model->id]);
                    } else {
                        return 'Обновлено';
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $errors = $model->getErrors();
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'errors' => $errors,
                ];
            }

        }

        $userDrafts = DraftContractChange::find()->where(['user_id' => \Yii::$app->user->id])->select('id, contract_id')->asArray()->all();

        return $this->render('update', [
            'model' => $modelForm,
            'userModel' => Yii::$app->user->identity,
            'contractsInfo' => $contractsInfo['success'],
            'userDrafts' => ArrayHelper::map($userDrafts, 'contract_id', 'id')
        ]);

    }

    public function actionSendDraft($id)
    {
        $model = $this->findModel($id);
        $data = ['id' => \Yii::$app->user->identity->id_db];

        $currentContract = Contract::findOne(['full_name' => '№ '.$model->contract_id]);
        $data['contract'] = $currentContract->uid;

        $contractsInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/pricechanging/draft', $data);
        $sendData = array_filter($contractsInfo['success'], function ($key) {
            return strpos($key, 'List') === false;
        }, ARRAY_FILTER_USE_KEY);

        $sendData['ContractNumber'] = $currentContract->uid;
        $sendData['IncludeVolumeInContract'] = $model->contract_volume_plane_include;
//        $sendData['DirectorPosition'] = $model->off_budget;
//        $sendData['DirectorFullName'] = $model->off_budget;
//        $sendData['DirectorOrder'] = $model->off_budget;
        $sendData['ContractPrice'] = $model->contract_price;
        $sendData['ContractVolume'] = $model->contract_volume;
        $sendData['ContractPriceNew'] = $model->contract_price_new;
        $sendData['ContractVolumeNew'] = $model->contract_volume_new;
        $sendData['ContactPerson4Request']['FullName'] = $model->contact_name;
        $sendData['ContactPerson4Request']['Phone'] = $model->contact_phone;
        $sendData['ContactPerson4Request']['Email'] = $model->contact_email;

        $xmlData = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Request xmlns="http://rgmek.ru/contractPriceChanging" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"></Request>');
        $this->arrayToXml($sendData, $xmlData);
        $xmlString = $xmlData->asXML();

        $result = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/pricechanging/', $xmlString, false, 'POST', true);

        if ($result['success'] && $messageId = Messages::createMessageFromDraft($model, $currentContract->id)) {
            $model->send = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
            $model->save();
            // $model->delete();
            $this->redirect(['messages/update', 'id' => $messageId]);
        } else {
            $this->redirect(['update', 'id' => $id]);
        }
    }

    /**
     * Finds the DraftContractChange model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DraftContractChange the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DraftContractChange::findOne(['id' => $id, 'user_id' => \Yii::$app->user->identity->getId()])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
