<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/6/2019
 * Time: 05:51
 */
namespace phpchassis\lib\html;

use phpchassis\auth\AuthUser;

/**
 * Class HtmlElementProvider
 * @package phpchassis-ddd\data\providers
 * @deprecated This class will be replaced by HtmlElement
 */
final class HtmlElementProvider {

    /**
     * @var HtmlElementProvider
     */
    protected static $selfInstance = null;

    /**
     * @var string
     */
    protected $tag;

    protected function __construct($tag) {
        $this->tag = $tag;
    }

    /**
     * @param string $tag
     * @return HtmlElementProvider
     */
    public static function tag($tag = ''): self {

        self::$selfInstance = new self($tag);
        return self::$selfInstance;
    }

    /**
     * @param $value
     * @return HtmlElementProvider
     */
    public function id($value): self {
        return $this->set("id", $value);
    }

    /**
     * Sets one or an array of attributes
     * @param $attributes
     * @param null $value
     * @return HtmlElementProvider
     */
    protected function set($attributes, $value = null): self {

        if (is_string($attributes)) {
            $this[$attributes] = $value;
        }
        elseif(is_array($attributes)) {

            foreach ($attributes as $key => $value) {
                $this[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * Wrapper for method set()
     * @param $attribute
     * @param null $value
     * @return HtmlElementProvider
     */
    protected function attr($attribute, $value = null): self {
        return call_user_func_array(array($this, 'set'), func_get_args());
    }

    /**
     * Add a class to classList
     * @param string $value
     * @return HtmlTag instance
     */
//    public function addClass($value)
//    {
//        if (!isset($this->attributeList['class']) || is_null($this->attributeList['class'])) {
//            $this->attributeList['class'] = array();
//        }
//        $this->attributeList['class'][] = $value;
//        return $this;
//    }

    /**
     * Remove a class from classList
     * @param string $value
     * @return HtmlTag instance
     */
//    public function removeClass($value)
//    {
//        if (!is_null($this->attributeList['class'])) {
//            unset($this->attributeList['class'][array_search($value, $this->attributeList['class'])]);
//        }
//        return $this;
//    }

    // TODO Remove me once the HtmlElementProvider builder class has been implemented
    public static function button($text, $imageSrc, $action, $class) {

        $image = ($imageSrc == null) ? "" : "<img src='$imageSrc'>";
        $link = AuthUser::isLoggedIn() ? $action : "notSignedIn()";

        return "<button class='$class' onclick='$link'>
                    $image
                    <span class='text'>$text</span>
                </button>";
    }
}