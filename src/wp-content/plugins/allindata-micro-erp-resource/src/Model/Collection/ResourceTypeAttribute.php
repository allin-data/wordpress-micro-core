<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model\Collection;

use AllInData\MicroErp\Core\Helper\MethodUtil;
use AllInData\MicroErp\Core\Model\AbstractCollection;
use InvalidArgumentException;

/**
 * Class ResourceTypeAttribute
 * @package AllInData\MicroErp\Resource\Model\Collection
 */
class ResourceTypeAttribute extends AbstractCollection
{
    const RESOURCE_TYPE_FILTER_ATTRIBUTE = 'resource_type_id';

    /**
     * @return string
     */
    protected function getQuery(): string
    {
        $db = $this->getResource()->getDatabase()->getInstance();
        return 'SELECT * FROM `'.$db->postmeta.'` AS pm ' .
            'LEFT JOIN `'.$db->posts.'` AS p ON p.`ID` = pm.`post_id` ' .
            'WHERE p.`post_type`=%s AND pm.`meta_key`=%s AND pm.`meta_value`=%s';
    }

    /**
     * @param string $rawQueryString
     * @param array $args
     * @return string
     */
    protected function getPreparedQuery(string $rawQueryString, array $args): string
    {
        $db = $this->getResource()->getDatabase()->getInstance();
        $resourceTypeId = $this->getResourceTypeId($args);
        return $db->prepare(
            $rawQueryString,
            $this->getResource()->getEntityName(),
            static::RESOURCE_TYPE_FILTER_ATTRIBUTE,
            $resourceTypeId
        );
    }

    /**
     * @param array $args
     * @return int
     */
    private function getResourceTypeId(array $args): int
    {
        $resourceTypeField = MethodUtil::decanonicalizeAttributeName(static::RESOURCE_TYPE_FILTER_ATTRIBUTE);
        $resourceTypeId = $args[$resourceTypeField] ?? null;

        if (!$resourceTypeId) {
            throw new InvalidArgumentException(
                sprintf(__('Resource type id missing', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN))
            );
        }

        return (int)$resourceTypeId;
    }
}