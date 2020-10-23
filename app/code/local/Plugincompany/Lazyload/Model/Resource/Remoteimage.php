<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Lazy Load Ultimate, released under the MIT License.
 * See the LICENSE file in the project root.
 */ 
class Plugincompany_Lazyload_Model_Resource_Remoteimage extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('plugincompany_lazyload/remoteimage', 'image_id');
    }

}