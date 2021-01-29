<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/20/2019
 * Time: 02:38
 */
namespace phpchassis\ui\form\element;

use phpchassis\ui\form\Generic;

/**
 * Class Select
 * @package phpchassis\form\element
 */
class Select extends Generic {

    protected $options;
    protected $selectedKey = parent::DEFAULT_OPTION_KEY;

    /**
     * setOptions
     * @param array $options
     * @param $selectedKey
     */
    public function setOptions(array $options, $selectedKey = parent::DEFAULT_OPTION_KEY, $spacer = null): void {

        $this->options = $options;
        $this->selectedKey = $selectedKey;

        if (isset($this->attributes['multiple'])) {
            $this->name .= '[]';
        }
    }

    /**
     * getSelect
     * @return string
     */
    protected function getSelect(): string {
        $this->pattern = '<select name="%s" %s> ' . PHP_EOL;
        return sprintf($this->pattern, $this->name, $this->getAttribs());
    }

    /**
     * getOptions
     * @return string
     */
    protected function getOptions(): string {

        $output = '';

        foreach ($this->options as $key => $value) {
            if (is_array($this->selectedKey)) {
                $selected = (in_array($key, $this->selectedKey)) ? ' selected' : '';
            }
            else {
                $selected = ($key == $this->selectedKey) ? ' selected' : '';
            }
            $output .= "<option value='{$key}' {$selected}>{$value}</option>";
        }
        return $output;
    }

    /**
     * getInputOnly
     * @return string
     */
    public function getInputOnly(): string {

        $output = $this->getSelect();
        $output .= $this->getOptions();
        $output .= '</' . $this->type() . '>';
        return $output;
    }
}