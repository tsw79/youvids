<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/20/2019
 * Time: 08:23
 */
namespace phpchassis\ui\form\element;

use phpchassis\ui\form\Generic;

/**
 * Class Form
 * @package phpchassis\form\element
 */
class Form extends Generic {

    /**
     * @return string
     */
    public function getInputOnly(): string {
        $this->pattern = '<form name="%s" %s> ' . PHP_EOL;
        return sprintf($this->pattern, $this->name, $this->getAttribs());
    }

    /**
     * @return string
     */
    public function closeTag(): string {
        return '</' . $this->type . '>';
    }
}