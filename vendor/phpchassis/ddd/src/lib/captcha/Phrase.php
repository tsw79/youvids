<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/27/2019
 * Time: 04:03
 */
namespace phpchassis\lib\captcha;

/**
 * Class Phrase
 *  Generates the phrase to be presented (and decoded by the web visitor)
 * @package phpchassis\lib\captcha
 */
class Phrase {

    const DEFAULT_LENGTH   = 5;
    const DEFAULT_NUMBERS  = '0123456789';
    const DEFAULT_UPPER    = 'ABCDEFGHJKLMNOPQRSTUVWXYZ';
    const DEFAULT_LOWER    = 'abcdefghijklmnopqrstuvwxyz';
    const DEFAULT_SPECIAL  = '¬\`|!"£$%^&*()_-+={}[]:;@\'~#<,>.?/|\\';
    const DEFAULT_SUPPRESS = ['O','l'];

    protected $phrase;
    protected $includeNumbers;
    protected $includeUpper;
    protected $includeLower;
    protected $includeSpecial;
    protected $otherChars;
    protected $suppressChars;
    protected $string;
    protected $length;

    /**
     * Phrase constructor.
     *
     * @param int $length
     * @param bool $includeNumbers
     * @param bool $includeUpper
     * @param bool $includeLower
     * @param bool $includeSpecial
     * @param string $otherChars
     * @param array|null $suppressChars
     * @throws \Exception
     */
    public function __construct($length = null, $includeNumbers = true, $includeUpper= true, $includeLower= true,
                                $includeSpecial = false, $otherChars = null, array $suppressChars = null) {

        $this->length = $length ?? self::DEFAULT_LENGTH;
        $this->includeNumbers = $includeNumbers;
        $this->includeUpper = $includeUpper;
        $this->includeLower = $includeLower;
        $this->includeSpecial = $includeSpecial;
        $this->otherChars = $otherChars;
        $this->suppressChars = $suppressChars ?? self::DEFAULT_SUPPRESS;
        $this->phrase = $this->generatePhrase();
    }

    /**
     * This method initializes the base string.
     *
     * @return mixed|string
     */
    public function initString() {

        $string = '';

        if ($this->includeNumbers) {
            $string .= self::DEFAULT_NUMBERS;
        }
        if ($this->includeUpper) {
            $string .= self::DEFAULT_UPPER;
        }
        if ($this->includeLower) {
            $string .= self::DEFAULT_LOWER;
        }
        if ($this->includeSpecial) {
            $string .= self::DEFAULT_SPECIAL;
        }
        if ($this->otherChars) {
            $string .= $this->otherChars;
        }
        if ($this->suppressChars) {
            $string = str_replace( $this->suppressChars, '', $string );
        }
        return $string;
    }

    /**
     * Generates a random phrase
     * @return string
     * @throws \Exception
     */
    public function generatePhrase() {

        $phrase = '';
        $this->string = $this->initString();
        $max = strlen($this->string) - 1;

        for ($x = 0; $x < $this->length; $x++) {
            $phrase .= substr( $this->string, random_int(0, $max), 1 );
        }
        return $phrase;
    }

    /**
     * Getter/Setter for phrase
     * @param array $phrase
     * @return array
     */
    public function phrase(array $phrase = null) {
        if(null === $phrase) {
            return $this->phrase;
        }
        else {
            $this->phrase = $phrase;
        }
    }

    /**
     * Getter/Setter for includeNumbers
     * @param bool $includeNumbers
     * @return bool
     */
    public function includeNumbers(bool $includeNumbers = null) {
        if(null === $includeNumbers) {
            return $this->includeNumbers;
        }
        else {
            $this->includeNumbers = $includeNumbers;
        }
    }

    /**
     * Getter/Setter for includeUpper
     * @param bool $includeUpper
     * @return bool
     */
    public function includeUpper(bool $includeUpper = null) {
        if(null === $includeUpper) {
            return $this->includeUpper;
        }
        else {
            $this->includeUpper = $includeUpper;
        }
    }

    /**
     * Getter/Setter for includeLower
     * @param bool $includeLower
     * @return bool
     */
    public function includeLower(bool $includeLower = null) {
        if(null === $includeLower) {
            return $this->includeLower;
        }
        else {
            $this->includeLower = $includeLower;
        }
    }

    /**
     * Getter/Setter for includeSpecial
     * @param bool $includeSpecial
     * @return bool
     */
    public function includeSpecial(bool $includeSpecial = null) {
        if(null === $includeSpecial) {
            return $this->includeSpecial;
        }
        else {
            $this->includeSpecial = $includeSpecial;
        }
    }

    /**
     * Getter/Setter for otherChars
     * @param string $otherChars
     * @return string
     */
    public function otherChars(string $otherChars = null) {
        if(null === $otherChars) {
            return $this->otherChars;
        }
        else {
            $this->otherChars = $otherChars;
        }
    }

    /**
     * Getter/Setter for suppressChars
     * @param array $suppressChars
     * @return array
     */
    public function suppressChars(array $suppressChars = null) {
        if(null === $suppressChars) {
            return $this->suppressChars;
        }
        else {
            $this->suppressChars = $suppressChars;
        }
    }

    /**
     * Getter/Setter for string
     * @param string $string
     * @return string
     */
    public function string(string $string = null) {
        if(null === $string) {
            return $this->string;
        }
        else {
            $this->string = $string;
        }
    }

    /**
     * Getter/Setter for length
     * @param int $length
     * @return int
     */
    public function length(int $length = null) {
        if(null === $length) {
            return $this->length;
        }
        else {
            $this->length = $length;
        }
    }
}