<?php

namespace backend\controllers;

use common\models\Media;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MediaController extends Controller
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        ['actions' => ['download'], 'allow' => true, 'roles' => ['@']],
                    ],
                ],
            ]
        );
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionDownload($id): void
    {
        $media = Media::findOne($id);
        if (!$media) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!file_exists(Yii::getAlias('@app') . '/uploads/' . $media->id . '.' . $media->file_type)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $file = Yii::getAlias('@app') . '/uploads/' . $media->id . '.' . $media->file_type;

        $file_name = basename($file);
        $content_type = mime_content_type($file);

        header("Content-Type: " . $content_type);
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Length: " . filesize($file));

        readfile($file);
        exit;
    }
}
