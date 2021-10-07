<?php
ini_set('session.cookie_httponly', 1);           // Mitigate XSS
ini_set('session.use_only_cookies', 1);          // No session fixation
ini_set('session.cookie_lifetime', 0);           // Avoid XSS, CSRF, Clickjacking
ini_set('session.cookie_secure', 1);             // Never let this be propagated via HTTP/80

// Start the initial session
session_start();

// Start output buffering on
ob_start();

// Include functions.php that contains all our functions and constants
require_once("functions.php");

// Include ipfunctions.php that contains code to convert IPv4 and v6 to decimal(39,0) and vice-versa.
require_once("ipfunctions.php");

// Include class.xmSession.php that contains code to store individual session data
require_once("class.xmSession.php");

// Set timezone to be UTC
date_default_timezone_set("UTC");

$errorMask          = 0;
$errorHeaderMessage = null;
$errorMessage       = null;
$infoMessage        = null;

$formPosted         = false;
$xmSession          = null;
$logId              = null;
$username           = "";

$logAccess          = false;

// First off, check if the application is being used by someone not typing the actual server name in the header
if (strtolower($_SERVER["HTTP_HOST"]) !== $global_siteCookieQualifier) {
    // Transfer user to same page, served over HTTPS and full-domain name
    header("Location: https://" . $global_siteCookieQualifier . $_SERVER["REQUEST_URI"]);
    exit();
}   //  End if (strtolower($_SERVER["HTTP_HOST"]) !== $global_siteCookieQualifier)

// First call being made for app
if (!array_key_exists("xm_created", $_SESSION)) {

    $xmSession = new xmSession;

    // Set a sessionKey for this request
    $xmSession->setSessionKey(createMyGuid());

    echo("Storing session " . $xmSession->getSessionKey());
    // Store this object back in session
    $_SESSION["xm_created"] = 1;
    $_SESSION["xm_session"] = serialize($xmSession);

    echo("Session stored!");

    // ********* 1. Call Web Service over HTTP GET, to get settings ********** //
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $global_siteUrl . "services/GetSystemSettings.php");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'ApiKey: e6ca4533012d418b8a93ec3abd5cbb9c',           // $$ API_KEY $$
        'Accept: application/json'));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

    session_write_close();

    $response = curl_exec($ch);

    curl_close($ch);

    $responseJson = json_decode(utf8_decode($response), true);
    $responseData = $responseJson["response"];
    $errorCode = $responseData["errorCode"];

    if ($errorCode == 0) {
        $settings = $responseData["settings"];

        foreach ($settings as $setting) {
            foreach($setting as $key => $value) {
                if ($key == "logAccess") {
                    $logAccess = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                } else if ($key == "errorEmail") {
                    $xmSession->setAdminEmail($value);
                }   //  End if ($key == "logAccess")
            }   //  End foreach($setting as $key => $value)
        }   //  End foreach($settings as &$setting)

        if ($logAccess) {
            // Log incoming entry in to accessLogs table
            // STEP 1 - Call logUserAccess.php Web Service
            // ********* Call Web Service to set access log metadata **********
            $ch = curl_init();

            $elements                  = array();
            $elements["ipAddress"]     = $_SERVER["REMOTE_ADDR"];

            if (isset($elements["HTTP_REFERER"])) {
                $elements["referer"]       = $_SERVER["HTTP_REFERER"];
            }   //  End if (isset($elements["HTTP_REFERER"]))

            $elements["browserString"] = $_SERVER["HTTP_USER_AGENT"];
            $elements["sessionKey"]    = $xmSession->getSessionKey();

            $request                   = array();
            $request["request"]        = $elements;

            $ch                        = curl_init();

            curl_setopt($ch, CURLOPT_URL, $global_siteUrl . "services/LogUserAccess.php");

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "ApiKey: e6ca4533012d418b8a93ec3abd5cbb9c",           // $$ API_KEY $$
                "Content-Type: application/x-www-form-urlencoded",
                "Accept: application/json"));

            curl_setopt($ch, CURLOPT_SSLVERSION, 6);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, urlencode(utf8_encode(json_encode($request))));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

            session_write_close();

            $response = curl_exec($ch);

            curl_close($ch);

            $logResponseJson = json_decode(utf8_decode($response), true);
            $logResponse     = $logResponseJson["response"];

            $errorCode = intval($logResponse["errorCode"]);

            if ($errorCode > 0) {
                $errorMessage = $logResponse["error"];

                mail($xmSession->getAdminEmail(), "index.php error " . $errorCode, $errorMessage);
            } else {
                $logId = intval($logResponse["logId"]);
            }   //  End if ($errorCode > 0)
            // *************** END Step 1 ************************
        }   //  End if ($logAccess)
    } else {
        $errorMessage = $responseData["errorMessage"];
        mail("sundar@passion8cakes.com", "index.php error " . $errorCode, $errorMessage);   // $$ ADMIN_EMAIL_ADDRESS $$

        //  Redirect to error page
    }
}   //  End if (!array_key_exists("xm_session", $_SESSION)) {

// Fetch xm_session object for xmSession
$xmSession = unserialize($_SESSION["xm_session"]);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>XM: Manage your money</title>
    <link rel="stylesheet" type="text/css" href="_static/main.css" />

    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

    <script type="text/javascript" language="JavaScript" src="_static/scripts.js"></script>
  </head>
  <body class="bodyContent" onload="loadLandingPage();">
    <?php include_once("header.php"); ?>
    <div id="errorSection" style="display: <?php print (($errorMask === 0) ? "none" : "block"); ?>;">
      <div class="errorPanel" style="width: 450px">
        <span class="boldLabel" id="errorHeaderSpan"><?php print($errorHeaderMessage); ?>:</span><br/>
        <span class="inputLabel" id="errorText"><?php
    if ($errorMask > 0) {
        print($errorMessage);
    }   //  End if ($errorMask > 0)
        ?></span>
      </div>
      <div class="fillerPanel20px">&nbsp;</div>
    </div>

    <div id="infoSection" style="display: <?php print (($infoMessage === null) ? "none" : "block"); ?>;">
      <div class="infoPanel" style="width: 450px">
        <span class="inputLabel" id="infoText"><?php print($infoMessage); ?></span>
      </div>
      <div class="fillerPanel20px">&nbsp;</div>
    </div>

    <form name="loginForm" method="POST" action="index.php">
      <div style="margin-left: 20px;">
        <input type="hidden" id="errorMask" name="errorMask" value="<?php print($errorMask); ?>"/>
        <input type="hidden" id="sessionKey" name="sessionKey" value="<?php print($xmSession->getSessionKey()); ?>"/>
        <input type="hidden" id="accessLogId" name="accessLogId" value="<?php
    if ($logId !== null) {
        print($logId);
    } else {
        print(0);
    }   //  End if ($logId !== null)
        ?>"/>
        <input type="text" placeholder="user-name" width="120" maxlength="24" name="txtUsername" id="txtUsername" value="<?php
    print(($username === null) ? "" : $username);
        ?>" /><br/>
        <input type="password" placeholder="password" width="120" maxlength="48" name="txtPassword" id="txtPassword" /><br/>
        <input type="Submit" value="Log-in" onclick="return validateLoginForm();"/>
      </div>
    </form>
    <div class="fillerPanel40px">&nbsp;</div>
    <?php include_once("footer.php"); ?>
  </body>
</html>
<?php
ob_end_flush();
?>
