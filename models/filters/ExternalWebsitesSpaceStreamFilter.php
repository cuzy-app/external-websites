<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models\filters;

use Yii;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\externalWebsites\models\Page;


/**
 * Add filters in a stream show in a space
 */
class ExternalWebsitesSpaceStreamFilter extends \humhub\modules\stream\models\filters\StreamQueryFilter
{
    /**
     * Default filters
     */
    // 'website_id_'.$website->id

    /**
     * Array of stream filters to apply to the query.
     */
    public $filters = [];

    public $websites;

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
        $space = Yii::$app->controller->contentContainer;
        $this->websites = Website::findAll(['space_id' => $space->id]);

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
        foreach ($this->websites as $website) {
            if ($this->isFilterActive('website_id_'.$website->id)) {

                if (!$isFiltered) {
                    $isFiltered = true;
                    $this->query->andFilterWhere(['external_websites_website_page.website_id' => $website->id]);
                }
                else {
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
