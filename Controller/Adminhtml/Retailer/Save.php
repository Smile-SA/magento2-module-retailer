<?php

declare(strict_types=1);

namespace Smile\Retailer\Controller\Adminhtml\Retailer;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Session;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\Store;
use Magento\Ui\Component\MassAction\Filter;
use Smile\Retailer\Api\Data\RetailerInterfaceFactory;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;
use Smile\Retailer\Model\ResourceModel\Retailer\CollectionFactory;
use Smile\Seller\Api\Data\SellerInterface;

/**
 * Retailer Adminhtml Save controller.
 */
class Save extends AbstractRetailer implements HttpPostActionInterface
{
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Registry $coreRegistry,
        RetailerRepositoryInterface $retailerRepository,
        RetailerInterfaceFactory $retailerFactory,
        Filter $filter,
        CollectionFactory $collectionFactory,
        private array $postDataHandlers = []
    ) {
        parent::__construct(
            $context,
            $resultPageFactory,
            $resultForwardFactory,
            $coreRegistry,
            $retailerRepository,
            $retailerFactory,
            $filter,
            $collectionFactory
        );
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        /** @var Request $request */
        $request = $this->getRequest();
        $data = $request->getPostValue();
        $redirectBack = $request->getParam('back', false);

        if ($data) {
            $identifier = $request->getParam('id');
            $storeId = $request->getParam('store_id', Store::DEFAULT_STORE_ID);
            $model = $this->retailerFactory->create();
            $media = false;

            if (
                !empty($data[SellerInterface::MEDIA_PATH])
                && isset($data[SellerInterface::MEDIA_PATH][0]['name'])
            ) {
                $media = $data[SellerInterface::MEDIA_PATH][0]['name'];
            }
            unset($data[SellerInterface::MEDIA_PATH]);

            if ($identifier) {
                $model = $this->retailerRepository->get((int) $identifier);
                if (!$model->getId()) {
                    $this->messageManager->addError(__('This retailer no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            foreach ($this->postDataHandlers as $handler) {
                $data = $handler->getData($model, $data);
            }

            $model->setData($data);
            $model->setData('store_id', $storeId);
            if ($media) {
                $model->setMediaPath($media);
            }

            try {
                $this->retailerRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the retailer %1.', $model->getName()));
                $this->_objectManager->get(Session::class)->setFormData(false);

                if ($redirectBack) {
                    $redirectParams = ['id' => $model->getId()];
                    if (null !== $storeId) {
                        $redirectParams['store'] = $storeId;
                    }

                    return $resultRedirect->setPath('*/*/edit', $redirectParams);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_objectManager->get(Session::class)->setFormData($data);

                $returnParams = ['id' => $model->getId()];

                return $resultRedirect->setPath('*/*/edit', $returnParams);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
