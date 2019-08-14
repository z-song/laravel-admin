+function($){"use strict";function ZoomService(){this._activeZoom=this._initialScrollPosition=this._initialTouchPosition=this._touchMoveListener=null
this._$document=$(document)
this._$window=$(window)
this._$body=$(document.body)
this._boundClick=$.proxy(this._clickHandler,this)}
ZoomService.prototype.listen=function(){this._$body.on('click','[data-action="zoom"]',$.proxy(this._zoom,this))}
ZoomService.prototype._zoom=function(e){var target=e.target
if(!target||target.tagName!='IMG')return
if(this._$body.hasClass('zoom-overlay-open'))return
if(e.metaKey||e.ctrlKey){return window.open((e.target.getAttribute('data-original')||e.target.src),'_blank')}
if(target.width>=($(window).width()-Zoom.OFFSET))return
this._activeZoomClose(true)
this._activeZoom=new Zoom(target)
this._activeZoom.zoomImage()
this._$window.on('scroll.zoom',$.proxy(this._scrollHandler,this))
this._$document.on('keyup.zoom',$.proxy(this._keyHandler,this))
this._$document.on('touchstart.zoom',$.proxy(this._touchStart,this))
if(document.addEventListener){document.addEventListener('click',this._boundClick,true)}else{document.attachEvent('onclick',this._boundClick,true)}
if('bubbles'in e){if(e.bubbles)e.stopPropagation()}else{e.cancelBubble=true}}
ZoomService.prototype._activeZoomClose=function(forceDispose){if(!this._activeZoom)return
if(forceDispose){this._activeZoom.dispose()}else{this._activeZoom.close()}
this._$window.off('.zoom')
this._$document.off('.zoom')
document.removeEventListener('click',this._boundClick,true)
this._activeZoom=null}
ZoomService.prototype._scrollHandler=function(e){if(this._initialScrollPosition===null)this._initialScrollPosition=$(window).scrollTop()
var deltaY=this._initialScrollPosition-$(window).scrollTop()
if(Math.abs(deltaY)>=40)this._activeZoomClose()}
ZoomService.prototype._keyHandler=function(e){if(e.keyCode==27)this._activeZoomClose()}
ZoomService.prototype._clickHandler=function(e){if(e.preventDefault)e.preventDefault()
else event.returnValue=false
if('bubbles'in e){if(e.bubbles)e.stopPropagation()}else{e.cancelBubble=true}
this._activeZoomClose()}
ZoomService.prototype._touchStart=function(e){this._initialTouchPosition=e.touches[0].pageY
$(e.target).on('touchmove.zoom',$.proxy(this._touchMove,this))}
ZoomService.prototype._touchMove=function(e){if(Math.abs(e.touches[0].pageY-this._initialTouchPosition)>10){this._activeZoomClose()
$(e.target).off('touchmove.zoom')}}
function Zoom(img){this._fullHeight=this._fullWidth=this._overlay=this._targetImageWrap=null
this._targetImage=img
this._$body=$(document.body)}
Zoom.OFFSET=80
Zoom._MAX_WIDTH=2560
Zoom._MAX_HEIGHT=4096
Zoom.prototype.zoomImage=function(){var img=document.createElement('img')
img.onload=$.proxy(function(){this._fullHeight=Number(img.height)
this._fullWidth=Number(img.width)
this._zoomOriginal()},this)
img.src=this._targetImage.src}
Zoom.prototype._zoomOriginal=function(){this._targetImageWrap=document.createElement('div')
this._targetImageWrap.className='zoom-img-wrap'
this._targetImage.parentNode.insertBefore(this._targetImageWrap,this._targetImage)
this._targetImageWrap.appendChild(this._targetImage)
$(this._targetImage).addClass('zoom-img').attr('data-action','zoom-out')
this._overlay=document.createElement('div')
this._overlay.className='zoom-overlay'
document.body.appendChild(this._overlay)
this._calculateZoom()
this._triggerAnimation()}
Zoom.prototype._calculateZoom=function(){this._targetImage.offsetWidth
var originalFullImageWidth=this._fullWidth
var originalFullImageHeight=this._fullHeight
var scrollTop=$(window).scrollTop()
var maxScaleFactor=originalFullImageWidth/this._targetImage.width
var viewportHeight=($(window).height()-Zoom.OFFSET)
var viewportWidth=($(window).width()-Zoom.OFFSET)
var imageAspectRatio=originalFullImageWidth/originalFullImageHeight
var viewportAspectRatio=viewportWidth/viewportHeight
if(originalFullImageWidth<viewportWidth&&originalFullImageHeight<viewportHeight){this._imgScaleFactor=maxScaleFactor}else if(imageAspectRatio<viewportAspectRatio){this._imgScaleFactor=(viewportHeight/originalFullImageHeight)*maxScaleFactor}else{this._imgScaleFactor=(viewportWidth/originalFullImageWidth)*maxScaleFactor}}
Zoom.prototype._triggerAnimation=function(){this._targetImage.offsetWidth
var imageOffset=$(this._targetImage).offset()
var scrollTop=$(window).scrollTop()
var viewportY=scrollTop+($(window).height()/2)
var viewportX=($(window).width()/2)
var imageCenterY=imageOffset.top+(this._targetImage.height/2)
var imageCenterX=imageOffset.left+(this._targetImage.width/2)
this._translateY=viewportY-imageCenterY
this._translateX=viewportX-imageCenterX
var targetTransform='scale('+this._imgScaleFactor+')'
var imageWrapTransform='translate('+this._translateX+'px, '+this._translateY+'px)'
if($.support.transition){imageWrapTransform+=' translateZ(0)'}
$(this._targetImage).css({'-webkit-transform':targetTransform,'-ms-transform':targetTransform,'transform':targetTransform})
$(this._targetImageWrap).css({'-webkit-transform':imageWrapTransform,'-ms-transform':imageWrapTransform,'transform':imageWrapTransform})
this._$body.addClass('zoom-overlay-open')}
Zoom.prototype.close=function(){this._$body.removeClass('zoom-overlay-open').addClass('zoom-overlay-transitioning')
$(this._targetImage).css({'-webkit-transform':'','-ms-transform':'','transform':''})
$(this._targetImageWrap).css({'-webkit-transform':'','-ms-transform':'','transform':''})
if(!$.support.transition){return this.dispose()}
$(this._targetImage).one($.support.transition.end,$.proxy(this.dispose,this)).emulateTransitionEnd(300)}
Zoom.prototype.dispose=function(){if(this._targetImageWrap&&this._targetImageWrap.parentNode){$(this._targetImage).removeClass('zoom-img').attr('data-action','zoom')
this._targetImageWrap.parentNode.replaceChild(this._targetImage,this._targetImageWrap)
this._overlay.parentNode.removeChild(this._overlay)
this._$body.removeClass('zoom-overlay-transitioning')}}
$(function(){new ZoomService().listen()})}(jQuery)