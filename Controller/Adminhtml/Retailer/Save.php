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
use Smile\Retailer\Api\Data\OpeningHoursInterface;
use Smile\Retailer\Api\Data\SpecialOpeningHoursInterface;
use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;

/**
 * Retailer Adminhtml Save controller.
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class Save extends AbstractRetailer
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data         = $this->getRequest()->getPostValue();
        $redirectBack = $this->getRequest()->getParam('back', false);

        if ($data) {
            $identifier = $this->getRequest()->getParam('id');
            $storeId    = $this->getRequest()->getParam('store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID);
            $model      = $this->retailerFactory->create();

            if ($identifier) {
                $model = $this->retailerRepository->get($identifier);
                if (!$model->getId()) {
                    $this->messageManager->addError(__('This retailer no longer exists.'));

                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);
            if (null !== $storeId) {
                $model->setStoreId($storeId);
            }

            $this->processExtensionAttributes($model, $data);

            try {
                $this->retailerRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the retailer %1.', $model->getName()));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);

                if ($redirectBack) {
                    $redirectParams = ['id' => $model->getId()];
                    if (null !== $storeId) {
                        $redirectParams['store'] = $storeId;
                    }

                    return $resultRedirect->setPath('*/*/edit', $redirectParams);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);

                $returnParams = ['id' => $model->getId()];

                return $resultRedirect->setPath('*/*/edit', $returnParams);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process time based extension attributes
     *
     * @param \Smile\Retailer\Api\Data\RetailerInterface $model The retailer
     * @param array                                      $data  POST Data from the form
     */
    private function processExtensionAttributes($model, $data)
    {
        if (isset($data[OpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE])) {
            $extension = $model->getExtensionAttributes();
            $openingHours = $this->openingHoursFactory->create();
            $openingHours->loadPostData($data[OpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE]);
            $extension->setOpeningHours($openingHours);
            $model->setExtensionAttributes($extension);
        }

        if (isset($data[SpecialOpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE])) {
            $extension = $model->getExtensionAttributes();
            $specialOpeningHours = $this->specialOpeningHoursFactory->create();
            $specialOpeningHours->loadPostData($data[SpecialOpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE]);
            $extension->setSpecialOpeningHours($specialOpeningHours);
            $model->setExtensionAttributes($extension);
        }
    }
}
