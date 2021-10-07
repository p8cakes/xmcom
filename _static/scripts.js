/****************************** Module Header ******************************
 * Module Name:  xm website
 * Project:      xm website to track everyday expenses
 *
 * Main CSS
 *
 * Scripts file for all JavaScript related functions
 *
 * 1. loadLandingPage - Load landing page, set form element to current time zone, submit form
 * 2. equalsIgnoreCase page - compare two strings for case
 * 3. fasttrim - remove whitespace characters, front and back
 * 4. validateLoginForm - Validate login form before posting to server
 *
 * Revisions:
 *     1. Sundar Krishnamurthy          sundar_k@hotmail.com       06/10/2017      Initial file created.
***************************************************************************/


// Actual URL of our application - FQDN
var global_fqdn = "https://cloudsec.karmalab.net/xmcom/";                      // $$ SITE_URL $$

// 1. Index page
// Used in:
//    index.php
function loadLandingPage() {

    // Construct Date object
    var currentDate = new Date();
    var accessLogId = parseInt($("#accessLogId").val());
    var errorMask = parseInt($("#errorMask").val());
    var sessionKey = $("#sessionKey").val();

    if ((accessLogId > 0) && (errorMask == 0)) {

        var xhr = new XMLHttpRequest();
        xhr.open("POST", global_fqdn + "services/UpdateTimeZone.php", true);

        // Send the proper header information along with the request
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if ((xhr.readyState == 4) && (xhr.status == 200)) {
                console.log(xhr.responseText);
            }
        };

        xhr.send("{\"request\":{\"sessionKey\":\"" +
            sessionKey +
            "\",\"timezone\":" +
            (-currentDate.getTimezoneOffset() / 60).toString() + "}}");

        $("#accessLogId").val("0");
        document.getElementById("accessLogId").value = "0";
    }

    if (errorMask == 2) {
        $("#txtPassword").focus();
    } else {
        $("#txtUsername").focus();
    }
}

// 2. Utility function U1 - compare two strings for case
function equalsIgnoreCase(arg1, arg2) {
    return (arg1.toLowerCase() === arg2.toLowerCase());
}

// 3. Utility function U2 - remove whitespace characters, front and back
function fasttrim(str) {
    str = str.replace(/^\s\s*  /, ''),
        ws = /\s/,
        i = str.length;

    while (ws.test(str.charAt(--i)))
        ;

    return str.slice(0, i + 1);
}

// 4. Validate login form before posting to server
function validateLoginForm() {

    // Return value, default error message is <ul>
    var errorMask = 0;

    var errorMessage = "<ul>";

    // Locate errorSection block, update display to block (from none)
    var errorSectionElement = document.getElementById("errorSection");

    // Read data on the login text field, trim it to remove whitespace on either side
    var loginElement = document.getElementById("txtUsername");
    var loginValue = fasttrim(loginElement.value);

    // If the values don't match, replace it with the trimmed version
    if (loginValue != loginElement.value) {
        loginElement.value = loginValue;
    }

    // If the data field was blank, error has occured and update message
    if (loginValue === "") {
        // We found an error
        errorMask = 1;
        errorMessage += "<li>Please enter your username.</li>";
    } else {

        for (i = 0; i < loginValue.length; i++) {
            var c = loginValue[i];

            if (!(((c >= 'a') && (c <= 'z')) || ((c >= 'A') && (c <= 'Z')) || ((c >= '0') && (c <= '9')))) {
                if ((c != '.') && (c != '_') && (c != '-')) {
                    errorMask = 1;
                    errorMessage += "<li>Please enter a valid username.</li>";
                    break;
                }
            }
        }
    }

    var txtPassword = $("#txtPassword").val();

    if (txtPassword === "") {
        errorMask |= 2;
        errorMessage += "<li>Please enter your password.</li>";
    }

    document.getElementById("errorMask").value = errorMask;

    // In case you found errors, display error message block
    if (errorMask > 0) {
        errorMessage += "</ul>";

        // Display error section
        errorSectionElement.style.display = "block";

        // Get section for errorHeaderSpan, set boiler-plate text for header
        document.getElementById("errorHeaderSpan").innerHTML = "Please correct these errors below:";

        // Locate errorText element, set innerHTML to message we constructed above
        var errorTextElement = document.getElementById("errorText");
        errorTextElement.innerHTML = errorMessage;

        loadLandingPage();
    } else {
        // Reset error message
        errorMessage = "";

        // Hide error section
        errorSectionElement.style.display = "none";
    }

    return (errorMask === 0);
}

