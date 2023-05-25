<?php

namespace Smile\Retailer\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Retailer Actions for Ui Component.
 */
class RetailerActions extends Column
{
    private const RETAILER_URL_PATH_EDIT = 'smile_retailer/retailer/edit';
    private const RETAILER_URL_PATH_DELETE = 'smile_retailer/retailer/delete';

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        protected UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        private string $editUrl = self::RETAILER_URL_PATH_EDIT
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source.
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                $itemName = $item['name'] ?? __('Entity ID : ') . $item['entity_id'] ;

                if (isset($item['entity_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['id' => $item['entity_id']]),
                        'label' => __('Edit'),
                    ];

                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::RETAILER_URL_PATH_DELETE,
                            ['id' => $item['entity_id']]
                        ),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete %1', $itemName),
                            'message' => __('Are you sure you want to delete %1 ?', $itemName),
                        ],
                    ];
                }
            }
        }

        return $dataSource;
    }
}
