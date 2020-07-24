<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%app_version}}".
 *
 * @property int $id
 * @property int $type app类型
 * @property int $v_id 版本号
 * @property string $version 版本
 * @property int $is_force_update 是否强制更新
 * @property string $pkg_url 整包下载地址
 * @property string|null $wgt_url 热更新地址
 * @property string|null $desc 说明
 * @property int $created_at 添加时间
 * @property int $status 状态
 * @property string|null $other_download_urls 其他下载地址
 */
class AppVersion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%app_version}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'v_id', 'version', 'is_force_update', 'pkg_url', 'wgt_url', 'desc', 'created_at', 'status', 'other_download_urls'], 'trim'],
            [['type', 'v_id', 'version', 'pkg_url', 'created_at', 'status'], 'required'],
            [['type', 'v_id', 'is_force_update', 'created_at', 'status'], 'integer'],
            [['desc', 'other_download_urls'], 'string'],
            [['version'], 'string', 'max' => 20],
            [['pkg_url', 'wgt_url'], 'string', 'max' => 150],
            [['is_force_update', 'wgt_url', 'desc', 'other_download_urls'], 'default', 'value' => null],
            [['type', 'version'], 'unique', 'targetAttribute' => ['type', 'version']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'app类型'),
            'v_id' => Yii::t('app', '版本号'),
            'version' => Yii::t('app', '版本'),
            'is_force_update' => Yii::t('app', '是否强制更新'),
            'pkg_url' => Yii::t('app', '整包下载地址'),
            'wgt_url' => Yii::t('app', '热更新地址'),
            'desc' => Yii::t('app', '说明'),
            'created_at' => Yii::t('app', '添加时间'),
            'status' => Yii::t('app', '状态'),
            'other_download_urls' => Yii::t('app', '其他下载地址'),
        ];
    }
}
