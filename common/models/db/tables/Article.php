<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%article}}".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int|null $updated_by
 * @property string|null $get_password
 * @property int $min_level
 * @property int $min_integral
 * @property int $is_boutique 是否精品
 * @property int $create_type 创作方式
 * @property string|null $tieba_url 贴吧url
 * @property string|null $tieba_post_ids json格式已处理楼层
 * @property string|null $tieba_author_id
 * @property string|null $tieba_author_name
 *
 * @property Collection[] $collections
 * @property Comments[] $comments
 * @property User $createdBy
 * @property TagArticle[] $tagArticles
 * @property Tag[] $tags
 * @property User $updatedBy
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'get_password', 'min_level', 'min_integral', 'is_boutique', 'create_type', 'tieba_url', 'tieba_post_ids', 'tieba_author_id', 'tieba_author_name'], 'trim'],
            [['title', 'content', 'status', 'created_at', 'updated_at', 'created_by'], 'required'],
            [['content', 'tieba_post_ids'], 'string'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'min_level', 'min_integral', 'is_boutique', 'create_type'], 'integer'],
            [['title', 'get_password', 'tieba_url', 'tieba_author_name'], 'string', 'max' => 180],
            [['tieba_author_id'], 'string', 'max' => 20],
            [['updated_by', 'get_password', 'tieba_url', 'tieba_post_ids', 'tieba_author_id', 'tieba_author_name'], 'default', 'value' => null],
            [['min_level', 'min_integral', 'is_boutique'], 'default', 'value' => 0],
            [['create_type'], 'default', 'value' => 1],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'get_password' => Yii::t('app', 'Get Password'),
            'min_level' => Yii::t('app', 'Min Level'),
            'min_integral' => Yii::t('app', 'Min Integral'),
            'is_boutique' => Yii::t('app', '是否精品'),
            'create_type' => Yii::t('app', '创作方式'),
            'tieba_url' => Yii::t('app', '贴吧url'),
            'tieba_post_ids' => Yii::t('app', 'json格式已处理楼层'),
            'tieba_author_id' => Yii::t('app', 'Tieba Author ID'),
            'tieba_author_name' => Yii::t('app', 'Tieba Author Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollections()
    {
        return $this->hasMany(Collection::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTagArticles()
    {
        return $this->hasMany(TagArticle::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('{{%tag_article}}', ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
