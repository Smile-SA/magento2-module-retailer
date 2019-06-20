<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2019 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\Retailer\Controller\Adminhtml\Retailer;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;

/**
 * Retailer Adminhtml MassDisallowDelivery controller.
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class MassDisallowDelivery extends AbstractRetailer implements HttpPostActionInterface
{
    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $retailerIds = $this->getRequest()->getParam('selected');
        foreach ($retailerIds as $id) {
            $model = $this->retailerRepository->get($id);
            $model->setData('allow_store_delivery', false);
            $this->retailerRepository->save($model);
        }

        $this->messageManager->addSuccessMessage(
            __('A total of %1 record(s) have been disallowed to store delivery.', count($retailerIds))
        );

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }
}
