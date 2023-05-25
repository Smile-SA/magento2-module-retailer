<?php

namespace Smile\Retailer\Block\Adminhtml\Retailer\Edit\Button;

/**
 * Save and Continue Button for retailer edition.
 */
class SaveAndContinue extends AbstractButton
{
    /**
     * @inheritdoc
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'saveAndContinueEdit'],
                ],
            ],
            'sort_order' => 80,
        ];
    }
}
