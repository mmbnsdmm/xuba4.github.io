<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/9/14
 * Time: 10:03
 */

namespace admin\modules\article\models\forms;


use common\components\Tools;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class UploadFileArticle extends Model
{
    public $txts;

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels = ArrayHelper::merge($attributeLabels, [
            'txts' => "txt文件",
        ]);
        return $attributeLabels;
    }

    public function rules()
    {
        $rules = parent::rules();
        foreach ($rules as $k => $v) {
            if ($v[1] == 'required'){
//                $rules[$k][0] = array_diff($rules[$k][0], ['txts']);
            }
        }
        $rules = ArrayHelper::merge($rules, [
            [['txts'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 100, 'extensions' => 'txt'],
        ]);
        return $rules;
    }

    public function uploadAndSave()
    {
        $files = UploadedFile::getInstances($this, 'txts');
        $article = new Article;
        $article->status = Article::STATUS_ACTIVE;
        $article->create_type = Article::CREATE_TYPE_REPRINTED;
        foreach ($files as $k => $v){
            $content = file_get_contents($v->tempName);
            $content = str_replace("http://120.92.150.43:8003/storage/uploads/prod", "http://49.235.220.19:7053/storage/uploads/prod/xuba3", $content);
            $a = clone $article;
            $a->title = $v->baseName;
            $a->content = $content;
            if (!$a->save()){
                throw new Exception(\wodrow\yii2wtools\tools\Model::getModelError($a));
            }
        }
        return true;
    }
}