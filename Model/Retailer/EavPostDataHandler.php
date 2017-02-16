<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2017 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\Retailer\Model\Retailer;

/**
 * EAV Post Data handler for retailer edition : will handle values submitted with the "Use Default" checkbox checked.
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class EavPostDataHandler implements PostDataHandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public function getData(\Smile\Retailer\Api\Data\RetailerInterface $retailer, $data)
    {
        if (isset($data['use_default']) && !empty($data['use_default'])) {
            foreach ($data['use_default'] as $attributeCode => $attributeValue) {
                if ($attributeValue && isset($data[$attributeCode])) {
                    $data[$attributeCode] = null;
                }
            }
        }

        return $data;
    }
}
