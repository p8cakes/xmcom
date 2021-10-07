<?php
// Module Name:  xm website
// Project:      xm website to track everyday expenses
//
// File: UpdateTimeZone.php - Update the timezone for this user
//
// Input JSON:
//   {"sessionKey":"a02cb1f0377a3164c819ed8979a10a60",
//    "timezone":5.5
//    "dump":true
//   }
//
// Output JSON:
//   {"errorCode":0,
//      "logId":1}
//
//   {"errorCode":1,
//      "error":"Could not connect to database server."}
//
// Functions:
//    None
//
// Query Parameters:
//    None
//
// Custom Headers:
//     ApiKey: Must contain magic value for this service to be employed
//
// Session Variables:
//     None
//
// Stored Procedures:
//    updateTimeZone - update the timezone for a session
//
// JavaScript functions:
//    None
//
// Revisions:
//     1. Sundar Krishnamurthy          sundar_k@hotmail.com       09/04/2017      Initial file created.


ini_set('session.cookie_httponly', 1);           // Mitigate XSS
ini_set('session.use_only_cookies', 1);          // No session fixation
ini_set('session.cookie_lifetime', 0);           // Avoid XSS, CSRF, Clickjacking
ini_set('session.cookie_secure', 1);             // Never let this be propagated via HTTP/80

// Include functions.php that contains all our functions
require_once("../functions.php");

// Start output buffering on
ob_start();

// Start the initial session
session_start();

// First off, check if the application is being used by someone not typing the actual server name in the header
if (strtolower($_SERVER["HTTP_HOST"]) !== $global_siteCookieQualifier) {
    // Transfer user to same page, served over HTTPS and full-domain name
    header("Location: https://" . $global_siteCookieQualifier . $_SERVER["REQUEST_URI"]);
    exit();
}   //  End if (strtolower($_SERVER["HTTP_HOST"]) !== $global_siteCookieQualifier)

$query         = null;
$errorCode     = 0;
$errorMessage  = null;

$dump          = false;

// Javascript is posting this form with Time zone, and this is a page that has already been dispatched over the same session
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // $xmSession = $_SESSION["xm_session"];
    $postBody = utf8_decode(urldecode(file_get_contents("php://input")));

    // We found a valid body to process
    if ($postBody !== "") {

        $query         = null;
        $bitmask       = 0;
        $errorCode     = 0;
        $errorMessage  = null;

        $sessionKey    = null;
        $timezone      = null;
        $dump          = false;

        $outputJson    = array();

        $request     = json_decode($postBody, true);
        $requestJson = $request["request"];

        if (array_key_exists("timezone", $requestJson)) {
            $timezone = floatval($requestJson["timezone"]);
            $bitmask = 1;
        }   //  End if (array_key_exists("timezone", $requestJson))

        if (array_key_exists("sessionKey", $requestJson)) {
            $sessionKey = $requestJson["sessionKey"];
            $bitmask |= 2;
        }   //  End if (array_key_exists("sessionKey", $requestJson))

        if (array_key_exists("dump", $requestJson)) {
            $dump = boolval($requestJson["dump"]);
        }   //  End if (array_key_exists("dump", $requestJson))

        // We have valid data coming for everything
        if ($bitmask === 3) {

            // Update sessionId and incoming data
            // Connect to DB
            $con = mysqli_connect($global_dbServer, $global_dbUsername, $global_dbPassword);

            // Unable to connect, display error message
            if (!$con) {
                $errorCode    = 1;
                $errorMessage = "Could not connect to database server.";
            } else {
                // DB selected will be selected Database on server
                $db_selected = mysqli_select_db($con, $global_dbName);

               // Unable to use DB, display error message
               if (!$db_selected) {
                    $errorCode    = 2;
                    $errorMessage = "Could not connect to the database.";
                } else {

                    $useSessionKey = mysqli_real_escape_string($con, $sessionKey);

                    if (strlen($useSessionKey) > 32) {
                        $useSessionKey = substr($useSessionKey, 0, 32);
                    }   //  End if (strlen($useSessionKey) > 32)

                    // This is the query we will use to update timezone in the DB
                    $query = "call updateTimeZone('$useSessionKey',$timezone);";

                    // Result of query
                    $result = mysqli_query($con, $query);

                    // Unable to fetch result, display error message
                    if (!$result) {
                        $errorCode     = 3;
                        $errorMessage  = "Invalid query: " . mysqli_error($con) . "<br/>";
                        $errorMessage .= ("Whole query: " . $query);
                    } else if ($row = mysqli_fetch_assoc($result)) {
                        $outputJson["logId"] = intval($row["logId"]);

                        // Free result
                        mysqli_free_result($result);
                    }   //  End if (!$result)
                }   //  End if (!$db_selected)

                // Close connection
                mysqli_close($con);
            }   //  End if (!$con)
        } else {
            $errorCode = 4;
            $errorMessage = "Missing one or more fields (timezone) in incoming request JSON.";
        }   //  End if ($bitmask === 3)

        $outputJson["errorCode"] = $errorCode;

        if ($errorMessage !== null) {
            $outputJson["error"] = $errorMessage;
        }   //  End if ($errorMessage !== null)

        if (($dump === true) && ($query !== null)) {
            $outputJson["query"] = $query;
        }   //  End if (($dump === true) && ($query !== null))

        // Send result back
        header("Content-Type: application/json; charset=utf-8");
        print(utf8_encode(json_encode($outputJson)));
    }   //  End if ($postBody !== "")
}   //  End if (($_SERVER["REQUEST_METHOD"] === "POST") &&

ob_end_flush();
?>
