<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/25/2019
 * Time: 05:21
 */
namespace phpchassis\data\repository;

use phpchassis\lib\traits\PhpCommons;

/**
 * Class AssociateRepository
 * @package phpchassis\data\repository
 */
class AssociateRepository extends BaseRepository {

    use PhpCommons;

    /**
     * List of Associated repositories
     * @var array
     */
    protected $associateRepos = array();

    /**
     * Adds an Associated Repository to this class
     * @param $name
     * @param $value
     * @return AssociateRepository
     */
    public function withAssocRepo($name, $value): self {

        if (is_array($this->associateRepos) && !$this->array_key_isset($name, $this->associateRepos)) {
            $this->associateRepos[$name] = $value;
        }
        else {
            if (is_object($this->associateRepos) && !isset($this->associateRepos->name)) {
                $this->associateRepos->name = $value;
            }
        }
        return $this;
    }

    /**
     * Returns the array of repositories as an instance of stdClass
     * @return AssociateRepository
     */
    public function toObject(): self {
        if (is_array($this->associateRepos)) {
            $this->associateRepos = (object)$this->associateRepos;
        }
        return $this;
    }
}