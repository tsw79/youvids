<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/18/2019
 * Time: 01:02
 */
namespace youvids\domain\valueobject;

class __Currency {

    private $isoCode;

    public function __construct($anIsoCode) {
        $this->setIsoCode($anIsoCode);
    }

    public function equals(Currency $currency) {
        return $currency->isoCode() === $this->isoCode();
    }

    private function setIsoCode($anIsoCode) {

        if (!preg_match('/^[A-Z]{3}$/', $anIsoCode)) {
            throw new InvalidArgumentException();
        }
        $this->isoCode = $anIsoCode;
    }

    public function isoCode() {
        return $this->isoCode;
    }
}