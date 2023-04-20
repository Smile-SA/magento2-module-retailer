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

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Smile\Retailer\Api\Data\RetailerInterfaceFactory;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;
use Smile\Retailer\Model\ResourceModel\Retailer\CollectionFactory;
use Smile\Retailer\Model\Retailer\PostDataHandlerInterface;
use Smile\Seller\Api\Data\SellerInterface;

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
     * @var PostDataHandlerInterface[]
     */
    private array $postDataHandlers;

    /**
     * Constructor.
     *
     * @param Context                       $context              Application context.
     * @param PageFactory                   $resultPageFactory    Result Page factory.
     * @param ForwardFactory                $resultForwardFactory Result forward factory.
     * @param Registry                      $coreRegistry         Application registry.
     * @param RetailerRepositoryInterface   $retailerRepository   Retailer Repository
     * @param RetailerInterfaceFactory      $retailerFactory      Retailer Factory.
     * @param Filter                        $filter               Mass Action Filter.
     * @param CollectionFactory             $collectionFactory    Retailer collection for Mass Action.
     * @param PostDataHandlerInterface[]    $postDataHandlers     Form data handlers.
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Registry $coreRegistry,
        RetailerRepositoryInterface $retailerRepository,
        RetailerInterfaceFactory $retailerFactory,
        Filter $filter,
        CollectionFactory $collectionFactory,
        array $postDataHandlers = []
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
        $this->postDataHandlers = $postDataHandlers;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Redirect|ResponseInterface|ResultInterface
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data         = $this->getRequest()->getPostValue();
        $redirectBack = $this->getRequest()->getParam('back', false);

        if ($data) {
            $identifier = $this->getRequest()->getParam('id');
            $storeId    = $this->getRequest()->getParam('store_id', \Magento\Store\Model\Store::DEFAULT_STORE_ID);
            $model      = $this->retailerFactory->create();

            $media = false;
            if (!empty($data[SellerInterface::MEDIA_PATH])
                && isset($data[SellerInterface::MEDIA_PATH][0]['name'])
            ) {
                $media = $data[SellerInterface::MEDIA_PATH][0]['name'];
            }
            unset($data[SellerInterface::MEDIA_PATH]);

            if ($identifier) {
                $model = $this->retailerRepository->get($identifier);
                if (!$model->getId()) {
                    $this->messageManager->addError(__('This retailer no longer exists.'));

                    return $resultRedirect->setPath('*/*/');
                }
            }

            foreach ($this->postDataHandlers as $handler) {
                $data = $handler->getData($model, $data);
            }

            $model->setData($data);
            $model->setStoreId($storeId);
            if ($media) {
                $model->setMediaPath($media);
            }

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
}
