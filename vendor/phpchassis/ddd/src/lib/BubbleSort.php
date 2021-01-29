<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/27/2019
 * Time: 06:43
 */
namespace phpchassis\lib;

/**
 * Class BubbleSort
 *  To move the values ar
 * @package PhpChassis\lib
 */
class BubbleSort {

    /**
     * bubbleSort
     *  To move the values around in the array will be too expensive on resource usage. Instead, we will use a linked list:
     *    1.  First we build a linked list using the buildLinkedList() function
     *    2.  Then define a new function, bubbleSort(), which accepts the linked list by reference, the primary list,
     *        a sort field, and a parameter that represents sort order (ascending or descending).
     * 
     * @param $linked
     * @param $primary
     * @param $sortField
     * @param string $order
     */
    public static function bubbleSort(&$linked, $primary, $sortField, $order = 'A') {

        static $iterations = 0;
        $swaps = 0;
        $iterator = new ArrayIterator($linked);

        /*
        In the while() loop, we only proceed if the iteration is still valid (still in progress). 
        Then obtain the current key and value, and the next key and value. 
          Note the extra if() statement to ensure the iteration is still valid.
        */
        while ($iterator->valid()) {

            $currentLink = $iterator->current();
            $currentKey  = $iterator->key();

            if (!$iterator->valid()) {
                break;
            }

            $iterator->next();
            $nextLink = $iterator->current();
            $nextKey  = $iterator->key();

            /*
            Check to see whether the sort is to be ascending or descending. Depending on the direction, check to see whether 
            the next value is greater than, or less than, the current value. The result of the comparison is stored in $expr.
            */
            if ($order == 'A') {
                $expr = $primary[$linked->offsetGet($currentKey)][$sortField] > $primary[$linked->offsetGet($nextKey)][$sortField];
            }
            else {
                $expr = $primary[$linked->offsetGet($currentKey)][$sortField] < $primary[$linked->offsetGet($nextKey)][$sortField];
            }

            /*
            If the value of $expr is TRUE, and we have valid current and next keys, the values
            are swapped in the linked list. We also increment $swaps:
            */
            if ($expr && $currentKey && $nextKey && $linked->offsetExists($currentKey) && $linked->offsetExists($nextKey)) {

                $tmp = $linked->offsetGet($currentKey);
                $linked->offsetSet($currentKey, $linked->offsetGet($nextKey));
                $linked->offsetSet($nextKey, $tmp);
                $swaps++;
            }
        }

        // If any swaps have occurred, we need to run through the iteration again, until
        // there are no more swaps. Accordingly, we make a recursive call to the same method:
        if ($swaps) {
            self::bubbleSort($linked, $primary, $sortField, $order);
        }

        // The real return value is the re-organized linked list. In addition, the number of
        // iterations is also returned, just for reference.
        return ++$iterations;
    }
}