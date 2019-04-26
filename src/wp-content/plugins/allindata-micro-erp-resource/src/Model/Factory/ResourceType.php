<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model\Factory;

use AllInData\MicroErp\Core\Model\AbstractFactory;
use AllInData\MicroErp\Core\Model\AbstractModel;
use AllInData\MicroErp\Core\Model\GenericFactory;
use AllInData\MicroErp\Resource\Model\ResourceType as Entity;

/**
 * Class ResourceType
 * @package AllInData\MicroErp\Resource\Model\Factory
 */
class ResourceType extends AbstractFactory
{
    /**
     * @var GenericFactory
     */
    private $resourceTypeAttributeFactory;

    /**
     * AbstractFactory constructor.
     * @param string $modelClass
     * @param GenericFactory $resourceTypeAttributeFactory
     */
    public function __construct(string $modelClass, GenericFactory $resourceTypeAttributeFactory)
    {
        parent::__construct($modelClass);
        $this->resourceTypeAttributeFactory = $resourceTypeAttributeFactory;
    }

    /**
     * @param array $data
     * @return Entity
     */
    public function create(array $data = []): AbstractModel
    {
        if (isset($data['attributes']) && is_array($data['attributes'])) {
            foreach ($data['attributes'] as $attributeDataSet) {
                if (!is_array($attributeDataSet)) {
                    continue;
                }
                $data['attributes'][] = $this->resourceTypeAttributeFactory->create($attributeDataSet);
            }
        } else {
            $data['attributes'] = null;
        }
        return parent::create($data);
    }
}