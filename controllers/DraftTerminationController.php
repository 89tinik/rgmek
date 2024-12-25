<?php

namespace app\controllers;

use app\models\Contract;
use app\models\Messages;
use SimpleXMLElement;
use Yii;
use app\models\DraftTermination;
use app\models\DraftTerminationForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DraftTerminationController implements the CRUD actions for DraftTermination model.
 */
class DraftTerminationController extends BaseController
{


    /**
     * Creates a new DraftTermination model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ($draftContract = DraftTermination::findOne(['user_id' => \Yii::$app->user->id])) {
            return $this->redirect(['update', 'id' => $draftContract->id]);
        }
        $model = new DraftTermination();
        $model->user_id = \Yii::$app->user->id;
        if ($model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        }

    }

    /**
     * Updates an existing DraftTermination model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->request->isAjax) {
            $data = ['id' => \Yii::$app->user->identity->id_db];
            $contractsInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/termination/draft', $data);

        }

        $model = $this->findModel($id);

        $modelForm = new DraftTerminationForm();
        $modelForm->attributes = $model->attributes;

        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            $fileChange = false;
            $model->attributes = $modelForm->attributes;
            $modelForm->filesUpload = UploadedFile::getInstances($modelForm, 'filesUpload');
            $model->contract_price = preg_replace('/[\s\xC2\xA0]+/u', '', $model->contract_price);
            $model->contract_volume_price = preg_replace('/[\s\xC2\xA0]+/u', '', $model->contract_volume_price);

            if ($modelForm->filesUpload) {
                $fileChange = true;
                $folderId = $model->id;
                $uploadDirectory = DraftTermination::UPLOAD_FILES_FOLDER_PATH . $folderId;

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

        return $this->render('update', [
            'model' => $modelForm,
            'userModel' => Yii::$app->user->identity,
            'contractsInfo' => $contractsInfo['success']
        ]);

    }

    public function actionSendDraft($id)
    {
        $model = $this->findModel($id);
        $data = ['id' => \Yii::$app->user->identity->id_db];

        $currentContract = Contract::findOne(['full_name' => '№ '.$model->contract_id]);
        $data['contract'] = $currentContract->uid;

        $contractsInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/termination/draft', $data);
        $sendData = array_filter($contractsInfo['success'], function ($key) {
            return strpos($key, 'List') === false;
        }, ARRAY_FILTER_USE_KEY);

        $sendData['ContractNumber'] = $currentContract->uid;
        $sendData['ContractPrice'] = $model->contract_price;
        $sendData['ProvidedServicesCost'] = $model->contract_volume_price;
        $sendData['ContactPerson4Request']['FullName'] = $model->contact_name;
        $sendData['ContactPerson4Request']['Phone'] = $model->contact_phone;
        $sendData['ContactPerson4Request']['Email'] = $model->contact_email;

        $xmlData = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Request xmlns="http://rgmek.ru/contractTermination" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"></Request>');
        $this->arrayToXml($sendData, $xmlData);
        $xmlString = $xmlData->asXML();

        $result = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/termination/', $xmlString, false, 'POST', true);

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
     * Finds the DraftTermination model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DraftTermination the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DraftTermination::findOne(['id' => $id, 'user_id' => \Yii::$app->user->identity->getId()])) !== null) {
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
