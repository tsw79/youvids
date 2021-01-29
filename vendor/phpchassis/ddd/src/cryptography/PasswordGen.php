<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/5/2019
 * Time: 05:37
 */
namespace phpchassis\cryptography;

/**
 * Class PasswordGen
 *  Generates a hashed password
 * @package phpchassis\cryptography
 */
class PasswordGen {

    /*
     How to use this class example:
    -----------------------

        $source = [
          'https://www.gutenberg.org/files/4300/4300-0.txt',
          'https://www.gutenberg.org/files/2600/2600-h/2600-h.htm',
          'https://www.gutenberg.org/files/1342/1342-h/1342-h.htm',
        ];

        $passwdGen = new PasswordGen($source, 4, CACHE_DIR);
        echo $passwdGen->generate();
     */

    /**
     * SOURCE_SUFFIX
     */
    const SOURCE_SUFFIX = 'src';

    /**
     * SPECIAL_CHARS
     */
    const SPECIAL_CHARS = '\`¬|!"£$%^&*()_-+={}[]:@~;\'#<>?,./|\\';

    /**
     * @var
     */
    protected $algorithm;

    /**
     * @var
     */
    protected $sourceList;

    /**
     * @var
     */
    protected $word;

    /**
     * @var
     */
    protected $list;

    /**
     * PasswordGen constructor.
     * @param array $wordSource
     * @param $minWordLength
     * @param $cacheDir
     */
    public function __construct(array $wordSource, $minWordLength, $cacheDir) {

        $this->processSource($wordSource, $minWordLength, $cacheDir);
        $this->initAlgorithm();
    }

    /**
     * Initializes a set of algorithms, defined as an array, with method calls available in this class.
     *  In order for the generator not to produce passwords of the same pattern, these method calls allow us
     *  to place the various components of a password in different positions in the final password string.
     * @return void
     */
    public function initAlgorithm(): void {

        $this->algorithm = [
            ['word', 'digits', 'word', 'special'],
            ['digits', 'word', 'special', 'word'],
            ['word', 'word', 'special', 'digits'],
            ['special', 'word', 'special', 'digits'],
            ['word', 'special', 'digits', 'word', 'special'],
            ['special', 'word', 'special', 'digits', 'special', 'word', 'special'],
        ];
    }

    /**
     * Generates a password by selecting an algorithm at random, and then loop through it, calling the appropriate methods.
     * @return mixed
     * @throws \Exception
     */
    public function generate() {

        $pwd = '';
        $key = random_int(0, count($this->algorithm) - 1);

        foreach ($this->algorithm[$key] as $method) {
            $pwd .= $this->$method();
        }

        return str_replace("\n", '', $pwd);
    }

    /**
     * Produces random digits
     *  Note: PHP 7 function random_int() marginally slower, but offers true CSPRNG capabilities!
     * @param int $max
     * @return int
     * @throws \Exception
     */
    public function digits($max = 999) {
        return random_int(1, $max);
    }

    /**
     * Produces a single character from the SPECIAL_CHARS class constant
     *  Note: PHP 7 function random_int() marginally slower, but offers true CSPRNG capabilities!
     * @return mixed
     * @throws \Exception
     */
    public function special() {

        $maxSpecial = strlen(self::SPECIAL_CHARS) - 1;
        return self::SPECIAL_CHARS[random_int(0, $maxSpecial)];
    }

    /**
     * Generating a hard-to-guess word.
     * @param $wordSource
     * @param $minWordLength
     * @param $cacheDir
     * @return bool
     */
    public function processSource($wordSource, $minWordLength, $cacheDir) {

        foreach ($wordSource as $html) {

            $hashKey = md5($html);

            $sourceFile = $cacheDir
                . '/'
                . $hashKey
                . '.'
                . self::SOURCE_SUFFIX;

            $this->sourceList[] = $sourceFile;

            /*
             * If the file doesn't exist, or is zero-byte, we process the contents. If the source is HTML, we only accept
             * content inside the <body> tag. We then use str_word_count() to pull a list of words out of the string, also
             * employing strip_tags() to remove any markup
             */
            if (!file_exists($sourceFile) || filesize($sourceFile) == 0) {

                echo 'Processing: ' . $html . PHP_EOL;
                $contents = file_get_contents($html);

                if (preg_match('/<body>(.*)<\/body>/i',
                    $contents, $matches)) {
                    $contents = $matches[1];
                }
                $list = str_word_count(strip_tags($contents), 1);

                /*
                 * We then remove any words that are too short, and use array_unique() to get rid of duplicates.
                 * The final result is stored in a file.
                 */
                foreach ($list as $key => $value) {

                    if (strlen($value) < $minWordLength) {
                        $list[$key] = 'xxxxxx';
                    }
                    else {
                        $list[$key] = trim($value);
                    }
                }

                $list = array_unique($list);
                file_put_contents($sourceFile, implode("\n",$list));
            }
        }
        return true;
    }

    // @TODO We could move this function to a Utility class, or a Trait??

    /**
     * Flips random letters in the word to uppercase
     * @param $word
     * @return mixed
     * @throws \Exception
     */
    public function flipUpper($word) {

        $maxLen   = strlen($word);
        $numFlips = random_int(1, $maxLen - 1);
        $flipped  = strtolower($word);

        for ($x = 0; $x < $numFlips; $x++) {

            $pos = random_int(0, $maxLen - 1);
            $word[$pos] = strtoupper($word[$pos]);
        }

        return $word;
    }

    /**
     * Chooses a word source at random, and uses the file() function to read from the appropriate cached file.
     * @return mixed
     * @throws \Exception
     */
    public function word() {

        $wsKey    = random_int(0, count($this->sourceList) - 1);
        $list     = file($this->sourceList[$wsKey]);
        $maxList  = count($list) - 1;
        $key      = random_int(0, $maxList);
        $word     = $list[$key];

        return $this->flipUpper($word);
    }
}