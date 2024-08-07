<?php
/**
 * Enter your params here
 */

// HumHub URL
use humhub\libs\Html;

$humhubUrl = 'http://www.my-humhub.tdl/dashboard';
// JWT token (optional, see docs/README.md "Auto add groups to user")
$token = '';

/**
 * If you want to make redirections work (see docs/README.md "Space's content redirected to external website")
 */
if (isset($_GET['humhubUrl'])) {
    $humhubUrl = urldecode((string)$_GET['humhubUrl']);
}

/**
 * Add token param
 */
if (!empty($token)) {
    $humhubUrl .= ((strpos($humhubUrl, '?') === false) ? '?' : '&') . 'token=' . $token;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My title</title>

    <style>
        iframe#humhub {
            width: 100%;
        }
    </style>
</head>
<body>

<h2>My page with HumHub embedded</h2>

<!-- Where you want to show HumHub -->
<iframe id="humhub" src="<?= $humhubUrl ?>" style="min-height: 700px;" onload="loadIFrameResize();"></iframe>

<!-- Just before </body> -->
<script type="text/javascript" src="js/iframeResizer.min.js"></script>
<script <?= Html::nonce() ?>>
    var loadIFrameResize = function () {
        const iframes = iFrameResize({
            log: false,
            scrolling: true,
            onInit: function () {
                // Remove min-height if iframe resizer has loaded (e.g. after SSO login)
                document.getElementById("humhub").style.minHeight = "auto";
                document.getElementById("humhub").scrolling = "no";
            }
        }, '#humhub');
    };
</script>

</body>
</html>
