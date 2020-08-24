/**
 * Author and copyright: Stefan Haack (https://shaack.com)
 * Repository: https://github.com/shaack/bootstrap-input-spinner
 * License: MIT, see file 'LICENSE'
 */

;(function ($) {
    "use strict"

    var triggerKeyPressed = false
    var originalVal = $.fn.val
    $.fn.val = function (value) {
        if (arguments.length >= 1) {
            if (this[0] && this[0]["bootstrap-input-spinner"] && this[0].setValue) {
                var element = this[0]
                setTimeout(function () {
                    element.setValue(value)
                })
            }
        }
        return originalVal.apply(this, arguments)
    }

    $.fn.inputSpinner = function (methodOrOptions) {

        if (methodOrOptions === "destroy") {
            this.each(function () {
                this.destroyInputSpinner()
            })
            return this
        }

        var config = {
            decrementButton: "<strong>&minus;</strong>", // button text
            incrementButton: "<strong>&plus;</strong>", // ..
            groupClass: "", // css class of the resulting input-group
            buttonsClass: "btn-outline-secondary",
            buttonsWidth: "2.5rem",
            textAlign: "center",
            autoDelay: 500, // ms holding before auto value change
            autoInterval: 100, // speed of auto value change
            boostThreshold: 10, // boost after these steps
            boostMultiplier: "auto" // you can also set a constant number as multiplier
        }
        for (var option in methodOrOptions) {
            // noinspection JSUnfilteredForInLoop
            config[option] = methodOrOptions[option]
        }

        var html = '<div class="input-group ' + config.groupClass + '">' +
            '<div class="input-group-prepend">' +
            '<button style="min-width: ' + config.buttonsWidth + '" class="btn btn-decrement ' + config.buttonsClass + '" type="button">' + config.decrementButton + '</button>' +
            '</div>' +
            '<input type="text" inputmode="decimal" style="text-align: ' + config.textAlign + '" class="form-control"/>' +
            '<div class="input-group-append">' +
            '<button style="min-width: ' + config.buttonsWidth + '" class="btn btn-increment ' + config.buttonsClass + '" type="button">' + config.incrementButton + '</button>' +
            '</div>' +
            '</div>'

        var locale = navigator.language || "en-US"

        this.each(function () {

            var $original = $(this)
            $original[0]["bootstrap-input-spinner"] = true
            $original.hide()

            var autoDelayHandler = null
            var autoIntervalHandler = null
            var autoMultiplier = config.boostMultiplier === "auto"
            var boostMultiplier = autoMultiplier ? 1 : config.boostMultiplier

            var $inputGroup = $(html)
            var $buttonDecrement = $inputGroup.find(".btn-decrement")
            var $buttonIncrement = $inputGroup.find(".btn-increment")
            var $input = $inputGroup.find("input")

            var min = null
            var max = null
            var step = null
            var stepMax = null
            var decimals = null
            var digitGrouping = null
            var numberFormat = null

            updateAttributes()

            var value = parseFloat($original[0].value)
            var boostStepsCount = 0

            var prefix = $original.attr("data-prefix") || ""
            var suffix = $original.attr("data-suffix") || ""

            if (prefix) {
                var prefixElement = $('<span class="input-group-text">' + prefix + '</span>')
                $inputGroup.find(".input-group-prepend").append(prefixElement)
            }
            if (suffix) {
                var suffixElement = $('<span class="input-group-text">' + suffix + '</span>')
                $inputGroup.find(".input-group-append").prepend(suffixElement)
            }

            $original[0].setValue = function (newValue) {
                setValue(newValue)
            }
            $original[0].destroyInputSpinner = function () {
                destroy()
            }

            var observer = new MutationObserver(function () {
                updateAttributes()
                setValue(value, true)
            })
            observer.observe($original[0], {attributes: true})

            $original.after($inputGroup)

            setValue(value)

            $input.on("paste input change focusout", function (event) {
                var newValue = $input[0].value
                var focusOut = event.type === "focusout"
                newValue = parseLocaleNumber(newValue)
                setValue(newValue, focusOut)
                dispatchEvent($original, event.type)
            })

            onPointerDown($buttonDecrement[0], function () {
                stepHandling(-step)
            })
            onPointerDown($buttonIncrement[0], function () {
                stepHandling(step)
            })
            onPointerUp(document.body, function () {
                resetTimer()
            })

            function setValue(newValue, updateInput) {
                if (updateInput === undefined) {
                    updateInput = true
                }
                if (isNaN(newValue) || newValue === "") {
                    $original[0].value = ""
                    if (updateInput) {
                        $input[0].value = ""
                    }
                    value = NaN
                } else {
                    newValue = parseFloat(newValue)
                    newValue = Math.min(Math.max(newValue, min), max)
                    newValue = Math.round(newValue * Math.pow(10, decimals)) / Math.pow(10, decimals)
                    $original[0].value = newValue
                    if (updateInput) {
                        $input[0].value = numberFormat.format(newValue)
                    }
                    value = newValue
                }
            }

            function destroy() {
                $original.prop("required", $input.prop("required"))
                observer.disconnect()
                resetTimer()
                $input.off("paste input change focusout")
                $inputGroup.remove()
                $original.show()
            }

            function dispatchEvent($element, type) {
                if (type) {
                    setTimeout(function () {
                        var event
                        if (typeof (Event) === 'function') {
                            event = new Event(type, {bubbles: true})
                        } else { // IE
                            event = document.createEvent('Event')
                            event.initEvent(type, true, true)
                        }
                        $element[0].dispatchEvent(event)
                    })
                }
            }

            function stepHandling(step) {
                if (!$input[0].disabled && !$input[0].readOnly) {
                    calcStep(step)
                    resetTimer()
                    autoDelayHandler = setTimeout(function () {
                        autoIntervalHandler = setInterval(function () {
                            if (boostStepsCount > config.boostThreshold) {
                                if (autoMultiplier) {
                                    calcStep(step * parseInt(boostMultiplier, 10))
                                    if (boostMultiplier < 100000000) {
                                        boostMultiplier = boostMultiplier * 1.1
                                    }
                                    if (stepMax) {
                                        boostMultiplier = Math.min(stepMax, boostMultiplier)
                                    }
                                } else {
                                    calcStep(step * boostMultiplier)
                                }
                            } else {
                                calcStep(step)
                            }
                            boostStepsCount++
                        }, config.autoInterval)
                    }, config.autoDelay)
                }
            }

            function calcStep(step) {
                if (isNaN(value)) {
                    value = 0
                }
                setValue(Math.round(value / step) * step + step)
                dispatchEvent($original, "input")
                dispatchEvent($original, "change")
            }

            function resetTimer() {
                boostStepsCount = 0
                boostMultiplier = boostMultiplier = autoMultiplier ? 1 : config.boostMultiplier
                clearTimeout(autoDelayHandler)
                clearTimeout(autoIntervalHandler)
            }

            function updateAttributes() {
                // copy properties from original to the new input
                if($original.prop("required")) {
                    $input.prop("required", $original.prop("required"))
                    $original.removeAttr('required')
                }
                $input.prop("placeholder", $original.prop("placeholder"))
                $input.attr("inputmode", $original.attr("inputmode") || "decimal")
                var disabled = $original.prop("disabled")
                var readonly = $original.prop("readonly")
                $input.prop("disabled", disabled)
                $input.prop("readonly", readonly)
                $buttonIncrement.prop("disabled", disabled || readonly)
                $buttonDecrement.prop("disabled", disabled || readonly)
                if (disabled || readonly) {
                    resetTimer()
                }
                var originalClass = $original.prop("class")
                var groupClass = ""
                // sizing
                if (/form-control-sm/g.test(originalClass)) {
                    groupClass = "input-group-sm"
                } else if (/form-control-lg/g.test(originalClass)) {
                    groupClass = "input-group-lg"
                }
                var inputClass = originalClass.replace(/form-control(-(sm|lg))?/g, "")
                $inputGroup.prop("class", "input-group " + groupClass + " " + config.groupClass)
                $input.prop("class", "form-control " + inputClass)

                // update the main attributes
                min = parseFloat($original.prop("min")) || 0
                max = isNaN($original.prop("max")) || $original.prop("max") === "" ? Infinity : parseFloat($original.prop("max"))
                step = parseFloat($original.prop("step")) || 1
                stepMax = parseInt($original.attr("data-step-max")) || 0
                var newDecimals = parseInt($original.attr("data-decimals")) || 0
                var newDigitGrouping = !($original.attr("data-digit-grouping") === "false")
                if (decimals !== newDecimals || digitGrouping !== newDigitGrouping) {
                    decimals = newDecimals
                    digitGrouping = newDigitGrouping
                    numberFormat = new Intl.NumberFormat(locale, {
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals,
                        useGrouping: digitGrouping
                    })
                }
            }

            function parseLocaleNumber(stringNumber) {
                var numberFormat = new Intl.NumberFormat(locale)
                var thousandSeparator = numberFormat.format(11111).replace(/1/g, '') || '.'
                var decimalSeparator = numberFormat.format(1.1).replace(/1/g, '')
                return parseFloat(stringNumber
                    .replace(new RegExp(' ', 'g'), '')
                    .replace(new RegExp('\\' + thousandSeparator, 'g'), '')
                    .replace(new RegExp('\\' + decimalSeparator), '.')
                )
            }
        })

        return this
    }

    function onPointerUp(element, callback) {
        element.addEventListener("mouseup", function (e) {
            callback(e)
        })
        element.addEventListener("touchend", function (e) {
            callback(e)
        })
        element.addEventListener("keyup", function (e) {
            if ((e.keyCode === 32 || e.keyCode === 13)) {
                triggerKeyPressed = false
                callback(e)
            }
        })
    }

    function onPointerDown(element, callback) {
        element.addEventListener("mousedown", function (e) {
            e.preventDefault()
            callback(e)
        })
        element.addEventListener("touchstart", function (e) {
            if (e.cancelable) {
                e.preventDefault()
            }
            callback(e)
        })
        element.addEventListener("keydown", function (e) {
            if ((e.keyCode === 32 || e.keyCode === 13) && !triggerKeyPressed) {
                triggerKeyPressed = true
                callback(e)
            }
        })
    }

}(jQuery))
