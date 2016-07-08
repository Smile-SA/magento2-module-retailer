<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\Retailer\Block\Retailer;

use Magento\Framework\App\Cache\Type\Collection;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Retailer picker
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class Chooser extends Template
{
    /** @var \Smile\Seller\Model\ResourceModel\Seller\Collection  */
    private $collection = null;

    /**
     * @var array The default attributes to add to our select
     */
    private $defaultAttributesToSelect = ['name'];

    /**
     * @var array Configuration for the input, can be passed by layouting.
     */
    private $inputData = [];

    /**
     * @var null|\Magento\Framework\View\Element\Html\Select
     */
    private $input = null;

    /**
     * @var Collection
     */
    private $collectionCache;

    /**
     * Chooser constructor. Iniitializes the retailer collection and possible input attributes.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context         Application context
     * @param \Magento\Framework\App\Cache\Type\Collection     $collectionCache Collection Cache
     * @param array                                            $data            Constructor Data
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(
        Context $context,
        Collection $collectionCache,
        array $data = []
    ) {
        parent::__construct($context, $data);

        if (!isset($data['collection'])) {
            throw new \InvalidArgumentException("Cannot instantiate Chooser block without collection instance");
        }
        $this->collection = $data['collection'];
        $this->collectionCache = $collectionCache;

        $this->collection->setStoreId($this->_storeManager->getStore()->getId());
        if (isset($data['collectionAttributes']) && !empty($data['collectionAttributes'])) {
            $this->collection->addAttributeToSelect(array_merge($data['collectionAttributes'], $this->defaultAttributesToSelect));
        }

        if (isset($data['input']) && !empty($data['input'])) {
            $this->inputData = array_merge($data['input'], $this->inputData);
        }
    }

    /**
     * Prepare HTML Input for rendering
     *
     * @SuppressWarnings(PHPMD.ElseExpression)
     *
     * @return \Magento\Framework\View\Element\Html\Select
     */
    public function getChooserInput()
    {
        if (null == $this->input) {
            $cacheKey = 'SMILE_RETAILER_RETAILER_COLLECTION' . $this->_storeManager->getStore()->getId();
            $cache = $this->collectionCache->load($cacheKey);

            if ($cache) {
                $options = unserialize($cache);
            } else {
                $options = $options = $this->collection->toOptionArray();
                $this->collectionCache->save(serialize($options), $cacheKey);
            }

            /** @var \Magento\Framework\View\Element\Html\Select $selectElement */
            $selectElement = $this->getLayout()->createBlock('Magento\Framework\View\Element\Html\Select')
                ->setData($this->inputData)
                ->setValue(intval($this->getRetailerId()))
                ->setOptions($options);

            $this->input = $selectElement;
        }

        return $this->input;
    }

    /**
     * Render Retailer Html Element
     *
     * @return string
     */
    public function getRetailerHtmlSelect()
    {
        return $this->getChooserInput()->getHtml();
    }

    /**
     * Retrieve Html Id of the chooser input
     *
     * @return string
     */
    public function getInputId()
    {
        return $this->getChooserInput()->getId();
    }
}
