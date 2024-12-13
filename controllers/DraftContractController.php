<?php

namespace app\controllers;

use app\models\Contract;
use app\models\Messages;
use SimpleXMLElement;
use Yii;
use app\models\DraftContract;
use app\models\DraftContractForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DraftContractController implements the CRUD actions for DraftContract model.
 */
class DraftContractController extends BaseController
{


    /**
     * Displays a single DraftContract model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DraftContract model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ($draftContract = DraftContract::findOne(['user_id' => \Yii::$app->user->id])) {
            return $this->redirect(['update', 'id' => $draftContract->id]);
        }
        $model = new DraftContract();
        $model->user_id = \Yii::$app->user->id;
        if ($model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
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
        if (!Yii::$app->request->isAjax) {
            $data = ['id' => \Yii::$app->user->identity->id_db];
            $contractsInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/conclusion/draft', $data);

        }

        $model = $this->findModel($id);

        $modelForm = new DraftContractForm();
        $modelForm->attributes = $model->attributes;

        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            $fileChange = false;
            $model->attributes = $modelForm->attributes;
            $modelForm->filesUpload = UploadedFile::getInstances($modelForm, 'filesUpload');

            if ($modelForm->filesUpload) {
                $fileChange = true;
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

        $currentContract = Contract::findOne(['number' => $model->contract_id]);
        $data['contract'] = $currentContract->uid;

        $contractsInfo = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/conclusion/draft', $data);
        $sendData = array_filter($contractsInfo['success'], function ($key) {
            return strpos($key, 'List') === false; // Удаляем элементы, где ключ содержит 'List'
        }, ARRAY_FILTER_USE_KEY);

        $sendData['ContractNumber'] = $currentContract->uid;
        $sendData['ContractType'] = $this->get1CId($contractsInfo['success']['ContractTypeList']['item'], $model->contract_type);
        $sendData['WithDate'] = $model->from_date;
        $sendData['ByDate'] = $model->to_date;
        $sendData['Basis'] = $this->get1CId($contractsInfo['success']['BasisList']['item'], $model->basis_purchase);
        $sendData['PurchaseIdentificationCode'] = $model->ikz;
        $sendData['ContractPrice'] = $model->contract_price;
        $sendData['IncludeVolumeInContract'] = $model->contract_volume_plane_include;
        $sendData['FundingSource'] = $this->get1CId($contractsInfo['success']['FundingSourceList']['item'], $model->source_funding);
        $sendData['ExtraBudgetaryFundsEnable'] = $model->off_budget;
        $sendData['FundingSourceAnother'] = $model->off_budget_name;
        $sendData['ContractPriceAnother'] = $model->off_budget_value;
        $sendData['BudgetFunds'] = $model->budget_value;
        $sendData['RestrictionNotifyContact']['Phone'] = $model->user_phone;
        $sendData['RestrictionNotifyContact']['Email'] = $model->user_email;
        $sendData['ContactPerson4Request']['FullName'] = $model->contact_name;
        $sendData['ContactPerson4Request']['Phone'] = $model->contact_phone;
        $sendData['ContactPerson4Request']['Email'] = $model->contact_email;

        $xmlData = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Request xmlns="http://rgmek.ru/contractConclusion" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"></Request>');
        $this->arrayToXml($sendData, $xmlData);
        $xmlString = $xmlData->asXML();

        $result = $this->sendToServer('http://s2.rgmek.ru:9900/rgmek.ru/hs/lk/contracts/conclusion/', $xmlString, false, 'POST', true);

        if ($result['success']) {
            $messageId = Messages::createMessageFromDraft($model);
            $model->delete();
            $this->redirect(['messages/update', 'id' => $messageId]);
        }
    }

    /**
     * Deletes an existing DraftContract model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRemoveFile()
    {
        $model = $this->findModel(Yii::$app->request->post('draftId'));
        if ($model->removeFile(Yii::$app->request->post('fileId'))) {
            return $this->renderPartial('_uploaded-files', ['files' => $model->files, 'draft' => $model->id]);
        }
    }

    /**
     * Deletes an existing DraftContract model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }

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

    protected function get1CId($dataArray, $modelData)
    {
        foreach ($dataArray as $item) {
            if ($item['description'] == $modelData) {
                return $item['id'];
            }
        }
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
