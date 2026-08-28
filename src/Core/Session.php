<?php
namespace Wandervafla\PhpBlog\Core;

class Session
{
    private static $username = "username";
    private static $user_id = "user_id";
    private static $user_email = 'email';
    private static $user_role = "role";
    private static $last_action = 'last_action';

    public static function getUsername(): ?string
    {
        return $_SESSION[self::$username] ?? null;
    }
    public static function getUserId(): ?int
    {
        return $_SESSION[self::$user_id] ?? null;
    }
    public static function getUserRole(): ?string
    {
        return $_SESSION[self::$user_role] ?? null;
    }
    public static function getEmail(): ?string
    {
        return $_SESSION[self::$user_email] ?? null;
    }
    public static function updateCurrentUserData(array $userData)
    {
        $_SESSION["username"] = $userData["name"];
        $_SESSION['email'] = $userData['email'];
        $_SESSION["role"] = $userData['role'];
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
    public static function addAction(string $action)
    {
        $_SESSION[self::$last_action] = $action;
    }
    public static function destroySession()
    {
        unset($_SESSION[self::$username]);
        unset($_SESSION[self::$user_id]);
        unset($_SESSION[self::$user_role]);
    }
    public static function isLoggedIn(): bool
    {
        $user_id = self::getUserId();

        if (isset($user_id) || !empty($user_id)) {
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
