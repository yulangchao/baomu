define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'xshop/app_update/index' + location.search,
                    add_url: 'xshop/app_update/add',
                    edit_url: 'xshop/app_update/edit',
                    del_url: 'xshop/app_update/del',
                    multi_url: 'xshop/app_update/multi',
                    table: 'xshop_app_update',
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
                        {field: 'version', title: __('Version')},
                        {field: 'description', title: __('Description')},
                        {field: 'platform', title: __('Platform')},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.toggle},
                        {field: 'source_file', title: __('资源'),width: '150px'},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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