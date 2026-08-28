<?php
namespace Wandervafla\PhpBlog\Core;

use Error;
use Wandervafla\PhpBlog\Filteres\PasswordComplexityFilter;
// TODO: maybe add $errors in __construct
// TODO: maybe do system of errors more autonome. Set global array in index.php
class DisplayErrors
{
    // TODO: add this check into each function
    static function checkArrayKeys(array $array): bool
    {
        if (isset($array['value']) && isset($array['name'])) {
            return true;
        }
        return false;
    }
    public static function diaplay(array &$errors, string $inputNmae, string $nameError) {
        $errors["{$inputNmae}"] = $nameError;
    }
    // TODO: replace this function *partout* in code
    public static function emptyPasswordEmail(
        array &$errors,
        ?string $email,
        ?string $password,
    ) {
        if (empty($email)) {
            $errors["email"] = MESSAGE_EMAIL_REQUIRE;
        }
        if (empty($password)) {
            $errors["password"] = MESSAGE_PASSWORD_REQUIRE;
        }
    }
    
    /**
     * @param array &$errors
     * @param array{{
     *      name: string,
     *      value: string,
     * }} $inputs
     */ 
    public static function checkEmptyInputs(array &$errors, array $inputs) {
        if (self::checkArrayKeys($inputs)) {
            $inputs = [$inputs];
        }
        foreach ($inputs as $input) {
                if (empty($input['value'])) {
                    $errors[$input['name']] = MESSAGE_LINE_REQUIRE;
                }
            }
        }
    
    /**
     * @param array<string> $errors
     * @param array{name: string, value: string} $emailInput
     */
    public static function checkEmail(array &$errors, array $emailInput) {
            if (!filter_var($emailInput['value'], FILTER_VALIDATE_EMAIL)) {
                $errors[$emailInput['name']] = MESSAGE_EMAIL_VALIDATE;
            }
    }
    
    public static function checkForcePassword(
        array &$errors, 
        array $inputDatas
    ) {
        $validated = PasswordComplexityFilter::validate($inputDatas['value']);
        if (!$validated) {
            $errors["{$inputDatas['name']}"] = MESSAGE_PASSWORD_VALIDATE_COMPLEXITY;
        }
    }
    /**
     * 
     */
    public static function checkConfirmPassword(array &$errors, array $inputsPass) {
        $firstPassword = null;
        for ($i = 0; $i < count($inputsPass); $i++) {
            $currentInput = $inputsPass[$i];
             if ($i === 0) {
                 $firstPassword = $currentInput['value'];
             }
             if ($i !== 0 && $currentInput['value'] !== $firstPassword) {
                 $errors["{$currentInput['name']}"] = MESSAGE_PASSWORDS_REPEAT_FAILED;
             }
        }

    }
    public static function checkLogin(array &$errors, ?array &$userData = null)
    {
        $errors["form"] = MESSAGE_LOGIN_FAILED;
    }
    public static function checkSingup(
        array &$errors,
        ?string &$name,
        ?string &$email,
        ?string &$password,
        ?string &$confirmPassword,
    ) {

        
        if (empty($name)) {
            $errors["username"] = MESSAGE_USERNAME_REQUIRE;
        } elseif (str_word_count($name) > 1) {
            $errors["username"] = MESSAGE_USERNAME_VALIDATE;
        }
        
        self::emptyPasswordEmail(errors: $errors, email: $email, password: $password);

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = MESSAGE_EMAIL_VALIDATE;
        }

        if (!empty($password) && !PasswordComplexityFilter::validate($password)) {
            $errors["password"] = MESSAGE_PASSWORD_VALIDATE_COMPLEXITY;
        }

        if (empty($confirmPassword)) {
            $errors["confirm-password"] = MESSAGE_CONFITM_PASSWORD_REQUIRE;
        } elseif ($password !== $confirmPassword) {
            $errors["confirm-password"] = MESSAGE_CONFITM_PASSWORD_VALIDATE;
        }
    }
}
