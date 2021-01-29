<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/15/2019
 * Time: 19:41
 */
namespace phpchassis\configs\base;

/**
 * Class ConfigContext (Based on the Strategy pattern)
 *  This class serves as a buffer between possible number of clients and the concrete strategies they'd request.
 *
 * Note:  The context class has been added to prevent tight binding between the clients and the strategies.
 */
class ConfigContext {

    /**
     * @var ConfigStrategy $strategy
     */
    private $confStrategy;

    public function configInterface(ConfigStrategy $strategy) {

        $this->confStrategy = $strategy;
        $this->confStrategy->init();
    }

    public function confStrategy($strategy = null) {

        // Getter
        if($strategy === null) {
            return $this->confStrategy;
        }
        // Setter
        else {
            $this->confStrategy = $strategy;
        }
    }
}