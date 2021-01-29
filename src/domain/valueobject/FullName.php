<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/18/2019
 * Time: 00:09
 */
namespace youvids\domain\valueobject;

use Assert\Assertion;
use phpchassis\data\valueobject\ValueObjectInterface;
use phpchassis\validate\DomainValidationInterface;

/**
 * Class FullName
 * @package youvids\domain\valueobject
 */
class FullName implements ValueObjectInterface, DomainValidationInterface {

    /**
     * Condition: Valid format = alphanumeric characters or underscores
     */
    private const FORMAT = '/^[a-zA-Z0-9_]+$/';

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
    * FullName constructor.
    * @param $email
    */
    private function __construct(string $firstName, string $lastName) {

        $this->assert($firstName, $lastName);
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * Creates a new instance of EmailAddress
     * @param $value
     * @return EmailAddress
     */
    public static function create(string $firstName, string $lastName): self {
        return new self($firstName, $lastName);
    }

    /**
     * Asserts a valid state of $value
     * @param $value
     * @return bool
     */
    private function assert(string $firstName, string $lastName): bool {

        Assertion::regex($firstName, self::FORMAT);
        Assertion::regex($lastName, self::FORMAT);
    }

    /**
     * Returns true if the two value objects are the same
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equalsTo(ValueObjectInterface $fullName): bool {

        return $fullName->firstName === $this->firstName
            && $fullName->lastName === $this->lastName;
    }

    /**
     * Returns the value of firstName
     * @return string
     */
    public function firstName() {
        return $this->firstName;
    }

    /**
     * Returns the value of lastName
     * @return string
     */
    public function lastName() {
        return $this->lastName;
    }
}