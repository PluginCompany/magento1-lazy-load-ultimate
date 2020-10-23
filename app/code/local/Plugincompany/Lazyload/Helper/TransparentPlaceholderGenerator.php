<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Lazy Load Ultimate, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Lazyload_Helper_TransparentPlaceholderGenerator
{
    private $originalWidth;
    private $originalHeight;
    private $placeholderCache = array();

    public function getPlaceholderSrcForDimensions($width, $height)
    {
        $this->originalHeight = (int)$height ? (int)$height : 1;
        $this->originalWidth = (int)$width ? (int)$width : 1;
        return $this->getBase64CodeForPlaceholder();
    }

    private function getBase64CodeForPlaceholder()
    {
        if(isset($this->placeholderCache[$this->getPlaceholderCacheKey()])) {
            return $this->placeholderCache[$this->getPlaceholderCacheKey()];
        }
        $placeholder = 'data:image/gif;base64,' . base64_encode($this->getPngPlaceholder());
        $this->placeholderCache[$this->getPlaceholderCacheKey()] = $placeholder;
        return $placeholder;
    }

    private function getPngPlaceholder()
    {
        $img = imagecreatetruecolor($this->getPlaceholderWidth(), $this->getPlaceholderHeight());
        imagefill($img, 0, 0, 0);
        imagecolortransparent ( $img, 0);
        ob_start();
        imagegif($img);
        return ob_get_clean();

    }

    private function getPlaceholderCacheKey()
    {
        return $this->originalWidth . 'x' . $this->originalHeight;
    }

    private function getPlaceholderWidth()
    {
        return $this->originalWidth;
    }

    private function getPlaceholderHeight()
    {
        return $this->originalHeight;
    }

}
