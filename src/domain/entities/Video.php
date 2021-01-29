<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/2/2019
 * Time: 03:56
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\BaseEntity;
use phpchassis\data\mapper\DataMapperInterface;
use youvids\domain\mappers\VideoMapper;

/**
 * Class Video
 * @package Youvids\data
 */
class Video extends BaseEntity {

    /**
     * @var string $tableName
     */
    protected static $tableName = "videos";

    /**
     * MAX_WORD_COUNT
     */
    const MAX_CHAR_COUNT = 350;

    /**
     * Contains the title of the video
     * @var string $title
     */
    private $title;

    /**
     * Contains the description of the video
     * @var string $description
     */
    private $description;

    /**
     * Privacy
     * @var string $privacy
     */
    private $privacy;

    /**
     * File path
     * @var string $filePath
     */
    private $filePath;

    /**
     * Category Id
     * @var id $categoryId
     */
    private $categoryId;

    /**
     * Category
     * @var Category $category
     */
    private $category;

    /**
     * Number of views
     * @var int $views
     */
    private $views;

    /**
     * Duration
     * @var string $duration
     */
    private $duration;

    // @TODO Have a look at this lazy loading!
    public function category($category = null) {

        if($category !== null) {
            $this->category = $category;
        }
        else {
            if ($this->category === null) {
                $this->category = new Video($this->categoryId);
            }
            return $this->category;
        }
    }



    /**
     * Getter/Setter for title
     * @param null $title
     * @return string
     */
    public function title($title = null) {

        if($title === null) {
            return $this->title;
        }
        else {
            $this->title = $title;
        }
    }

    /**
     * Getter/Setter for $description
     * @param null $description
     * @return string
     */
    public function description($description = null) {

        if($description === null) {
            return $this->description;
        }
        else {
            $this->description = $description;
        }
    }

    /**
     * Getter/Setter for privacy
     * @param null $privacy
     * @return string
     */
    public function privacy($privacy = null) {

        if($privacy === null) {
            return $this->privacy;
        }
        else {
            $this->privacy = $privacy;
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
     * Getter/Setter for categoryId
     * @param null $categoryId
     * @return string
     */
    public function categoryId($categoryId = null) {

        if($categoryId === null) {
            return $this->categoryId;
        }
        else {
            $this->categoryId = $categoryId;
        }
    }

    /**
     * Wrapper for created
     * @param null $uploadDate
     * @return string
     */
    public function uploadDate($uploadDate = null) {
        return $this->created($uploadDate);
    }

    /**
     * Getter/Setter for views
     * @param null $views
     * @return string
     */
    public function views($views = null) {

        if($views === null) {
            return $this->views;
        }
        else {
            $this->views = $views;
        }
    }

    /**
     * Getter/Setter for duration
     * @param null $duration
     * @return string
     */
    public function duration($duration = null) {

        if($duration === null) {
            return $this->duration;
        }
        else {
            $this->duration = $duration;
        }
    }

    /**
     * Getter/Setter for uploadedById
     * @param null $uploadedById
     * @return string
     */
    public function uploadedById($uploadedById = null) {

        if($uploadedById === null) {
            return $this->createdBy;
        }
        else {
            $this->createdBy = $uploadedById;
        }
    }
    /**
     * Returns the uploaded date
     * @return datetime
     */
    public function uploaded() {
        return $this->created();
    }

    /**
     * Returns the description with an ellipses IF the word count is greater than 350
     * @return string
     */
    public function displayDescription(): string {

        if ($this->description != null && (strlen($this->description) > self::MAX_CHAR_COUNT)) {

            $maxChars = self::MAX_CHAR_COUNT - 3;
            return substr($this->description, 0, $maxChars) . "...";
        }

        return $this->description;
    }

    /**
     * Returns true if the logged-in user uploaded the current video
     * @param int $userId
     * @return bool
     */
    public function wasUploadedBy(int $userId): bool {
        return $userId === $this->createdBy;
    }

    /**
     * Returns the html <select> options with the one that's selected
     * @return string
     */
    public function privacySelectOptions(): string {

        $privateSelected = ($this->privacy == 1) ? "selected='selected'" : "";
        $publicSelected = ($this->privacy == 2) ? "selected='selected'" : "";

        return "<option value='1' {$privateSelected}>Private</option>
                <option value='2' {$publicSelected}>Public</option>";
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
            $this->dataMapper = new VideoMapper();
        }
        return $this->dataMapper;
    }

    public function rules(): array { return []; }
}