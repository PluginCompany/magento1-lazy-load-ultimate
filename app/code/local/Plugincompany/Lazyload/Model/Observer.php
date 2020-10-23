<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Lazy Load Ultimate, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Lazyload_Model_Observer {

    public function coreBlockAbstractToHtmlAfter(Varien_Event_Observer $observer){
        if(!$this->isEnabled()){
            return;
        }

        if(Mage::registry('disable_lazy_load')){
            return;
        }

        $block     = $observer->getEvent()->getBlock();
        if(in_array($block->getTemplate(), ['turpentine/ajax.phtml', 'turpentine/esi.phtml'])){
            return;
        }

        if(!$this->isAllowedBlockInstance($block)){
            return;
        }

        //check if current URL is allowed
        if(!$this->isAllowedUrl()){
            Mage::register('disable_lazy_load', true);
            return;
        }

        //check if enabled for page type
        if(!$this->isAllowedPageType()){
            Mage::register('disable_lazy_load', true);
            return;
        }

        //check if lazy load is enabled for layout handle
        $layoutHandle = $block->getNameInLayout();
        $layoutHandles = Mage::getStoreConfig('plugincompany_lazyload/general/layout_handles');
        $layoutHandles = explode(',',$layoutHandles);
        $additionalLayoutHandles = Mage::getStoreConfig('plugincompany_lazyload/general/additional_layout_handles');
        $additionalLayoutHandles = explode(",", $additionalLayoutHandles);
        $layoutHandles = array_merge($layoutHandles, $additionalLayoutHandles);
        if(!in_array($layoutHandle,$layoutHandles)){
            return;
        }

        $transport = $observer->getTransport();
        $html      = $transport->getHtml();

        //check if already processed
        if(strstr($html,'pc_lazyload')){
            return;
        }

        //lazy load images
        if(strstr($html,'<img')){

//            Mage::log('URL: ' . Mage::helper('core/url')->getCurrentUrl(), null, 'lazyload.log', true);
//            Mage::log('LAYOUT HANDLE: ' . $layoutHandle, null, 'lazyload.log', true);
//            Mage::log('addLazyLoad helper called', null, 'lazyload.log', true);
            $html = Mage::helper('plugincompany_lazyload/image')->addLazyLoad($html);
        }

        //lazy load iframes
        $iframeHelper = Mage::helper('plugincompany_lazyload/iframe');
        if($iframeHelper->isEnabled() && strstr($html,'<iframe')){
            $html = $iframeHelper->addLazyLoad($html);
        }

        $transport->setHtml($html);
    }

    private function isAllowedBlockInstance($block){
        if(
            $block instanceof Mage_Page_Block_Html_Header
            || $block instanceof Mage_Core_Block_Text_List
            || $block instanceof Mage_Catalog_Block_Category_View
            || $block instanceof Mage_Page_Block_Html_Footer
        ){
            return true;
        }
        return false;
    }

    public function isAllowedUrl(){
        $excluded = Mage::getStoreConfig('plugincompany_lazyload/general/excluded_urls');
        $excluded = explode(PHP_EOL,$excluded);

        foreach($excluded as $regex){
            try {
                $regex = trim($regex);
                if (!$regex) continue;
                $regex = "|$regex|";

                if (preg_match($regex, Mage::helper('core/url')->getCurrentUrl())) {
                    return false;
                }
            }catch(Exception $e) {
                //do nothing
            }
        }
        return true;
    }

    public function isAllowedPageType(){
        $request = Mage::app()->getRequest();
        $path = $request->getRouteName() . '_' . $request->getControllerName() . '_' . $request->getActionName();

        //cms page
        if(in_array($path,array('cms_index_index','cms_page_view')) && !Mage::getStoreConfigFlag('plugincompany_lazyload/general/enable_cms_pages'))
        {
            return false;
        }

        if(in_array($path,array('catalog_category_view','catalogsearch_result_index')) && !Mage::getStoreConfigFlag('plugincompany_lazyload/general/enable_category_pages'))
        {
            return false;
        }

        if($path == 'catalog_product_view' && !Mage::getStoreConfigFlag('plugincompany_lazyload/general/enable_product_pages'))
        {
            return false;
        }

        if((stristr($path,'checkout') || stristr($path, 'opc')) && !Mage::getStoreConfigFlag('plugincompany_lazyload/general/enable_checkout_pages'))
        {
            return false;
        }
        return true;
    }

    public function isEnabled(){
        return Mage::getStoreConfigFlag('plugincompany_lazyload/general/enable');
    }


}
