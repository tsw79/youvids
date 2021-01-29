<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/26/2019
 * Time: 06:24
 */
namespace youvids\domain\valueobject;

use phpchassis\data\valueobject\ValueObjectInterface;

/**
 * Class VideoPrivacy
 * @package src\data\valueobject
 */
class VideoPrivacy implements ValueObjectInterface {

    /**
     * @const PRIVATE
     */
    private const PRIVATE = 1;

    /**
     * @const PUBLIC
     */
    private const PUBLIC = 2;

    /**
     * @var int
     */
    private $status;

    /**
     * VideoPrivacy constructor.
     * @param int $status
     */
    public function __construct(int $status) {
        $this->status = $status;
    }

    /**
     * Returns an instance of VideoPrivacy with the status set to PRIVATE
     * @return VideoPrivacy
     */
    public static function private(): self {
        return new self(self::PRIVATE);
    }

    /**
     * Returns an instance of VideoPrivacy with the status set to PUBLIC
     * @return VideoPrivacy
     */
    public static function public(): self {
        return new self(self::PUBLIC);
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
     * Returns the value of the Value Object
     * @return string
     */
    public function get(): int {
        return $this->status;
    }

    // @TODO Need o remove this. Perhaps, creating a new interface without the create method??
    public static function create($value): self { }
}