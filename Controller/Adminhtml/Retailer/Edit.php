<?php

declare(strict_types=1);

namespace Smile\Retailer\Controller\Adminhtml\Retailer;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;

/**
 * Retailer Adminhtml Edit controller.
 */
class Edit extends AbstractRetailer implements HttpGetActionInterface
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $retailerId = (int) $this->getRequest()->getParam('id');
        $storeId    = (int) $this->getRequest()->getParam('store', null);
        $retailer   = null;

        try {
            $retailer = $this->retailerRepository->get($retailerId, $storeId);
            $this->coreRegistry->register('current_seller', $retailer);
            $resultPage->getConfig()->getTitle()->prepend(__('Edit %1', $retailer->getName()));
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while editing the retailer.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/index');

            return $resultRedirect;
        }

        $resultPage->addBreadcrumb(__('Retailer'), __('Retailer'));

        return $resultPage;
    }
}
