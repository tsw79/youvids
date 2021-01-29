<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/20/2019
 * Time: 02:04
 */
namespace phpchassis\ui\form\element;

use phpchassis\ui\form\Generic;

/**
 * Class Radio
 * @package phpchassis\form
 */
class Radio extends Generic {

    const DEFAULT_AFTER = TRUE;
    const DEFAULT_SPACER = '&nbps;';
    //const DEFAULT_OPTION_KEY = 0;
    const DEFAULT_OPTION_VALUE = 'Choose';

    protected $after = self::DEFAULT_AFTER;
    protected $spacer = self::DEFAULT_SPACER;
    protected $options = array();
    protected $selectedKey = parent::DEFAULT_OPTION_KEY;

    /**
     * setOptions
     * @method_override
     * @param array $options
     * @param int $selectedKey
     * @param string $spacer
     * @param bool $after
     * @return Radio
     */
    public function setOptions(array $options, $selectedKey = parent::DEFAULT_OPTION_KEY, $spacer = self::DEFAULT_SPACER, $after  = true): self {

        $this->after = $after;
        $this->spacer = $spacer;
        $this->options = $options;
        $this->selectedKey = $selectedKey;
        return $this;
    }

    /**
     * getInputOnly
     */
    public function getInputOnly(): string {

        $count  = 1;
        $baseId = $this->attributes['id'];
        $output = '';

        foreach ($this->options as $key => $value) {
            $this->attributes['id'] = $baseId . $count++;
            $this->attributes['value'] = $key;

            if ($key == $this->selectedKey) {
                $this->attributes['checked'] = '';
            }
            elseif (isset($this->attributes['checked'])) {
                unset($this->attributes['checked']);
            }
            if ($this->after) {
                $html = parent::getInputOnly() . $value;
            }
            else {
                $html = $value . parent::getInputOnly();
            }
            $output .= $this->spacer . $html;
        }
        return $output;
    }
}