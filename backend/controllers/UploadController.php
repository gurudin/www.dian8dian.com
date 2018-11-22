<?php
namespace backend\controllers;

use Yii;
use yii\web\UploadedFile;

class UploadController extends BaseController
{
    /**
     * Upload file
     *
     * @param file file
     */
    public function actionAjaxUpload()
    {
        $file = UploadedFile::getInstanceByName('file');
        $dir  = 'resource/' . date('Ym');
        $path = $dir . '/' . $this->randstr(5) . date('YmdHis') . '.' . $file->getExtension();

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        if ($file->saveAs(Yii::getAlias("@webroot") . '/' . $path) === true) {
            return ['status' => true, 'path' => '/' . $path, 'data' => $file];
        } else {
            return ['status' => false, 'msg' => 'Failed to upload.'];
        }
    }
}
