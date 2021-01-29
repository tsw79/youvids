<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/27/2019
 * Time: 04:19
 */
namespace phpchassis\lib\captcha;

/**
 * Class Image
 * @package phpchassis\lib\captcha
 */
class Image implements CaptchaInterface {

    const DEFAULT_WIDTH = 200;
    const DEFAULT_HEIGHT = 50;
    const DEFAULT_LABEL = 'Enter this phrase';
    const DEFAULT_BG_COLOR = [255,255,255];
    const DEFAULT_URL = '/captcha';
    const IMAGE_PREFIX = 'CAPTCHA_';
    const IMAGE_SUFFIX = '.jpg';
    const IMAGE_EXP_TIME = 300; // seconds
    const ERROR_REQUIRES_GD = 'Requires the GD extension + the JPEG library';
    const ERROR_IMAGE = 'Unable to generate image';

    protected $phrase;
    protected $imageFn;
    protected $label;
    protected $imageWidth;
    protected $imageHeight;
    protected $imageRGB;
    protected $imageDir;
    protected $imageUrl;

    /**
     * Image constructor.
     *  The constructor needs to accept all the arguments required for phrase generation.
     *  In addition, we need to accept arguments required for image generation: 
     *    - The two mandatory parameters are $imageDir and $imageUrl:
     *        $imageDir - where the graphic will be written
     *        $imageUrl - is the base URL
     *    - $imageFont is provided in case we want to provide TrueType fonts, which will produce a more secure CAPTCHA. 
     *        Otherwise, we're limited to the default fonts which, to quote a line in a famous movie, ain't a pretty sight.
     *
     * @param $imageDir
     * @param $imageUrl
     * @param null $imageFont
     * @param null $label
     * @param null $length
     * @param bool $includeNumbers
     * @param bool $includeUpper
     * @param bool $includeLower
     * @param bool $includeSpecial
     * @param null $otherChars
     * @param array|NULL $suppressChars
     * @param null $imageWidth
     * @param null $imageHeight
     * @param array|NULL $imageRGB
     * @throws \Exception
     */
    public function __construct( $imageDir, $imageUrl, $imageFont = null, $label = null, $length = null, $includeNumbers = true,
                                 $includeUpper= true, $includeLower= true, $includeSpecial = false, $otherChars = null,
                                array $suppressChars = null, $imageWidth = null, $imageHeight = null, array $imageRGB = null ) {

        /*
         * Check to see whether the imagecreatetruecolor function exists. 
         *  If this comes back as FALSE, we know the GD extension is not available. 
         *  Otherwise, we assign parameters to properties, generate the phrase, remove old images, and write out the CAPTCHA graphic.
         */
        if (!function_exists('imagecreatetruecolor')) {
            throw new \Exception(self::ERROR_REQUIRES_GD);
        }

        $this->imageDir = $imageDir;
        $this->imageUrl = $imageUrl;
        $this->imageFont = $imageFont;
        $this->label = $label ?? self::DEFAULT_LABEL;
        $this->imageRGB = $imageRGB ?? self::DEFAULT_BG_COLOR;
        $this->imageWidth = $imageWidth ?? self::DEFAULT_WIDTH;
        $this->imageHeight= $imageHeight ?? self::DEFAULT_HEIGHT;

        if (substr($imageUrl, -1, 1) == '/') {
            $imageUrl = substr($imageUrl, 0, -1);
        }
        $this->imageUrl = $imageUrl;

        if (substr($imageDir, -1, 1) == DIRECTORY_SEPARATOR) {
            $imageDir = substr($imageDir, 0, -1);
        }
        $this->phrase = new Phrase(
            $length,
            $includeNumbers,
            $includeUpper,
            $includeLower,
            $includeSpecial,
            $otherChars,
            $suppressChars
        );

        $this->removeOldImages();
        $this->generateJpg();
    }

