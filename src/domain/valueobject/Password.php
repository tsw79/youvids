<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/3/2019
 * Time: 07:57
 */
namespace youvids\domain\valueobject;

use Assert\Assertion;
use phpchassis\data\valueobject\ValueObjectInterface;
use phpchassis\validate\DomainValidationInterface;

/**
 * Class Password
 * @package youvids\domain\valueobject
 */
class Password implements ValueObjectInterface, DomainValidationInterface {

    /**
     * MIN_LENGTH
     */
    private const MIN_LENGTH = 5;

    /**
     * MAX_LENGTH
     */
    private const MAX_LENGTH = 30;

    /**
     * FORMAT
     */
    //private const FORMAT = "/^[a-zA-Z0-9_]+$/";

    /**
     * @var string
     */
    private $password;

    /**
     * Password constructor.
     * @param string $password
     */
    private function __construct(string $password) {
        $this->assert($password);
    }

    /**
     * Creates a new instance of Password
     * @param $value
     * @return Password
     */
    public static function create($value): self {
        return new self($value);
    }

    /**
     * Asserts
     * @param $value
     * @return bool
     */
    private function assert($password): bool {

        Assertion::alnum($this->password);  // is alphanumeric
        Assertion::betweenLength($this->password, self::MIN_LENGTH, self::MAX_LENGTH);
        $this->password = $password;
        return true;
    }

    /**
     * Returns true if the two value objects are the same
     * @param ValueObjectInterface $password
     * @return bool
     */
    public function equalsTo(ValueObjectInterface $password): bool {
        return $this->get() === $password->get();
    }

    /**
     * Getter
     * @return string
     */
    public function get(): string {
        return $this->password;
    }
}