<?php

declare(strict_types=1);

namespace Smile\Retailer\Controller\Adminhtml\Retailer;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;

/**
 * Retailer Adminhtml MassAllowDelivery controller.
 */
class MassAllowDelivery extends AbstractRetailer implements HttpPostActionInterface
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $retailerIds = $this->getAllSelectedIds();
        foreach ($retailerIds as $id) {
            $model = $this->retailerRepository->get($id);
            $model->setData('allow_store_delivery', true);
            $this->retailerRepository->save($model);
        }

        $this->messageManager->addSuccessMessage(
            __('A total of %1 record(s) have been allowed to store delivery.', count($retailerIds))
        );

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}
