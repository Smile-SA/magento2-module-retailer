<?php

namespace Smile\Retailer\Controller\Adminhtml\Retailer;

use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;

/**
 * Retailer Adminhtml Index controller.
 */
class Index extends AbstractRetailer
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
