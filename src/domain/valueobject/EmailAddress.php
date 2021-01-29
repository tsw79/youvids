<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/28/2019
 * Time: 18:14
 */
namespace youvids\domain\valueobject;

use Assert\Assertion;
use phpchassis\data\valueobject\ValueObjectInterface;
use phpchassis\validate\DomainValidationInterface;

/**
 * Class EmailAddress
 * @package youvids\data\valueobject
 */
final class EmailAddress implements ValueObjectInterface, DomainValidationInterface {

    /**
     * @var string
     */
    private $email;

    /**
     * EmailAddress constructor.
     * @param $email
     */
    private function __construct(string $email) {
        $this->assert($email);
        $this->email = $email;
    }

    /**
     * Creates a new instance of EmailAddress
     * @param $value
     * @return EmailAddress
     */
    public static function create($value): self {
        return new self($value);
    }

    /**
     * Asserts
     * @param $value
     * @return bool
     */
    private function assert($value): bool {
        Assertion::email($value);
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
        return $this->email;
    }
}