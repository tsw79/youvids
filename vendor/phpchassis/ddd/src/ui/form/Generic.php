<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/20/2019
 * Time: 00:10
 */
namespace phpchassis\ui\form;

/**
 * Class Generic
 * @package phpchassis\form
 */
class Generic {

    const ROW = 'row';
    const FORM = 'form';
    const INPUT = 'input';
    const LABEL = 'label';
    const ERRORS = 'errors';
    const TYPE_FORM = 'form';
    const TYPE_TEXT = 'text';
    const TYPE_EMAIL = 'email';
    const TYPE_RADIO = 'radio';
    const TYPE_SUBMIT = 'submit';
    const TYPE_SELECT = 'select';
    const TYPE_PASSWORD = 'password';
    const TYPE_CHECKBOX = 'checkbox';

    const DEFAULT_TYPE = self::TYPE_TEXT;
    const DEFAULT_WRAPPER = 'div';
    const DEFAULT_OPTION_KEY = 0;

    protected $name;
    protected $type    = self::DEFAULT_TYPE;
    protected $label   = '';
    protected $errors  = array();
    protected $wrappers;
    protected $attributes;    // HTML form attributes
    protected $pattern =  '<input type="%s" name="%s" %s>';

    /**
     * Generic constructor.
     * @param $name
     * @param $type
     * @param string $label
     * @param array $wrappers
     * @param array $attributes
     * @param array $errors
     */
    public function __construct($name, $type, $label = '', array $wrappers = array(), array $attributes = array(), array $errors = array()) {

        $this->name = $name;

        if ($type instanceof Generic) {
            $this->type       = $type->type();
            $this->label      = $type->label();
            $this->errors     = $type->errors();
            $this->wrappers   = $type->wrappers();
            $this->attributes = $type->attributes();
        }
        else {
            $this->type = $type ?? self::DEFAULT_TYPE;
            $this->label = $label;
            $this->errors = $errors;
            $this->attributes = $attributes;

            if ($wrappers) {
                $this->wrappers = $wrappers;
            }
            else {
                $this->wrappers[self::INPUT]['type']  = self::DEFAULT_WRAPPER;
                $this->wrappers[self::LABEL]['type']  = self::DEFAULT_WRAPPER;
                $this->wrappers[self::ERRORS]['type'] = self::DEFAULT_WRAPPER;
            }
        }
        $this->attributes['id'] = $name;
    }

    /**
     * Produces the appropriate wrapping tags for the label, input, and error display.
     *  Note:  $wrappers has three primary subkeys: INPUT, LABEL, and ERRORS.
     * @param $type
     * @return string
     */
    public function getWrapperPattern($type): string {

        $pattern = '<' . $this->wrappers[$type]['type'];

        foreach ($this->wrappers[$type] as $key => $value) {
            if ($key != 'type') {
                $pattern .= ' ' . $key . '="' . $value . '"';
            }
        }
        $pattern .= '>%s</' . $this->wrappers[$type]['type'] . '>';
        return $pattern;
    }

    /**
     * Produces a string of key-value pairs (attributes) separated by a space
     * @return string
     */
    public function getAttribs(): string {

        $attribs = '';

        foreach ($this->attributes as $key => $value) {
            $key = strtolower($key);
            if ($value) {
                if ($key == 'value') {
                    if (is_array($value)) {
                        foreach ($value as $k => $i) {
                            $value[$k] = htmlspecialchars($i);
                        }
                    }
                    else {
                        $value = htmlspecialchars($value);
                    }
                }
                elseif ($key == 'href') {
                    //  For security reasons, we escape the values! They could be user-supplied hence skeptical.
                    $value = urlencode($value);
                }
                $attribs .= $key . '="' . $value . '" ';
            }
            else {
                $attribs .= $key . ' ';
            }
        }
        return trim($attribs);
    }

    /**
     * Display element validation errors
     *  Returns an empty string if no errors, otherwise errors are rendered as:
     *      <ul>
     *          <li>error 1</li>
     *          <li>error 2</li>
     *      </ul>
     *
     * @return string
     */
    public function getErrors() {

        if (!$this->errors || count($this->errors === 0)) {
            return '';
        }
        $html = '';
        $pattern = '<li>%s</li>';
        $html .= '<ul>';

        foreach ($this->errors as $error) {
            $html .= sprintf($pattern, $error);
        }
        $html .= '</ul>';
        return sprintf($this->getWrapperPattern(self::ERRORS), $html);
    }

    /**
     * Renders the element
     * @return string
     */
    public function render() {
        return $this->label() . $this->getInputWithWrapper();
    }

    /**
     * @param $key
     * @param $value
     */
    public function setSingleAttribute($key, $value) {
        $this->attributes[$key] = $value;
    }

    /**
     * @param $error
     */
    public function addSingleError($error) {
        $this->errors[] = $error;
    }

    /**
     * @return string
     */
    public function getInputOnly() {
        return sprintf($this->pattern, $this->type, $this->name, $this->getAttribs());
    }

    /**
     * @return string
     */
    public function getInputWithWrapper() {
        return sprintf($this->getWrapperPattern(self::INPUT), $this->getInputOnly());
    }

    public function pattern($pattern) {
        $this->pattern = $pattern;
    }

    /**
     * Getter/Setter for type
     * @param string $type
     * @return string
     */
    public function type($type = null) {
        if($type === null) {
            return $this->type;
        }
        else{
            $this->type = $type;
            return $this;
        }
    }

    /**
     * Getter/Setter for name
     * @param string $name
     * @return string
     */
    public function name($name = null) {
        if($name === null) {
            return $this->name;
        }
        else {
            $this->name = $name;
            return $this;
        }
    }

    /**
     * Getter/Setter for label
     * @param string $label
     * @return string
     */
    public function label($label = null) {
        if($label === null) {
            return sprintf($this->getWrapperPattern(self::LABEL), $this->label);
        }
        else {
            $this->label = $label;
            return $this;
        }
    }

    /**
     * Getter/Setter for wrappers
     * @param array $wrappers
     * @return array
     */
    public function wrappers(array $wrappers = null) {
        if($wrappers === null) {
            return $this->wrappers;
        }
        else {
            $this->wrappers = $wrappers;
            return $this;
        }
    }

    /**
     * Getter/Setter for errors
     * @param array $errors
     * @return array
     */
    public function errors(array $errors = null) {
        if($errors === null) {
            return $this->errors;
        }
        else {
            $this->errors = $errors;
            return $this;
        }
    }

    /**
     * Getter/Setter for attributes
     * @param array $attributes
     * @return array
     */
    public function attributes(array $attributes = null) {
        if($attributes === null) {
            return $this->attributes;
        }
        else {
            $this->attributes = $attributes;
            return $this;
        }
    }
}