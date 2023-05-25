<?php

namespace Smile\Retailer\Controller\Adminhtml\Retailer;

use Exception;
use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;

/**
 * Retailer Adminhtml Delete controller.
 */
class Delete extends AbstractRetailer
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $identifier = $this->getRequest()->getParam('id', false);
        $model = $this->retailerFactory->create();
        if ($identifier) {
            $model = $this->retailerRepository->get($identifier);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This retailer no longer exists.'));

                return $resultRedirect->setPath('*/*/index');
            }
        }

        try {
            $this->retailerRepository->delete($model);
            $this->messageManager->addSuccess(__('You deleted the retailer %1.', $model->getName()));

            return $resultRedirect->setPath('*/*/index');
        } catch (Exception $e) {
            $this->messageManager->addError($e->getMessage());

            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
    }
}
