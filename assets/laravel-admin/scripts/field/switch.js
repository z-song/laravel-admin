'use strict'
;
(function ($) {
  window.attach('[data-block="field-switch"]', function () {
    var $block = $(this)
    var dataSet = $block.data('optionsFieldSwitch')
    var $input = $block.find('[data-element="field-switch-input"]')
    var $keeper = $block.find('[data-element="field-switch-keeper"]')
    var $unset = $block.find('[data-element="field-switch-unset"]')
    var prevState
    var options = $.extend({}, dataSet, {
      onInit: function (event, state) {
        prevState = $keeper.val()
      },
      onSwitchChange: function (event, state) {
        prevState = (state ? 'on' : 'off')
        $input.val(prevState)
        $keeper.val(prevState)
      }
    })
    $input.bootstrapSwitch(options)
    $unset.on('click', function () {
      var isNull = $input.bootstrapSwitch('indeterminate')
      if (isNull) {
        $input.bootstrapSwitch('indeterminate', false)
        if (!prevState) {
          prevState = 'off'
        }
        $input.bootstrapSwitch('state', (prevState === 'on'))
        $input.val(prevState)
        $keeper.val(prevState)
      } else {
        $input.bootstrapSwitch('indeterminate', true)
        $keeper.val('null')
        $input.val('null')
      }
    })
  })
})(jQuery)