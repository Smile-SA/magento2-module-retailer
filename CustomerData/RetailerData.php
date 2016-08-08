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

use Magento\Checkout\Model\Session;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

/**
 * Retailer Section for frontend usage
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class RetailerData
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @param Session                $checkoutSession       The checkout session
     * @param CookieManagerInterface $cookieManager         Cookie Manager
     * @param CookieMetadataFactory  $cookieMetadataFactory Cookie Metadata Factory
     *
     */
    public function __construct(
        Session $checkoutSession,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory
    ) {
        $this->checkoutSession       = $checkoutSession;
        $this->cookieManager         = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
    }

    /**
     * Apply retailer id and pickup date to current customer cookies.
     *
     * @param integer $retailerId The retailer Id
     * @param string  $pickupDate The pickup date
     *
     * @return $this
     */
    public function setParams($retailerId, $pickupDate)
    {
        $this->checkoutSession->setRetailerId($retailerId);
        $this->checkoutSession->setPickupDate($pickupDate);

        $this->updateCookies();

        return $this;
    }

    /**
     * Retrieve current retailer Id if any.
     *
     * @return int|null
     */
    public function getRetailerId()
    {
        return $this->checkoutSession->getRetailerId();
    }

    /**
     * Retrieve current Pickup Date if any.
     *
     * @return string|null
     */
    public function getPickupDate()
    {
        $pickupDate = $this->checkoutSession->getPickupDate();

        return $pickupDate;
    }

    /**
     * Update Customer cookies with current values for retailer id and pickup date.
     */
    private function updateCookies()
    {
        $metadata = $this->cookieMetadataFactory->createPublicCookieMetadata();
        $metadata->setPath($this->checkoutSession->getCookiePath());
        $metadata->setDomain($this->checkoutSession->getCookieDomain());
        $metadata->setDuration($this->checkoutSession->getCookieLifetime());
        $this->cookieManager->setPublicCookie('smile_retailer_id', $this->getRetailerId(), $metadata);
        $this->cookieManager->setPublicCookie('smile_retailer_pickupdate', $this->getPickupDate(), $metadata);
    }
}
