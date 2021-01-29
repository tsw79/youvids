<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/25/2019
 * Time: 02:11
 */
namespace youvids\data;

use phpchassis\lib\html\HtmlElementProvider;
use youvids\domain\entities\ {Comment, User};
use youvids\domain\repositories\CommentRepo;

/**
 * Class VideoCommentControls
 * @package youvids\data
 */
class VideoCommentControls implements VideoControlsInterface {

    use VideoControls;

    /**
     * @var CommentRepo
     */
    private $commentRepo;

    /**
     * @var Comment
     */
    private $comment;

    /**
     * VideoCommentControls constructor.
     * @param User $authUser
     * @param CommentRepo $commentRepo
     * @param Comment $comment
     */
    public function __construct(User $authUser, CommentRepo $commentRepo, Comment $comment) {

        $this->authUser = $authUser;
        $this->commentRepo = $commentRepo;
        $this->comment = $comment;
    }

    /**
     * Returns the controls for the Comments sections
     * @return string
     */
    public function create(): string {

        return "<div class='controls'>
                    {$this->replyButton()}
                    {$this->likesCount()}
                    {$this->likeButton()}
                    {$this->dislikeButton()}
                </div>
                {$this->commentForm()}";
    }

    /**
     * Returns the COMMENT button
     * @return string
     * @throws \phpchassis\data\entity\InvalidArgumentException
     */
    public static function commentButton(string $username, int $videoId): string {

        $commentAction = "postComment(this, \"{$username}\", {$videoId}, null, \"comments\")";
        return HtmlElementProvider::button("COMMENT", null, $commentAction, "postComment");
    }

    /**
     * Returns the Reply buttin in html
     * @return string
     */
    public function replyButton(): string {

        $action = "toggleReply(this)";
        return HtmlElementProvider::button("REPLY", null, $action, "null");
    }

    /**
     * Returns the number of likes difference
     * @return string
     * @throws \phpchassis\data\entity\InvalidArgumentException
     */
    public function likesCount(): string {

        $text = $this->commentRepo->countLikesDiff($this->comment->id());
        $text = $text == 0 ? '' : $text;
        return "<span class='likesCount'>{$text}</span>";
    }

    /**
     * Returns the Like's image button
     * @return string
     * @throws \phpchassis\data\entity\InvalidArgumentException
     */
    public function likeButton(): string {

        $action = "likeComment({$this->comment->id()}, this, {$this->comment->videoId()})";
        $class = "likeButton";

        $imageSrc = $this->commentRepo->wasLikedBy($this->comment->id(), $this->authUser->id())
            ? WEB_ROOT .  "/images/icons/thumb-up-active.png"
            : WEB_ROOT .  "/images/icons/thumb-up.png";

        return HtmlElementProvider::button("", $imageSrc, $action, $class);
    }

    /**
     * Returns the Dislike's image button
     * @return string
     * @throws \phpchassis\data\entity\InvalidArgumentException
     */
    public function dislikeButton(): string {

        $action = "dislikeComment({$this->comment->id()}, this, {$this->comment->videoId()})";
        $class = "dislikeButton";

        $imageSrc = $this->commentRepo->wasDislikedBy($this->comment->id(), $this->authUser->id())
            ? WEB_ROOT .  "/images/icons/thumb-down-active.png"
            : WEB_ROOT .  "/images/icons/thumb-down.png";

        return HtmlElementProvider::button("", $imageSrc, $action, $class);
    }

    /**
     * Returns the Replies section's Comment form
     * @return string
     * @throws \phpchassis\data\entity\InvalidArgumentException
     */
    public function commentForm(): string {

        $profileButton = $this->userProfileButton(
            $this->authUser->username(),
            $this->authUser->profilePic()
        );

        $cancelButtonClass = "cancelComment";
        $cancelButtonAction = "toggleReply(this)";
        $cancelButton = HtmlElementProvider::button("Cancel", null, $cancelButtonAction, $cancelButtonClass);

        $postButtonClass = "postComment";
        $postButtonAction = "postComment(this, \"{$this->authUser->username()}\", {$this->comment->videoId()}, {$this->comment->id()}, \"repliesSection\")";
        $postButton = HtmlElementProvider::button("Reply", null, $postButtonAction, $postButtonClass);

        return "<div class='commentForm hidden'>
                    {$profileButton}
                    <textarea class='commentBodyClass' placeholder='Add a public comment'></textarea>
                    {$cancelButton}
                    {$postButton}
                </div>";
    }
}