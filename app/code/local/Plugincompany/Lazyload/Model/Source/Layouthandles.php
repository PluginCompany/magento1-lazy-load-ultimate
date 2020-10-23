<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Lazy Load Ultimate, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Lazyload_Model_Source_Layouthandles {

    /**
     * get possible values
     * @access public
     * @param bool $withEmpty
     * @return array
     * @author Milan Simek
     */
    public function getAllOptions($withEmpty = true){
        $options = array(
            array(
                'label' => 'After body start',
                'value' => 'after_body_start'
            ),
            array(
                'label' => 'Header',
                'value' => 'header'
            ),
            array(
                'label' => 'Left column (first)',
                'value' => 'left_first'
            ),
            array(
                'label' => 'Left column (main)',
                'value' => 'left'
            ),
            array(
                'label' => 'Main content area',
                'value' => 'content'
            ),
            array(
                'label' => 'Right column',
                'value' => 'right'
            ),
            array(
                'label' => 'Before footer',
                'value' => 'footer_before'
            ),
            array(
                'label' => 'Footer',
                'value' => 'footer'
            ),
            array(
                'label' => 'Before body end',
                'value' => 'before_body_end'
            ),
            array(
                'label' => 'Category Products',
                'value' => 'category.products'
            )
        );
        return $options;
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
