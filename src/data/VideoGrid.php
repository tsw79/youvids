<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/30/2019
 * Time: 05:51
 */
namespace youvids\data;

use phpchassis\auth\AuthUser;
use phpchassis\lib\collections\Collection;
use phpchassis\data\DIContainer;
use youvids\domain\entities\Video;
use youvids\domain\repositories\ {UserRepo, VideoRepo};

/**
 * Class VideoGrid
 * @package youvids\data
 */
class VideoGrid {

    // @TODO Neeed to get this from the ConfigLoader
    private $largeMode;

    /**
     * @var User    Logged-in user
     */
    protected $authUser;

    /**
     * @var
     */
    private $videoRepo;

    /**
     * @var UserRepo
     */
    private $userRepo;

    /**
     * @var array
     */
    private $videos;

    /**
     * @var string
     */
    private $title;

    /**
     * @var bool
     */
    private $showFilter;

    /**
     * VideoGrid constructor.
     * @param Collection|null $videos
     * @param string|null $title
     * @param bool $showFilter
     * @param bool $largeMode
     * @throws \ReflectionException
     * @throws \phpchassis\lib\exceptions\AuthenticationException
     * @throws \phpchassis\lib\exceptions\DIContainerException
     */
    public function __construct(Collection $videos = null, string $title = null, bool $showFilter = false, $largeMode = false) {

        $this->videoRepo = DIContainer::instance()->get(VideoRepo::class);
        $this->userRepo = DIContainer::instance()->get(UserRepo::class);
        $this->authUser = AuthUser::instance()->loggedInEntity($this->userRepo);
        $this->videos = $videos;
        $this->title = $title;
        $this->showFilter = $showFilter;
        $this->largeMode = $largeMode;
    }

    /**
     * @return string
     */
    public function create(): string {

        if (count($this->videos) > 0) {

            $gridItemsHtml = '';
            $gridHeader = '';

            foreach ($this->videos as $video) {
                $gridItemsHtml .= $this->genGridItem($video);
            }

            if ($this->title != null) {
                $gridHeader = $this->gridHeaderHtml();
            }

            $gridClass = $this->largeMode ? "videoGrid large" : "videoGrid";

            return "{$gridHeader}
                <div class='{$gridClass}'>
                    {$gridItemsHtml}
                </div>";
        }

        return false;
    }

    /**
     * Returns the Grid item in html
     * @param Video $video
     * @return string
     */
    private function genGridItem(Video $video) {

        $url = "/src/application/views/watch.php?id=" . $video->id();
        $thumbPath = $this->videoRepo->thumbnailPath($video->id());
        $uploadedBy = $this->userRepo->findById($video->uploadedById());
        $description = !$this->largeMode ? '' : $video->displayDescription();

        return "<a href='$url'>
                    <div class='videoGridItem'>
                        <div class='thumbnail'>
                            <img src='{$thumbPath}'>
                            <div class='duration'>
                                <span>{$video->duration()}</span>
                            </div>
                        </div>
                        <div class='details'>
                            <h3 class='title'>{$video->title()}</h3>
                            <span class='username'>{$uploadedBy->username()}</span>
                            <div class='stats'>
                                <span class='viewCount'>{$video->views()} views - </span>
                                <span class='timeStamp'>{$video->displayUploadedTimestamp()}</span>
                            </div>
                            <span class='description'>{$description}</span>
                        </div>
                    </div>
                </a>";
    }

    /**
     * Returns the Grid's header
     * @return string
     */
    private function gridHeaderHtml(): string {

        return "<div class='videoGridHeader'>
                    <div class='left'>
                        $this->title
                    </div>
                    {$this->headerFilter()}
                </div>";
    }

    /**
     * Returns the filter of the Grid's header
     * @return string
     */
    private function headerFilter(): string {

        $filter = '';

        if ($this->showFilter) {

            // @TODO Do I use Midlleware with this?

            $link = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
            $url = parse_url($link);
            $filterUrl = basename($_SERVER["PHP_SELF"]) . '?';

            if (isset($url["query"])) {

                $queryString = $url["query"];
                parse_str($queryString, $params);
                unset($params["orderBy"]);

                if (count($params) > 0) {
                    $aQueryString = http_build_query($params);
                    $filterUrl .= $aQueryString . '&';
                }
            }

            $filter = "<div class='right'>
                            <span>Order by:</span>
                            <a href='{$filterUrl}orderBy=uploadDate'>Upload date</a>
                            <a href='{$filterUrl}orderBy=views'>Most viewed</a>
                        </div>";
        }

        return $filter;
    }
}