<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Description of EventSearch
 */
class WebsiteSearch extends Website
{
    public $query;

    /**
     * instance of \humhub\modules\space\models\Space
     */
    public $contentContainer;


    /**
     * @inheritdoc
     */
    public function attributes()
    {
        // add related fields to searchable attributes
        return parent::attributes();
    }

    /**
     * @inheritdoc
     * List of fields that can be filtered
     */
    public function rules()
    {
        return parent::rules();
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
        /* @var $query \humhub\modules\user\components\ActiveQueryUser */

        if (!empty($this->contentContainer)) {
            $query->contentContainer($this->contentContainer);
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