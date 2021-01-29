<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/29/2019
 * Time: 07:29
 */
namespace youvids\data;

use phpchassis\lib\collections\Collection;
use youvids\domain\entities\ {Comment, User};
use youvids\domain\repositories\ {CommentRepo, UserRepo};

/**
 * Class CommentItem
 * @package youvids\data
 */
class CommentItem {

    /**
     * @var User    Logged-in user
     */
    protected $authUser;

    /**
     * @var CollectionSpl Comment
     */
    private $comments;

    /**
     * @var CommentRepo
     */
    private $commentRepo;

    /**
     * @var UserRepo
     */
    private $userRepo;

    /**
     * CommentItem constructor.
     * @param User $authUser
     * @param CommentRepo $commentRepo
     * @param Collection $comments
     */
    public function __construct(User $authUser, CommentRepo $commentRepo, UserRepo $userRepo, Collection $comments) {

        $this->authUser = $authUser;
        $this->commentRepo = $commentRepo;
        $this->userRepo = $userRepo;
        $this->comments = $comments;
    }

    /**
     * Creates and returns the Comment container with items
     * @return string
     */
    public function create(): string {

        $items = "";

        foreach($this->comments as $comment) {

            $commentControls = new VideoCommentControls(
                $this->authUser,
                $this->commentRepo,
                $comment
            );

            $commentControlsHtml = $commentControls->create();
            $numReplies = $this->commentRepo->countReplies($comment->id());
            $repliesSectionHtml = $this->repliesSectionHtml($comment->videoId(), $comment->id(), $numReplies);
            $postedBy = $this->userRepo->findById($comment->postedBy());

            $items .= $this->genHtml(
                $postedBy,
                $comment,
                VideoCommentControls::userProfileButton($postedBy->username(), $postedBy->profilePic()),
                $commentControlsHtml,
                $repliesSectionHtml
            );
        }

        return $items;
    }

    /**
     * Returns a Comment item in html form
     * @param User $postedBy
     * @param Comment $comment
     * @param string $profileButton
     * @param string $commentControlsHtml
     * @param string $repliesText
     * @return string
     */
    private function genHtml(User $postedBy, Comment $comment, string $profileButton, string $commentControlsHtml, string $repliesText): string {

        return "<div class='itemContainer'>
                    <div class='comment'>
                        {$profileButton}

                        <div class='mainContainer'>

                            <div class='commentHeader'>
                                <a href='profile.php?username={$postedBy->username()}'>
                                    <span class='username'>{$postedBy->username()}</span>
                                </a>
                                <span class='timestamp'>{$comment->timeElapsed()}</span>
                            </div>

                            <div class='body'>
                                {$comment->body()}
                            </div>
                        </div>

                    </div>

                    {$commentControlsHtml}
                    {$repliesText}
                </div>";
    }

    /**
     * Returns the Replies section for a given Comment
     * @param int $numResponses
     * @param int $commentId
     * @return string
     * @throws \phpchassis\data\entity\InvalidArgumentException
     */
    private function repliesSectionHtml(int $videoId, int $commentId, int $numResponses): string {

        $replyText = $numResponses == 1 ? "reply" : "replies";

        if($numResponses > 0) {
            return "<span class='repliesSection viewReplies' onclick='getReplies({$commentId}, this, {$videoId})'>
                        View {$numResponses} {$replyText}
                    </span>";
        }
        else {
            return "<div class='repliesSection'></div>";
        }
    }
}