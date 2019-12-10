<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Fanny DECLERCK <fadec@smile.fr>
 * @copyright 2019 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\Retailer\Ui\Component\Form\Retailer;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\App\RequestInterface;
use Smile\Retailer\Model\ResourceModel\Retailer\CollectionFactory;

/**
 * Source Model for Retailer Picker
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Fanny DECLERCK <fadec@smile.fr>
 */
class Options implements OptionSourceInterface
{
    /**
     * @var \Smile\Retailer\Model\ResourceModel\Retailer\CollectionFactory
     */
    protected $retailerCollectionFactory;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $retailersList;

    /**
     * @param CollectionFactory $retailerCollectionFactory The Retailer Collection Factory
     * @param RequestInterface  $request                   The application request
     */
    public function __construct(
        CollectionFactory $retailerCollectionFactory,
        RequestInterface $request
    ) {
        $this->retailerCollectionFactory = $retailerCollectionFactory;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getRetailerList();
    }

    /**
     * Retrieve retailer tree
     *
     * @return array
     */
    protected function getRetailerList()
    {
        if ($this->retailersList === null) {
            $this->retailersList = [];
            $storeId = $this->request->getParam('store');

            /* @var $collection \Smile\Seller\Model\ResourceModel\Seller\Collection */
            $collection = $this->retailerCollectionFactory->create();

            $collection
                ->addAttributeToSelect(['name', 'is_active'])
                ->setStoreId($storeId);

            foreach ($collection as $retailer) {
                $this->retailersList[$retailer->getId()] = [
                    'value'     => $retailer->getId(),
                    'is_active' => $retailer->getIsActive(),
                    'label'     => $retailer->getName(),
                ];
            }
        }

        return $this->retailersList;
    }
}
