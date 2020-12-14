<?php
/**
 * External Websites
 * @link https://gitlab.com/funkycram/humhub-modules-external-websites
 * @license https://gitlab.com/funkycram/humhub-modules-external-websites/-/raw/master/docs/LICENCE.md
 * @author [Marc Farre](https://marc.fun)
 */

namespace humhub\modules\externalWebsites\models\forms;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use humhub\modules\externalWebsites\models\Website;
use humhub\modules\content\models\Content;


class SpaceSettingsForm extends \yii\base\Model
{
    /**
     * @var \humhub\modules\space\models\Space
     */
    public $contentContainer;

    /**
     * @var string default null
     */
    public $urlToRedirect;


    public function init()
    {
        $settings = Yii::$app->getModule('external-websites')->settings->space($this->contentContainer);

        $this->urlToRedirect = $settings->get('urlToRedirect');

        return parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['urlToRedirect'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'urlToRedirect' => Yii::t('ExternalWebsitesModule.base', 'URL of the external website to redirect all URLs of this space (if URL empty or this space is not the first page opened in the browser: no redirection)'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'urlToRedirect' => Yii::t('CalendarEventsExtensionModule.base', '{humhubUrl} will be replaced with the Humhub\'s source URL. E.g https://www.my-external-website.tdl?humhubUrl={humhubUrl} value will redirect https://wwww.my-humhub.tdl/s/space-name/xxx to https://www.my-external-website.tdl?humhubUrl=https://wwww.my-humhub.tdl/s/space-name/xxx'),
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

        return true;
    }
}