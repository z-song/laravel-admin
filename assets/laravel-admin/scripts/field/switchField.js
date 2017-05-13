'use strict'
;
(function ($) {
  window.attach('[data-block="switchfield"]', function () {
    var $block = $(this)
    var dataSet = $block.data('optionsSwitchfield')
    var $input = $block.find('[data-element="switchfield-input"]')
    var $keeper = $block.find('[data-element="switchfield-keeper"]')
    var $unset = $block.find('[data-element="switchfield-unset"]')
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