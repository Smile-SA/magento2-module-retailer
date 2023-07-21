<?php

declare(strict_types=1);

namespace Smile\Retailer\Controller\Adminhtml\Retailer;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;

/**
 * Retailer Adminhtml MassEnable controller.
 */
class MassEnable extends AbstractRetailer implements HttpPostActionInterface
{
    /**
     * @inheritdoc
     */
    public function execute()
    {
        $retailerIds = $this->getAllSelectedIds();
        foreach ($retailerIds as $id) {
            $model = $this->retailerRepository->get((int) $id);
            $model->setData('is_active', true);
            $this->retailerRepository->save($model);
        }

        $this->messageManager->addSuccessMessage(
            __('A total of %1 record(s) have been enabled.', count($retailerIds))
        );

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}
