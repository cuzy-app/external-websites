<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/humhub-modules-iframe
 * @license https://gitlab.com/funkycram/humhub-modules-iframe/-/raw/master/docs/LICENCE.md
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\models\filters;

use Yii;
use humhub\modules\iframe\models\ContainerPage;
use humhub\modules\iframe\models\ContainerUrl;


/**
 * Add filters in a stream show in a space
 */
class IframeSpaceStreamFilter extends \humhub\modules\stream\models\filters\StreamQueryFilter
{
    /**
     * Default filters
     */
    // 'container_page_id_'.$containerPage['id']

    /**
     * Array of stream filters to apply to the query.
     */
    public $filters = [];

    public $containerPages;

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
        $this->containerPages = ContainerPage::findAll(['space_id' => $space['id']]);

        $this->filters = $this->streamQuery->filters;
        parent::init();
        $this->filters = (is_string($this->filters)) ? [$this->filters] : $this->filters;
    }

    public function apply()
    {
        $this->query->leftJoin(
            'iframe_container_url',
            'content.object_id = iframe_container_url.id AND content.object_model = :containerUrlClass',
            [':containerUrlClass' => ContainerUrl::class]
        );

        $isFiltered = false;
        foreach ($this->containerPages as $containerPage) {
            if ($this->isFilterActive('container_page_id_'.$containerPage['id'])) {

                if (!$isFiltered) {
                    $isFiltered = true;
                    $this->query->andFilterWhere(['iframe_container_url.container_page_id' => $containerPage['id']]);
                }
                else {
                    // if more than one filter, use 'or'
                    $this->query->orFilterWhere(['iframe_container_url.container_page_id' => $containerPage['id']]);
                }
            }
        }
    }

    public function isFilterActive($filter)
    {
        return in_array($filter, $this->filters);
    }
}
