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
namespace Smile\Retailer\Model\Retailer\OpeningHours;

use Smile\Retailer\Api\Data\OpeningHours\OpeningHoursManagementInterface;
use Smile\Retailer\Api\Data\OpeningHours\OpeningHoursRepositoryInterface;

/**
 * Opening Hours Management class
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class Management implements OpeningHoursManagementInterface
{
    /**
     * @var \Smile\Retailer\Api\Data\OpeningHours\OpeningHoursRepositoryInterface
     */
    private $openingHoursRepository;

    /**
     * Management constructor.
     *
     * @param \Smile\Retailer\Api\Data\OpeningHours\OpeningHoursRepositoryInterface $openingHoursRepository Opening Hours repository
     */
    public function __construct(OpeningHoursRepositoryInterface $openingHoursRepository)
    {
        $this->openingHoursRepository = $openingHoursRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function isOpen($retailer, $dateTime)
    {
        // TODO: Implement isOpen() method.
    }

    /**
     * {@inheritdoc}
     */
    public function isClosed($retailer, $dateTime)
    {
        return !$this->isOpen($retailer, $dateTime);
    }

    /**
     * Retrieve Opening Hours Repository
     *
     * @return OpeningHoursRepositoryInterface
     */
    private function getOpeningHoursRepository()
    {
        return $this->openingHoursRepository;
    }
}
