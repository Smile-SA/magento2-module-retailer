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

use Smile\Retailer\Api\SpecialOpeningHoursRepositoryInterface;

/**
 * SpecialOpening Hours Repository
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class SpecialOpeningHoursRepository implements SpecialOpeningHoursRepositoryInterface
{
    /**
     * @var \Smile\Retailer\Model\Retailer\SpecialOpeningHoursFactory
     */
    private $specialOpeningHoursFactory;

    /**
     * Special Opening Hours Repository constructor.
     *
     * @param SpecialOpeningHoursFactory $specialOpeningHoursFactory The specialOpening hours factory
     */
    public function __construct(SpecialOpeningHoursFactory $specialOpeningHoursFactory)
    {
        $this->specialOpeningHoursFactory = $specialOpeningHoursFactory;
    }

    /**
     * Retrieve special Opening hours for a given retailer
     *
     * @param \Smile\Retailer\Api\Data\RetailerInterface $retailer The retailer
     *
     * @return array
     */
    public function getByRetailer($retailer)
    {
        $specialOpeningHoursModel = $this->specialOpeningHoursFactory->create(['retailerId' => $retailer->getId()]);
        $specialOpeningHoursModel->loadTimeRanges();

        return $specialOpeningHoursModel;
    }

    /**
     * Save specialOpening hours for a given retailer
     *
     * @param int                                            $retailerId   The retailer id
     * @param \Smile\Retailer\Api\Data\SpecialOpeningHoursInterface $specialOpeningHours The specialOpening hours object
     *
     * @return bool
     */
    public function save($retailerId, $specialOpeningHours)
    {
        $specialOpeningHours->setRetailerId($retailerId)->saveTimeRanges();
    }

    /**
     * Delete specialOpening hours for a given retailer
     *
     * @param int $retailerId The retailer id
     *
     * @return bool
     */
    public function deleteByRetailerId($retailerId)
    {
        $specialOpeningHoursModel = $this->specialOpeningHoursFactory->create();

        return $specialOpeningHoursModel->deleteByRetailerId($retailerId);
    }
}
