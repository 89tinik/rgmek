<?php

namespace app\controllers;

use app\models\Contract;
use app\models\Messages;
use SimpleXMLElement;
use Yii;
use app\models\DraftContract;
use app\models\DraftContractForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * DraftContractController implements the CRUD actions for DraftContract model.
 */
class DraftContractController extends BaseController
{
    /**
     * Creates a new DraftContract model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param string $contract
     * @return mixed
     */
    public function actionCreate($contract = '')
    {
        if ($draftContract = DraftContract::findOne(['user_id' => \Yii::$app->user->id, 'contract_id' => $contract])) {
            return $this->redirect(['update', 'id' => $draftContract->id]);
        }
        $data = ['id' => \Yii::$app->user->identity->id_db];
        $model = new DraftContract();
        $model->user_id = \Yii::$app->user->id;
        if (!empty($contract)) {
            $model->contract_id = $contract;
            $currentContract = Contract::findOne(['number' => $contract]);
            $data['contract'] = $currentContract->uid;
        }
        $contractsInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/conclusion/draft', $data);
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
     * Updates an existing DraftContract model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelForm = new DraftContractForm();
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
                $model->off_budget_value = preg_replace('/[\s\xC2\xA0]+/u', '', $model->off_budget_value);
                $model->budget_value = preg_replace('/[\s\xC2\xA0]+/u', '', $model->budget_value);
                $model->contract_volume_plane = preg_replace('/[\s\xC2\xA0]+/u', '', $model->contract_volume_plane);
                if ($fileChange) {
                    $folderId = $model->id;
                    $uploadDirectory = DraftContract::UPLOAD_FILES_FOLDER_PATH . $folderId;

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
        $userDrafts = DraftContract::find()->where(['user_id' => \Yii::$app->user->id])->select('id, contract_id')->asArray()->all();
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

        $currentContract = Contract::findOne(['number' => $model->contract_id]);
        $data['contract'] = $currentContract->uid;

        $contractsInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/conclusion/draft', $data);
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

        $xmlData = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Request xmlns="http://rgmek.ru/contractConclusion" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"></Request>');
        $this->arrayToXml($sendData, $xmlData);
        $xmlString = $xmlData->asXML();

        $result = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/conclusion/', $xmlString, false, 'POST', true);

        if ($result['success'] && $messageId = Messages::createMessageFromDraft($model, $currentContract->id)) {
            $messageModel = Messages::findOne(['id' => $messageId]);
            $messageModel->sendAdminNoticeEmail('Заявление на заключение контракта (договора) '.$model->contract_id.' энергоснабжения на следующий период');
            $model->send = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
            $model->save();
            // $model->delete();
            $this->redirect(['messages/update', 'id' => $messageId]);
        } else {
            $this->redirect(['update', 'id' => $id]);
        }
    }


    /**
     * Finds the DraftContract model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DraftContract the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DraftContract::findOne(['id' => $id, 'user_id' => \Yii::$app->user->identity->getId()])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGeneratePdf()
    {
        if (Yii::$app->request->isPost) {
            $model = $this->findModel(Yii::$app->request->post('draft'));
            $fileName = time() . ' Заявление.pdf';
            $model->generatePdf($fileName);

            $pdfPath = Yii::getAlias('@webroot/temp_pdf/' . $fileName);
            if (file_exists($pdfPath)) {
                return $this->asJson([
                    'status' => 'success',
                    'pdfUrl' => Yii::getAlias('@web') . '/web/temp_pdf/' . $fileName,
                ]);
            } else {
                return $this->asJson([
                    'status' => 'error',
                    'message' => 'PDF файл не найден',
                ]);
            }
        }

        return $this->asJson([
            'status' => 'error',
            'message' => 'Данные не получены',
        ]);
    }
}
