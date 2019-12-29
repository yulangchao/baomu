define(['jquery'], function($) {
    'use strict';
    var Api = {
        status: function(value, row, index) {
            var source = this.source || {
                "1": {
                    "color": "green",
                    "text": "启用"
                },
                "0": {
                    "color": "red",
                    "text": "停用"
                }
            }
            var result = ''
            for(var key in source){
                if (value == key) {
                    if (this.search === true) {
                        result = '<a style="color:' + source[key].color +'" href="javascript:;" class="searchit" data-toggle="tooltip" title="' + __('Click to search %s', source[key].text) + '" data-field="' + this.field + '" data-value="' + value + '">' + source[key].text + '</a>';
                    } else {
                        result = '<label style="color:' + source[key].color + ';">' + source[key].text + '</label>'
                    }
                }
            }
            return result
        },
        linebreak: function(value, row, index) {
            if (!value) return value;
            var arr = value.split('\\n')
            var res = [];
            for (var i = 0; i < arr.length; i ++) {
                res.push("<span>" + arr[i] +"</span>")
            }
            return res.join('<br>')
        },
        search: function (value, row, index, text) {
            var field = this.field;
            if (typeof this.customField !== 'undefined' && typeof row[this.customField] !== 'undefined') {
                value = row[this.customField];
                field = this.customField;
            }
            var source = this.source
            if (source) text = source[value].text
            return '<a href="javascript:;" class="searchit" data-toggle="tooltip" title="' + __('Click to search %s', (text || value)) + '" data-field="' + field + '" data-value="' + value + '">' + (text || value) + '</a>';
        },
        bindEvent: function (form, success, error) {
            form = typeof form === 'object' ? form : $(form);
            var events = Api.events;
            events.xshop_choose_product(form)
            events.xshop_choose_category(form)
            events.products_show(form)
            events.selectpage(form)
        },
        events: {
            xshop_choose_product: function (form) {
                //绑定xshop-choose-product选择附件事件
                if ($(".xshop-choose-product", form).size() > 0) {
                    $(".xshop-choose-product", form).on('click', function () {
                        var that = this;
                        var multiple = $(this).data("multiple") ? $(this).data("multiple") : false;
                        var ids = $('#' + $(this).data('input-id')).val()
                        parent.Fast.api.open("xshop/product/select?multiple=" + multiple + "&ids=" + ids, "选择商品", {
                            callback: function (data) {
                                var button = $("#" + $(that).attr("id"));
                                var maxcount = $(button).data("maxcount");
                                var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                                maxcount = typeof maxcount !== "undefined" ? maxcount : 0;
                                if (input_id && data.multiple) {
                                    var productIdArr = data.data;
                                    var inputObj = $("#" + input_id);
                                    var value = $.trim(inputObj.val());
                                    productIdArr = $.merge(productIdArr, inputObj.val().split(','))
                                    var result = $.unique(productIdArr.join(',').split(','))
                                    inputObj.val(result).trigger("change").trigger("validate");
                                } else {
                                    $("#" + input_id).val(data.row.id).trigger("change").trigger("validate");
                                }
                            }
                        });
                        return false;
                    });
                }
                
            },
            xshop_choose_category: function (form) {
                //绑定xshop-choose-category选择附件事件
                if ($(".xshop-choose-category", form).size() > 0) {
                    $(".xshop-choose-category", form).on('click', function () {
                        var that = this;
                        var multiple = $(this).data("multiple") ? $(this).data("multiple") : false;
                        parent.Fast.api.open("xshop/category/select?multiple=" + multiple, "选择分类", {
                            callback: function (data) {
                                var button = $("#" + $(that).attr("id"));
                                var maxcount = $(button).data("maxcount");
                                var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                                maxcount = typeof maxcount !== "undefined" ? maxcount : 0;
                                
                                if (input_id && data.multiple) {
                                    var catArr = data.data
                                    var inputObj = $("#" + input_id);
                                    var value = $.trim(inputObj.val());
                                    if (value !== "") {
                                        catArr.push(inputObj.val());
                                    }
                                    var result = catArr.join(",");
                                    inputObj.val(result).trigger("change").trigger("validate");
                                } else {
                                    $("#" + input_id).val(data.row.id).trigger("change").trigger("validate");
                                }
                            }
                        });
                        return false;
                    });
                }
            },
            products_show: function(form) {
                if ($(".products-show", form).size() > 0) {
                    $('.products-show', form).on('click', function() {
                        parent.Fast.api.open( 'xshop/product/show?ids=' + $(this).val(), "商品")
                    })
                }
            },
            citypicker: function (form) {
                //绑定城市远程插件
                if ($("[data-toggle='city-picker']", form).size() > 0) {
                    require(['citypicker'], function () {
                        $(form).on("reset", function () {
                            setTimeout(function () {
                                $("[data-toggle='city-picker']", form).citypicker('refresh');
                            }, 1);
                        });
                    });
                }
            },
            selectpage: function (form) {
                //绑定selectpage元素事件
                if ($(".selectpage", form).size() > 0) {
                    require(['selectpage'], function () {
                        $('.selectpage', form).selectPage({
                            eAjaxSuccess: function (data) {
                                data.list = typeof data.rows !== 'undefined' ? data.rows : (typeof data.list !== 'undefined' ? data.list : []);
                                data.totalRow = typeof data.total !== 'undefined' ? data.total : (typeof data.totalRow !== 'undefined' ? data.totalRow : data.list.length);
                                return data;
                            }
                        });
                    });
                    //给隐藏的元素添加上validate验证触发事件
                    $(document).on("change", ".sp_hidden", function () {
                        $(this).trigger("validate");
                    });
                    $(document).on("change", ".sp_input", function () {
                        $(this).closest(".sp_container").find(".sp_hidden").trigger("change");
                    });
                    $(form).on("reset", function () {
                        setTimeout(function () {
                            $('.selectpage', form).selectPageClear();
                        }, 1);
                    });
                }
            },
        }
    }

    return Api;
})

