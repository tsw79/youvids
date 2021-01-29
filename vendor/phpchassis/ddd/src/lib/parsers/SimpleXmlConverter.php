<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/28/2019
 * Time: 15:55
 */
namespace phpchassis\lib\parsers;

/**
 * Class SimpleXmlConverter
 * @package phpchassis\parsers
 */
class SimpleXmlConverter {

    /**
     * xmlToArray method produces a PHP array from an CML document
     * @param SimpleXMLIterator $xml
     * @return array
     */
    public static function xmlToArray(\SimpleXMLIterator $xml) : array {

        $a = array();

        for( $xml->rewind(); $xml->valid(); $xml->next() ) {

            if(!array_key_exists($xml->key(), $a)) {
                $a[$xml->key()] = array();
            }
            if($xml->hasChildren()){
                $a[$xml->key()][] = self::xmlToArray($xml->current());
            }
            else{
                $a[$xml->key()] = (array) $xml->current()->attributes();
                $a[$xml->key()]['value'] = strval($xml->current());
            }
        }
        return $a;
    }

    /**
     * Converts an array of data to XML format
     * @param array $a
     * @return string (XML format)
     */
    public static function arrayToXml(array $a): string {

        $xml = new \SimpleXMLElement(
            '<?xml version="1.0" standalone="yes"?><root></root>'
        );
        self::phpToXml($a, $xml);
        return $xml->asXML();
    }

    /**
     * Converts php code (an object or an array) to XML format
     * @param $value
     * @param $xml
     */
    protected static function phpToXml($value, &$xml) {

        $node = $value;

        if (is_object($node)) {
            $node = get_object_vars($node);
        }
        if (is_array($node)) {
            foreach ($node as $k => $v) {
                if (is_numeric($k)) {
                    $k = 'number' . $k;
                }
                if (is_array($v)) {
                    $newNode = $xml->addChild($k);
                    self::phpToXml($v, $newNode);
                }
                elseif (is_object($v)) {
                    $newNode = $xml->addChild($k);
                    self::phpToXml($v, $newNode);
                }
                else {
                    $xml->addChild($k, $v);
                }
            }
        }
        else {
            $xml->addChild(self::UNKNOWN_KEY, $node);
        }
    }
}