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
                        'content.object_id = iframe_container_url.id AND content.object_model = :containerUrlClass',
                        [':containerUrlClass' => ContainerUrl::class]
                    );
                    $this->query->leftJoin(
                        'comment',
                        'comment.object_id = content.object_id'
                    );

                    // Hide content without no comment
                    $this->query->andFilterWhere(['comment.object_model' => ContainerUrl::class]);

                    // Filter to show only the filtered container page
                    $this->query->andFilterWhere(['iframe_container_url.container_page_id' => $containerPage['id']]);
                }
                else {
                    // if more than one filter, use 'or'
                    $this->query->orFilterWhere(['iframe_container_url.container_page_id' => $containerPage['id']]);
                }
                $isFiltered = true;
            }
        }

        // If no filter, hide content related to ContainerUrl with `hide_in_stream` === true and content without no comment
        if (!$isFiltered) {
            $this->query->innerJoin(
                ['iframe_container_url', 'comment'],
                '(content.object_id NOT IN (SELECT id FROM iframe_container_url)) OR (content.object_id = iframe_container_url.id AND content.object_model = :containerUrlClass AND iframe_container_url.hide_in_stream = 0 AND EXISTS (SELECT NULL FROM comment WHERE content.object_id = comment.object_id AND content.object_model = comment.object_model))',
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
