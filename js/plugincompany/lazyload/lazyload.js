jQuery(document).ready(function($){
    initLazyLoad();
});

function initLazyLoad()
{
    window.pcLazyLoadLib = new LazyLoad({
        elements_selector: ".lazyload:not(.lazyloaded)",
        class_loaded: "lazyloaded",
        threshold: pcLazyLoad.distance,
        callback_loaded: function() {
            updateLazyLoad();
        }
    });
    if(jQuery('video[autoplay]').length) {
        loadLazyLoadImagesNow();
    }
}

function updateLazyLoad()
{
    if(typeof pcLazyLoadLib != 'undefined' && typeof pcLazyLoadLib.update != "undefined"){
        if(typeof window.Event !== 'function') {
            jQuery(window).trigger('resize');
        }else {
            window.dispatchEvent(new Event('resize'));
        }
    }
}

function loadLazyLoadImagesNow()
{
    var lazyImages = jQuery('.lazyload').not('.lazyloaded');
    lazyImages.each(function(){
        if(jQuery(this).attr('data-src') !== undefined){
            jQuery(this)
                .attr('src', jQuery(this).attr('data-src'))
                .addClass('lazyloaded')
        }
    })
}

(function() {
    var origOpen = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function() {
        this.addEventListener('load', function() {
            if(typeof pcLazyLoad != "undefined") {
                setTimeout(function(){
                    updateLazyLoad();
                },10);
            }
        });
        origOpen.apply(this, arguments);
    };
})();
