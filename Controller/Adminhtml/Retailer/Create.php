<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\Retailer\Controller\Adminhtml\Retailer;

use Magento\Backend\App\Action;
use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;

/**
 * Retailer Adminhtml Edit controller.
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class Create extends AbstractRetailer
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultPage = $this->createPage();

        $resultPage->setActiveMenu('Smile_Seller::sellers');
        $resultPage->getConfig()->getTitle()->prepend(__('New Retailer'));

        return $resultPage;
    }
}