    /**
     * We use the DirectoryIterator class to scan the designated directory and check the access time.
     * We calculate an old image file as one that is the current time minus the value specified by IMAGE_EXP_TIME:
     */
    public function removeOldImages() {

        $old = time() - self::IMAGE_EXP_TIME;

        foreach (new \DirectoryIterator($this->imageDir) as $fileInfo) {

            if($fileInfo->isDot()) 
              continue;

            if ($fileInfo->getATime() < $old) {
                unlink($this->imageDir . DIRECTORY_SEPARATOR . $fileInfo->getFilename());
            }
        }
    }

    /**
     * Generates the Jpg captcha
     * @throws \Exception
     */
    public function generateJpg() {

        /*
         * First, we split the $imageRGB array into $red, $green, and $blue. We use the core imagecreatetruecolor()
         * function to generate the base graphic with the width and height specified. We use the RGB values to colorize
         * the background.
         */
        try {

            list($red, $green, $blue) = $this->imageRGB;
            $im = imagecreatetruecolor($this->imageWidth, $this->imageHeight);
            $black = imagecolorallocate($im, 0, 0, 0);
            $imageBgColor = imagecolorallocate($im, $red, $green, $blue);
            imagefilledrectangle($im, 0, 0, $this->imageWidth, $this->imageHeight, $imageBgColor);

            /*
             * Next, we define x and y margins based on image width and height. We then initialize variables to be used
             * to write the phrase onto the graphic. We then loop a number of times that matches the length of the phrase:
             */

            $xMargin = (int) ($this->imageWidth * .1 + .5);
            $yMargin = (int) ($this->imageHeight * .3 + .5);
            $phrase = $this->getPhrase();
            $max = strlen($phrase);
            $count = 0;
            $x = $xMargin;
            $size = 5;

            for ($i = 0; $i < $max; $i++) {

                // If $imageFont is specified, we are able to write each character with a different size and angle.
                // We also need to adjust the x axis (that is, horizontal) value according to the size:
                if ($this->imageFont) {

                    /*$this->imageFont = realpath($this->imageFont);*/          
                    
                    var_dump($this->imageFont);

                    $size = rand(12, 32);
                    $angle = rand(0, 30);
                    $y = rand($yMargin + $size, $this->imageHeight);
                    imagettftext($im, $size, $angle, $x, $y, $black, $this->imageFont, $phrase[$i]);
                    $x += (int) ($size + rand(0, 5));
                }
                // Otherwise, we're stuck with the default fonts. We use the largest size of 5, as smaller sizes are unreadable.
                // We provide a low level of distortion by alternating between imagechar(), which writes the image normally,
                // and imagecharup(), which writes it sideways:
                else {
                    $y = rand(0, ($this->imageHeight - $yMargin));

                    if ($count++ & 1) {
                        imagechar($im, 5, $x, $y, $phrase[$i], $black);
                    }
                    else {
                        imagecharup($im, 5, $x, $y, $phrase[$i], $black);
                    }

                    $x += (int) ($size * 1.2);
                }
            }

            // Add noise in the form of random dots.
            $numDots = rand(10, 999);
            for ($i = 0; $i < $numDots; $i++) {
                imagesetpixel(
                    $im,
                    rand(0, $this->imageWidth),
                    rand(0, $this->imageHeight),
                    $black
                );
            }

            // Create a random image filename using md5() with the date and a random number from 0 to 9999 as arguments. 
            // Note, safely use md5() as we are not trying to hide any secret information; we're merely interested in 
            // generating a unique filename quickly. We wipe out the image object as well to conserve memory.

            $this->imageFn = self::IMAGE_PREFIX
                . md5(date('YmdHis') . rand(0,9999))
                . self::IMAGE_SUFFIX;

            imagejpeg($im, $this->imageDir . DIRECTORY_SEPARATOR . $this->imageFn);
            imagedestroy($im);
        }
        catch (\Throwable $e) {
            // TODO Need to integrate with PhpChassis logging system
            error_log(__METHOD__ . ':' . $e->getMessage());
            throw new \Exception(self::ERROR_IMAGE);
        }
    }

    /**
     * @return string
     */
    public function getLabel(): string {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getImage() {
        return sprintf('<img src="%s/%s" />', $this->imageUrl, $this->imageFn);
    }

    /**
     * @return mixed
     */
    public function getPhrase() {
        return $this->phrase->phrase();
    }
}