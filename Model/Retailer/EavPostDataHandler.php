<?php

namespace Smile\Retailer\Model\Retailer;

use Smile\Retailer\Api\Data\RetailerInterface;

/**
 * EAV Post Data handler for retailer edition : will handle values submitted with the "Use Default" checkbox checked.
 */
class EavPostDataHandler implements PostDataHandlerInterface
{
    /**
     * @inheritdoc
     */
    public function getData(RetailerInterface $retailer, mixed $data): mixed
    {
        if (!empty($data['use_default'])) {
            foreach ($data['use_default'] as $attributeCode => $attributeValue) {
                if ($attributeValue && isset($data[$attributeCode])) {
                    $data[$attributeCode] = null;
                }
            }
        }

        return $data;
    }
}
