<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/humhub-modules-external-websites
 * @license https://github.com/cuzy-app/humhub-modules-external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models;

use humhub\modules\user\components\ActiveQueryUser;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Description of EventSearch
 */
class WebsiteSearch extends Website
{
    public $query;

    /**
     * @inheritdoc
     * List of fields that can be filtered
     */
    public function rules()
    {
        return [
            [['title', 'icon', 'first_page_url', 'remove_from_url_title'], 'string'],
            [['space_id', 'sort_order', 'default_content_visibility', 'default_content_archived'], 'integer'],
            [['humhub_is_embedded', 'show_in_menu', 'hide_sidebar'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ($this->query == null) ? Website::find() : $this->query;
        /* @var $query ActiveQueryUser */

        if (!empty($this->space_id)) {
            $query->andWhere(['space_id' => $this->space_id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->emulateExecution();
            return $dataProvider;
        }

        return $dataProvider;
    }
}