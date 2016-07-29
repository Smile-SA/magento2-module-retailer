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
use Magento\Framework\Stdlib\DateTime;

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

    private $dateTime;

    /**
     * @param CustomerSession $customerSession The Customer Session
     */
    public function __construct(CustomerSession $customerSession, DateTime $dateTime)
    {
        $this->customerSession = $customerSession;
        $this->dateTime        = $dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        return [
            'retailer_id' => $this->customerSession->getRetailerId(),
            'pickup_date' => $this->getPickupDate(),
        ];
    }

    private function getPickupDate()
    {
        $pickupDate = $this->customerSession->getRetailerPickupDate();

        if ($pickupDate === null) {
            $now = new \DateTime();
            $pickupDate = $this->dateTime->formatDate($now, false);
        }

        return $pickupDate;
    }
}
