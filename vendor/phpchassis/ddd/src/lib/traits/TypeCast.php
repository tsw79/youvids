<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/29/2019
 * Time: 11:04
 */
namespace phpchassis\lib\traits;

/**
 * Class TypeCast
 * @package phpchassis\traits
 */
trait TypeCast {

    /*
      https://dev.to/mattsparks/how-to-use-php-traits-459m  
    
      How to use:
      -----------
      
      use TypeCast;

      $class = new aClass();
      $class->setTitle("title");

      var_dump($class->asArray());
      var_dump($class->asJson());
    */

    public function asArray(): array {
        return get_object_vars($this);
    }

    public function asJson(): string {
        return json_encode($this->asArray());
    }
}