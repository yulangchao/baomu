define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'backend/xshop/libs/CustomFormatter'], function ($, undefined, Backend, Table, Form, Formatter) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'xshop/hook/index' + location.search,
                    add_url: 'xshop/hook/add',
                    edit_url: 'xshop/hook/edit',
                    del_url: 'xshop/hook/del',
                    multi_url: 'xshop/hook/multi',
                    table: 'xshop_hook',
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
                        {field: 'group', title: __('Group'), formatter: Table.api.formatter.search},
                        {field: 'hook', title: __('Hook')},
                        {field: 'hook_desc', title: __('Hook_desc'), align: 'left', formatter: Formatter.linebreak},
                        {field: 'payload', title: __("携带数据"), align: 'left', formatter: Formatter.linebreak},
                        {field: 'hook_addon', title: '状态', formatter: function(value, row, index) {
                            var count = 0;
                            for (var i = 0; i < row.hook_addon.length; i ++) {
                                let item = row.hook_addon[i]
                                if (item.status == 1) count += 1;
                            }
                            return row.hook_addon.length + "个行为，启用" + count + "个";
                        }},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate, buttons: [
                            {
                                name: 'view',
                                title: '管理行为',
                                text: "管理行为",
                                classname: 'btn btn-xs btn-primary btn-dialog',
                                url: function(row) {
                                    return "xshop/hook/addons?hook=" + row.hook
                                }
                            },
                            {
                                name: 'edit'
                            }
                            
                        ]}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            $('.btn-reload-hooks', document).on('click', function() {
                Fast.api.ajax({
                    url: 'xshop/hook/reload',
                    data: {addon_name: $('#addon_name').val()},
                    function(data, ret) {
                        table.bootstrapTable('refresh');
                        return false
                    }
                })
            })
        },
        addons: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'xshop/hook/addons' + location.search,
                    add_url: 'xshop/hook/add',
                    edit_url: 'xshop/hook/edit',
                    del_url: 'xshop/hook/del',
                    multi_url: 'xshop/hook/multi',
                    table: 'xshop_hook',
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
                        {field: 'hook', title: __('钩子标识')},
                        {field: 'class_name', title: __('执行类')},
                        {field: 'class_desc', title: __('行为描述')},
                        {field: 'addon_name', title: __('插件标识')},
                        {field: 'addon_title', title: __('插件名称')},
                        {field: 'sort', title: __('执行顺序')},
                        {field: 'status', title: __('状态'), formatter: Table.api.formatter.toggle},
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