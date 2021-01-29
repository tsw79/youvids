<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/29/2019
 * Time: 01:03
 */
namespace youvids\domain\valueobject;

use Assert\Assertion;
use phpchassis\data\valueobject\ValueObjectInterface;
use phpchassis\validate\DomainValidationInterface;

/**
 * Class Username
 * @package youvids\data\valueobject
 */
final class Username implements ValueObjectInterface, DomainValidationInterface {

    /**
     * Condition: Minimum Length
     */
    private const MIN_LENGTH = 5;

    /**
     * Condition: Maximum Length
     */
    private const MAX_LENGTH = 10;

    /**
     * Condition: Valif format
     */
    private const FORMAT = '/^[a-zA-Z0-9_]+$/';

    /**
     * @var
     */
    private $username;

    /**
     * Username constructor.
     * @param string $username
     * @internal param $value
     */
    private function __construct(string $username) {

        $this->assert($username);
        $this->username = $username;
    }

    /**
     * Creates a new instance of Username
     * @param $value
     * @return EmailAddress
     */
    public static function create($value): self {
        return new self($value);
    }

    /**
     * Asserts a valid state of $value
     * @param $value
     * @return bool
     */
    private function assert($value): bool {

        //Assertion::notEmpty($value);                        // Must not be empty
        Assertion::minLength($value, self::MIN_LENGTH);     // Must be at least 5 characters
        Assertion::maxLength($value, self::MAX_LENGTH);     // Must be less than 10 characters
        Assertion::regex(self::FORMAT);                     // Must follow a format of alphanumeric characters or underscores
        return true;
    }

    /**
     * Returns true if the two value objects are the same
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equalsTo(ValueObjectInterface $object): bool {
        return $this->get() === $object->get();
    }


    /**
     * Getter
     * @return string
     */
    public function get(): string {
        return $this->username;
    }
}