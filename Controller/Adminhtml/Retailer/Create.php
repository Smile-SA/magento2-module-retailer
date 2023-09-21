<?php

declare(strict_types=1);

namespace Smile\Retailer\Controller\Adminhtml\Retailer;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;

/**
 * Retailer Adminhtml Edit controller.
 */
class Create extends AbstractRetailer implements HttpGetActionInterface
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
