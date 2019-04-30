<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model\Attribute\Type;

/**
 * Class Text
 * @package AllInData\MicroErp\Resource\Model\Attribute\Type
 */
class Text extends AbstractType
{
    const TYPE = 'text';

    /**
     * @inheritDoc
     */
    public function getTypeLabel(): string
    {
        return __('Text', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function renderValue($value): string
    {
        return (string)$value;
    }
}