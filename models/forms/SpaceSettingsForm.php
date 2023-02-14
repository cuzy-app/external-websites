<?php
/**
 * External Websites
 * @link https://github.com/cuzy-app/humhub-modules-external-websites
 * @license https://github.com/cuzy-app/humhub-modules-external-websites/blob/master/docs/LICENSE.md
 * @author [Marc FARRE](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models\forms;

use humhub\modules\space\models\Space;
use Yii;
use yii\base\Model;


class SpaceSettingsForm extends Model
{
    /**
     * @var Space
     */
    public $contentContainer;

    /**
     * @var string default null
     */
    public $urlToRedirect;

    /**
     * @var bool
     */
    public $preventLeavingSpace = false;


    public function init()
    {
        $settings = Yii::$app->getModule('external-websites')->settings->space($this->contentContainer);

        $this->urlToRedirect = $settings->get('urlToRedirect');
        $this->preventLeavingSpace = $settings->get('preventLeavingSpace', $this->preventLeavingSpace);

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['urlToRedirect'], 'string'],
            [['preventLeavingSpace'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'urlToRedirect' => Yii::t('ExternalWebsitesModule.base', 'URL of the external website to redirect all URLs of this space (if URL empty or this space is not the first page opened in the browser: no redirection)'),
            'preventLeavingSpace' => Yii::t('ExternalWebsitesModule.base', 'If Humhub is embedded, prevent browsing outside of space'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'urlToRedirect' => Yii::t('ExternalWebsitesModule.base', '{humhubUrl} will be replaced with the Humhub\'s source URL. E.g https://www.my-external-website.tdl?humhubUrl={humhubUrl} value will redirect https://wwww.my-humhub.tdl/s/space-name/xxx to https://www.my-external-website.tdl?humhubUrl=https://wwww.my-humhub.tdl/s/space-name/xxx'),
            'preventLeavingSpace' => Yii::t('ExternalWebsitesModule.base', 'Prevents clicking on links that will show an other page than the current space'),
        ];
    }

    /**
     * Saves the current model values to the current user or globally.
     *
     * @return boolean success
     */
    public function save()
    {
        $settings = Yii::$app->getModule('external-websites')->settings->space($this->contentContainer);

        $settings->set('urlToRedirect', $this->urlToRedirect);
        $settings->set('preventLeavingSpace', $this->preventLeavingSpace);

        return true;
    }
}