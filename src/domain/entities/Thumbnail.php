<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/4/2019
 * Time: 16:24
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\BaseEntity;
use phpchassis\data\mapper\DataMapperInterface;
use youvids\domain\mappers\ThumbnailMapper;

/**
 * Class Thumbnail
 * @package Youvids\data
 */
class Thumbnail extends BaseEntity {

    /**
     * @var string $tableName
     */
    protected static $tableName = "thumbnails";

    /**
     * Video Id
     *
     * @var int $videoId
     */
    private $videoId;

    /**
     * File path
     *
     * @var string $title
     */
    private $filePath;

    /**
     * Has the user selected it
     *
     * @var boolean $selected
     */
    public $selected;

    /**
     * Getter/Setter for videoId
     * @param null $videoId
     * @return int
     */
    public function videoId($videoId = null) {

        if($videoId === null) {
            return $this->videoId;
        }
        else {
            $this->videoId = $videoId;
        }
    }

    /**
     * Getter/Setter for filePath
     * @param null $filePath
     * @return string
     */
    public function filePath($filePath = null) {

        if($filePath === null) {
            return $this->filePath;
        }
        else {
            $this->filePath = $filePath;
        }
    }

    /**
     * Getter/Setter for selected
     * @param null $selected
     * @return bool
     */
    public function selected($selected = null) {

        if($selected === null) {
            return $this->selected;
        }
        else {
            $this->selected = $selected;
        }
    }

    /**
     * Returns true if thumbnail is selected
     * @return bool
     */
    public function isSeleted(): bool {
        return $this->selected;
    }

    /**
     * Returns the Fully Qualified Class Name
     * @return string
     */
    public static function fqcn(): string {
        return self::class;
    }

    /**
     * Returns the associated data mapper for a particular Entity
     * @return DataMapperInterface
     */
    public function dataMapper(): DataMapperInterface {

        if (null == $this->dataMapper) {
            $this->dataMapper = new ThumbnailMapper();
        }
        return $this->dataMapper;
    }

    public function rules(): array { return []; }
}