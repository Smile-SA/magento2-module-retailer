<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\Retailer\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Retailer Section for frontend usage
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class RetailerData implements SectionSourceInterface
{
    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @param CustomerSession $customerSession
     */
    public function __construct(CustomerSession $customerSession) {
        $this->customerSession = $customerSession;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        return [
            'retailer_id' => $this->customerSession->getRetailerId(),
            'pickup_date' => $this->customerSession->getRetailerPickupDate(),
        ];
    }
}
