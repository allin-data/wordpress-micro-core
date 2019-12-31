<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

/**
 * Class AbstractPostModel
 * @package AllInData\Micro\Core\Model
 */
abstract class AbstractPostModel extends AbstractModel
{
    /**
     * @var int|string|null
     */
    private $id;
    /**
     * @var int|null
     */
    private $postAuthor;
    /**
     * @var string|null
     */
    private $postDate;
    /**
     * @var string|null
     */
    private $postDateGmt;
    /**
     * @var string|int|null
     */
    private $postContent;
    /**
     * @var string|int|null
     */
    private $postTitle;
    /**
     * @var string|int|null
     */
    private $postExcerpt;
    /**
     * @var string|null
     */
    private $postStatus;
    /**
     * @var string|null
     */
    private $commentStatus;
    /**
     * @var string|null
     */
    private $pingStatus;
    /**
     * @var string|null
     */
    private $postPassword;
    /**
     * @var string|int|null
     */
    private $postName;
    /**
     * @var string|int|null
     */
    private $toPing;
    /**
     * @var string|int|null
     */
    private $pinged;
    /**
     * @var string|null
     */
    private $postModified;
    /**
     * @var string|null
     */
    private $postModifiedGmt;
    /**
     * @var string|int|null
     */
    private $postContentFiltered;
    /**
     * @var int|null
     */
    private $postParent;
    /**
     * @var string|int|null
     */
    private $guid;
    /**
     * @var int|null
     */
    private $menuOrder;
    /**
     * @var string|int|null
     */
    private $postType;
    /**
     * @var string|null
     */
    private $postMimeType;
    /**
     * @var int|null
     */
    private $commentCount;

    /**
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|string|null $id
     * @return AbstractPostModel
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPostAuthor(): ?int
    {
        return $this->postAuthor;
    }

    /**
     * @param int|null $postAuthor
     * @return AbstractPostModel
     */
    public function setPostAuthor(?int $postAuthor): AbstractPostModel
    {
        $this->postAuthor = $postAuthor;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostDate(): ?string
    {
        return $this->postDate;
    }

    /**
     * @param string|null $postDate
     * @return AbstractPostModel
     */
    public function setPostDate(?string $postDate): AbstractPostModel
    {
        $this->postDate = $postDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostDateGmt(): ?string
    {
        return $this->postDateGmt;
    }

    /**
     * @param string|null $postDateGmt
     * @return AbstractPostModel
     */
    public function setPostDateGmt(?string $postDateGmt): AbstractPostModel
    {
        $this->postDateGmt = $postDateGmt;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getPostContent()
    {
        return $this->postContent;
    }

    /**
     * @param int|string|null $postContent
     * @return AbstractPostModel
     */
    public function setPostContent($postContent)
    {
        $this->postContent = $postContent;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getPostTitle()
    {
        return $this->postTitle;
    }

    /**
     * @param int|string|null $postTitle
     * @return AbstractPostModel
     */
    public function setPostTitle($postTitle)
    {
        $this->postTitle = $postTitle;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getPostExcerpt()
    {
        return $this->postExcerpt;
    }

    /**
     * @param int|string|null $postExcerpt
     * @return AbstractPostModel
     */
    public function setPostExcerpt($postExcerpt)
    {
        $this->postExcerpt = $postExcerpt;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostStatus(): ?string
    {
        return $this->postStatus;
    }

    /**
     * @param string|null $postStatus
     * @return AbstractPostModel
     */
    public function setPostStatus(?string $postStatus): AbstractPostModel
    {
        $this->postStatus = $postStatus;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCommentStatus(): ?string
    {
        return $this->commentStatus;
    }

    /**
     * @param string|null $commentStatus
     * @return AbstractPostModel
     */
    public function setCommentStatus(?string $commentStatus): AbstractPostModel
    {
        $this->commentStatus = $commentStatus;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPingStatus(): ?string
    {
        return $this->pingStatus;
    }

    /**
     * @param string|null $pingStatus
     * @return AbstractPostModel
     */
    public function setPingStatus(?string $pingStatus): AbstractPostModel
    {
        $this->pingStatus = $pingStatus;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostPassword(): ?string
    {
        return $this->postPassword;
    }

    /**
     * @param string|null $postPassword
     * @return AbstractPostModel
     */
    public function setPostPassword(?string $postPassword): AbstractPostModel
    {
        $this->postPassword = $postPassword;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getPostName()
    {
        return $this->postName;
    }

    /**
     * @param int|string|null $postName
     * @return AbstractPostModel
     */
    public function setPostName($postName)
    {
        $this->postName = $postName;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getToPing()
    {
        return $this->toPing;
    }

    /**
     * @param int|string|null $toPing
     * @return AbstractPostModel
     */
    public function setToPing($toPing)
    {
        $this->toPing = $toPing;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getPinged()
    {
        return $this->pinged;
    }

    /**
     * @param int|string|null $pinged
     * @return AbstractPostModel
     */
    public function setPinged($pinged)
    {
        $this->pinged = $pinged;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostModified(): ?string
    {
        return $this->postModified;
    }

    /**
     * @param string|null $postModified
     * @return AbstractPostModel
     */
    public function setPostModified(?string $postModified): AbstractPostModel
    {
        $this->postModified = $postModified;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostModifiedGmt(): ?string
    {
        return $this->postModifiedGmt;
    }

    /**
     * @param string|null $postModifiedGmt
     * @return AbstractPostModel
     */
    public function setPostModifiedGmt(?string $postModifiedGmt): AbstractPostModel
    {
        $this->postModifiedGmt = $postModifiedGmt;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getPostContentFiltered()
    {
        return $this->postContentFiltered;
    }

    /**
     * @param int|string|null $postContentFiltered
     * @return AbstractPostModel
     */
    public function setPostContentFiltered($postContentFiltered)
    {
        $this->postContentFiltered = $postContentFiltered;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPostParent(): ?int
    {
        return $this->postParent;
    }

    /**
     * @param int|null $postParent
     * @return AbstractPostModel
     */
    public function setPostParent(?int $postParent): AbstractPostModel
    {
        $this->postParent = $postParent;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * @param int|string|null $guid
     * @return AbstractPostModel
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMenuOrder(): ?int
    {
        return $this->menuOrder;
    }

    /**
     * @param int|null $menuOrder
     * @return AbstractPostModel
     */
    public function setMenuOrder(?int $menuOrder): AbstractPostModel
    {
        $this->menuOrder = $menuOrder;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getPostType()
    {
        return $this->postType;
    }

    /**
     * @param int|string|null $postType
     * @return AbstractPostModel
     */
    public function setPostType($postType)
    {
        $this->postType = $postType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostMimeType(): ?string
    {
        return $this->postMimeType;
    }

    /**
     * @param string|null $postMimeType
     * @return AbstractPostModel
     */
    public function setPostMimeType(?string $postMimeType): AbstractPostModel
    {
        $this->postMimeType = $postMimeType;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCommentCount(): ?int
    {
        return $this->commentCount;
    }

    /**
     * @param int|null $commentCount
     * @return AbstractPostModel
     */
    public function setCommentCount(?int $commentCount): AbstractPostModel
    {
        $this->commentCount = $commentCount;
        return $this;
    }
}
