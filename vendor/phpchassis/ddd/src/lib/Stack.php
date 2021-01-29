<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/27/2019
 * Time: 06:55
 */
namespace phpchassis\lib;

use SplStack;

/**
 * Class Stack
 *  A simple algorithm normally implemented as Last In First Out (LIFO).
 * 
 *    One possible use for a stack is to store messages in which we retrieve the latest message first:
 * 
 *      $stack = new Stack();
 *      echo 'Do Something ... ' . PHP_EOL;
 *      $stack->push('1st Message: ' . date('H:i:s'));
 *      sleep(3);
 * 
 *      echo 'Do Something Else ... ' . PHP_EOL;
 *      $stack->push('2nd Message: ' . date('H:i:s'));
 *      sleep(3);
 * 
 *      echo 'Do Something Else Again ... ' . PHP_EOL;
 *      $stack->push('3rd Message: ' . date('H:i:s'));
 *      sleep(3);
 * 
 *      echo 'What Time Is It?' . PHP_EOL;
 *      foreach ($stack() as $item) {
 *        echo $item . PHP_EOL;
 *      }
 * 
 *
 * @package PhpChassis\lib
 */
class Stack {

    protected $stack;

    public function __construct() {
        $this->stack = new SplStack();
    }

    public function push($message) {
        $this->stack->push($message);
    }

    public function pop() {
        return $this->stack->pop();
    }

    /**
     * Returns an instance of the stack property. This allows us to use the object in a direct function call.
     * @return SplStack
     */
    public function __invoke() {
        return $this->stack;
    }
}