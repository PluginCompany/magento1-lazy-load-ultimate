<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Lazy Load Ultimate, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Lazyload_Model_Source_AdditionalLayouthandles
    extends Mage_Widget_Block_Adminhtml_Widget_Instance_Edit_Chooser_Block
{

    /**
     * get possible values
     * @access public
     * @param bool $withEmpty
     * @return array
     * @author Milan Simek
     */
    public function getAllOptions($withEmpty = true){
        $this->setLayoutHandle('PRODUCT_TYPE_simple,cms_index_index,catalog_product_view,catalog_category_view,catalog_category_default,catalog_category_layered,page_one_column,page_two_columns_left,page_two_columns_right,page_three_columns,cms_page');
        $this->setArea('frontend');
        $this->setPackage($this->getStoreConfig('design/package/name'));
        $this->setTheme($this->getThemeConfig());
        $blocks = $this->getBlocks();
        unset($blocks[0]);
        $options = array();
        foreach($blocks as $value => $label) {
            $options[] = array('label' => $label, 'value' => $value);
        }
        return $options;
    }

    private function getThemeConfig()
    {
        $theme = $this->getStoreConfig('design/theme/layout');
        if($theme) {
            return $theme;
        }
        return $this->getStoreConfig('design/theme/default');
    }

    private function getStoreConfig($path)
    {
        $storeCode = $this->getRequest()->getParam('store');
        if($storeCode) {
            return Mage::getStoreConfig($path, $storeCode);
        }
        $value = Mage::getConfig()->getNode($path, 'default');
        if($value && isset($value[0])) {
            return $value[0];
        }
        return;
    }

    /**
     * get options as array
     * @access public
     * @param bool $withEmpty
     * @return string
     * @author Milan Simek
     */
    public function getOptionsArray($withEmpty = true) {
        $options = array();
        foreach ($this->getAllOptions($withEmpty) as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    public function toOptionArray($withEmpty = true){
        return $this->getAllOptions($withEmpty);
    }
    /**
     * get option text
     * @access public
     * @param mixed $value
     * @return string
     * @author Milan Simek
     */
    public function getOptionText($value) {
        $options = $this->getOptionsArray();
        if (!is_array($value)) {
            $value = array($value);
        }
        $texts = array();
        foreach ($value as $v) {
            if (isset($options[$v])) {
                $texts[] = $options[$v];
            }
        }
        return implode(', ', $texts);
    }
}
