<?php
/**
 * iFrame module
 * @link https://gitlab.com/funkycram/module-humhub-iframe
 * @license https://www.humhub.com/licences
 * @author [FunkycraM](https://marc.fun)
 */

namespace humhub\modules\iframe\models\filters;

use Yii;
use humhub\modules\iframe\models\ContainerPage;
use humhub\modules\iframe\models\ContainerUrl;


class IframeStreamFilter extends \humhub\modules\stream\models\filters\StreamQueryFilter
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
        $isFiltered = false;

        foreach ($this->containerPages as $containerPage) {
            if ($this->isFilterActive('container_page_id_'.$containerPage['id'])) {
                // Add left join only once
                if (!$isFiltered) {
                    $this->query->leftJoin(
                        'iframe_container_url',
                        'content.object_id = iframe_container_url.id AND content.object_model = :containerUrlClass INNER JOIN `iframe_container_page` ON iframe_container_url.container_page_id = iframe_container_page.id',
                        [':containerUrlClass' => ContainerUrl::class]
                    );
                }
                $isFiltered = true;

                // Filter to show only the filtered container page
                $this->query->andFilterWhere(['iframe_container_page.id' => $containerPage['id']]);
            }
        }

        // If no filter, hide content related to ContainerUrl with `hide_in_stream` === true
        if (!$isFiltered) {
            $this->query->innerJoin(
                'iframe_container_url',
                '(content.object_id NOT IN (SELECT id FROM iframe_container_url)) OR (content.object_id = iframe_container_url.id AND content.object_model = :containerUrlClass AND iframe_container_url.hide_in_stream = 0)',
                [':containerUrlClass' => ContainerUrl::class]
            );
        }
    }

    public function isFilterActive($filter)
    {
        return in_array($filter, $this->filters);
    }

    protected function filterContents($hide, $containerPageId)
    {
        // $this->query->andWhere([
        //     'and',
        //     ['iframe_container_page.id' => $containerPageId],
        //     ['iframe_container_page.default_hide_in_stream' => $hide]
        // ]);


        // Default filter from database
        // $this->query->andFilterWhere(['not', ['iframe_container_url.hide_in_stream' => 1]]);

        return $this;
    }
}
