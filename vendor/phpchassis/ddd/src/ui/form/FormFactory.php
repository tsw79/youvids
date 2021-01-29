<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/20/2019
 * Time: 08:10
 */
namespace phpchassis\ui\form;

use phpchassis\filter\ {Filter, Validator};
use phpchassis\ui\form\element\Form;

/**
 * Class FormFactory
 * @package phpchassis\form
 */
class FormFactory {

    const DATA_NOT_FOUND      = 'Data not found. Run setData()';
    const FILTER_NOT_FOUND    = 'Filter not found. Run setFilter()';
    const VALIDATOR_NOT_FOUND = 'Validator not found. Run setValidator()';

    protected $filter;
    protected $validator;
    protected $data;

    /**
     * @var array
     */
    protected $elements;

    /**
     * @var FormFactory
     */
    protected $formFactory = null;

    /**
     * @param array $elements
     * @return FormFactory
     */
    public static function create(array $elements): self {
        $f = new self();
        return $f->doCreate($f, $elements);
    }

    /**
     * @param self $formFactory
     * @return FormFactory
     */
    private function doCreate(self $formFactory, array $elements): self {

        $this->formFactory = $formFactory;

        foreach ($elements as $key => $params) {

            // check for parameters that are optional in the constructor for the Generic class
            $params['errors'] = $params['errors'] ?? array();
            $params['wrappers'] = $params['wrappers'] ?? array();
            $params['attributes'] = $params['attributes'] ?? array();

            // Create the form element instance and store it in $elements
            $this->formFactory->elements[$key] = new $params['class'] (
                $key,
                $params['type'],
                $params['label'],
                $params['wrappers'],
                $params['attributes'],
                $params['errors']
            );
            if (isset($params['options'])) {

                $optionsList = $params["options"]["list"];
                $selected = $params["options"]["selected"];
                //$this->formFactory->elements[$key]->setOptions($optionsList, $selected, "<br>");

                switch ($params['type']) {
                   case Generic::TYPE_RADIO:
                   case Generic::TYPE_CHECKBOX:
                       $this->formFactory->elements[$key]->setOptions($optionsList, $selected, "<br>");
                       break;
                   case Generic::TYPE_SELECT:
                       $this->formFactory->elements[$key]->setOptions($optionsList, $selected);
                       break;
                   default                     :
                       $this->formFactory->elements[$key]->setOptions($optionsList, $selected);
                       break;
                }
            }
        }
        return $this->formFactory;
    }

    /**
     * @param $wrapper
     * @return string
     */
    protected function getWrapperPattern($wrapper): string {

        $type = $wrapper['type'];
        unset($wrapper['type']);
        $pattern = '<' . $type;

        foreach ($wrapper as $key => $value) {
            $pattern .= ' ' . $key . '="' . $value . '"';
        }
        $pattern .= '>%s</' . $type . '>';
        return $pattern;
    }

    /**
     * Renders the form
     * @param $formConfig
     * @return string
     */
    public function render($formConfig) {

        $rowPattern = $this->formFactory->getWrapperPattern($formConfig['row_wrapper']);
        $contents   = '';

        foreach ($this->formFactory->elements() as $element) {
            $contents .= sprintf($rowPattern, $element->render());
        }
        $formTag = new Form(
            $formConfig['name'],
            Generic::TYPE_FORM,
            '',
            array(),
            $formConfig['attributes']
        );
        $formPattern = $this->formFactory->getWrapperPattern($formConfig['form_wrapper']);

        if (isset($formConfig['form_tag_inside_wrapper']) && !$formConfig['form_tag_inside_wrapper']) {

            $formPattern = '%s' . $formPattern . '%s';
            return sprintf($formPattern, $formTag->getInputOnly(), $contents, $formTag->closeTag());
        }
        else {
            return sprintf($formPattern, $formTag->getInputOnly() . $contents . $formTag->closeTag());
        }
    }

    /**
     * Validates data
     * @return bool
     */
    public function runValidation(): bool {

        if (!$this->data){
            throw new \RuntimeException(self::DATA_NOT_FOUND);
        }
        if (!$this->validator){
            throw new \RuntimeException(self::VALIDATOR_NOT_FOUND);
        }
        // Valid or not, we will use this flag to indicate to the View that there were some error messages or not.
        $valid = $this->validator->process($this->data);
        $results = $this->validator->getResults();

        foreach ($this->elements as $element) {
            if (isset($results[$element->name()])) {
                $element->errors(
                    $results[$element->name()]->messages()
                );
            }
        }

        return $valid;
    }

    /**
     * Filters data
     * @TODO Need to make use of Logging for reporting purposes!
     */
    public function runFilters(): void {

        if (!$this->data){
            throw new \RuntimeException(self::DATA_NOT_FOUND);
        }
        if (!$this->filter){
            throw new \RuntimeException(self::FILTER_NOT_FOUND);
        }
        $this->filter->process($this->data);
        $results = $this->filter->getResults();

        foreach ($results as $key => $result) {
            if (isset($this->elements[$key])) {
                $this->elements[$key]->setSingleAttribute('value', $result->item());
                if (isset($result->messages) && count($result->messages())) {
                    $messages = $result->messages();
                    foreach ($messages as $message) {
                        $this->elements[$key]->addSingleError($message);
                    }
                }
            }
        }
    }

    /**
     * Getter/Setter for elements
     * @param array $elements
     * @return FormFactory
     */
    public function elements(array $elements = null) {
        if($elements === null) {
            return $this->elements;
        }
        else {
            $this->elements = $elements;
            return $this;
        }
    }

    /**
     * Getter/Setter for filter
     * @param Filter|null $filter
     * @return FormFactory
     */
    public function filter(Filter $filter = null) {
        if($filter === null) {
            return $this->filter;
        }
        else {
            $this->filter = $filter;
            return $this;
        }
    }

    /**
     * Getter/Setter for validator
     * @param Validator|null $validator
     * @return FormFactory
     */
    public function validator(Validator $validator = null) {
        if($validator === null) {
            return $this->validator;
        }
        else {
            $this->validator = $validator;
            return $this;
        }
    }

    /**
     * Getter/Setter for data
     * @param array|null $data
     * @return FormFactory
     */
    public function data(array $data = null) {
        if($data === null) {
            return $this->data;
        }
        else {
            $this->data = $data;
            return $this;
        }
    }
}