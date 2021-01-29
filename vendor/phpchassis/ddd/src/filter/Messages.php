<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/18/2019
 * Time: 06:07
 */
namespace phpchassis\filter;

/**
 * Class Messages
 * @package phpchassis\filter
 */
class Messages {

    private const MESSAGE_UNKNOWN = 'Unknown error';

    /**
     * @var
     */
    private static $messages = [
        /*
         * Validator messages
         */
        "alnum"        => "Only letters and numbers allowed",
        "float"        => "Only numbers or decimal point",
        "integer"      => "Only numbers (no decimal points)",
        "email"        => "Invalid email address",
        "inArray"      => "Not found in the list",
        "sameAs"       => "The two fields are not the same",
        "minLength"    => "Must be at least %d",
        "maxLength"    => "Must be no more than %d",
        "phone"        => "Phone number is [+n] nnn-nnn-nnnn",
        "digits"       => "Data contains characters which are not numbers",
        "required"     => "Please be sure to enter a value",

        /*
         * Filter messages
         */
        "test"         => "TEST",
        "trim"         => "Item was trimmed",
        "stripTags"    => "Tags were removed from this item",
        "filterLength" => "Reduced to specified length",
        "filterFloat"  => "Converted to a decimal number",
        "upper"        => "Input value has been converted to upper case",
        "filterEmail"  => "Email has been sanitized",
        "alpha"        => "Input value has been converted to alpha characters only",
        "filterAlnum"  => "Input value has been converted to alphanumeric characters only",

        "exception"    => "Item has skipped the '%s' check."
    ];

    /**
     * @param $key
     * @param $message
     */
//    public static function setMessage($key, $message) {
    public static function set($key, $message) {
        self::$messages[$key] = $message;
    }

    /**
     * @param $key
     * @return string
     */
//    public static function getMessages($key) {
    public static function get($key) {
        return self::$messages[$key] ?? self::MESSAGE_UNKNOWN;
    }

    /**
     * @param array $messages
     */
//    public static function messages(array $messages = null) {
//
//        if ($messages === null) {
//            return self::$messages;
//        }
//        self::$messages = $messages;
//    }
}