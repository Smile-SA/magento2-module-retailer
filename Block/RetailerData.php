<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this file to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\Retailer\Block;

use Magento\Framework\View\Element\Template;

/**
 * Block that handles configuration for the Javascript Data provider component
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class RetailerData extends Template
{
    /**
     * Get CookieLifeTime
     *
     * @return null|string
     */
    public function getCookieLifeTime()
    {
        return $this->_scopeConfig->getValue(
            \Magento\Framework\Session\Config::XML_PATH_COOKIE_LIFETIME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get url for Retailer data ajax requests. Returns url with protocol matching used to request page.
     *
     * @param string $route A route to be displayed on the block.
     *
     * @return string Retailer Api Base URL.
     */
    public function getApiBaseUrl($route)
    {
        return $this->getUrl($route, ['_secure' => $this->getRequest()->isSecure()]);
    }
}
