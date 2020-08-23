<?php

namespace common\models\db\tables;

use Yii;

/**
 * This is the model class for table "{{%tag_article}}".
 *
 * @property int $id
 * @property int $tag_id
 * @property int|null $tag_name
 * @property int $article_id
 * @property int|null $article_title
 * @property int $created_by
 * @property int|null $updated_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 *
 * @property Article $article
 * @property User $createdBy
 * @property Tag $tag
 * @property User $updatedBy
 */
class TagArticle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tag_article}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_id', 'tag_name', 'article_id', 'article_title', 'created_by', 'updated_by', 'created_at', 'updated_at', 'status'], 'trim'],
            [['tag_id', 'article_id', 'created_by', 'created_at', 'updated_at', 'status'], 'required'],
            [['tag_id', 'tag_name', 'article_id', 'article_title', 'created_by', 'updated_by', 'created_at', 'updated_at', 'status'], 'integer'],
            [['tag_name', 'article_title', 'updated_by'], 'default', 'value' => null],
            [['tag_id', 'article_id'], 'unique', 'targetAttribute' => ['tag_id', 'article_id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::className(), 'targetAttribute' => ['tag_id' => 'id']],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tag_id' => Yii::t('app', 'Tag ID'),
            'tag_name' => Yii::t('app', 'Tag Name'),
            'article_id' => Yii::t('app', 'Article ID'),
            'article_title' => Yii::t('app', 'Article Title'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
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
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }
}
