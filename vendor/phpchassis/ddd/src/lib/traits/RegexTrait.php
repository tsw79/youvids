<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 8/20/2019
 * Time: 13:41
 */
namespace phpchassis\lib\traits;

/**
 * Trait RegexTrait
 * @package phpchassis\lib\traits
 */
trait RegexTrait {

    /**
     * Case sensitive regex to verify if a string is a valid domain name.
     * @param string $url
     * @return string
     */
    public static function domainName(string $url): string {

        $match = preg_match(
            "/^(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?/i",
            $url
        );
        return $match ? "Your url is ok." : "Wrong url.";
    }

    /**
     * Highlights a word in a given text (useful for search results)
     * @param string $text
     * @param string $rgb
     * @return string
     */
    public static function highlightText(string $text, $rgb = "#5fc9f6"): string {

        return preg_replace(
            "/b(regex)b/i",
            "<span style='background:{$rgb}'>1</span>",
            $text
        );
    }

    /**
     * Get all image urls from an html document
     * @param string $data
     */
    public function extractImageUrls(string $data) {

        $images = array();
        preg_match_all('/(img|src)\=(\"|\')[^\"\'\>]+/i', $data, $media);
        unset($data);
        $data=preg_replace('/(img|src)(\"|\'|\=\"|\=\')(.*)/i', "$3", $media[0]);

        foreach($data as $url) {
            $info = pathinfo($url);

            if (isset($info['extension'])) {

                if (($info['extension'] == 'jpg') || ($info['extension'] == 'jpeg') ||
                    ($info['extension'] == 'gif') || ($info['extension'] == 'png')) {
                    array_push($images, $url);
                }
            }
        }
    }

    /**
     * Strip non printable characters
     * @param string $text
     * @return string
     */
    public static function stripNonPrintableChars(string $text): string {
        return preg_replace("/[^[:print:]]+/", "", $text);
    }

    /**
     * Remove HTML tags
     *  Note:  strip_tags sort of does this, but fails to remove script, style etc.
     * @param string $text
     * @return string
     */
    public static function removeHtmlTags(string $text): string {

        $text = preg_replace ([
                // Remove invisible content
                '@<head[^>]*?>.*?</head>@siu',
                /*'@<style[^>]*?>.*?</style>@siu',*/
                '@<script[^>]*?.*?</script>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',
                // Add line breaks before & after blocks
                '@<((br)|(hr))@iu',
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu'],
            [
                ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                "\n\$0", "\n\$0"],
            $text
        );
        // Remove all remaining tags and comments and return.
        return strip_tags( $text );
    }

    /**
     * https://davebrooks.wordpress.com/2009/04/22/php-preg_replace-some-useful-regular-expressions/
     * Remove carriage returns, line feeds and tabs
     * @return mixed
     */
    public static function removeCrLfTabs(string $text) {
        return str_replace(array("\r\n", "\r", "\n", "\t"), '', $text);
    }

    /**
     * https://davebrooks.wordpress.com/2009/04/22/php-preg_replace-some-useful-regular-expressions/
     * Clean up a sentence end that has no trailing space
     *  E.g. ‘Keep your head.Don’t fall apart’ becomes ‘Keep your head. Don’t fall apart’
     *  This uses lookahead.
     * @return mixed
     */
    public static function cleanupSentenceEnd(string $text) {
        return preg_replace("/\.(?! )/i", ". ", $text);
    }

    /**
     * https://davebrooks.wordpress.com/2009/04/22/php-preg_replace-some-useful-regular-expressions/
     * Remove repeated punctuation
     *  E.g.  ‘Keep your head…’ becomes ‘Keep your head.’
     * @return mixed
     */
    public static function removeRepeatedPunctuation(string $text) {
        return preg_replace("/\.+/i", ".", $text);
    }

    /**
     * https://davebrooks.wordpress.com/2009/04/22/php-preg_replace-some-useful-regular-expressions/
     * Remove repeated words (case insensitive)
     *  E.g.  ‘Keep your your head’ becomes ‘Keep your head’
     * @param string $text
     * @return mixed
     */
    public static function removeRepeatedWords(string $text) {
        return preg_replace("/\s(\w+\s)\1/i", "$1", $text);
    }

    /**
     * Matches an XML/HTML Tag
     *  This simple function takes two arguments: The first is the tag you’d like to match, and the second is the
     *  variable containing the XML or HTML.
     * @param string $tag
     * @param string $xml
     * @return mixed
     */
    public static function matchTag(string $tag, string $xml) {

        $tag = preg_quote($tag);
        preg_match_all(
            "{<{$tag}[^>]*>(.*?)</{$tag}>}",
            $xml,
            $matches,
            PREG_PATTERN_ORDER
        );
        return $matches[1];
    }

    /**
     * Match an HTML/XML Tag With a Specific Attribute Value
     * @param string $attr
     * @param string $value
     * @param string $xml
     * @param string|null $tag
     * @return mixed
     */
    public static function matchTagWithAttribute(string $attr, string $value, string $xml, string $tag = null) {

        $tag = (null === $tag) ? '\w+' : preg_quote($tag);
        $attr = preg_quote($attr);
        $value = preg_quote($value);
        $tagPattern = "/<(".$tag.")[^>]*$attr\s*=\s*(['\"])$value\\2[^>]*>(.*?)<\/\\1>/";
        preg_match_all($tagPattern, $xml, $matches, PREG_PATTERN_ORDER);
        return $matches[3];
    }

    /**
     * Returns true if given colour is Hexadecimal Color Values
     * @param string $rgb
     * @return false|int
     */
    public static function isHexadecimalColour(string $rgb): bool {
        return preg_match("/^#(?:(?:[a-fd]{3}){1,2})$/i", $rgb);
    }

    /**
     * Returns the Page Title
     *  Finds and prints the text within the <title></title> tags of an HTML page.
     * @param string $htmlFile
     * @return mixed
     */
    public static function extractPageTitle(string $htmlFile) {

        //$fp = fopen("https://catswhocode.com/blog","r");
        $fp = fopen($htmlFile,"r");
        $page = '';

        while (!feof($fp)){
            $page .= fgets($fp, 4096);
        }
        $title = eregi("<title>(.*)</title>",$page,$regs);
        fclose($fp);
        return $regs[1];
    }

    /**
     * Replace Double Quotes by Smart Quotes
     * @param string $text
     * @return null|string|string[]
     */
    public static function doubleQuotesToSmartQuotes(string $text) {
        return preg_replace('B"b([^"x84x93x94rn]+)b"B', '?1?', $text);
    }

    /**
     * Checks Password Complexity
     */
    public static function passwordComplexity() {

        //Password complexity
        //Tests if the input consists of 6 or more letters, digits, underscores and hyphens.
        //The input must contain at least one upper case letter, one lower case letter and one digit.
        '\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])[-_a-zA-Z0-9]{6,}\z';

        //Password complexity
        //Tests if the input consists of 6 or more characters.
        //The input must contain at least one upper case letter, one lower case letter and one digit.
        '\A(?=[-_a-zA-Z0-9]*?[A-Z])(?=[-_a-zA-Z0-9]*?[a-z])(?=[-_a-zA-Z0-9]*?[0-9])\S{6,}\z';
    }
}