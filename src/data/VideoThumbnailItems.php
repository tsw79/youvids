<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/11/2019
 * Time: 03:31
 */
namespace youvids\data;

use youvids\domain\repositories\ThumbnailRepo;

/**
 * Class VideoThumbnailItems
 * @package youvids\data
 */
class VideoThumbnailItems {

    /**
     * @var int
     */
    private $videoId;

    /**
     * @var ThumbnailRepo
     */
    private $thumbnailRepo;

    /**
     * VideoThumbnailItems constructor.
     * @param int $videoId
     * @param ThumbnailRepo $thumbnailRepo
     */
    public function __construct(int $videoId, ThumbnailRepo $thumbnailRepo) {

        $this->videoId = $videoId;
        $this->thumbnailRepo = $thumbnailRepo;
    }

    public function create(): string {

        $html = '';
        $thumbnails = $this->thumbnailRepo->allByVideoId($this->videoId);

        // Set up thumbnail items for display
        foreach ($thumbnails as $thumbnail) {

            $selected = $thumbnail->selected() == 1 ? "selected" : "";
            $html .= "<div class='thumbnailItem {$selected}' onclick='setSelectedThumbnail({$thumbnail->id()}, {$thumbnail->videoId()}, this)'>
                        <img src='{$thumbnail->filePath()}'>
                      </div>";
        }

        return "<div class='thumbnailItemsContainer'>
                    $html
                </div>";
    }
}