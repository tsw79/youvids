<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/27/2019
 * Time: 06:18
 */
namespace phpchassis\ui;

use phpchassis\exceptions\FileException;

/**
 * Class Template
 * 
 *    How to use:
 *    -----------
 * 
 *    (new Template("/path/to/main.tpl"))->tags([
 *      'username' => 'Tharwat',
 *      'age'      => '21',
 *      'city'     => 'London',
 *      'header'   => Template::getFile('header.tpl')
 *    ])
 *    ->render();
 * 
 *                  --- OR ---
 * 
 *    (new Template("/path/to/main.tpl"))
 *      ->set('username', 'Tharwat')
 *      ->set('age', '21')
 *      ->set('city', 'London')
 *      ->set('header', Template::getFile('header.tpl'))
 *      ->render();
 * 
 * 
 * @package phpchassis\ui
 */
class Template {

    /**
     * @var array
     */
    private $tags = array();

    /**
     * @var string
     */
    private $template = null;

    /**
     * Template constructor.
     * @param string $filename
     */
    public function __construct(string $filename) {
        $this->template = self::getFile($filename);
        //if (null === $this->template) { return false; }
    }

    /**
     * Returns the file content by a given filename (including path).
     * @param $filename
     * @return Template
     */
    public static function getFile(string $filename): string {

        if(!file_exists($filename)) {
            // TODO Log Error
            throw new FileException("Can't load template file: " . self::filename());
            //return false;
        }
        return file_get_contents($filename);
    }

    /**
     * Sets one tag
     * @param $tag
     * @param $value
     * @return Template
     */
    public function set($tag, $value) {
        $this->tags[$tag] = $value;
        return $this;
    }

    /**
     * Displays the template
     */
    public function render() {
        $this->replaceTags();
        echo $this->template;
    }

    /**
     * Replace all {tags} with corresponding values from $tags array
     * @return bool
     */
    private function replaceTags() {
        foreach ($this->tags as $tag => $value) {
            $this->template = str_replace('{' . $tag . '}', $value, $this->template);
        }
    }

    /**
     * Getter/Setter for tags
     * @param array $tags
     * @return array|Template
     */
    public function tags(array $tags = null) {
        if($tags === null) {
            return $this->tags;
        }
        else {
            $this->tags = $tags;
            return $this;
        }
    }
}