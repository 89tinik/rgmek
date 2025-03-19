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
    public function actionCreate($contract = '')
    {
        if ($draftContract = DraftContractChange::findOne(['user_id' => \Yii::$app->user->id, 'contract_id' => $contract])) {
            return $this->redirect(['update', 'id' => $draftContract->id]);
        }
        $data = ['id' => \Yii::$app->user->identity->id_db];
        $model = new DraftContractChange();
        $model->user_id = \Yii::$app->user->id;
        if (!empty($contract)) {
            $model->contract_id = $contract;
            $currentContract = Contract::findOne(['number' => $contract]);
            $data['contract'] = $currentContract->uid;
        }
        $contractsInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/pricechanging/draft', $data);
        if ($contractsInfo['success']) {
            $model->setDefault($contractsInfo['success']);

            if ($model->save()) {
                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                $errors = $model->getErrors();
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'errors' => $errors,
                ];
            }
        } else {
            $this->redirect(['err/one-c']);
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
        $modelForm = new DraftContractChangeForm();
        if (empty($model->temp_data)) {
            $data = ['id' => \Yii::$app->user->identity->id_db];
            $currentContract = Contract::findOne(['number' => $model->contract_id]);
            $data['contract'] = $currentContract->uid;
            $contractsInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/pricechanging/draft', $data);
            if ($contractsInfo['success']) {
                $model->setDefault($contractsInfo['success']);
                if (!$model->save()) {
                    $errors = $model->getErrors();
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'errors' => $errors,
                    ];
                }
            }
        }

        $modelForm->attributes = $model->attributes;
        if ($modelForm->load(Yii::$app->request->post())) {
            $modelForm->filesUpload = UploadedFile::getInstances($modelForm, 'filesUpload');
            if ($modelForm->filesUpload) {
                $fileChange = true;
                $postAttributes = ['filesUpload'];
            } else {
                $fileChange = false;
                $postAttributes = array_keys(Yii::$app->request->post($modelForm->formName(), []));
            }
            if ($modelForm->validate($postAttributes)) {
                if (!Yii::$app->request->isAjax) {
                    return $this->redirect(['send-draft', 'id' => $model->id]);
                }
                $model->attributes = $modelForm->attributes;
                $model->contract_price = preg_replace('/[\s\xC2\xA0]+/u', '', $model->contract_price);
                $model->contract_volume = preg_replace('/[\s\xC2\xA0]+/u', '', $model->contract_volume);
                $model->contract_price_new = preg_replace('/[\s\xC2\xA0]+/u', '', $model->contract_price_new);
                $model->contract_volume_new = preg_replace('/[\s\xC2\xA0]+/u', '', $model->contract_volume_new);
                if ($fileChange) {
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
            } else {
                $errors = $modelForm->getErrors();
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'errors' => $errors,
                    ];
                }
                $errorMessages = [];
                $errorAttributes = [];
                foreach ($errors as $attribute => $messages) {
                    $errorMessages[] = implode('<br>', $messages);
                    $errorAttributes[] = $attribute;
                }
                Yii::$app->session->setFlash('error', implode('<br>', $errorMessages));
                Yii::$app->session->setFlash('error-attr', implode(',', $errorAttributes));
            }
        }

        $model->markLast();
        $userDrafts = DraftContractChange::find()->where(['user_id' => \Yii::$app->user->id])->select('id, contract_id')->asArray()->all();
        return $this->render('update', [
            'model' => $modelForm,
            'userModel' => Yii::$app->user->identity,
            'contractsInfo' => json_decode($model->temp_data, true),
            'userDrafts' => ArrayHelper::map($userDrafts, 'contract_id', 'id')
        ]);

    }

    public function actionSendDraft($id)
    {
        $model = $this->findModel($id);
        $arrayModelAttributesto1C = $model->getArrayModelAttributesto1C();
        $data = ['id' => \Yii::$app->user->identity->id_db];

        $currentContract = Contract::findOne(['full_name' => '№ ' . $model->contract_id]);
        $data['contract'] = $currentContract->uid;

        $contractsInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/pricechanging/draft', $data);
        $sendData = array_filter($contractsInfo['success'], function ($key) {
            return strpos($key, 'List') === false;
        }, ARRAY_FILTER_USE_KEY);
        $listArr = ['source_funding', 'basis_purchase', 'contract_type', 'contract_id'];
        foreach ($arrayModelAttributesto1C as $attribute => $oneC) {
            if (in_array($attribute, $listArr)) {
                $sendData[$oneC] = $this->get1CId($contractsInfo['success'][$oneC . 'List']['item'], $model->$attribute);
            } else {
                if (is_array($oneC)) {
                    $sendData[$oneC[0]][$oneC[1]] = $model->$attribute;
                } else {
                    $sendData[$oneC] = $model->$attribute;
                }
            }
        }

        $xmlData = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Request xmlns="http://rgmek.ru/contractPriceChanging" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"></Request>');
        $this->arrayToXml($sendData, $xmlData);
        $xmlString = $xmlData->asXML();

        $result = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/pricechanging/', $xmlString, false, 'POST', true);

        if ($result['success'] && $messageId = Messages::createMessageFromDraft($model, $currentContract->id)) {
            $messageModel = Messages::findOne(['id' => $messageId]);
            $messageModel->sendAdminNoticeEmail('Заявление на заключение контракта (договора) энергоснабжения на следующий период');
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
