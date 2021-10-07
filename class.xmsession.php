<?php
// Module Name:  xm website
// Project:      xm website to track everyday expenses
//
// class xmSession - store all session information in variables here
//
// Revisions:
//     1. Sundar Krishnamurthy          sundar_k@hotmail.com       04/25/2017      Initial file created.


class xmSession {

    //  Set to a key for this session, this is not Session ID
    private $sessionKey;

    // User ID of this user logging in
    private $userId;

    // First name [space] last name
    private $fullName;

    // First name
    private $firstName;

    // Email address used to login to the system for this user
    private $email;

    // Salt for this user saved in the session
    private $salt;

    // Password hash we read from the database
    private $passwordHash;

    // Whether this user account is active
    private $active;

    // Status bit-mask
    private $status;

    // Whether this user has cookies dispatched, or not
    private $cookies;

    // What is the userKey we dispatch in the cookie?
    private $userKey;

    // No logging is to be done for users logging out and going back to index page
    private $noLog;

    // Number of times user has tried to login
    private $loginCount;

    // Number of times user has tried to login from an IP Address
    private $failedLoginCount;

    // Is this user locked from trying again?
    private $locked;

    // When was this user locked out earlier?
    private $lockTime;

    // Error message if something happened on a page
    private $errorMessage;

    // What is the next URL that we need to redirect this user to?
    private $nextUrl;

    // Display message if something needs to be shown
    private $displayMessage;

    // Where in the login stage is this user?
    private $loginStatus;

    // Exclude flag for this user
    private $exclude;

    // LogId
    private $logId;

    // Username
    private $username;

    // Access Key
    private $accessKey;

    // What is the cookie GUID?
    private $cookie;

    // What is the SHA256 hash of the browser string?
    private $browserHash;

    // Do we keep the session still active?
    private $sessionActive;

    // When does this cookie expire?
    private $cookieExpires;

    // Notification Mask 
    private $notificationMask;

    // OTP key we email to user
    private $otpKey;

    // Selected Sequence, how to proceed with three question-answer sets
    private $selectedSequence;

    // Source-targets associative array
    private $sourceTargets;

    // Source-targets JSON
    private $sourceTargetJson;

    // Admin email
    private $adminEmail;

    // Default Constructor
    function __construct() {

        $this->sessionKey             = null;
        $this->userId                 = 0;
        $this->fullName               = null;
        $this->firstName              = null;
        $this->email                  = null;
        $this->salt                   = null;
        $this->passwordHash           = null;
        $this->active                 = false;
        $this->status                 = 0;
        $this->cookies                = false;
        $this->userKey                = null;
        $this->noLog                  = 0;

        $this->loginCount             = 0;
        $this->failedLoginCount       = 0;
        $this->locked                 = false;
        $this->lockTime               = null;
        $this->errorMessage           = null;
        $this->nextUrl                = null;
        $this->displayMessage         = null;

        $this->loginStatus            = 0;
        $this->exclude                = false;
        $this->logId                  = 0;
        $this->username               = null;
        $this->accessKey              = null;

        $this->cookie                 = null;
        $this->browserHash            = null;
        $this->sessionActive          = null;

        $this->cookieExpires          = null;
        $this->notificationMask       = null;
        $this->otpKey                 = null;
        $this->selectedSequence       = null;
        $this->sourceTargets          = null;
        $this->sourceTargetJson       = null;
        $this->adminEmail       = null;
    }   //  End function __construct()

    // Get the session key
    public function getSessionKey() {
        return $this->sessionKey;
    }   //  End public function getSessionKey()

    // Get the userId
    public function getUserId() {
        return $this->userId;
    }   //  End public function getUserId()

    // Get the full name
    public function getFullName() {
        return $this->fullName;
    }   //  End public function getFullName()

    // Get the first name
    public function getFirstName() {
        return $this->firstName;
    }   //  End public function getFirstName()

    // Get the Email
    public function getEmail() {
        return $this->email;
    }   //  End public function getEmail()

    // Get the salt
    public function getSalt() {
        return $this->salt;
    }   //  End public function getSalt()

    // Get the Password Hash
    public function getPasswordHash() {
        return $this->passwordHash;
    }   //  End public function getPasswordHash()

    // Get the Active Flag
    public function getActive() {
        return $this->active;
    }   //  End public function getActive()

    // Get the Status Flag
    public function getStatus() {
        return $this->status;
    }   //  End public function getStatus()

    // Get the Cookies Flag
    public function getCookies() {
        return $this->cookies;
    }   //  End public function getCookies()

    // Get the User Key
    public function getUserKey() {
        return $this->userKey;
    }   //  End public function getUserKey()

    // Get the no-log value we set prior
    public function getNoLog() {
        return $this->noLog;
    }   //  End public function getNoLog()

    // Get the Login attempt count
    public function getLoginCount() {
        return $this->loginCount;
    }   //  End public function getLoginCount()

