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
use Magento\Framework\Stdlib\DateTime;
use Smile\Retailer\Helper\Settings;

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
     * @var \Smile\Retailer\CustomerData\SettingsHelper|\Smile\Retailer\Helper\Settings
     */
    private $settingsHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @param Session                $checkoutSession       The checkout session
     * @param CookieManagerInterface $cookieManager         Cookie Manager
     * @param CookieMetadataFactory  $cookieMetadataFactory Cookie Metadata Factory
     * @param Settings               $settingsHelper        Retailer Settings Helper
     * @param DateTime               $dateTime              DateTime
     */
    public function __construct(
        Session $checkoutSession,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        Settings $settingsHelper,
        DateTime $dateTime
    ) {
        $this->checkoutSession       = $checkoutSession;
        $this->cookieManager         = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->settingsHelper        = $settingsHelper;
        $this->dateTime              = $dateTime;
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
        if (!$this->settingsHelper->isPickupDateDisplayed()) {
            $date = new \DateTime();

            return $this->dateTime->formatDate($date, false);
        }

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
