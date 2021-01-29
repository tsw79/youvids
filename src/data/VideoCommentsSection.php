<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/27/2019
 * Time: 05:44
 */
namespace youvids\data;

use youvids\domain\entities\ {Comment, User, Video};
use youvids\domain\repositories\ {CommentRepo, UserRepo};

/**
 * Class VideoCommentSection
 * @package youvids\data
 */
class VideoCommentsSection {

    /**
     * @var Video
     */
    private $video;

    /**
     * @var CommentRepo
     */
    private $commentRepo;

    /**
     * @var UserRepo
     */
    private $userRepo;

    /**
     * VideoCommentSection constructor.
     * @param User $authUser
     * @param Video $video
     * @param CommentRepo $commentRepo
     * @param UserRepo $userRepo
     */
    public function __construct(User $authUser, Video $video, CommentRepo $commentRepo, UserRepo $userRepo) {

        $this->video = $video;
        $this->commentRepo = $commentRepo;
        $this->authUser = $authUser;
        $this->userRepo = $userRepo;
    }

    public function create() {

        $parentComments = $this->commentRepo->findAllParentsByVideo($this->video->id());

        if (is_null($parentComments)) {
            return false;
        }

        $commentItems = (new CommentItem(
            $this->authUser,
            $this->commentRepo,
            $this->userRepo,
            $parentComments
        ))->create();

        $numComments = $this->commentRepo->countByVideo($this->video->id());
        $commentButton = VideoCommentControls::commentButton($this->authUser->username(), $this->video->id());

        return $this->commentFormHtml($commentButton, $numComments, $commentItems);

    }

    /**
     * Returns the Comments section in html, for a particular video
     * @param int $numComments
     * @param string $commentItems
     * @return string
     */
    private function commentFormHtml(string $commentButton, int $numComments, string $commentItems): string {

        $profileButton = VideoCommentControls::userProfileButton(
            $this->authUser->username(),
            $this->authUser->profilePic()
        );

        return "<div class='header'>
                    <span class='commentCount'>{$numComments} Comments</span>

                        <div class='commentForm'>
                            {$profileButton}
                            <textarea class='commentBodyClass' placeholder='Add a public comment'></textarea>
                            {$commentButton}
                        </div>
                    </div>

                    <div class='comments'>
                        {$commentItems}
                    </div>
                </div>";
    }



    
}