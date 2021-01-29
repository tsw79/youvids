<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/18/2019
 * Time: 01:04
 */
namespace youvids\domain\valueobject;

class __Money {

    private $amount;
    private $currency;

    public function __construct($anAmount, Currency $aCurrency) {
        $this->setAmount($anAmount);
        $this->setCurrency($aCurrency);
    }

    public static function fromMoney(Money $aMoney) {

        return new self(
            $aMoney->amount(),
            $aMoney->currency()
        );
    }

    public static function ofCurrency(Currency $aCurrency) {
        return new self(0, $aCurrency);
    }

    // If require a state change, do it like this...
    public function increaseAmountBy($anAmount) {

        return new self(
            $this->amount() + $anAmount,
            $this->currency()
        );
    }

    // For every mutable operation (add will change the state), use it like this. In this way, immutability is guaranteed!
    public function add(Money $money) {

        if (!$money->currency()->equals($this->currency())) {
            throw new \InvalidArgumentException();
        }

        return new self(
            $money->amount() + $this->amount(),
            $this->currency()
        );
    }

    public function equals(Money $money) {

        return
            $money->currency()->equals($this->currency()) &&
            $money->amount() === $this->amount();
    }

    private function setAmount($anAmount) {
        $this->amount = (int) $anAmount;
    }

    private function setCurrency(Currency $aCurrency) {
        $this->currency = $aCurrency;
    }

    public function amount() {
        return $this->amount;
    }

    public function currency() {
        return $this->currency;
    }
}


/*
$aMoney = new Money(100, new Currency('USD')); 
$otherMoney = $aMoney->increaseAmountBy(100);

var_dump($aMoney === otherMoney); // bool(false)

$aMoney = $aMoney->increaseAmountBy(100);
var_dump($aMoney === $otherMoney); // bool(false)
 */