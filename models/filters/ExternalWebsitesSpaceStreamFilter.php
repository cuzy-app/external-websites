<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/external-websites
 * @license https://github.com/cuzy-app/external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models\filters;

use humhub\modules\externalWebsites\models\Page;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\stream\models\filters\StreamQueryFilter;
use Yii;


/**
 * Add filters in a stream show in a space
 * Model: humhub\modules\stream\models\filters\DefaultStreamFilter
 */
class ExternalWebsitesSpaceStreamFilter extends StreamQueryFilter
{
    /**
     * Default filters
     * $website->id will be added to the prefix
     */
    const FILTER_SURVEY_STATE_PREFIX = 'filter_external_website_id_';

    /**
     * Array of stream filters to apply to the query.
     */
    public $filters = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filters'], 'safe']
        ];
    }

    public function init()
    {
        $this->filters = $this->streamQuery->filters;
        parent::init();
        $this->filters = (is_string($this->filters)) ? [$this->filters] : $this->filters;
    }

    public function apply()
    {
        $this->query->leftJoin(
            'external_websites_website_page',
            'content.object_id = external_websites_website_page.id AND content.object_model = :pageClass',
            [':pageClass' => Page::class]
        );

        $isFiltered = false;
        $space = Yii::$app->controller->contentContainer;
        $websites = Website::findAll(['space_id' => $space->id]);
        foreach ($websites as $website) {
            if ($this->isFilterActive(static::FILTER_SURVEY_STATE_PREFIX . $website->id)) {

                if (!$isFiltered) {
                    $isFiltered = true;
                    $this->query->andFilterWhere(['external_websites_website_page.website_id' => $website->id]);
                } else {
                    // if more than one filter, use 'or'
                    $this->query->orFilterWhere(['external_websites_website_page.website_id' => $website->id]);
                }
            }
        }
    }

    public function isFilterActive($filter)
    {
        return in_array($filter, $this->filters);
    }
}
