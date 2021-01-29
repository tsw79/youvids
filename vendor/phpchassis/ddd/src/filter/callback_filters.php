<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/18/2019
 * Time: 18:50
 */
use phpchassis\filter\ {CallbackInterface, Result, Messages};

return [

    /**
     * Test filter
     * @callback
     * @param: none
     * @return Result
     */
    'test' => new class () implements CallbackInterface {

        public function __invoke($item, array $params = []) : Result {
            return new Result($item, Messages::get('test'));
        }
    },

    /**
     * Trim filter
     * @callback
     * @param: none
     * @return Result
     */
    'trim' => new class () implements CallbackInterface {

        public function __invoke($item, array $params = []) : Result {

            $changed  = array();
            $filtered = trim($item);

            if ($filtered !== $item) {
                $changed = Messages::get('trim');
            }
            return new Result($filtered, $changed);
        }
    },

    /**
     * Strip_tags filter
     * @callback
     * @param: none
     * @return Result
     */
    'strip_tags' => new class () implements CallbackInterface {

        public function __invoke($item, array $params = []) : Result {

            $changed  = array();
            $filtered = strip_tags($item);

            if ($filtered !== $item) {
                $changed = Messages::get('stripTags');
            }
            return new Result($filtered, $changed);
        }
    },

    /**
     * Length filter
     * @callback
     * @param: int  Length
     * @return Result
     */
    'length' => new class () implements CallbackInterface {

        public function __invoke($item, array $params = []) : Result {

            $changed  = array();
            $filtered = substr($item, 0, $params['length']);

            if ($filtered !== $item) {
                $changed = Messages::get('filterLength');
            }
            return new Result($filtered, $changed);
        }
    },

    /**
     * Length filter
     * @callback
     * @param: none
     * @return Result
     */
    'float' => new class () implements CallbackInterface {

        public function __invoke($item, array $params = []) : Result {

            $changed  = array();
            $filtered = (float) $item;

            if ($filtered !== $item) {
                $changed = Messages::get('filterFloat');
            }
            return new Result($filtered, $changed);
        }
    },

    /**
     * upper filter
     * @callback class
     */
    'upper' => new class () implements CallbackInterface {

        /**
         * @param $item
         * @param array $params
         * @return Result
         */
        public function __invoke($item, array $params = []) : Result {

            $changed  = array();
            $filtered = strtoupper($item);

            if ($filtered !== $item) {
                $changed = Messages::get('upper');
            }
            return new Result($filtered, $changed);
        }
    },

    /**
     * email filter
     * @callback class
     */
    'email' => new class () implements CallbackInterface {

        /**
         * @param $item
         * @param array $params
         * @return Result
         */
        public function __invoke($item, array $params = []) : Result {

            $changed  = array();
            $filtered = filter_var($item, FILTER_SANITIZE_EMAIL);

            if ($filtered !== $item) {
                $changed = Messages::get('filterEmail');
            }
            return new Result($filtered, $changed);
        }
    },

    /**
     * alpha filter
     * @callback class
     */
    'alpha' => new class () implements CallbackInterface {

        /**
         * @param $item
         * @param array $params
         * @return Result
         */
        public function __invoke($item, array $params = []) : Result {

            $changed  = array();
            $filtered = preg_replace('/[^A-Za-z]/', '', $item);

            if ($filtered !== $item) {
                $changed = Messages::get('alpha');
            }
            return new Result($filtered, $changed);
        }
    },

    /**
     * alnum filter
     * @callback class
     */
    'alnum' => new class () implements CallbackInterface {

        /**
         * @param $item
         * @param array $params
         * @return Result
         */
        public function __invoke($item, array $params = []) : Result {

            $changed  = array();
            $filtered = preg_replace('/[^0-9A-Za-z ]/', '', $item);

            if ($filtered !== $item) {
                $changed = Messages::get('filterAlnum');
            }
            return new Result($filtered, $changed);
        }
    }
];