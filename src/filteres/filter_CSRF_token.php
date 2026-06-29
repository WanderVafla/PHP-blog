<?php
function generate_CSRF_token()
{
    session_start();
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        echo 'csrf token genereated!';
    }
}

function validateCSRFToken()
{
    if (
        !isset($_POST["csrf_token"]) ||
        !isset($_SESSION["csrf_token"]) ||
        !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])
    ) {
        die("CSRF token validation failed");
    }
    return true;
}
