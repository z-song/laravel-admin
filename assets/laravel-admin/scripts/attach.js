'use strict'
;
(function ($, _) {
  window.attach = function (a, b, c, d) {
    var parent
    var child
    var event
    var opts
    if (typeof b == 'function') {
      parent = document.body
      child = a
      event = b
      opts = c
    } else if (typeof c == 'function') {
      parent = a
      child = b
      event = c
      opts = d
    } else {
      console.error('Attach error: ', arguments)
      return false
    }
    const config = $.extend(true, {
      nameSpace: child.replace(/[^a-zA-Z0-9]+/g, '_'),
      observe: {
        attributes: false,
        childList: true,
        characterData: false,
        subtree: true
      }
    }, opts)
    const eventName = 'attach.' + config.nameSpace
    var parentObject
    if (parent instanceof $) {
      parentObject = parent.get(0)
    } else if (typeof parent == 'string') {
      parentObject = $(parent).get(0)
    } else {
      parentObject = parent
    }
    const $parent = $(parentObject)
    const executeHandler = function () {
      var $all = $(this).find(child)
      $all.each(function () {
        var data = $(this).data()
        if (!data[eventName]) {
          data[eventName] = true
          event.call(this)
        }
      })
    }
    const timer = [];
    const observer = new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        if (timer[mutation.target]) {
          clearTimeout(timer[mutation.target])
        }
        timer[mutation.target] = setTimeout(function () {
          executeHandler.call(mutation.target)
        }, 100)
      })
    })
    observer.observe(parentObject, config.observe)
    executeHandler.call(parentObject)
    $parent.off(eventName).on(eventName, executeHandler).trigger(eventName)
  }
})(jQuery)
