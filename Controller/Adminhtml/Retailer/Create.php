<?php

namespace Smile\Retailer\Controller\Adminhtml\Retailer;

use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;

/**
 * Retailer Adminhtml Edit controller.
 */
class Create extends AbstractRetailer
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $resultPage = $this->createPage();

        $resultPage->setActiveMenu('Smile_Seller::sellers');
        $resultPage->getConfig()->getTitle()->prepend(__('New Retailer'));

        return $resultPage;
    }
}
