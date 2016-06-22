<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade Smile Elastic Suite to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\Retailer\Controller\Adminhtml\Retailer;

use Smile\Retailer\Api\Data\RetailerInterface;
use Smile\Retailer\Controller\Adminhtml\AbstractRetailer;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Seller\Model\SellerFactory;

/**
 * Retailer Adminhtml Inline Editing controller.
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class InlineEdit extends AbstractRetailer
{
    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context                     $context              Application context
     * @param PageFactory                 $resultPageFactory    Result Page factory
     * @param ForwardFactory              $resultForwardFactory Result forward factory
     * @param Registry                    $coreRegistry         Application registry
     * @param RetailerRepositoryInterface $retailerRepository   Retailer Repository
     * @param SellerFactory               $retailerFactory      Retailer Factory
     * @param JsonFactory                 $jsonFactory          JSON Factory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Registry $coreRegistry,
        RetailerRepositoryInterface $retailerRepository,
        SellerFactory $retailerFactory,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context, $resultPageFactory, $resultForwardFactory, $coreRegistry, $retailerRepository, $retailerFactory);
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * Process inline editing of retailers
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);

            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
                
                return $resultJson->setData(['messages' => $messages, 'error' => $error]);
            }

            foreach (array_keys($postItems) as $retailerId) {
                /** @var \Smile\Retailer\Api\Data\RetailerInterface $retailer */
                $retailer = $this->retailerRepository->get($retailerId);
                try {
                    $retailer->setData(array_merge($retailer->getData(), $postItems[$retailerId]));
                    $this->retailerRepository->save($retailer);
                } catch (\Exception $e) {
                    $messages[] = $this->getErrorWithRetailerId($retailer, __($e->getMessage()));
                    $error = true;
                }
            }
        }

        return $resultJson->setData(['messages' => $messages, 'error' => $error]);
    }

    /**
     * Add retailer title to error message
     *
     * @param RetailerInterface $retailer  The retailer
     * @param string            $errorText The error message
     *
     * @return string
     */
    protected function getErrorWithRetailerId(RetailerInterface $retailer, $errorText)
    {
        return '[Retailer ID: ' . $retailer->getId() . '] ' . $errorText;
    }
}
