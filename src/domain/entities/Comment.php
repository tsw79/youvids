<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 5/25/2019
 * Time: 01:51
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\BaseEntity;
use phpchassis\data\mapper\DataMapperInterface;
use youvids\domain\mappers\CommentMapper;

/**
 * Class Comment
 * @package youvids\data\entities
 */
class Comment extends BaseEntity {

    /*
     --  created_by = postedBy()
    --    The user who posted the comment
    --
    --  response_to_id = similar to a parent id
    --    The comment the user is responding to
     */

    /**
     * @var string $tableName
     */
    protected static $tableName = "comments";

    /**
     * @var int $videoId
     */
    private $videoId;

    /**
     * @var int $responseToId
     */
    private $responseToId;

    /**
     * @var string $body
     */
    private $body;

    /**
     * Getter/Setter for videoId
     * @param null $videoId
     * @return string
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
     * Getter/Setter for $responseToId
     * @param null $responseToId
     * @return string
     */
    public function responseToId($responseToId = null) {

        if($responseToId === null) {
            return $this->responseToId;
        }
        else {
            $this->responseToId = $responseToId;
        }
    }

    /**
     * Getter/Setter for body
     * @param null $body
     * @return string
     */
    public function body($body = null) {

        if($body === null) {
            return $this->body;
        }
        else {
            $this->body = $body;
        }
    }

    /**
     * Alias for the getter method responseToId
     * @return int
     */
    public function parentId(): int {
        return $this->responseToId();
    }

    /**
     * Alias for the getter method createdBy
     * @return int
     */
    public function postedBy(): int {
        return $this->createdBy();
    }

    public function postedAt() {
        return $this->created();
    }

    /**
     * Calculates the time elapsed until NOW
     * @param bool $full
     * @return string
     */
    public function timeElapsed($full = false): string {

        $now = new \DateTime();
        $ago = new \DateTime($this->postedAt());
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );

        foreach ($string as $k => &$v) {

            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            }
            else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string) . ' ago' : 'just now';
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
            $this->dataMapper = new CommentMapper();
        }
        return $this->dataMapper;
    }

    public function rules(): array { return []; }
}