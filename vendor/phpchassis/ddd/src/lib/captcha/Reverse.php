<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/27/2019
 * Time: 04:13
 */
namespace phpchassis\lib\captcha;

/**
 * Class Reverse produces text in reverse.
 * @package phpchassis\lib\captcha
 */
class Reverse implements CaptchaInterface {

    const DEFAULT_LABEL = 'Type this in reverse';
    const DEFAULT_LENGTH = 6;

    /**
     * @var Phrase
     */
    protected $phrase;

    /**
     * Reverse constructor.
     * @param string $label
     * @param int $length
     * @param bool $includeNumbers
     * @param bool $includeUpper
     * @param bool $includeLower
     * @param bool $includeSpecial
     * @param null $otherChars
     * @param array|null $suppressChars
     * @throws \Exception
     */
    public function __construct( $label = self::DEFAULT_LABEL, $length = self:: DEFAULT_LENGTH, $includeNumbers = true,
                                 $includeUpper = true, $includeLower = true, $includeSpecial = false, $otherChars = null,
                                 array $suppressChars = null ) {

        $this->label = $label;

        $this->phrase = new Phrase(
            $length,
            $includeNumbers,
            $includeUpper,
            $includeLower,
            $includeSpecial,
            $otherChars,
            $suppressChars);
    }

    /**
     * @return string
     */
    public function getLabel(): string {
        return $this->label;
    }

    /**
     * Returns the phrase in reverse
     * @return string
     */
    public function getImage() {
        return strrev($this->phrase->phrase());  // return strrev($this->phrase->getPhrase());
    }

    /**
     * @return mixed
     */
    public function getPhrase() {
        return $this->phrase->phrase();  // return strrev($this->phrase->getPhrase());
    }
}