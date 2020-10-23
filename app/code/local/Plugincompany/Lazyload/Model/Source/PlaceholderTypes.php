<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Lazy Load Ultimate, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Lazyload_Model_Source_PlaceholderTypes {

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
                'label' => 'Blurred Placeholder (with transition)',
                'value' => 'blurred'
            ),
            array(
                'label' => 'Low Resolution Placeholder',
                'value' => 'lowres'
            ),
            array(
                'label' => 'Transparent Placeholder',
                'value' => 'transparent'
            ),
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
