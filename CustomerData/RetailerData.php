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
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @param \Magento\Checkout\Model\Session                        $checkoutSession The checkout session
     * @param \Magento\Framework\Stdlib\CookieManagerInterface       $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     *
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
    ) {
        $this->checkoutSession       = $checkoutSession;
        $this->cookieManager         = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
    }

    public function setParams($retailerId, $pickupDate)
    {
        $this->checkoutSession->setRetailerId($retailerId);
        $this->checkoutSession->setPickupDate($pickupDate);

        $this->updateCookies();

        return $this;
    }

    public function getRetailerId()
    {
        return $this->checkoutSession->getRetailerId();
    }

    public function getPickupDate()
    {
        $pickupDate = $this->checkoutSession->getPickupDate();

        return $pickupDate;
    }

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
