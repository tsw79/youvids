<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/27/2019
 * Time: 06:29
 */
namespace phpchassis\lib;

use ArrayIterator;

/**
 * Class LinkedList
 *  LinkedList is where one list contains keys that point to keys in another list.
 *    - One index might produce a list of items by ID.
 *    - Another index might yield a list according to title.
 *
 * @package PhpChassis\lib
 */
class LinkedList {

    /**
     * buildLinkedList method produces a display of items in a different order.
     * 
     *    How to use orderedList:
     *    -----------------------
     *    foreach ($linked as $key => $link) {
     *      $output .= printRow($customer[$link]);
     *    }
     * 
     * @param array $primary
     * @param callable $makeLink    Anonymous function generates the new key in order to provide extra flexibility.
     * @return ArrayIterator
     */
    public static function orderedList(array $primary, callable $makeLink): ArrayIterator {

        $linked = new ArrayIterator();

        foreach ($primary as $key => $row) {
            $linked->offsetSet($makeLink($row), $key);
        }
        $linked->ksort();   // Sort by key
        return $linked;
    }


    /**
     * filteredList method expands the orderedList() method by adding a filter column and filter value.
     * @param array $primary
     * @param callable $makeLink
     * @param null $filterCol
     * @param null $filterVal
     * @return ArrayIterator
     */
    public static function filteredList(array $primary, callable $makeLink, $filterCol = NULL, $filterVal = NULL) {

        $linked = new ArrayIterator();
        $filterVal = trim($filterVal);

        foreach ($primary as $key => $row) {
            if ($filterCol) {
                if (trim($row[$filterCol]) == $filterVal) {
                    $linked->offsetSet($makeLink($row), $key);
                }
            }
            else {
                $linked->offsetSet($makeLink($row), $key);
            }
        }
        $linked->ksort();
        return $linked;
    }

    /**
     * doublyLinkedList method is constructed in such a manner that the iteration can occur in either a forward or reverse direction.
     *  Note: The terminology for SplDoublyLinkedList can be misleading. SplDoublyLinkedList::top() actually points
     *    to the end of the list, whereas SplDoublyLinkedList::bottom() points to the beginning!
     *
     * @param ArrayIterator $linked
     * @return SplDoublyLinkedList
     */
    public static function doublyLinkedList(ArrayIterator $linked) {

        $double = new SplDoublyLinkedList();
        foreach ($linked as $key => $value) {
            $double->push($value);
        }
        return $double;
    }
}