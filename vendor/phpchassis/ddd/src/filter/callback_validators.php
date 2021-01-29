<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/18/2019
 * Time: 07:53
 */
use phpchassis\filter\Result;
use phpchassis\filter\Messages;
use phpchassis\filter\CallbackInterface;

return [

    /**
     * Checks that a value is alphanumeric
     * @callback
     * @param: bool allowWhitespace
     * @return Result
     */
    'alnum' => new class () implements CallbackInterface {

        public function __invoke($item, array $params = []) : Result {

            $error = array();
            $allow = $params['allowWhiteSpace'] ?? false;

            if ($allow) {
                $item = str_replace(' ', '', $item);
            }
            $valid = ctype_alnum($item);

            if (!$valid) {
                $error[] = Messages::get('alnum');
            }
            return new Result($valid, $error);
        }
    },

    /**
     * Checks that a value is an integer
     * @callbackS
     * @return Result
     */
    'integer' => new class () implements CallbackInterface {

        public function __invoke($item, array $params = []) : Result {

            $error = array();
            $valid = $item == (int) $item;

            if (!$valid) {
                $error[] = Messages::get('integer');
            }
            return new Result($valid, $error);
        }
    },

    /**
     * Checks that a value is a float
     * @callbackS
     * @return Result
     */
    'float' => new class () implements CallbackInterface {

        public function __invoke($item, array $params = []) : Result {

            $error = array();
            $valid = $item == (float) $item;

            if (!$valid) {
                $error[] = Messages::get('float');
            }
            return new Result($valid, $error);
        }
    },

    /**
     * Validates an email
     * @callback
     * @param: none
     * @return Result
     */
    'email' => new class () implements CallbackInterface {

        public function __invoke($item, array $params = []) : Result {

            $error = array();
            $valid = filter_var($item, FILTER_VALIDATE_EMAIL);

            if (!$valid) {
                $error[] = Messages::get('email');
            }
            return new Result($valid, $error);
        }
    },

    /**
     * Validates whether a field exists in an array
     * @callback
     * @param: none
     * @return Result
     */
    'in_array' => new class () implements CallbackInterface {

        public function __invoke($item, array $params = []) : Result {

            $error = array();
            $valid = in_array($item, $params);

            if (!$valid) {
                $error[] = Messages::get('inArray');
            }
            return new Result($valid, $error);
        }
    },

    /**
     * Validates whether the value of one field is the same as another
     * @callback
     * @param: string
     * @return Result
     */
    'same_as' => new class () implements CallbackInterface {

        public function __invoke($item, array $params = []) : Result {

            $error = array();
            $item2 = $params['fieldValue'];
            $valid = $item === $item2 ? true : false;

            if (!$valid) {
                $error[] = Messages::get('sameAs');
            }
            return new Result($valid, $error);
        }
    },

    /**
     * Validates a certain Length
     * @callback
     * @param string $item      Input value
     * @param array $params     Paramaters
     *          int $min (minimum value)
     *          int $max (maximum value)
     * @param string $msg       Error message
     * @return Result
     */
    'length' => new class () implements CallbackInterface {

        public function __invoke($item, array $params = []) : Result {

            $valid = 0;
            $count = 0;
            $error = array();

            $min = $params['min'] ?? false;
            $max = $params['max'] ?? false;

            if ($min) {
                $count++;
                if (strlen($item) >= $min) {
                    $valid++;
                }
                else {
                    $error[] = sprintf($msg ?? Messages::get('minLength'), $min);
                }
            }

            if ($max) {
                $count++;
                if (strlen($item) <= $max) {
                    $valid++;
                }
                else {
                    $error[] = sprintf($msg ?? Messages::get('maxLength'), $max);
                }
            }

            return new Result(($valid == $count), $error);
        }
    },

    /**
     * Validates a Phone field
     * @callback class
     */
    'phone' => new class () implements CallbackInterface {

        /**
         * @param $item
         * @param array $params
         * @return Result
         */
        public function __invoke($item, array $params = []) : Result {

            $error = array();
            $valid = (bool) preg_match('/^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/', $item);   // preg_match('/[^0-9() -+]/', $item)

            if (!$valid) {
                $error[] = Messages::get('phone');
            }
            return new Result($valid, $error);
        }
    },

    /**
     * Validates a Digits-only field
     * @callback class
     */
    'digits' => new class () implements CallbackInterface {

        /**
         * @param $item
         * @param array $params
         * @return Result
         */
        public function __invoke($item, array $params = []) : Result {

            $error = array();
            $valid = (bool) preg_match('/[^0-9.]/', $item);

            if (!$valid) {
                $error[] = Messages::get('digits');
            }
            return new Result($valid, $error);
        }
    },

    /**
     * Validates a Required field
     * @callback class
     */
    'required' => new class () implements CallbackInterface {

        /**
         * @param $item
         * @param array $params
         * @return Result
         */
        public function __invoke($item, array $params = []) : Result {

            $error = array();
            $valid = boolval($item);

            if (!$valid) {
                $error[] = Messages::get('required');
            }
            return new Result($valid, $error);
        }
    }
];