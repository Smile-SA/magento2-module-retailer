<?php

declare(strict_types=1);

namespace Smile\Retailer\Controller\Adminhtml\Retailer;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;

/**
 * Retailer Adminhtml Index controller.
 */
class Index extends AbstractRetailer implements HttpGetActionInterface
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $resultPage = $this->createPage();
        $resultPage->getConfig()->getTitle()->prepend(__('Retailers List'));

        return $resultPage;
    }
}
