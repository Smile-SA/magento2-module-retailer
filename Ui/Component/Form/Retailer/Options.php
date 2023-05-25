<?php

namespace Smile\Retailer\Ui\Component\Form\Retailer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Smile\Retailer\Model\ResourceModel\Retailer\CollectionFactory;

/**
 * Source Model for Retailer Picker.
 */
class Options implements OptionSourceInterface
{
    protected ?array $retailersList = null;

    public function __construct(
        protected CollectionFactory $retailerCollectionFactory,
        protected RequestInterface $request
    ) {
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray(): array
    {
        return $this->getRetailerList();
    }

    /**
     * Retrieve retailer tree.
     */
    protected function getRetailerList(): array
    {
        if ($this->retailersList === null) {
            $this->retailersList = [];
            $storeId = $this->request->getParam('store');

            /* @var $collection SellerResourceModelCollection */
            $collection = $this->retailerCollectionFactory->create();

            $collection
                ->addAttributeToSelect(['name', 'is_active'])
                ->setStoreId($storeId);

            foreach ($collection as $retailer) {
                $this->retailersList[$retailer->getId()] = [
                    'value' => $retailer->getId(),
                    'is_active' => $retailer->getIsActive(),
                    'label' => $retailer->getName(),
                ];
            }
        }

        return $this->retailersList;
    }
}