    // Get the Login attempt count from an IP address
    public function getFailedLoginCount() {
        return $this->failedLoginCount;
    }   //  End public function getFailedLoginCount()

    // Get the status if the user is locked out
    public function getLocked() {
        return $this->locked;
    }   //  End public function getLocked()

    // Get the last timestamp when this user was locked out
    public function getLockTime() {
        return $this->lockTime;
    }   //  End public function getLockTime()

    // Get the error message, if set prior
    public function getErrorMessage() {
        return $this->errorMessage;
    }   //  End public function getErrorMessage()

    // Get the next URL, if set prior
    public function getNextUrl() {
        return $this->nextUrl;
    }   //  End public function getNextUrl()

    // Get the display message, if set prior
    public function getDisplayMessage() {
        return $this->displayMessage;
    }   //  End public function getDisplayMessage()

    // Get the login status, if set prior
    public function getLoginStatus() {
        return $this->loginStatus;
    }   //  End public function getLoginStatus()

    // Get the Exclude Flag
    public function getExclude() {
        return $this->exclude;
    }   //  End public function getExclude()

    // Get the Log ID
    public function getLogId() {
        return $this->logId;
    }   //  End public function getLogId()

    // Get the Username
    public function getUsername() {
        return $this->username;
    }   //  End public function getUsername()

    // Get the Access Key
    public function getAccessKey() {
        return $this->accessKey;
    }   //  End public function getAccessKey()

    // Get the Cookie
    public function getCookie() {
        return $this->cookie;
    }   //  End public function getCookie()

    // Get the Browser Hash
    public function getBrowserHash() {
        return $this->browserHash;
    }   //  End public function getBrowserHash()

    // Get the Session Active flag
    public function getSessionActive() {
        return $this->sessionActive;
    }   //  End public function getSessionActive()

    // Get the Cookie Expires date
    public function getCookieExpires() {
        return $this->cookieExpires;
    }   //  End public function getCookieExpires()

    // Get the Notification Mask
    public function getNotificationMask() {
        return $this->notificationMask;
    }   //  End public function getNotificationMask()

    // Get the OTP Key
    public function getOtpKey() {
        return $this->otpKey;
    }   //  End public function getOtpKey()

    // Get the Selected Sequence
    public function getSelectedSequence() {
        return $this->selectedSequence;
    }   //  End public function getSelectedSequence()

    // Get the source-targets associative array
    public function getSourceTargets() {
        return $this->sourceTargets;
    }   //  End public function getSourceTargets()

    // Gets the source-targets JSON retrieved from DB
    public function getSourceTargetJson() {
        return $this->sourceTargetJson;
    }   //  End public function getSourceTargetJson()

    // Get the admin email
    public function getAdminEmail() {
        return $this->adminEmail;
    }   //  End public function getSessionKey()



    // Set the session key
    public function setSessionKey($sessionKey) {
        $this->sessionKey = $sessionKey;
    }   //  End public function setSessionKey($sessionKey)

    // Set the userId
    public function setUserId($userId) {
        $this->userId = $userId;
    }   //  End public function setUserId($userId)

    // Set the full name
    public function setFullName($fullName) {
        $this->fullName = $fullName;
    }   //  End public function setFullName($fullName)

