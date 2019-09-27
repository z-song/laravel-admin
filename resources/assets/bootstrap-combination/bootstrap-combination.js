(function ($) {
    $.fn.bootstrapCombinations = function () {
        var combination = new Combination(this);
        return combination.init();
    };
    // 对象开始
    var Combination = function (element) {
        this.$element = element;//当前元素对象
        this.combination_key = 0;//记录新组合次数
        this.defaultOptions = {
            attributeClass: 'attribute_ids',
            grid: [
                ['预览图', 'image', 'file', ''],
                ['成本价', 'cost_price', 'text', '0.00'],
                ['单价', 'price', 'text', '0.00'],
                ['库存', 'num', 'text', '9999']
            ]
        };
        var inputOptions = {
            attributeClass: this.$element.data('attribute'),
            grid: eval(this.$element.data('grid'))
        };

        this.options = $.extend({}, this.defaultOptions, inputOptions);
    };
    Combination.prototype = {
        init: function () {
            var _this = this;
            var attributeClass = $('.' + _this.options.attributeClass);
            if (_this.$element.data('value')) {//编辑回显
                var attribute_data = attributeClass.select2('data');//获取属性数据
                var attribute_val = attributeClass.select2('val');//获取属性值

                $.each(attribute_val, function (key, val) {
                    _this.addAttributeGroups(attribute_data, val);//新增属性组
                });
                $.each(eval(_this.$element.find('.labels').data('value')), function (key, val) {
                    $('.attribute-groups[data-id="' + val.attribute_id + '"]').find('.labels-add').parent().before(_this.labelGroupsTpl(val.id, val.attribute_id, val.product_id, val.name));
                });
                var label_group = $('.label-group');
                label_group.find('input.name').attr('readonly', true);
                label_group.find('.fa-check').removeClass('fa-check').addClass('fa-edit');
                $('.combinations').after(_this.combinationTableTpl());

                _this.combinationTable();//显示组合表格

                $.each(eval(_this.$element.find('.combinations').data('value')), function (key, val) {
                    $('.tr-td').find('.label_id').each(function () {
                        var labels = _this.arrayIntersect(val.label_id.split(','), $(this).val().split(','));//算出差集
                        if (labels.length === 0) {
                            var tr_td = $(this).parent();
                            $.each(_this.options.grid, function (k, v) {
                                if (v[2] === 'file') {
                                    tr_td.find('.' + v[1]).attr('name', 'combinations[' + val.id + '][' + v[1] + ']').prev().attr('src', val[v[1]]);
                                }
                                if (v[2] === 'text') {
                                    tr_td.find('.' + v[1]).val(val[v[1]]).attr('name', 'combinations[' + val.id + '][' + v[1] + ']');
                                }
                            });
                            tr_td.find('.id').val(val.id).attr('name', 'combinations[' + val.id + '][id]');
                            tr_td.find('.label_id').attr('name', 'combinations[' + val.id + '][label_id]');
                            tr_td.find('.product_id').val(val.product_id).attr('name', 'combinations[' + val.id + '][product_id]');
                            tr_td.find('.update').attr('name', 'combinations[' + val.id + '][update]');
                            tr_td.find('.remove').attr('name', 'combinations[' + val.id + '][remove]');
                        }
                    });
                });
            }

            //属性标签
            var label_id = 0;//记录新增标签次数
            $('#bootstrap-combination')
            //新增标签事件
                .on('click', '.labels-add', function () {
                    label_id++;
                    var attribute_id = $(this).parents('.attribute-groups').data('id');//获取当前属性组ID
                    $('.attribute-groups .fa-check').addClass('disabled');//禁用所有确认
                    $('.attribute-groups .fa-edit').addClass('disabled');//禁用所有编辑
                    $('.attribute-groups .fa-close').addClass('disabled');//禁用所有删除
                    $('.labels-add').addClass('disabled');//禁用所有新增
                    $(this).parent().addClass('new-label');//标记新增
                    attributeClass.attr('disabled', true);//禁用属性选择
                    $(this).parent().before(_this.labelGroupsTpl('new-' + label_id, attribute_id));
                })
                //标签check事件
                .on('click', '.fa-check', function () {
                    var label_name = $(this).parent().prev();
                    if ($.trim(label_name.val()) !== '') {//去除左边空格后判断是否为空
                        label_name.val($.trim(label_name.val()));//去除左边空格
                        label_name.attr('readonly', true);//标签不可编辑
                        $(this).removeClass('fa-check').addClass('fa-edit');
                        $('.attribute-groups .fa-check').removeClass('disabled');
                        $('.attribute-groups .fa-edit').removeClass('disabled');
                        $('.attribute-groups .fa-close').removeClass('disabled');
                        $('.labels-add').removeClass('disabled');
                        attributeClass.attr('disabled', false);

                        if (_this.$element.find('.box-body').length === 0) {
                            $('.combinations').after(_this.combinationTableTpl());
                        }

                        //true为新增，false为编辑
                        var label_id = $(this).parent().prevAll('.id').val();
                        var labels_add = $(this).parents('.col-sm-3.fields-group').next();//当前标签的新增元素
                        if (labels_add.hasClass('new-label')) {//新增标签
                            var attribute_id = $(this).parents('.attribute-groups').data('id');

                            labels_add.removeClass('new-label');
                            //获取之前的属性ID
                            var tr_th_attribute_ids = [];
                            $('.tr-th-attribute').each(function () {
                                tr_th_attribute_ids.push($(this).data('id'));
                            });
                            if ($.inArray(attribute_id, tr_th_attribute_ids) === -1) {//判断当前属性ID是否在之前的属性ID中
                                $('#bootstrap-combination tbody .tr-td').each(function () {
                                    if ($(this).find('.label_id').val().search('new') !== -1) {
                                        $(this).remove();
                                    } else {
                                        $(this).addClass('hide').find('.remove').val(1);
                                    }
                                });
                            }
                            //显示组合表格
                            _this.combinationTable(attribute_id, label_id);
                        } else {//编辑标签
                            $('.combination-labels[data-id="' + label_id + '"]').text(label_name.val());
                        }
                    } else {
                        label_name.val('');
                    }
                })
                //标签edit事件
                .on('click', '.fa-edit', function () {
                    $('.attribute-groups .fa-check').addClass('disabled');
                    $('.attribute-groups .fa-edit').addClass('disabled');
                    $('.attribute-groups .fa-close').addClass('disabled');
                    $('.labels-add').addClass('disabled');
                    attributeClass.attr('disabled', true);
                    $(this).parent().prev().attr('readonly', false);//标签可编辑
                    $(this).removeClass('fa-edit disabled').addClass('fa-check');
                })
                //标签close事件
                .on('click', '.fa-close', function () {
                    var close_label_id = $(this).parent().prevAll('.id').val();
                    $('.attribute-groups .fa-check').removeClass('disabled');
                    $('.attribute-groups .fa-edit').removeClass('disabled');
                    $('.attribute-groups .fa-close').removeClass('disabled');
                    $('.labels-add').removeClass('disabled');
                    attributeClass.attr('disabled', false);
                    var labels_add = $(this).parents('.col-sm-3.fields-group').next();//当前标签的新增元素
                    if (labels_add.hasClass('new-label')) {
                        labels_add.removeClass('new-label');
                    }
                    if (close_label_id.search('new') !== -1) {//如果删除的标签是新增
                        $(this).parents('.label-group').remove();//真删除标签
                        $('#bootstrap-combination .tr-td').each(function () {
                            if ($(this).find('.label_id').val().search(close_label_id) !== -1) {
                                $(this).remove();//真删除组合
                            }
                        });
                    } else {
                        $(this).parents('.label-group').addClass('hide').removeClass('label-group');//假删除标签
                        $(this).parent().prevAll('.remove').val(1);
                        $(this).parent().prevAll('.name').removeAttr('required');
                        $('#bootstrap-combination .tr-td').each(function () {
                            if ($(this).find('.label_id').val().search(close_label_id) !== -1) {
                                $(this).addClass('hide').find('.remove').val(1);//假删除组合
                            }
                        });
                    }
                    //显示组合表格
                    _this.combinationTable();
                })
                //标签值改变事件
                .on('propertychange input', '.label-group .name', function () {
                    if ($(this).val() === '') {
                        $(this).prevAll('.update').val(0);
                    } else {
                        $(this).prevAll('.update').val(1);
                    }
                })
                //组合值改变事件
                .on('propertychange input', '.tr-td .table-input', function () {
                    if ($(this).val() === '') {
                        $(this).val('0.00');
                    } else {
                        $(this).parents('.tr-td').find('.update').val(1);
                    }
                })
                //属性改变事件
                .on('change', '.' + _this.options.attributeClass, function () {
                    var attribute_data = $(this).select2('data');//获取属性数据
                    var attribute_val = $(this).select2('val');//获取属性值
                    attribute_val = attribute_val === null ? [] : attribute_val;
                    _this.$element.parents('.form-group').removeClass('hide');//显示视图
                    var view_attribute_val = [];//已存在的属性值
                    $.each($('.attribute-groups'), function (key, val) {
                        view_attribute_val.push(String($(val).data('id')));
                    });
                    var attribute_difference = _this.arrayIntersect(attribute_val, view_attribute_val);//算出差集

                    $.each(attribute_difference, function (key, val) {
                        if ($.inArray(val, view_attribute_val) === -1) {
                            _this.addAttributeGroups(attribute_data, val);//新增属性组
                        } else {
                            $('.attribute-groups.attribute-' + val).addClass('hide').removeClass('attribute-groups').find('.remove').val(1);
                            $('.tr-td').each(function () {
                                $(this).addClass('hide').find('.remove').val(1);
                            });
                            //显示组合表格
                            _this.combinationTable();
                        }
                    });
                    if ($('.attribute-groups').length === 0) {
                        _this.$element.parents('.form-group').addClass('hide');
                    }
                })
                //统一值改变事件
                .on('change', '.unified', function () {
                    $('.' + $(this).data('class')).val($(this).val());
                    $('.tr-td').find('.update').val(1);
                    // _this.profit(tr, true);//计算利润
                })
                //修改组合图片
                .on('click', '.combination-img', function () {
                    $(this).next().click();
                })
                //图片改变事件
                .on('change', '.combination-image', function () {
                    var objUrl = _this.getObjectURL(this.files[0]);
                    $(this).prev().attr('src', objUrl).parents('.tr-td').find('.update').val(1);
                });
        },
        //获取数组差集
        arrayIntersect: function (a, b) {
            return $.merge($.grep(a, function (i) {
                    return $.inArray(i, b) === -1;
                }), $.grep(b, function (i) {
                    return $.inArray(i, a) === -1;
                })
            );
        },
        //新增属性组
        addAttributeGroups: function (attribute_data, val) {
            var _this = this;
            $.each(attribute_data, function (k, v) {
                if (v.id === val) {
                    _this.$element.find('.combinations').before(_this.attributeGroupsTpl(v));
                }
            });
        },
        //属性组模板
        attributeGroupsTpl: function (attribute) {
            return '<div class="form-group attribute-groups attribute-' + attribute.id + '" data-id="' + attribute.id + '">\n' +
                '       <label class="col-sm-1" style="text-align: left;"><span class="btn btn-warning">' + attribute.text + '</span></label>\n' +
                '       <div class="col-sm-11 has-many-labels">\n' +
                '           <div class="form-group has-many-labels-forms">\n' +
                '               <div class="col-sm-2">\n' +
                '                   <div class="labels-add btn btn-success"><i class="fa fa-save"></i>&nbsp;新增标签</div>\n' +
                '               </div>\n' +
                '           </div>\n' +
                '       </div>\n' +
                '   </div>';
        },
        //标签模板
        labelGroupsTpl: function (label_id, attribute_id, value_product_id = '', value_name = '') {
            return '<div class="col-sm-3 label-group fields-group">\n' +
                '       <div class="form-group">\n' +
                '           <div class="col-sm-12">\n' +
                '               <div class="input-group">\n' +
                '                   <input type="hidden" name="labels[' + label_id + '][id]" value="' + label_id + '" class="id">\n' +
                '                   <input type="hidden" name="labels[' + label_id + '][attribute_id]" value="' + attribute_id + '" class="attribute_id">\n' +
                '                   <input type="hidden" name="labels[' + label_id + '][product_id]" value="' + value_product_id + '" class="product_id">\n' +
                '                   <input type="hidden" name="labels[' + label_id + '][update]" value="0" class="update">\n' +
                '                   <input type="hidden" name="labels[' + label_id + '][remove]" value="0" class="remove">\n' +
                '                   <input type="text" name="labels[' + label_id + '][name]" value="' + value_name + '" class="form-control name" maxlength="10" placeholder="输入标签" required>\n' +
                '                   <span class="input-group-addon"><i class="fa fa-check"></i></span>\n' +
                '                   <span class="input-group-addon"><i class="fa fa-close"></i></span>\n' +
                '               </div>\n' +
                '           </div>\n' +
                '       </div>\n' +
                '   </div>';
        },
        //组合数据
        combinationDate: function (attribute_id = null, label_id = null) {
            var tr_th = '';
            var tr_td = [];
            $.each($('.attribute-groups'), function (key, val) {
                if ($(val).find('.label-group').length > 0) {
                    tr_th += '<th class="tr-th-attribute table-th" data-id="' + $(val).data('id') + '">' + $(val).find('.btn-warning').text() + '</th>\n';
                    var labels = [];
                    $.each($(val).find('.label-group'), function (k, v) {
                        if ($(v).find('.attribute_id').val() !== String(attribute_id) || ($(v).find('.attribute_id').val() === String(attribute_id) && $(v).find('.id').val() === label_id)) {
                            labels.push($(v).find('.id').val() + '_' + $(v).find('.name').val());
                        }
                    });
                    tr_td.push(labels);
                }
            });
            return [tr_th, tr_td];
        },
        //组合框架
        combinationTable: function (attribute_id = null, label_id = null) {
            var _this = this;
            //显示组合视图
            if ($('.tr-th').length === 0) {
                _this.$element.find('tbody').append(_this.combinationThTpl(_this.combinationDate(attribute_id, label_id)[0]));
            } else {
                _this.$element.find('tbody .tr-th').replaceWith(_this.combinationThTpl(_this.combinationDate(attribute_id, label_id)[0]));
            }
            _this.$element.find('tbody').append(_this.combinationTdTpl(_this.combinationDate(attribute_id, label_id)[1]));
        },
        //组合框架模板
        combinationTableTpl: function () {
            return '<div class="box-body table-responsive no-padding">\n' +
                '       <table class="table table-hover text-center">\n' +
                '           <tbody>\n' +
                '           </tbody>\n' +
                '       </table>\n' +
                '   </div>';
        },
        //组合表格th模板
        combinationThTpl: function (tr_th) {
            tr_th += this.combinationGrid()[0];
            return '<tr class="tr-th">\n' + tr_th + '</tr>';
        },
        //组合表格td模板
        combinationTdTpl: function (labels_array) {
            var _this = this;
            var tbody = '';
            $.each(_this.combination(labels_array), function (key, val) {
                var label_id = '';
                var tr_td = '';
                if ($.isArray(val)) {
                    $.each(val, function (k, v) {
                        var c = v.split('_');
                        label_id += c[0] + ',';
                        tr_td += '<td class="combination-labels" data-id="' + c[0] + '">' + c[1] + '</td>\n';//累加列
                    });
                    label_id = label_id.substring(0, label_id.length - 1);
                } else {
                    var c = val.split('_');
                    label_id = c[0];
                    tr_td = '<td class="combination-labels" data-id="' + c[0] + '">' + c[1] + '</td>\n';
                }
                if ($('#bootstrap-combination tbody .tr-td:not(:has(".hide"))').find('input[value="' + label_id + '"]').length === 0) {
                    _this.combination_key++;
                    var combination_id = 'new-' + _this.combination_key;
                    tr_td += _this.combinationGrid(combination_id)[1];
                    tbody += '<tr class="tr-td">\n' + tr_td +
                        '       <input type="hidden" name="combinations[' + combination_id + '][id]" value="' + combination_id + '" class="id">\n' +
                        '       <input type="hidden" name="combinations[' + combination_id + '][label_id]" value="' + label_id + '" class="label_id">\n' +
                        '       <input type="hidden" name="combinations[' + combination_id + '][product_id]" value="" class="product_id">\n' +
                        '       <input type="hidden" name="combinations[' + combination_id + '][update]" value="0" class="update">\n' +
                        '       <input type="hidden" name="combinations[' + combination_id + '][remove]" value="0" class="remove">\n' +
                        '   </tr>';
                }
            });
            return tbody;
        },
        // 组合表格
        combinationGrid: function (combination_id = null) {
            var _this = this;
            var th = '';
            var td = '';
            $.each(_this.options.grid, function (key, val) {
                if (val[2] === 'file') {
                    th += '<th class="table-th">' + val[0] + '</th>\n';
                    td += '<td style="width: 60px;padding: 3px;">\n' +
                        '      <img src="' + val[3] + '" height="30" class="combination-img">\n' +
                        '      <input type="file" name="combinations[' + combination_id + '][' + val[1] + ']" accept="image/*" class="combination-image ' + val[1] + '" style="display: none">\n' +
                        '  </td>\n';
                }
                if (val[2] === 'text') {
                    th += '<th>' + val[0] + '：<input type="text" placeholder="统一' + val[0] + '" class="table-input unified" data-class="' + val[1] + '"></th>\n';
                    td += '<td><input type="text" name="combinations[' + combination_id + '][' + val[1] + ']" value="' + val[3] + '" class="table-input ' + val[1] + '"></td>\n';
                }
            });
            return [th, td];
        },
        // 标签组合
        combination: function (array) {
            var len = array.length;
            // 当数组大于等于2个的时候
            if (len >= 2) {
                // 第一个数组的长度
                var len1 = array[0].length;
                // 第二个数组的长度
                var len2 = array[1].length;
                // 2个数组产生的组合数
                var lenBoth = len1 * len2;
                //  申明一个新数组
                var items = new Array(lenBoth);
                // 申明新数组的索引
                var index = 0;
                for (var i = 0; i < len1; i++) {
                    for (var j = 0; j < len2; j++) {
                        if (array[0][i] instanceof Array) {
                            items[index] = array[0][i].concat(array[1][j]);
                        } else {
                            items[index] = [array[0][i]].concat(array[1][j]);
                        }
                        index++;
                    }
                }
                var newArr = new Array(len - 1);
                for (var k = 2; k < array.length; k++) {
                    newArr[k - 1] = array[k];
                }
                newArr[0] = items;
                return this.combination(newArr);
            } else {
                return array[0];
            }
        },
        //处理组合图片
        getObjectURL: function (file) {
            var url = null;
            if (window.createObjectURL !== undefined) { // basic
                url = window.createObjectURL(file);
            } else if (window.URL !== undefined) { // mozilla(firefox)
                url = window.URL.createObjectURL(file);
            } else if (window.webkitURL !== undefined) { // webkit or chrome
                url = window.webkitURL.createObjectURL(file);
            }
            return url;
        }
    };
    // 对象结束
})(jQuery);