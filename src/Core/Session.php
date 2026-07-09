<?php
namespace Wandervafla\PhpBlog\Core;

class Session
{
    private static $username = "username";
    private static $user_id = "user_id";
    private static $user_role = "role";

    public static function getUsername(): ?string
    {
        return $_SESSION[self::$username];
    }
    public static function getUserId(): ?int
    {
        return $_SESSION[self::$user_id];
    }
    public static function getUserRole(): ?string
    {
        return $_SESSION[self::$user_role];
    }
    public static function initSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }
    }
    public static function destroySession()
    {
        unset($_SESSION[self::$username]);
        unset($_SESSION[self::$user_id]);
        unset($_SESSION[self::$user_role]);
    }
    public static function isLoggedIn(): bool
    {
        $username = $_SESSION[self::$username];
        $user_id = $_SESSION[self::$user_id];

        if (isset($user_id) && isset($username)) {
            return true;
        }
        return false;
    }
    public static function notLoggedRedirect()
    {
        if (!self::isLoggedIn()) {
            header("Location: /login");
        }
    }
    public static function isByCurrentUser(int $postUserId)
    {
        if (self::getUserId() !== null && $postUserId === self::getUserId() || self::getUserRole() === 'admin') {
            return true;
        }
        return false;
    }
}
