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


/**
 * Add a filter in a stream show in the network (eg: dashboard)
 */
class IframeNetworkStreamFilter extends \humhub\modules\stream\models\filters\StreamQueryFilter
{
    /**
     * Default filters
     */
    // 'container_page_id_'.$containerPage['id']

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

    /**
     * Hide content related to ContainerUrl with `hide_in_stream` === true and content with no comment
     */
    public function apply()
    {
        $isFiltered = false;

        $this->query->leftJoin(
            'iframe_container_url',
            'content.object_id = iframe_container_url.id AND content.object_model = :containerUrlClass',
            [':containerUrlClass' => ContainerUrl::class]
        );
        $this->query->leftJoin(
            'comment',
            'comment.object_id = content.object_id AND comment.object_model = :containerUrlClass',
            [':containerUrlClass' => ContainerUrl::class]
        );

        // If not from iframe module, show it
        $this->query->andFilterWhere(['not', ['content.object_model' => ContainerUrl::class]]);
        $this->query->orFilterWhere([
            'and',
            // If from iframe module
            ['content.object_model' => ContainerUrl::class],
            // Show content if not `hide_in_stream`
            ['iframe_container_url.hide_in_stream' => 0],
            // Hide content without no comment
            ['comment.object_model' => ContainerUrl::class],
        ]);
    }
}