// 1. Index page
// Used in:
//    index.php
function loadVerificationPage() {

    var errorMask = parseInt($("#errorMask").val());

    if (errorMask === 2) {
        $("#txtAnswer2").focus();
    } else {
        var txtAnswer2Element = document.getElementById("txtAnswer2");

        if (txtAnswer2Element === null) {
            $("#txtPasscode").focus();
        } else {
            $("#txtAnswer1").focus();
        }
    }
}

function validateVerificationForm() {

    // Return value, default error message is <ul>
    var errorMask = 0;

    var errorMessage = "<ul>";

    // Locate errorSection block, update display to block (from none)
    var errorSectionElement = document.getElementById("errorSection");

    // Check if user is in secret question-answer workflow, or 
    var txtAnswer1Element = document.getElementById("txtAnswer1");

    // We have OTP flow
    if (txtAnswer1Element === null) {

        var txtPasscodeElement = document.getElementById("txtPasscode");

        var otpValue = fasttrim(txtPasscodeElement.value);
        var useOtpValue = "";

        for (var i = 0; i < otpValue.length; i++) {
            var c = otpValue.charAt(i);

            if ((c >= '0') && (c <= '9')) {
                useOtpValue += c;
            }
        }

        if (otpValue != useOtpValue) {
            txtPasscodeElement.value = useOtpValue;
        }

        if (useOtpValue.length != 6) {
            errorMask = 1;
            errorMessage += "<li>Please enter a valid numeric passcode.</li>";
        }
    } else {
        // We have Secret question answer workflow
        var txtAnswer1Element = document.getElementById("txtAnswer1");

        var txtAnswer1 = fasttrim(txtAnswer1Element.value);

        // If the values don't match, replace it with the trimmed version
        if (txtAnswer1 != txtAnswer1Element.value) {
            txtAnswer1Element.value = txtAnswer1;
        }

        if (txtAnswer1.length < 3) {
            errorMask = 1;
            errorMessage += "<li>Please enter a valid answer for question 1.</li>";
        }

        var txtAnswer2Element = document.getElementById("txtAnswer2");

        var txtAnswer2 = fasttrim(txtAnswer2Element.value);

        // If the values don't match, replace it with the trimmed version
        if (txtAnswer2 != txtAnswer2Element.value) {
            txtAnswer2Element.value = txtAnswer2;
        }

        if (txtAnswer2.length < 3) {
            errorMask |= 2;
            errorMessage += "<li>Please enter a valid answer for question 2.</li>";
        }
    }

    document.getElementById("errorMask").value = errorMask;

    // In case you found errors, display error message block
    if (errorMask > 0) {
        errorMessage += "</ul>";

        // Locate errorSection block, update display to block (from none)
        var infoSectionElement = document.getElementById("infoSection");

        if (infoSectionElement.style.display === "block") {
            infoSectionElement.style.display = "None";
        }

        // Display error section
        errorSectionElement.style.display = "block";

        // Get section for errorHeaderSpan, set boiler-plate text for header
        document.getElementById("errorHeaderSpan").innerHTML = "Please correct these errors below:";

        // Locate errorText element, set innerHTML to message we constructed above
        var errorTextElement = document.getElementById("errorText");
        errorTextElement.innerHTML = errorMessage;

        loadVerificationPage();
    } else {
        // Reset error message
        errorMessage = "";

        // Hide error section
        errorSectionElement.style.display = "none";
    }

    return (errorMask === 0);
}

// 2. Check input field for money, maximum 8 characters
function checkForMoney(field) {

    var inputValue = fasttrim(field.value);

    if (inputValue.indexOf('.') === -1) {
        inputValue += ".00";
    } else if (inputValue.length > 1) {
        if (inputValue.charAt(0) === '.') {
            inputValue = "0" + inputValue;
        } else if (inputValue.charAt(inputValue.length - 1) === '.') {
            inputValue += "00";
        }
    }

    if ((inputValue.length > 2) && (inputValue.charAt(inputValue.length - 2) === '.')) {
        inputValue += "0";
    }

    if (inputValue != field.value) {
        field.value = inputValue;
    }

    if (!inputValue.match(/^\d{1,10}$|^\d{1,10}\.\d{1,2}$/)) {
        field.value = "";
    } else {
        field.value = parseFloat(inputValue).toFixed(2);
    }
}
