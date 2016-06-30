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
namespace Smile\Retailer\Model\Retailer;

use Smile\Retailer\Api\Data\OpeningHours\OpeningHoursRepositoryInterface;
use Smile\Retailer\Api\Data\OpeningHoursInterface;
use Smile\Retailer\Model\ResourceModel\Retailer\TimeSlots as TimeSlotsResource;

/**
 * _________________________________________________
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class OpeningHoursRepository implements OpeningHoursRepositoryInterface
{
    /**
     * @var \Smile\Retailer\Model\Retailer\OpeningHoursFactory
     */
    private $openingHoursFactory;

    /**
     * Opening Hours Repository constructor.
     *
     * @param OpeningHoursFactory $openingHoursFactory The opening hours factory
     */
    public function __construct(OpeningHoursFactory $openingHoursFactory)
    {
        $this->openingHoursFactory = $openingHoursFactory;
    }

    /**
     * Retrieve opening hours for a given retailer
     *
     * @param \Smile\Retailer\Api\Data\RetailerInterface $retailer The retailer
     *
     * @return array
     */
    public function getList($retailer)
    {
        $openingHoursModel = $this->openingHoursFactory->create();
        $openingHours      = $openingHoursModel->setRetailerId($retailer->getId())->getTimeRanges();

        return $openingHours;
    }

    /**
     * Retrieve opening hours for a given retailer
     *
     * @param \Smile\Retailer\Api\Data\RetailerInterface $retailer The retailer
     *
     * @return array
     */
    public function getByRetailer($retailer)
    {
        $openingHoursModel = $this->openingHoursFactory->create();
        $openingHoursModel->setRetailerId($retailer->getId())->loadTimeRanges();

        return $openingHoursModel;
    }

    /**
     * Save opening hours for a given retailer
     *
     * @param int                                            $retailerId   The retailer id
     * @param \Smile\Retailer\Api\Data\OpeningHoursInterface $openingHours The opening hours object
     *
     * @return bool
     */
    public function save($retailerId, $openingHours)
    {
        $openingHours->setRetailerId($retailerId)->saveTimeRanges();
    }

    /**
     * Delete opening hours for a given retailer
     *
     * @param int $retailerId The retailer id
     *
     * @return bool
     */
    public function deleteByRetailerId($retailerId)
    {
        $openingHoursModel = $this->openingHoursFactory->create();

        return $openingHoursModel->deleteByRetailerId($retailerId);
    }
}
