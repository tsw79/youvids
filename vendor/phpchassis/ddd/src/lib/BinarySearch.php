<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/27/2019
 * Time: 07:02
 * 
 *    How to use BinarySearch:
 *    ------------------------
 * 
 *    $headers = array();
 *    $customer = readCsv(CUSTOMER_FILE, $headers);
 * 
 *    $search = new Search($customer);
 *    $item = 'Todd Lindsey';
 *    $cols = [1];
 *    echo "Searching For: $item\n";
 *    var_dump($search->binarySearch($cols, $item));
 * 
 *    echo 'Upper:Mid:Lower:<=> | ' . $upper . ':' . $mid . ':' .
 *    $lower . ':' . ($item <=> $binary[$mid]);
 */
namespace phpchassis\lib;

/**
 * Class BinarySearch
 * @package PhpChassis\lib
 */
class BinarySearch {

    protected $primary;
    protected $iterations;

    public function __construct($primary) {
        $this->primary = $primary;
    }

    /**
     * Sets up the search infrastructure.
     *  Builds a separate array, $search, where the key is a composite of the columns included in the search. 
     *  We then sort by key:
     */
    public function search(array $keys, $item) {

        $search = array();
        foreach ($this->primary as $primaryKey => $data) {
            $searchKey = function ($keys, $data) {
                $key = '';
                foreach ($keys as $k) $key .= $data[$k];
                return $key;
            };
            $search[$searchKey($keys, $data)] = $primaryKey;
        }
        ksort($search);

        /*
          Pull out the keys into another array, $binary, so the binary sort can be performed based on numeric keys. 
          Then call doBinarySearch(), which results in a key from our intermediary array $search, or a Boolean, FALSE:
         */
        $binary = array_keys($search);
        $result = $this->doBinarySearch($binary, $item);
        return $this->primary[$search[$result]] ?? FALSE;
    }

    /*
    The first doBinarySearch() initializes a series of parameters. 
      $iterations, $found, $loop, $done, and $max are all used to prevent an endless loop. 
      $upper and $lower represent the slice of the list to be examined.
    */
    public function doSearch($binary, $item) {

        $iterations = 0;
        $found = FALSE;
        $loop  = TRUE;
        $done  = -1;
        $max   = count($binary);
        $lower = 0;
        $upper = $max - 1;

        while ($loop && !$found) {

            // Set the mid point
            $mid = (int) (($upper - $lower) / 2) + $lower;

            /*
            PHP 7 spaceship operator <=>, which gives us, in a single comparison, less than, equal to, or greater than. 
              - If less, we set the upper limit to the midpoint. 
              - If greater, the lower limit is adjusted to the midpoint. 
              - If equal, we're done and home free:
             */
            switch ($item <=> $binary[$mid]) {
                // $item < $binary[$mid]
                case -1 :
                    $upper = $mid;
                    break;
                // $item == $binary[$mid]
                case 0 :
                    $found = $binary[$mid];
                    break;
                // $item > $binary[$mid]
                case 1 :
                default :
                    $lower = $mid;
            }

            /*
            Increment the number of iterations and make sure it does not exceed the size of the list. 
              If so, bail out. 
              Otherwise, check to see whether the upper and lower limits are the same more than twice in a row, 
              in which case the search item has not been found.
              Then store the number of iterations and return whatever was found (or not):
             */
            $loop = (($iterations++ < $max) && ($done < 1));
            $done += ($upper == $lower) ? 1 : 0;
        }
        $this->iterations = $iterations;
        return $found;
    }
}