<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "media".
 *
 * @property int $id
 * @property string|null $filename
 * @property string|null $file_type
 * @property string|null $path
 * @property string|null $uploaded_at
 * @property string|null $title
 * @property string|null $description
 * @property string|null $object_type
 * @property integer|null $object_id
 */
class Media extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['uploaded_at'], 'safe'],
            [['filename', 'file_type', 'path', 'title', 'object_type'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['object_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'file_type' => 'File Type',
            'path' => 'Path',
            'uploaded_at' => 'Uploaded At',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'object_type' => 'Тип объекта',
            'object_id' => 'ID объекта',
        ];
    }

    public static function getFileExtension($filename)
    {
        $filenameParts = explode('.', $filename);
        return end($filenameParts);
    }

    /**
     * @param string|null $fileName
     * @param string|null $fileType
     * @return string
     */
    public static function getFilePath(string $fileName = null, string $fileType = null): string
    {
        if ($fileName !== null) {
            return '/media/download?id=' . $fileName . '.' . $fileType;
        }

        return '/img/default-avatar.png';

    }
}
