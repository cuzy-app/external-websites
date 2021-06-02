<?php
/**
 * Enter your params here
 */
// Humhub URL
$humhubUrl = 'https://www.my-humhub.tdl';
// String space URL (In space management, "advanced" tab)
$spaceUrl = 'my-space';
// Integer - Humhub Website ID (get this value from the "Websites management" page)
$humhubWebsiteId = 1;
// String - This page title (usually the value in the <title> tag)
$currentPageTitle = 'Page title';
// Boolean (1 or 0) - Auto login (available if the module `auth-keycloak` is installed and SSO is configured)
$autoLogin = 1;
// JWT token (optional, see docs/README.md "Auto add groups to user")
$token = '';

/**
 * Auto discover the current page URL
 */
$currentPageUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $currentPageTitle ?></title>

    <style type="text/css">
        iframe#humhub-addons {
            float: right;
            width: 30%;
        }
    </style>
</head>
<body>

<h2>My page with Humhub addons</h2>

<!-- Where you want to show the addons -->
<iframe id="humhub-addons" src="<?= rtrim($humhubUrl, '/') ?>/s/<?= $spaceUrl ?>/external-websites/page?websiteId=<?= $humhubWebsiteId ?>&pageUrl=<?= urlencode($currentPageUrl) ?>&pageTitle=<?= urlencode($currentPageTitle) ?>&autoLogin=<?= $autoLogin ?>&token=<?= $token ?>" style="min-height: 700px;" onload="loadIFrameResize();"></iframe>

<!-- Just before </body> -->
<script type="text/javascript" src="js/iframeResizer.min.js"></script>
<script type="text/javascript">
    var loadIFrameResize = function() {
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