define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'backend/xshop/libs/CustomFormatter'], function ($, undefined, Backend, Table, Form, Custom) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'xshop/delivery/index' + location.search,
                    add_url: 'xshop/delivery/add',
                    edit_url: 'xshop/delivery/edit',
                    del_url: 'xshop/delivery/del',
                    multi_url: 'xshop/delivery/multi',
                    table: 'xshop_delivery_tpl',
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
                        {field: 'title', title: __('Title')},
                        {field: 'type', title: __('Type'), formatter: Custom.status, source: {
                            0: {text: '按重量'}, 1: {text: '按件数'}
                        }},
                        {field: 'sort', title: __('Sort')},
                        {field: 'is_default', title: __('Is_default'), url: 'xshop/delivery/setDefault', formatter: Table.api.formatter.toggle},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
            var tpl =   "<tr><td><div class='area' data-ids='__VALUE__'>" +
                        "<span>__LABEL__</span>" +
                        "<input class='area_ids' name='list[area_ids][]' style='display:none' value='__VALUE__' hidden='hidden' />" +
                        "<input class='area_names' name='list[area_names][]' style='display:none' value='__LABEL__' hidden='hidden' />" +
                        "</div></td>" + 
                        "<td width='100px'><input class='form-control' name='list[first_price][]' /></td>" + 
                        "<td width='100px'><input class='form-control' name='list[rest_price][]' /></td>" +
                        "<td width='60px'><span class='btn btn-danger btn-del btn-sm'>删除</span></td>" + 
                        "</tr>";
            Controller.api.bindCity(tpl)
            Controller.api.bindDel('.btn-del')
        },
        edit: function () {
            Controller.api.bindevent();
            var tpl =   "<tr><td><div class='area' data-ids='__VALUE__'>" +
                        "<span>__LABEL__</span>" +
                        "<input class='area_ids' name='list[area_ids][]' style='display:none' value='__VALUE__' hidden='hidden' />" +
                        "<input class='area_names' name='list[area_names][]' style='display:none' value='__LABEL__' hidden='hidden' />" +
                        "</div></td>" + 
                        "<td width='100px'><input class='form-control' name='list[first_price][]' /></td>" + 
                        "<td width='100px'><input class='form-control' name='list[rest_price][]' /></td>" +
                        "<td width='60px'><span class='btn btn-danger btn-del btn-sm'>删除</span></td>" +
                        "</tr>";
            Controller.api.bindCity(tpl)
            Controller.api.bindAreaClickEvent()
            Controller.api.bindDel('.btn-del')
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            getAreaData: function(selection) {
                var list = selection.checkedNodes
                var arr = []
                var provinces = []
                var ids = [];
                var names = [];
                for (var i = 0; i < list.length; i ++) {
                    let item = list[i]
                    if (item.id == 0) {
                        return {
                            data: [item], ids: [item.id], names: [item.name]
                        }
                    }
                    if (item.level == 1) {
                        provinces.push(item.id)
                        arr.push(item)
                        ids.push(item.id)
                        names.push(item.name)
                    } else {
                        if (provinces.indexOf(item.pid) == -1) {
                            arr.push(item)
                            ids.push(item.id)
                            names.push(item.name)
                        }
                    }
                }
                return {
                    data: arr, ids: ids, names: names
                }
            },
            parseTpl: function(data, temp) {
                vals = data.ids
                txts = data.names
                temp = temp.replace(/__VALUE__/g, vals.join(','))
                temp = temp.replace(/__LABEL__/g, txts.join(','))
                return temp
            },
            editRegion: function(el, cb) {
                parent.Fast.api.open("xshop/index/citySelector?ids=" + $(el).data('ids'), "编辑地区", {
                    callback: function(data) {
                        cb && cb(data)
                    }
                })
            },
            bindCity: function(tpl) {
                $('.btn-add-region', document).on('click', function() {
                    parent.Fast.api.open('xshop/index/citySelector', "选择运费区域", {
                        callback: function(data) {
                            var el = $(Controller.api.parseTpl(Controller.api.getAreaData(data), tpl))
                            $('.region-table tbody').append(el)
                            Controller.api.bindDel('.btn-del')
                            Controller.api.bindAreaClickEvent()
                        }
                    })
                })
            },
            bindAreaClickEvent: function() {
                $('.area').unbind('click')
                $('.area').click(function() {
                    var that = this
                    Controller.api.editRegion(this, function(data) {
                        data = Controller.api.getAreaData(data)
                        $('span', that).text(data.names.join(','))
                        $(that).data('ids', data.ids.join(','))
                        $('.area_ids', that).val(data.ids.join(','))
                        $('.area_names', that).val(data.names.join(','))
                    })
                })
            },
            bindDel: function(el) {
                $(el).unbind('click')
                $(el).click(function() {
                    $($(this).parents('tr')[0]).remove()
                })
            }
        }
    };
    return Controller;
});