<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model;

use AllInData\MicroErp\Core\Model\AbstractPostModel;

/**
 * Class ResourceType
 * @package AllInData\MicroErp\Resource\Model
 */
class ResourceType extends AbstractPostModel
{
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var string|null
     */
    private $label;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return ResourceType
     */
    public function setName(?string $name): ResourceType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     * @return ResourceType
     */
    public function setLabel(?string $label): ResourceType
    {
        $this->label = $label;
        return $this;
    }
}