'use strict'
;
(function ($) {
  window.attach('[data-block="field-select"]', function () {
    var $block = $(this)
    var dataSet = $block.data('optionsFieldSelect')
    var $input = $block.find('[data-element="field-select-input"]')
    var $keeper = $block.find('[data-element="field-select-keeper"]')
    var options = $.extend({}, dataSet, {

    })
    $input.select2(options);
    $input.on('change.select2', function () {
      var inputVal = $input.val()
      if (!inputVal.length) {
        $keeper.val('null')
      } else {
        $keeper.val(inputVal)
      }
    })
  })
})(jQuery)