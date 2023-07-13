<?php

declare(strict_types=1);

namespace Smile\Retailer\Block\Adminhtml\Retailer\Edit\Button;

/**
 * Save Button for retailer edition.
 */
class Save extends AbstractButton
{
    /**
     * @inheritdoc
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'save'],
                ],
            ],
            'sort_order' => 80,
        ];
    }
}
