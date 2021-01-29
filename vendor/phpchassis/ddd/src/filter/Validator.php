<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/19/2019
 * Time: 07:40
 */
namespace phpchassis\filter;

use phpchassis\lib\traits\PhpCommons;

/**
 * Class Validator
 *  Loops through the array of assignments, testing each data item against its assigned validator callbacks.
 * @package phpchassis\filter
 */
class Validator extends BaseFilter {

    use PhpCommons;

    /**
     * Scans an array of data and applies validators as per the array of assignments
     * @param array $data   Input data
     * @return bool
     */
    public function process(array $data): bool {

        $valid = true;

        if (false === (isset($this->assignments) && count($this->assignments))) {
            return $valid;
        }

        foreach ($data as $key => $value) {
            $this->results[$key] = new Result(true, array());
        }

        $assignments = $this->assignments;

        if (isset($assignments['*'])) {
            $this->processGlobalAssignment($assignments['*'], $data);
            unset($assignments['*']);
        }

        foreach ($assignments as $key => $assignment) {
            if (!isset($data[$key])) {
                $this->results[$key] = new Result(false, $this->missingMessage);
            }
            else {
                $this->processAssignment($assignment, $key, $data);
            }
            if (false == $this->results[$key]->item()) {
                $valid = false;
            }
        }
        return $valid;
    }

    /**
     * Loops through an array of callbacks, and because these assignments are global, we also loop through the entire
     * data set and apply each data filter.
     * @param $assignment
     * @param $data
     */
    protected function processGlobalAssignment($assignment, $data) {

        foreach ($assignment as $callback) {

            if (null === $callback || !isset($callback['key'])) {
                continue;
            }
            
            foreach ($data as $k => $value) {
                // Ignore every element in the exceptions list -OR- Callback not found
                if ( (isset($callback["except"]) && in_array($k, $callback["except"]))
                     || !$this->array_key_isset($callback['key'], $this->callbacks) ) {
                    continue;
                }
                $result = $this->callbacks[$callback['key']] (
                    $value,
                    $callback['params'] ?? array()
                );
                $this->results[$k]->mergeValidationResults($result);
            }
        }
    }

    /**
     * Executes each remaining callback assigned to each data key
     * @param $assignment
     * @param $key
     * @param $value
     */
    protected function processAssignment($assignment, $key, array $data) {

        foreach ($assignment as $callback) {

            $callbackId = $callback['key'];

            if (!$this->hasCallback($callbackId) || null === $callback) {
                // @TODO Log - No callback found
                continue;
            }

            $params = $callback['params'] ?? array();

            // @TODO Check to see if we need to retrieve values from the input fields
            //  Eample:
            //    [ 'key' => 'same_as', 'params' => ['input' => 'password2'], 'only' => 'signUp' ],
            if (!empty($params) && $this->array_key_isset('input', $params)) {
                $fieldnames = $params['input'];

                if (is_array($fieldnames)) {
                    foreach ($fieldnames as $value) {
                        $params[$value] = $data[$value];
                    }
                }
                else {
                    $params['fieldValue'] = $data[$fieldnames];
                }
            }

            // Execute the callback
            $result = $this->callbacks[$callbackId] ( $data[$key], $params );

            $this->results[$key]->mergeValidationResults($result);
        }
    }
}