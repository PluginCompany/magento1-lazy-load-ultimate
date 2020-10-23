<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Lazy Load Ultimate, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Lazyload_Helper_Image extends Mage_Core_Helper_Abstract {

    private $transparentPlaceholderGenerator;

    /**
     * @var Plugincompany_Lazyload_Model_Remoteimage
     */
    private $remoteImage;

    public function __construct()
    {
        $this->transparentPlaceholderGenerator = Mage::helper('plugincompany_lazyload/transparentPlaceholderGenerator');
        $this->remoteImage = Mage::getModel('plugincompany_lazyload/remoteimage');
    }

    public function addLazyLoad($html){
        preg_match_all("/<img[ \\n].*?(?=src)src=[\"']([^\"']+)[\"'].*?>/si", $html, $m);

//        Mage::log("#### HTML #####", null, 'lazyload.log', true);
//        Mage::log($html, null, 'lazyload.log', true);
//        Mage::log("#### MATCHES #####", null, 'lazyload.log', true);
//        Mage::log($m, null, 'lazyload.log', true);

        if(!isset($m[1])){
            return $html;
        }
        foreach($m[0] as $k => $tag){

            $imgUrl = $m[1][$k];
//            Mage::log('Image URL: '. $imgUrl, null, 'lazyload.log', true);

            if($this->isRelativeUrl($imgUrl)) {
                $imgUrl = $this->makeRelativeUrlAbsolute($imgUrl);

            }
            if(!$this->hasBaseUrl($imgUrl) && !$this->remoteImagesAllowed()){
                continue;
            }

            if(!$this->useTransparentPlaceholder() && $this->hasSkinUrl($imgUrl)){
                continue;
            }

            if($this->hasDisallowedCssClass($tag)){
                continue;
            }

            if($this->hasDisallowedString($tag)){
                continue;
            }

            if(stristr($tag, 'data-src')) {
                continue;
            }

            if(!$this->useTransparentPlaceholder()){
                // if only small size difference
                // no need to use lazy load
                $width = $this->_getImageWidth($imgUrl);
                if($width*0.7 <= $this->_getSize()){
                    continue;
                }
                $this->savePlaceHolderIfImageIsModified($imgUrl);
            }

            //add placeholder to html
            $html = $this->_addPlaceHolderHtml($html, $m[0][$k], $imgUrl);
        }
        return $html;
    }

    private function makeRelativeUrlAbsolute($urlPath)
    {
        $basePath = parse_url($this->_getBaseUrl(), PHP_URL_PATH);
        if(strpos($urlPath, $basePath) === 0) {
            $urlPath = substr($urlPath, strlen($basePath));
        }
        return $this->_getBaseUrl() . $urlPath;
    }

    private function hasBaseUrl($url)
    {
        foreach($this->_getAllBaseUrls() as $baseUrl) {
            if(strpos($url, $baseUrl) !== false) {
                return true;
            }
        }
        return false;
    }

    private function isRelativeUrl($url)
    {
        $first = substr($url, 0, 1);
        $firstTwo = substr($url, 0, 2);
        return $first == '/' && $firstTwo != '//';
    }

    private function remoteImagesAllowed()
    {
        return Mage::getStoreConfigFlag('plugincompany_lazyload/general/allow_remote_images') && $this->useTransparentPlaceHolder();
    }

    private function hasMediaUrl($url)
    {
        return strpos($url, $this->_getMediaUrl()) !== false;
    }

    private function hasSkinUrl($url)
    {
        return strpos($url, $this->_getSkinUrl()) !== false;
    }

    private function hasDisallowedCssClass($tag)
    {
        $classes = $this->getClassesFromTag($tag);
        if($this->hasExcludedClassInArray($classes)) {
            return true;
        }
        return false;
    }

    private function getClassesFromTag($tag)
    {
        $class = '';
        preg_match("/class=[\",'](.*?)[\",']/", $tag, $class);
        if(is_array($class) && isset($class[1])) {
            return array_filter(explode(' ', $class[1]));
        }
        return [];
    }

    private function hasExcludedClassInArray($classes)
    {
        foreach($classes as $class){
            if($this->isClassExcluded($class)) {
                return true;
            }
        }
        return false;
    }

    private function isClassExcluded($class)
    {
        return in_array($class, $this->getExcludedCssClassNames());
    }

    private function getExcludedCssClassNames()
    {
        $excluded = Mage::getStoreConfig('plugincompany_lazyload/general/excluded_css_classes');
        return array_filter(array_map('trim', explode(PHP_EOL,$excluded)));
    }

    private function hasDisallowedString($tag)
    {
        foreach($this->getExcludedStrings() as $string)
        {
            if(stristr($tag, $string)){
                return true;
            }
        }
        return false;
    }

    private function getExcludedStrings()
    {
        $excluded = Mage::getStoreConfig('plugincompany_lazyload/general/excluded_text');
        return array_filter(array_map('trim', explode(PHP_EOL,$excluded)));
    }

    private function useTransparentPlaceHolder()
    {
        return Mage::getStoreConfig('plugincompany_lazyload/general/placeholder_type') == 'transparent';
    }

    private function savePlaceHolderIfImageIsModified($imgUrl)
    {
        if(
            !$this->_getPlaceHolderModifiedTime($imgUrl)
            || $this->_getPlaceHolderModifiedTime($imgUrl) < $this->_getImageModifiedTime($imgUrl)
        ){
            $this->savePlaceHolder($imgUrl,$this->_getSize());
        }
        return $this;
    }

    protected function _addPlaceHolderHtml($html,$tag,$imgUrl){
        if(stristr($tag, '"')){
            $quote = '"';
        }else{
            $quote = "'";
        }
        $tagWithDataSrc = str_replace('src=',"src={$quote}{$this->_getPlaceHolderUrl($imgUrl)}{$quote} data-src=",$tag);
        $tagWithDataSrc = str_replace('srcset', 'data-srcset', $tagWithDataSrc);
        $css = '';
        if(!stristr($tagWithDataSrc, 'srcset=') && !stristr($tagWithDataSrc, 'width=') && !stristr($tagWithDataSrc, 'width:') && !$this->useTransparentPlaceHolder()){
            $css = 'width:' . $this->_getImageWidth($imgUrl) . 'px;';
        }
        if(
            strpos($tagWithDataSrc,'style="') !== false
            ||
            strpos($tagWithDataSrc,"style='") !== false
        ){
            $tagWithDataSrc = str_replace('style="','style="' . $css,$tagWithDataSrc);
            $tagWithDataSrc = str_replace("style='","style='" . $css,$tagWithDataSrc);
        }else{
            $tagWithDataSrc = str_replace("<img","<img style={$quote}" . $css . $quote, $tagWithDataSrc);
        }
        $class = "lazyload pc_lazyload ";
        if($this->isBlurTransitionEnabled()){
            $class .= 'lazyblur ';
        }
        if($this->isLowResPlaceholderEnabled()) {
            $class .= 'lowres ';
        }
        if(
            strpos($tagWithDataSrc,'class="') !== false
            ||
            strpos($tagWithDataSrc,"class='") !== false
        ){
            $tagWithDataSrc = str_replace('class="','class="' . $class,$tagWithDataSrc);
            $tagWithDataSrc = str_replace("class='","class='" . $class,$tagWithDataSrc);
        }else{
            $tagWithDataSrc = str_replace("<img","<img class={$quote}" . $class . $quote, $tagWithDataSrc);
        }

        /*$template = "
        <div class='lazyload' style='width:{$this->_getImageWidth($imgUrl)}px;max-width:100%;padding-bottom:{$this->_getImageAspectRatio($imgUrl)}%;height:0px;;background-size:cover'>
                <img class='backdrop' src='{$this->_getPlaceHolderUrl($imgUrl)}' />
                $tagWithDataSrc
            </div>
        ";*/
        $template = $tagWithDataSrc;
        return str_replace($tag,$template,$html);
    }

    private function isBlurTransitionEnabled()
    {
        $placeholderType = Mage::getStoreConfig('plugincompany_lazyload/general/placeholder_type');
        return $placeholderType == 'blurred';
    }

    private function isLowResPlaceholderEnabled()
    {
        $placeholderType = Mage::getStoreConfig('plugincompany_lazyload/general/placeholder_type');
        return $placeholderType == 'lowres';
    }

    public function savePlaceHolder($imgUrl){
        $this->_createPlaceHolderDir($imgUrl);
        $helper = Mage::helper('plugincompany_lazyload');
        $helper->source_path = $this->_getImageFile($imgUrl);
        $helper->target_path = $this->_getPlaceHolderFile($imgUrl);
        $helper->preserve_aspect_ratio = true;
        $helper->resize($this->_getSize());
        return $this;
    }
    protected function _getBaseUrl(){
        return Mage::getBaseUrl('web');
    }

    protected function _getAllBaseUrls() {
        $baseUrls = array();
        foreach(Mage::app()->getStores() as $store){
            $baseUrls[] = $store->getBaseUrl('web');
            $baseUrls[] = $this->getBaseUrlFromUrl($store->getBaseUrl('media'));
        }
        return $baseUrls;
    }

    private function getBaseUrlFromUrl($url)
    {
        $urlParts = parse_url($url);
        return $urlParts['scheme'] . '://' . $urlParts['host'] . '/';
    }

    protected function _getMediaUrl(){
        return Mage::getBaseUrl('media');
    }

    protected function _getSkinUrl(){
        return Mage::getBaseUrl('skin');
    }

    protected function _getBaseDir(){
        return Mage::getBaseDir();
    }

    protected function _getMediaDir(){
        return Mage::getBaseDir('media');
    }

    protected function _getSkinDir(){
        return Mage::getBaseDir('skin');
    }

    protected function _getPlaceHolderDir($imgUrl){
        $placeholderDir = explode('/',$this->_getPlaceHolderFile($imgUrl));
        array_pop($placeholderDir);
        $placeholderDir = implode('/',$placeholderDir);
        return $placeholderDir;
    }

    protected function _createPlaceHolderDir($imgUrl){
        @mkdir($this->_getPlaceHolderDir($imgUrl),0777,true);
        return $this;
    }

    protected function _getPlaceHolderFile($imgUrl){

        return  $this->_getPlaceHolderCacheDir() . $this->_removeBaseUrl($imgUrl);
    }

    protected function _getPlaceHolderCacheDir(){
        $mediaCacheDir = $this->_getMediaDir() . '/lazyload_placeholder/';
        return $mediaCacheDir;
    }

    protected function _removeBaseUrl($imgUrl) {
        foreach($this->_getAllBaseUrls() as $baseUrl) {
            $imgUrl = str_replace($baseUrl, '', $imgUrl);
        }
        return $imgUrl;
    }

    protected function _getPlaceHolderFileName($imgUrl)
    {
        return str_replace(array($this->_getMediaUrl()),array(""),$imgUrl);
    }

    protected function _getPlaceHolderUrl($imgUrl){
        if($this->useTransparentPlaceHolder()){
            return $this->getTransparentPlaceholder($imgUrl);
        }
        return $this->_getPlaceHolderCacheBaseUrl() . $this->_removeBaseUrl($imgUrl);
    }

    protected function _getPlaceHolderCacheBaseUrl()
    {
        return $this->_getMediaUrl() . 'lazyload_placeholder/';
    }

    private function getTransparentPlaceholder($imgUrl)
    {
        return $this->transparentPlaceholderGenerator
            ->getPlaceholderSrcForDimensions(
                $this->_getImageWidth($imgUrl),
                $this->_getImageHeight($imgUrl)
            );
    }

    protected function _getImageFile($imgUrl){
        return $this->_replaceBaseUrlsWithPath($imgUrl);
    }

    protected function _replaceBaseUrlsWithPath($imgUrl)
    {
        return str_replace(
            array(
                $this->_getMediaUrl(),
                $this->_getSkinUrl(),
                $this->_getBaseUrl()
            ),
            array(
                $this->_getMediaDir() . '/',
                $this->_getSkinDir() . '/',
                $this->_getBaseDir() . '/'
            ),
            $imgUrl
        );
    }

    protected function _getImageWidth($imgUrl){
        $imgSize = $this->_getImageDimensionsFromUrl($imgUrl);
        if($imgSize) {
            return $imgSize['width'];
        }
        if(!$this->hasBaseUrl($imgUrl)) {
            return $this->_getRemoteImageWidth($imgUrl);
        }
        $imgSize = @getimagesize($this->_getImageFile($imgUrl));
        if(!$imgSize) {
            return false;
        }
        $width = $imgSize[0];
        return $width;
    }

    protected function _getImageHeight($imgUrl){
        $imgSize = $this->_getImageDimensionsFromUrl($imgUrl);
        if($imgSize) {
            return $imgSize['height'];
        }
        if(!$this->hasBaseUrl($imgUrl)) {
            return $this->_getRemoteImageHeight($imgUrl);
        }
        $imgSize = @getimagesize($this->_getImageFile($imgUrl));
        if(!$imgSize) {
            return false;
        }
        $height = $imgSize[1];
        return $height;
    }

    protected function _getImageDimensionsFromUrl($imgUrl)
    {
        if(!$this->_isQuickDimensionsLookupAllowed()) return false;

        preg_match("/\/(\d{1,4})x(\d{1,4})\//", $imgUrl, $matches);
        if(count($matches) === 3) {
            return array('width' => $matches[1], 'height' => $matches[2]);
        }
        preg_match("/\/(\d{1,4})x\//", $imgUrl, $matches);
        if(count($matches) === 2) {
            return array('width' => $matches[1], 'height' => $matches[1]);
        }

        //Cloudinary CDN
        preg_match_all("/(w|h)_(\d{1,4})/", $imgUrl, $matches, PREG_SET_ORDER);
        if(count($matches) === 2) {
            $dimensions = array();
            if($matches[0][1] == 'h') {
                $dimensions['height'] = $matches[0][2];
                $dimensions['width'] = $matches[1][2];
            }else{
                $dimensions['height'] = $matches[1][2];
                $dimensions['width'] = $matches[0][2];
            }
            return $dimensions;
        }

        return false;
    }

    protected function _isQuickDimensionsLookupAllowed()
    {
        return Mage::getStoreConfigFlag('plugincompany_lazyload/general/enable_quick_dimensions_lookup');
    }

    protected function _getRemoteImageWidth($url)
    {
        $dimensions = $this->remoteImage->getDimensionsByUrl($url);
        if(!$dimensions) {
            return false;
        }
        return $dimensions['width'];
    }

    protected function _getRemoteImageHeight($url)
    {
        $dimensions = $this->remoteImage->getDimensionsByUrl($url);
        if(!$dimensions) {
            return false;
        }
        return $dimensions['height'];
    }

    protected function _getImageAspectRatio($imgUrl){
        list($width, $height) = @getimagesize($this->_getImageFile($imgUrl));
        $aspectRatio = $height / $width * 100;
        return $aspectRatio;
    }

    protected function _getImageModifiedTime($imgUrl){
        return @filemtime($this->_getImageFile($imgUrl));
    }

    protected function _getPlaceHolderModifiedTime($imgUrl){
        return @filemtime($this->_getPlaceHolderFile($imgUrl));
    }

    protected function _getSize(){
        return Mage::getStoreConfig('plugincompany_lazyload/general/placeholder_width');
    }
}
