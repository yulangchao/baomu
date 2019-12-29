define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'xshop/launch_log/index' + location.search,
                    add_url: 'xshop/launch_log/add',
                    edit_url: 'xshop/launch_log/edit',
                    del_url: 'xshop/launch_log/del',
                    multi_url: 'xshop/launch_log/multi',
                    table: 'xshop_launch_log',
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
                        {field: 'platform', title: __('Platform')},
                        {field: 'user.nickname', title: __('User_id')},
                        {field: 'systeminfo', title: __("Systeminfo"), formatter: function(val, row) {
                            try {
                                val = JSON.parse(val.replace(/&quot;/g, '"'));
                                return '<div>操作系统：'  + val.system + '</div>' +
                                        '<div>手机型号：' + val.model + '</div>' +
                                        '<div>手机品牌：' + (val.brand || '-') + '</div>'
                            } catch(e) {
                                return '-'
                            }
                        }},
                        {field: 'ip', title: __("Ip")},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'delete_time', title: __('Delete_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime, visible: false}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
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
            }
        }
    };
    return Controller;
});