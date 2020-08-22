/* ========================================================================
 * bootstrap-spin - v1.0
 * https://github.com/wpic/bootstrap-spin
 * ========================================================================
 * Copyright 2014 WPIC, Hamed Abdollahpour
 *
 * ========================================================================
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================================
 */

(function($) {
    $.fn.bootstrapNumber = function(options) {
        let settings = $.extend({
            upClass: 'default',
            downClass: 'default',
            center: true
        }, options);

        return this.each(function () {
            let self = $(this);
            let clone = self.clone();
            let group = $("<div class='input-group'></div>");

            let min = self.attr('min');
            let max = self.attr('max');

            setText(0);

            function setText(number) {
                if ((min && number < min)) {
                    number = min;
                }

                if (max && number > max) {
                    number = max;
                }

                clone.val(number);
            }

            function fixNumber() {
                let fixedNumber = clone.val().replace(/(-(-)|(\d+-))|([Na])|(-0).*/, 0);
                fixedNumber = fixedNumber.replace(/^0(\d+)/, '$1');

                setText(fixedNumber);
            }

            let buttonMinus = $("<button type='button'>-</button>")
              .attr('class', 'btn btn-' + settings.downClass)
              .click(function () {
                  setText(parseInt(clone.val(), 10) - 1);

                  clone.focus().trigger('change');
              });

            let buttonPlus = $("<button type='button'>+</button>")
              .attr('class', 'btn btn-' + settings.upClass)
              .click(function () {
                  setText(parseInt(clone.val(), 10) + 1);

                  clone.focus().trigger('change');
              });

            $("<span class='input-group-btn'></span>").prepend(buttonMinus).appendTo(group);

            clone.appendTo(group);
            if (clone) {
                clone.css('text-align', 'center');
            }

            $("<span class='input-group-btn'></span>").append(buttonPlus).appendTo(group);

            // remove spins from original
            clone.prop('type', 'text').keydown(function(e) {
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                  || (e.keyCode === 65 && e.ctrlKey === true)
                  || (e.keyCode >= 35 && e.keyCode <= 39)
                ) {
                    return;
                }

                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57))
                  && (e.keyCode < 96 || e.keyCode > 105)
                  && e.keyCode !== 189
                ) {
                    e.preventDefault();
                }
            }).bind('keyup keydown blur', function () {
                fixNumber();
            });


            self.replaceWith(group);
        });
    };
}($));
