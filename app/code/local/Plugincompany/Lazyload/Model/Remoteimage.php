<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Lazy Load Ultimate, released under the MIT License.
 * See the LICENSE file in the project root.
 */ 
class Plugincompany_Lazyload_Model_Remoteimage extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('plugincompany_lazyload/remoteimage');
    }

    public function getDimensionsByUrl($url)
    {
        if($this->getUrl() == $url) {
            return $this->getDimensions();
        }

        $this->_clearData();

        $this->load($url, 'url');
        if($this->getUrl() == $url) {
            return $this->getDimensions();
        }

        $this->setUrl($url);
        $this->updateDimensionsFromRemote();
        if(!$this->getWidth() || !$this->getHeight()) {
            return false;
        }
        $this->save();
        return $this->getDimensions();
    }

    private function getDimensions()
    {
        return array('width' => $this->getWidth(), 'height' => $this->getHeight());

    }

    private function updateDimensionsFromRemote()
    {
        $dimensions = $this->fetchImageDimensionsFromRemoteUrl($this->getUrl());
        if(!$dimensions) {
            return $this;
        }
        $this
            ->setWidth($dimensions['width'])
            ->setHeight($dimensions['height'])
        ;
        return $this;
    }

    /**
     * Get Image Size From Remote URL
     *
     * @param string $url
     * @return array
     */
    private function fetchImageDimensionsFromRemoteUrl($url) {
        // Get remote image
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt( $ch, CURLOPT_TIMEOUT, 5 );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
        $data = curl_exec( $ch );
        $http_status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
        curl_close( $ch );

        if ( $http_status != 200 ) {
            return false;
        }

        $image = imagecreatefromstring( $data );

        $dimensions = array(
            'width' => imagesx($image),
            'height' => imagesy($image)
        );

        imagedestroy($image);

        if(!$dimensions['width'] || !$dimensions['height']) {
            return false;
        }

        return $dimensions;
    }

    protected function _clearData()
    {
        $this->setData(array());
        $this->setOrigData();
        return $this;
    }

}