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
namespace Smile\Retailer\Block\Adminhtml\Retailer;

use Magento\Framework\Stdlib\DateTime;
use Smile\Retailer\Api\Data\RetailerInterface;
use Zend_Date;

/**
 * Opening Hours rendering block
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class OpeningHours extends \Magento\Backend\Block\AbstractBlock
{
    /**
     * @var \Magento\Framework\Data\FormFactory
     */
    private $formFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * Constructor.
     *
     * @param \Magento\Backend\Block\Context      $context     Block context.
     * @param \Magento\Framework\Data\FormFactory $formFactory Form factory.
     * @param \Magento\Framework\Registry         $registry    Registry.
     * @param array                               $data        Additional data.
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->formFactory = $formFactory;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * {@inheritDoc}
     */
    protected function _toHtml()
    {
        return $this->escapeJsQuote($this->getForm()->toHtml());
    }

    /**
     * Get retailer
     *
     * @return RetailerInterface
     */
    private function getRetailer()
    {
        return $this->registry->registry('current_seller');
    }

    /**
     * Create the form containing the virtual rule field.
     *
     * @return \Magento\Framework\Data\Form
     */
    private function getForm()
    {
        $form = $this->formFactory->create();
        $form->setHtmlId('opening_hours');

        $openingHoursFieldset = $form->addFieldset(
            'opening_hours',
            ['name' => 'opening_hours', 'label' => __('Opening Hours'), 'container_id' => 'opening_hours']
        );

        if ($this->getRetailer() && $this->getRetailer()->getOpeningHours()) {
            $timeRanges = $this->getRetailer()->getOpeningHours()->getTimeRanges();
            $timeRanges = $this->convertTimeRanges($timeRanges);
            $openingHoursFieldset->setValue($timeRanges);
        }

        $openingHoursRenderer = $this->getLayout()->createBlock('Smile\Retailer\Block\Adminhtml\Retailer\OpeningHours\Container\Renderer');
        $openingHoursFieldset->setRenderer($openingHoursRenderer);

        return $form;
    }

    /**
     * Convert Time Range to today date.
     *
     * @param array $timeRanges The time ranges to convert
     *
     * @return mixed
     */
    private function convertTimeRanges($timeRanges)
    {
        // Convert time ranges to current day for correct form display
        foreach ($timeRanges as &$values) {
            foreach ($values as &$timeRange) {
                foreach ($timeRange as &$hour) {
                    $date = new Zend_Date();
                    $date->setTime($hour);
                    $hour = $date->toString($this->getRetailer()->getOpeningHours()->getDateFormat());
                }
            }
        }

        return $timeRanges;
    }
}
