# Changelog: Lazy Load Ultimate for Magento 1 / OpenMage

All releases of this extension, newest first. This repository contains the latest release, tagged [`1.7.0`](https://github.com/PluginCompany/magento1-lazy-load-ultimate/releases/tag/1.7.0); earlier versions are listed for reference and have no tag.

## 1.7.0 — 2020-10-23

- Added support for remote image lazy loading (CDN and other remote URLs)
- Added placeholder dimension cache functionality for remote images

## 1.6.3 — 2020-02-12

- Fixed jQuery $ reference issue which was introduced in v1.6.1

## 1.6.2 — 2020-02-11

- Made image dimensions lookup by URL optional in admin config. While this feature improves perfomance, it's not compatible with all custom Magento themes.

## 1.6.1 — 2019-12-11

- Added option to disable lazy load image lookup on product image gallery click event. (Previously this option was always enabled.)

## 1.6.0 — 2019-10-09

- Extension now uses new Lazy Load library, supporting IntersectionObserver API for better performance
- IE11 compatibility fix

## 1.5.4 — 2019-08-23

- Fixed issue regarding image dimensions retrieval introduced in v1.5.2

## 1.5.3 — 2019-08-20

- Some minor code refactoring

## 1.5.2 — 2019-08-18

- Multiple performance improvements:
- 1. Image dimensions are taken from image URL if available
- 2. Transparent placeholders are cached during page load
- Both improvements reduce TTFB impact of extension
- Fixed minor issue regarding menu image lazy loading functionality

## 1.5.1 — 2019-07-07

- Additional support for mega menu
- Fix for some AJAX content images not being loaded correctly

## 1.5.0 — 2019-04-02

- New theme-specific layout handles field in system configuration, allowing you to enable lazy loading for layout handles added by your custom theme
- Added support for menu / mega menu images lazy loading
- Improved lazy loading core code for better browser compatibility

## 1.4.6 — 2019-01-27

- Fixed issue where image loading wasn't working correctly when scrolling inside a DIV element

## 1.4.5 — 2019-01-12

- Fixed issue regarding CDN based media URLs not lazy loading properly (CloudFront etc)
- Fixed issue where image tags with newlines inside wouldn't lazy load correctly

## 1.4.4 — 2019-01-04

- Changed left column layout handle to support most themes including RWD theme
- Small fix for issue which occurred with AJAX requests directly on page load

## 1.4.3 — 2018-12-07

- Added support for multi-line image tags, which weren't catched by the extension correctly

## 1.4.2 — 2018-09-18

- Fix for iframe class name issue

## 1.4.1 — 2018-09-18

- Fix for video iframe not loading due to data-src attribute issue
- Added support for iframes already containing a data-src attribute

## 1.4.0 — 2018-09-18

- Added support for lazy loading YouTube and Vimeo iframe embed videos
- Added workaround for lazy load images not showing when a video is playing on the page and the user is scrolling in Chrome

## 1.3.0 — 2018-09-12

- Added support for Fishpig Wordpress
- Added support for images in non skin/media custom directories
- Added support for images with relative URLs

## 1.2.4 — 2018-09-05

- Made transparent placeholder type the default setting
- Fixed some warning level errors which appeared when an image had an invalid source

## 1.2.3 — 2018-07-19

- Transparent placeholder now uses original image width
- When using transparent placeholder, no lazy load specific CSS is used anymore
- Bug fix for division by zero error
- Fixed issue where hardcoded inline width wouldn't be respected when image is lazy loaded
- Added additional check for lazy loading dynamically inserted (non-AJAX) images
- Improved system.xml descriptions

## 1.2.1 — 2018-03-01

- Performance improvements
- Added image srcset compatibility

## 1.2.0 — 2018-02-12

- Added JS events field for hooking lazy load check to js event (useful for sliders)
- Added category products layout handle for lazy loading AJAX category pages
- Added transparent placeholder option (good performance)
- Added option to display blurred placeholder without animation
- Added option to display low resolution placeholder: pixelated or blurry
- Added AJAX update hook to lazy load images retrieved over AJAX
- Optimized default settings for better performance
- Added fix for lazy loading images in ajax content
- Optimized extension for Google Lighthouse

## 1.1.0 — 2018-02-08

- Optimized lazy loading CPU load
- Added option to exclude certain image CSS classes from lazy loading
- Optimized lazy load animation by leveraging GPU
- Added fix for lazy loading images inside AJAX content

## 1.0.4 — 2017-09-10

- Compatibility fix for some custom zoom extensions

## 1.0.3 — 2017-07-19

- Fixed bug regarding image tags placed inside inline Javascript

## 1.0.2 — 2017-07-10

- Fixed Javascript sorting bug

## 1.0.1 — 2017-03-21

- Improved image source replacement regex
- Fixed small bug

## 1.0.0 — 2016-03-01

- First release of Magento Lazy Load Ultimate extension by Plugin Company.
