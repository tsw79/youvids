<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/18/2019
 * Time: 06:31
 */
namespace phpchassis\filter;

use phpchassis\lib\traits\PhpCommons;

/**
 * Class Filter
 * @package phpchassis\filter
 */
class Filter extends BaseFilter {

    use PhpCommons;

    /**
     * Scans an array of data and applies filters as per the array of assignments. If there are no assigned filters for
     * this data set, we simply return NULL.
     * @param array $data
     * @return null
     */
    public function process(array $data) {

        if (false === (isset($this->assignments) && count($this->assignments))) {
            return null;
        }
        foreach ($data as $key => $value) {
            $this->results[$key] = new Result($value, array());
        }

        $assignments = $this->assignments;

        if (isset($assignments['*'])) {
            $this->processGlobalAssignment($assignments['*'], $data);
            unset($assignments['*']);
        }
        foreach ($assignments as $key => $assignment) {
            $this->processAssignment($assignment, $key);
        }
    }

    /**
     * Loops through an array of callbacks. Because these assignments are global, we also loop through the entire data
     * set, and apply each global filter.
     * @param $assignment
     * @param $data
     */
    protected function processGlobalAssignment($assignment, $data) {

        foreach ($assignment as $callback) {

            if (null === $callback || !isset($callback['key'])) {
                continue;
            }
            foreach ($data as $k => $value) {

                // Ignore every element in the exceptions list
                if ((isset($callback["exceptions"]) && in_array($k, $callback["exceptions"]))
                    || !$this->array_key_isset($callback['key'], $this->callbacks) ) {

                    $this->results[$k]->mergeResults(
                        new Result($value, sprintf(Messages::get('exception'), $callback['key']))
                    );
                    continue;
                }

                $result = $this->callbacks[$callback['key']] (
                    $this->results[$k]->item(),
                    $callback['params'] ?? array()
                );

                $this->results[$k]->mergeResults($result);
            }
        }
    }

    /**
     * Executes each remaining callback assigned to each data key
     * @param $assignment
     * @param $key
     */
    protected function processAssignment($assignment, $key) {

        foreach ($assignment as $callback) {

            $callbackKey = $callback['key'];

            if (!$this->hasCallback($callbackKey) || $callback === null) {
                // @TODO Log - No callback found
                continue;
            }
            $result = $this->callbacks[$callbackKey] (
                $this->results[$key]->item(),
                $callback['params'] ?? array()
            );

            $this->results[$key]->mergeResults($result);
        }
    }
}