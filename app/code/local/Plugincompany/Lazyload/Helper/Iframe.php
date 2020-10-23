<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Lazy Load Ultimate, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Lazyload_Helper_Iframe extends Mage_Core_Helper_Abstract
{
    private $html;

    public function isEnabled()
    {
        return Mage::getStoreConfigFlag('plugincompany_lazyload/iframe/video');
    }

    public function addLazyLoad($html){
        preg_match_all("/<iframe .*?(?=src)src=[\"']([^\"']+)[\"'].*?>/si", $html, $m);
        if(!isset($m[1])){
            return $html;
        }
        $this->html = $html;
        foreach($m[0] as $k => $tag){

            $iframeUrl = $m[1][$k];

            if(!$this->isAllowedUrl($iframeUrl)){
                continue;
            }

            $this->addDataSrcToIframeTag($m[0][$k]);
        }
        return $this->html;
    }

    private function isAllowedUrl($url)
    {
        return stristr($url, 'https://www.youtube.com/embed/') || stristr($url, 'https://player.vimeo.com');
    }

    private function addDataSrcToIframeTag($tag)
    {
        if(stristr($tag, 'pc_lazyload')){
            return;
        }
        $newTag = $tag;
        if(!stristr($newTag, 'data-src=')){
            $newTag = str_replace('src=', 'data-src=', $tag);
        }
        if(!stristr($newTag, 'class=')){
            $newTag = str_replace('<iframe ', '<iframe class="" ', $newTag);
        }
        $newTag = preg_replace("/ class=(\"|')/", ' class=$1lazyload pc_lazyload ', $newTag);
        $this->html = str_replace($tag, $newTag, $this->html);
    }

}