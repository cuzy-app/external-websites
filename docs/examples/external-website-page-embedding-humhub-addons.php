<?php
/**
 * Updates the following values to your needs:
 */

use humhub\helpers\Html;

/** @var string $humhubUrl HumHub URL */
$humhubUrl = 'https://www.my-humhub.tdl';

/** @var string $spaceUrl Space URL (In space management, "advanced" tab) */
$spaceUrl = 'my-space';

/** @var array $params */
$params = [
    'websiteId' => 1, // integer - HumHub Website ID (get this value from the "Websites management" page)
    'pageTitle' => 'Page title', // string - This page title (usually the value in the <title> tag)
    'pageUrl' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", // string - Auto discover the current page URL
    'token' => '', // string - JWT token (optional, see docs/README.md "Auto add groups to user")
    'showComments' => 1, // 1 or 0 - Show comments
    'showLikes' => 1, // 1 or 0 - Show "Likes"
    'showPermalink' => 0, // 1 or 0 - Show permalink
];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $params['pageTitle'] ?></title>

    <style>
        iframe#humhub-addons {
            float: right;
            width: 30%;
        }
    </style>
</head>
<body>
<h2><?= $params['pageTitle'] ?></h2>

<!-- Where you want to show the addons -->
<iframe id="humhub-addons"
        src="<?= rtrim($humhubUrl, '/') ?>/s/<?= $spaceUrl ?>/external-websites/page?<?= http_build_query($params) ?>"
        style="min-height: 700px;" onload="loadIFrameResize();"></iframe>

<!-- Just before </body> -->
<script type="text/javascript" src="js/iframeResizer.min.js"></script>
<script <?= Html::nonce() ?>>
    var loadIFrameResize = function () {
        const iframes = iFrameResize({
            log: false,
            scrolling: true,
            heightCalculationMethod: 'lowestElement', // For resizing on mentioning
            onInit: function () {
                // Remove min-height if iframe resizer has loaded (e.g. after SSO login)
                document.getElementById("humhub-addons").style.minHeight = "auto";
                document.getElementById("humhub-addons").scrolling = "no";
            }
        }, '#humhub-addons');
    };
</script>

</body>
</html>
