<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/18/2019
 * Time: 18:42
 */
namespace phpchassis\filter;

/**
 * Class Callbacks
 *  List of configurations for filtering callbacks
 * @package phpchassis\filter
 */
class Callbacks {

    public const TYPE_VALIDATOR = 1;
    public const TYPE_FILTER = 2;

    /**
     * @var array
     */
    private $validators = null;

    /**
     * @var array
     */
    private $filters = null;

    /**
     * Getter for validators
     * @param array $validators
     * @return Callbacks
     */
    public function getValidators(): self {
        if(null === $this->validators) {
            $this->validators = require_once("callback_validators.php");;
        }
        return $this;
    }

    /**
     * Getter for validators
     * @param array $filters
     * @return Callbacks
     */
    public function getFilters(): self {
        if(null === $this->filters) {
            $this->filters = require_once("callback_filters.php");;
        }
        return $this;
    }

    /**
     * Returns a filter type based on the given filter
     * @param int|null $filterType
     * @return array
     */
    public static function get(int $filterType = null): array {

        switch ($filterType) {
            case self::TYPE_VALIDATOR:
                $value = self::validators();
                break;
            case self::TYPE_FILTER:
                $value = self::filters();
                break;
            default:
                $value = [
                    'validators' => self::validators(),
                    'filters'    => self::filters(),
                ];
        }
        return $value;
    }

    /**
     * Returns all validator callbacks
     * @return array
     */
    private static function validators(): array {
        return require_once("callback_validators.php");
    }

    /**
     * Returns all filter callbacks
     * @return array
     */
    private static function filters(): array {
        return require_once("callback_filters.php");
    }
}