    // Set the first name
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }   //  End public function setFirstName($firstName)

    // Set the email
    public function setEmail($email) {
        $this->email = $email;
    }   //  End public function setEmail($email)

    // Set the salt
    public function setSalt($salt) {
        $this->salt = $salt;
    }   //  End public function setSalt($salt)

    // Set the password hash
    public function setPasswordHash($passwordHash) {
        $this->passwordHash = $passwordHash;
    }   //  End public function setPasswordHash($passwordHash)

    // Set the active flag
    public function setActive($active) {
        $this->active = $active;
    }   //  End public function setActive($active)

    // Set the status flag
    public function setStatus($status) {
        $this->status = $status;
    }   //  End public function setStatus($status)

    // Set the flag if cookies are allowed
    public function setCookies($cookies) {
        $this->cookies = $cookies;
    }   //  End public function setCookies($cookies)

    // Set the flag for user key
    public function setUserKey($userKey) {
        $this->userKey = $userKey;
    }   //  End public function setUserKey($userKey)

    // Set the no logging flag
    public function setNoLog($noLog) {
        $this->noLog = $noLog;
    }   //  End public function setNoLog($noLog)

    // Set the Login Count
    public function setLoginCount($loginCount) {
        $this->loginCount = $loginCount;
    }   //  End public function resetLoginCount()

    // Set the Failed Login Count
    public function setFailedLoginCount($failedLoginCount) {
        $this->failedLoginCount = $failedLoginCount;
    }   //  End public function setFailedLoginCount($failedLoginCount)

    // Set the Locked status
    public function setLocked($locked) {
        $this->locked = $locked;
    }   //  End public function setLocked($locked)

    // Set the Lock Time
    public function setLockTime($lockTime) {
        $this->lockTime = $lockTime;
    }   //  End public function setLockTime($lockTime)

    // Set the Error Message
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
    }   //  End public function setErrorMessage($errorMessage)

    // Set the Next URL
    public function setNextUrl($nextUrl) {
        $this->nextUrl = $nextUrl;
    }   //  End public function setNextUrl($nextUrl)

    // Set the Display Message
    public function setDisplayMessage($displayMessage) {
        $this->displayMessage = $displayMessage;
    }   //  End public function setDisplayMessage($displayMessage)

    // Set the Login Status for this user
    public function setLoginStatus($loginStatus) {
        $this->loginStatus = $loginStatus;
    }   //  End public function setLoginStatus($loginStatus)

    // Set the Exclude flag for this user
    public function setExclude($exclude) {
        $this->exclude = $exclude;
    }   //  End public function setExclude($exclude)

    // Set the LogId
    public function setLogId($logId) {
        $this->logId = $logId;
    }   //  End public function setLogId($logId)

    // Set the Username
    public function setUsername($username) {
        $this->username = $username;
    }   //  End public function setUsername($username)

    // Set the AccessKey
    public function setAccessKey($accessKey) {
        $this->accessKey = $accessKey;
    }   //  End public function setAccessKey($accessKey)

    // Set the Cookie
    public function setCookie($cookie) {
        $this->cookie = $cookie;
    }   //  End public function setCookie($cookie)

    // Set the Browser Hash
    public function setBrowserHash($browserHash) {
        $this->browserHash = $browserHash;
    }   //  End public function setBrowserHash($browserHash)

    // Set the Session Active flag
    public function setSessionActive($sessionActive) {
        $this->sessionActive = $sessionActive;
    }   //  End public function setSessionActive($sessionActive)

    // Set the Cookie Expires date
    public function setCookieExpires($cookieExpires) {
        $this->cookieExpires = $cookieExpires;
    }   //  End public function setCookieExpires($cookieExpires)

    // Set the Notification Mask
    public function setNotificationMask($notificationMask) {
        $this->notificationMask = $notificationMask;
    }   //  End public function setNotificationMask($notificationMask)

    // Set the OTP Key
    public function setOtpKey($otpKey) {
        $this->otpKey = $otpKey;
    }   //  End public function setOtpKey($otpKey)

    // Set the source targets associative array
    public function setSourceTargets($sourceTargets) {
        $this->sourceTargets = $sourceTargets;
    }   //  End public function setSourceTargets($sourceTargets)

    // Set the source target JSON string
    public function setSourceTargetJson($sourceTargetJson) {
        $this->sourceTargetJson = $sourceTargetJson;
    }   //  End public function setSourceTargetJson($sourceTargetJson)

    // Set the admin email
    public function setAdminEmail($adminEmail) {
        $this->adminEmail = $adminEmail;
    }   //  End public function setAdminEmail($adminEmail)

    public function printObject() {

        print("sessionKey: "     . $this->sessionKey . "<br/>");
        print("userLogged: "     . $this->userLogged . "<br/>");
        print("userId: "         . $this->userId . "<br/>");
        print("fullName: "       . $this->fullName . "<br/>");
        print("firstName: "      . $this->firstName . "<br/>");
        print("email: "          . $this->email . "<br/>");
        print("salt: "           . $this->salt . "<br/>");
        print("passwordHash: "   . $this->passwordHash . "<br/>");
        print("active: "         . $this->active . "<br/>");
        print("status: "         . $this->status . "<br/>");
        print("sessionKey: "     . $this->sessionKey . "<br/>");
        print("cookies: "        . $this->cookies . "<br/>");

        print("userKey: "        . $this->userKey . "<br/>");
        print("noLog: "          . $this->noLog . "<br/>");
        print("loginCount: "     . $this->loginCount . "<br/>");

        print("lockTime: "       . $this->lockTime . "<br/>");
        print("errorMessage: "   . $this->errorMessage . "<br/>");
        print("nextUrl: "        . $this->nextUrl . "<br/>");
        print("displayMessage: " . $this->displayMessage . "<br/>");
        print("loginStatus: "    . $this->loginStatus . "<br/>");
        print("exclude: "        . $this->exclude . "<br/>");
        print("logId: "          . $this->logId . "<br/>");
        print("username: "       . $this->username . "<br/>");
        print("accessKey: "      . $this->accessKey . "<br/>");

        print("cookie: "           . $this->cookie . "<br/>");
        print("browserHash: "      . $this->browserHash . "<br/>");
        print("sessionActive: "    . $this->sessionActive . "<br/>");
        print("cookieExpires: "    . $this->cookieExpires . "<br/>");
        print("notificationMask: " . $this->notificationMask . "<br/>");
        print("otpKey: "           . $this->otpKey . "<br/>");
        print("adminEmail: "       . $this->adminEmail . "<br/>");
    }   //  End public function printObject()
}   //  End class xmSession 
?>
