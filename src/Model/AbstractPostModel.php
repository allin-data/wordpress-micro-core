<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Model;

/**
 * Class AbstractPostModel
 * @package AllInData\MicroErp\Core\Model
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
     * @var string|null
     */
    private $postContent;
    /**
     * @var string|null
     */
    private $postTitle;
    /**
     * @var string|null
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
     * @var string|null
     */
    private $postName;
    /**
     * @var string|null
     */
    private $toPing;
    /**
     * @var string|null
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
     * @var string|null
     */
    private $postContentFiltered;
    /**
     * @var int|null
     */
    private $postParent;
    /**
     * @var string|null
     */
    private $guid;
    /**
     * @var int|null
     */
    private $menuOrder;
    /**
     * @var string|null
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
     * @return string|null
     */
    public function getPostContent(): ?string
    {
        return $this->postContent;
    }

    /**
     * @param string|null $postContent
     * @return AbstractPostModel
     */
    public function setPostContent(?string $postContent): AbstractPostModel
    {
        $this->postContent = $postContent;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostTitle(): ?string
    {
        return $this->postTitle;
    }

    /**
     * @param string|null $postTitle
     * @return AbstractPostModel
     */
    public function setPostTitle(?string $postTitle): AbstractPostModel
    {
        $this->postTitle = $postTitle;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostExcerpt(): ?string
    {
        return $this->postExcerpt;
    }

    /**
     * @param string|null $postExcerpt
     * @return AbstractPostModel
     */
    public function setPostExcerpt(?string $postExcerpt): AbstractPostModel
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
     * @return string|null
     */
    public function getPostName(): ?string
    {
        return $this->postName;
    }

    /**
     * @param string|null $postName
     * @return AbstractPostModel
     */
    public function setPostName(?string $postName): AbstractPostModel
    {
        $this->postName = $postName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getToPing(): ?string
    {
        return $this->toPing;
    }

    /**
     * @param string|null $toPing
     * @return AbstractPostModel
     */
    public function setToPing(?string $toPing): AbstractPostModel
    {
        $this->toPing = $toPing;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPinged(): ?string
    {
        return $this->pinged;
    }

    /**
     * @param string|null $pinged
     * @return AbstractPostModel
     */
    public function setPinged(?string $pinged): AbstractPostModel
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
     * @return string|null
     */
    public function getPostContentFiltered(): ?string
    {
        return $this->postContentFiltered;
    }

    /**
     * @param string|null $postContentFiltered
     * @return AbstractPostModel
     */
    public function setPostContentFiltered(?string $postContentFiltered): AbstractPostModel
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
     * @return string|null
     */
    public function getGuid(): ?string
    {
        return $this->guid;
    }

    /**
     * @param string|null $guid
     * @return AbstractPostModel
     */
    public function setGuid(?string $guid): AbstractPostModel
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
     * @return string|null
     */
    public function getPostType(): ?string
    {
        return $this->postType;
    }

    /**
     * @param string|null $postType
     * @return AbstractPostModel
     */
    public function setPostType(?string $postType): AbstractPostModel
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
