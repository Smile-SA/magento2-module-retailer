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
namespace Smile\Retailer\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Retailer Settings Helper
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class Settings extends AbstractHelper
{
    /**
     * Location of RetailerSuite base settings configuration.
     *
     * @var string
     */
    const BASE_CONFIG_XML_PREFIX = 'smile_retailersuite_retailer_base_settings';

    /**
     * @var string
     */
    const NAVIGATION_SETTINGS_CONFIG_XML_PREFIX = 'navigation_settings';

    /**
     * Check if we should display the Pickup Date in Front Office.
     *
     * @return bool
     */
    public function isPickupDateDisplayed()
    {
        return (bool) $this->getNavigationSettings('display_pickup_date');
    }

    /**
     * Retrieve Retailer Configuration for a given field.
     *
     * @param string $path The config path to retrieve
     *
     * @return mixed
     */
    protected function getNavigationSettings($path)
    {
        $configPath = implode('/', [self::BASE_CONFIG_XML_PREFIX, self::NAVIGATION_SETTINGS_CONFIG_XML_PREFIX, $path]);

        return $this->scopeConfig->getValue($configPath);
    }
}
