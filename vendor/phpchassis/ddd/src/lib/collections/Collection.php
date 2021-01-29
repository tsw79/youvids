<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/28/2019
 * Time: 01:01
 */
namespace phpchassis\lib\collections;

use Traversable;
use ArrayIterator;
use phpchassis\lib\traits\ {PhpCommons, Dynamicable};

/**
 * Class Collection
 *  https://stackoverflow.com/questions/28997958/using-phps-arrayobject-to-implement-a-collection-of-model-objects
 *
 *      Doctrine:  https://github.com/illuminate/support/blob/master/Collection.php#L130
 *
 * @package vendor\phpchassis\lib\collections
 */
class Collection implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable {

    use PhpCommons;

    /**
     * @var array
     */
    protected $items = array();

    /**
     * Collection constructor.
     * @param array $items
     */
    public function __construct(array $items = []) {
        $this->items = $this->collectionableItems($items);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator(): ArrayIterator {
        return new ArrayIterator($this->items);
    }

    /**
     * Wrapper for getIterator()
     * @return Traversable
     */
    public function iterator(): ArrayIterator {
        return $this->getIterator();
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset --key <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset) {
        return array_key_isset($offset, $this->items); 
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset) {
        return $this->items[$offset];
    }

    /**
     * Wrapper:  Returns a particular item from the collection by key.
     * @param  mixed  $key
     * @return mixed
     */
    public function get($key) {
        if ($this->offsetExists($key)) {
            return $this->offsetGet($key);
        }
        return null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->items[] = $value;
        }
        else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Wrapper for offsetSet()
     * @param $value
     * @param null $key
     */
    public function set($value, $key = null): void {
        $this->offsetSet($key, $value);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset) {
        unset($this->items[$offset]);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count() {
        return count($this->items);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize() {
        return json_encode($this->items);
    }

    /**
     * Wrapper:  Get the collection of items as JSON.
     * @return mixed
     */
    public function toJson() {
        return $this->jsonSerialize();
    }

    /**
     * Returns all items in the collection
     * @return array
     */
    public function all() {
        return $this->items;
    }

    /**
     * @param $items
     * @return array
     */
    public function collectionableItems($items) {

        if (is_array($items)) {
            return $items;
        }
        elseif ($items instanceof self) {
            return $items->all();
        }
        return (array) $items;
    }

    /**
     * Converts the collection to its string representation.
     * @return string
     */
    public function __toString() {
        return $this->toJson();
    }
}