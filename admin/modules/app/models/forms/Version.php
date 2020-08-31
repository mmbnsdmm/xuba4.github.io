<?php

namespace admin\modules\app\models\forms;

use wodrow\yii2wtools\tools\FileHelper;
use Yii;
use yii\helpers\ArrayHelper;
use common\models\db\AppVersion;
use yii\web\UploadedFile;

/**
* Version represents the model behind the search form about `common\models\db\AppVersion`.
*/
class Version extends AppVersion
{
    public $pkg;
    public $wgt;

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels = ArrayHelper::merge($attributeLabels, [
            'pkg' => "安装包",
            'wgt' => "升级包",
        ]);
        return $attributeLabels;
    }

    public function rules()
    {
        $rules = parent::rules();
        foreach ($rules as $k => $v) {
            if ($v[1] == 'required'){
                $rules[$k][0] = array_diff($rules[$k][0], ['pkg_url', 'wgt_url']);
            }
        }
        $rules = ArrayHelper::merge($rules, [
            [['pkg', 'wgt'], 'file'],
        ]);
        return $rules;
    }
    
    public function uploadAndSave()
    {
        $file = UploadedFile::getInstance($this, 'pkg');
        if ($file){
            $relative_path = "apps/{$this->v_id}/{$this->version}/pkg";
            $fileName = Yii::getAlias("@uploads_root/{$relative_path}/{$file->name}");
            if (!is_dir(dirname($fileName)))FileHelper::createDirectory(dirname($fileName));
            if ($file->saveAs($fileName)){
                $fileUrl = Yii::$app->apiTool->baseUri.Yii::getAlias("@uploads_url/{$relative_path}/{$file->name}");
                $this->pkg_url = $fileUrl;
            }
        }
        $file = UploadedFile::getInstance($this, 'wgt');
        if ($file){
            $relative_path = "apps/{$this->v_id}/{$this->version}/wgt";
            $fileName = Yii::getAlias("@uploads_root/{$relative_path}/{$file->name}");
            if (!is_dir(dirname($fileName)))FileHelper::createDirectory(dirname($fileName));
            if ($file->saveAs($fileName)){
                $fileUrl = Yii::$app->apiTool->baseUri.Yii::getAlias("@uploads_url/{$relative_path}/{$file->name}");
                $this->wgt = $fileUrl;
            }
        }
        $this->save();
    }
}
