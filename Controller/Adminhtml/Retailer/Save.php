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
     * @var \Smile\Retailer\Model\Retailer\PostDataHandlerInterface[]
     */
    private $postDataHandlers;

    /**
     * Constructor.
     *
     * @param \Magento\Backend\App\Action\Context                       $context              Application context.
     * @param \Magento\Framework\View\Result\PageFactory                $resultPageFactory    Result Page factory.
     * @param \Magento\Framework\Controller\Result\ForwardFactory       $resultForwardFactory Result forward factory.
     * @param \Magento\Framework\Registry                               $coreRegistry         Application registry.
     * @param \Smile\Retailer\Api\RetailerRepositoryInterface           $retailerRepository   Retailer Repository
     * @param \Smile\Retailer\Api\Data\RetailerInterfaceFactory         $retailerFactory      Retailer Factory.
     * @param \Smile\Retailer\Model\Retailer\PostDataHandlerInterface[] $postDataHandlers     Form data handlers.
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Smile\Retailer\Api\RetailerRepositoryInterface $retailerRepository,
        \Smile\Retailer\Api\Data\RetailerInterfaceFactory $retailerFactory,
        array $postDataHandlers = []
    ) {
        parent::__construct($context, $resultPageFactory, $resultForwardFactory, $coreRegistry, $retailerRepository, $retailerFactory);
        $this->postDataHandlers = $postDataHandlers;
    }

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

            foreach ($this->postDataHandlers as $handler) {
                $data = $handler->getData($model, $data);
            }

            $model->setData($data);
            $model->setStoreId($storeId);

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
