define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'backend/xshop/libs/CustomFormatter'], function ($, undefined, Backend, Table, Form, Formatter) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'xshop/order/index' + location.search,
                    add_url: 'xshop/order/add',
                    edit_url: 'xshop/order/edit',
                    del_url: 'xshop/order/del',
                    multi_url: 'xshop/order/multi',
                    table: 'xshop_order',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'order_sn', title: __('Order_sn')},
                        {field: 'is_pay', title: __('Is_pay'), formatter: function(val, row, index) {
                            var that = this
                            var h = function(text) {
                                return Formatter.search.call(that, val, row, index, text)
                            }

                            if (val == 0) return h('未付款')
                            else {
                                var payTypeArr = {
                                    wechat: '微信支付', alipay: '支付宝'
                                }
                                var payMethodArr = {
                                    mp: '公众号', miniapp: '小程序', tt: '字节跳动'
                                }
                                return '<div style="color: green;">' + h('已付款') +'</div><div><span>' + (payTypeArr[row.pay_type] || '') + '</span>&nbsp;&nbsp;<span>' + (payMethodArr[row.pay_method] || '') + '</span></div><div>' + row.pay_time_text + '</div>'
                            }
                        }},
                        {field: 'pay_time', title: __('Pay_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime, visible: false},
                        {field: 'is_delivery', title: __('Is_delivery'), formatter: function(val, row, index) {
                            if (val == 0) return Formatter.search.call(this,val, row, index, '未发货')
                            else {
                                return '<div>' + row.express.name + '</div><div>' + row.express_no + '</div><div>' + row.delivery_text + '</div>'
                            }
                        }},
                        {field: 'delivery', title: __('Delivery'), addclass:'datetimerange', formatter: Table.api.formatter.datetime, visible: false},
                        {field: 'status', title: __('Status'), formatter: function(val, row) {
                            if (val == -1) return '<span style="color: red;">已取消</span>'
                            else return '正常'
                        }},
                        {field: 'contactor', title: __('Contactor'), formatter: function(val, row) {
                            return '<div>' + val +'&nbsp;&nbsp;' + row.contactor_phone + '</div><div>' + row.address + '</div><div>备注：' + row.remark + '</div>'
                        }},
                        {field: 'contactor_phone', title: __('Contactor_phone'), visible: false},
                        {field: 'delivery_price', title: __('Delivery_price'), operate:'BETWEEN'},
                        {field: 'order_price', title: __('Order_price'), operate:'BETWEEN'},
                        {field: 'products_price', title: __('Products_price'), operate:'BETWEEN'},
                        {field: 'discount_price', title: __('Discount_price'), operate:'BETWEEN'},
                        {field: 'payed_price', title: __('Payed_price'), operate:'BETWEEN'},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'user.username', title: __('User.username'), formatter: function(val, row) {
                            return '<div>' + val + '</div><div>'  + row.user.nickname + '</div>'
                        }},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,buttons: [
                            {
                                name: 'detail', text: '查看', icon: '', classname: 'btn btn-xs btn-success btn-dialog',
                                url: function(row) {
                                    return 'xshop/order_products/index?order_id=' + row.id;
                                }
                            },
                            {
                                name: 'pay', text: '付款', icon: '', classname: 'btn btn-xs btn-success btn-pay',
                                visible: function(row) {
                                    if (row.status != -1 && row.is_pay == 0) return true
                                    return false
                                }
                            },
                            {
                                name: 'edit'
                            }, {
                                name: 'ship', text: '发货', icon: '', classname: 'btn btn-xs btn-info btn-ship',
                                visible: function(row) {
                                    if (row.status != -1 && row.is_delivery == 0) return true
                                    return false
                                }
                            }
                        ]}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            table.on('post-body.bs.table', function(e, setting, json, xhr) {
                $('.btn-pay', this).on('click', function(e) {
                    var row = table.bootstrapTable('getData')[$(this).data('row-index')]
                    Layer.confirm('确定付款吗？', {icon: 3, title: '提示'}, function(index) {
                        Fast.api.ajax({
                            url: 'xshop/order/pay',
                            data: {
                                id: row.id
                            }
                        }, function(data, ret) {
                            table.bootstrapTable('refresh')
                        })
                        Layer.close(index)
                    })
                })

                $('.btn-ship', this).on('click', function(e) {
                    var row = table.bootstrapTable('getData')[$(this).data('row-index')]
                    if (!window.express) {
                        Fast.api.ajax({
                            url: 'xshop/express/getAll',
                        }, function(data, ret) {
                            window.express = data
                            Controller.api.shipFunc(row.id)
                            return false
                        }, function(data, ret) {
                            return false
                        })
                    } else {
                        Controller.api.shipFunc(row.id)
                    }
                })
            })

            
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            shipFunc: function(id) {
                var html = '<select class="form-control" id="express_code">'
                for (var i = 0; i < window.express.length; i ++) {
                    var item = window.express[i]
                    html += '<option value="' + item.code + '">' + item.name + '</option>'
                }
                html += '</select>'
                html += '<div class="form-group"><label class="col-xs-12 col-xs-12 label-control">快递单号</label><input class="form-control" id="express_no" /></div>'
                var content = '<div class="form" style="padding: 5px;"><div class="form-group"><label class="label-control col-xs-12 col-sm-12">快递公司</label>' + html + '</div></div>';
                var index = Layer.open({
                    type: 1,
                    title: '发货',
                    area: ['350px', '230px'],
                    content: content,
                    btn: ['确定', '取消'],
                    btn1: function() {
                        var express_code = $("#express_code").val()
                        var express_no = $('#express_no').val()
                        Fast.api.ajax({
                            url: 'xshop/order/ship',
                            data: {
                                id: id,
                                express_code: express_code,
                                express_no: express_no
                            }
                        }, function(data, ret) {
                            console.log(data, ret, '1')
                        }, function(data, ret) {
                            console.log(data, ret, 2)
                        })
                        Layer.close(index)
                    }
                })
            }
        }
    };
    return Controller;
});