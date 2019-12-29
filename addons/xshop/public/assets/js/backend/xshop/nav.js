define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'backend/xshop/libs/CustomFormatter'], 
    function ($, undefined, Backend, Table, Form, Formatter) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'xshop/nav/index' + location.search,
                    add_url: 'xshop/nav/add',
                    edit_url: 'xshop/nav/edit',
                    del_url: 'xshop/nav/del',
                    multi_url: 'xshop/nav/multi',
                    table: 'xshop_nav',
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
                        {field: 'description', title: __('Description')},
                        {field: 'image', title: __('Image'), formatter: Table.api.formatter.image},
                        {field: 'nav_type', title: __('Nav_type'), search: true, source: {
                            0: {text: '首页轮播图'}, 1: {text: '首页导航'}, 2: {text: '首页广告'}, 3: {text: '首页分类'}
                        }, formatter: Formatter.status},
                        {field: 'type', title: __('Type'), source: {
                            '0': {text: '无跳转'}, '1': {text: '跳转到商品'}, '2': {text: '跳转到分类'}, '3': {text: '跳转到路由'}, '4': {text: '跳转到外链'}
                        }, formatter: Formatter.status},
                        {field: 'target', title: __('Target')},
                        {field: 'params', title: __('Params')},
                        {field: 'sort', title: __('Sort')},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.toggle},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
            
            var htmls = {
                normal: '<div><span class="form-control">无</span>' + 
                        '<input id="c-target" name="row[target]" type="hidden" value="">' +
                        '</div>',
                product: '<div class="input-group">' +
                                '<input id="c-target" data-rule="required" class="form-control" size="50" name="row[target]" type="text" readonly>' +
                                '<div class="input-group-addon no-border no-padding">' +
                                    '<span><button type="button" id="btn-choose-product" class="btn btn-primary xshop-choose-product" data-input-id="c-target" data-preview-id="p-target" data-mimetype="image/*" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>' +
                                '</div>' +
                                '<span class="msg-box n-right" for="c-product"></span>' +
                            '</div>' +
                            '<ul class="row list-inline" id="p-target"></ul>',
                category: '<div class="input-group">' +
                            '<input id="c-target" data-rule="required" class="form-control" size="50" name="row[target]" type="text" readonly>' +
                            '<div class="input-group-addon no-border no-padding">' +
                                '<span><button type="button" id="btn-choose-category" class="btn btn-primary xshop-choose-category" data-input-id="c-target" data-preview-id="p-target" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>' +
                            '</div>' +
                            '<span class="msg-box n-right" for="c-category"></span>' +
                        '</div>' +
                        '<ul class="row list-inline" id="p-target"></ul>',
                router: '<input class="form-control" data-rule="required" name="row[target]" placeholder="请输入路由地址" />',
                link: '<input class="form-control" data-rule="required" name="row[target]" placeholder="请输入URL链接" />'
            };
            $('#target-render-content').html(htmls['normal'])
            $(document).on('change', '#c-type', function() {
                $($("#c-target").parents('.form-group')[0]).removeClass('has-error')
                var target = $('option:selected', this).data('target')
                $('#target-render-content').html(htmls[target])
                Formatter.bindEvent($('#target-render-content'))
            })
        },
        edit: function () {
            Controller.api.bindevent();
            
            var htmls = {
                normal: '<div><span class="form-control">无</span>' + 
                        '<input id="c-target" name="row[target]" type="hidden" value="">' +
                        '</div>',
                product: '<div class="input-group">' +
                                '<input id="c-target" data-rule="required" class="form-control" size="50" name="row[target]" type="text" readonly>' +
                                '<div class="input-group-addon no-border no-padding">' +
                                    '<span><button type="button" id="btn-choose-product" class="btn btn-primary xshop-choose-product" data-input-id="c-target" data-preview-id="p-target" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>' +
                                '</div>' +
                                '<span class="msg-box n-right" for="c-product"></span>' +
                            '</div>' +
                            '<ul class="row list-inline" id="p-target"></ul>',
                category: '<div class="input-group">' +
                                '<input id="c-target" data-rule="required" class="form-control" size="50" name="row[target]" type="text" readonly>' +
                                '<div class="input-group-addon no-border no-padding">' +
                                    '<span><button type="button" id="btn-choose-category" class="btn btn-primary xshop-choose-category" data-input-id="c-target" data-preview-id="p-target" data-multiple="false"><i class="fa fa-list"></i> 选择</button></span>' +
                                '</div>' +
                                '<span class="msg-box n-right" for="c-category"></span>' +
                            '</div>' +
                            '<ul class="row list-inline" id="p-target"></ul>',
                router: '<input class="form-control" id="c-target" name="row[target]" placeholder="请输入路由地址" />',
                link: '<input class="form-control" id="c-target" name="row[target]" placeholder="请输入URL链接" />'
            };
            $('#target-render-content').html(htmls[$('option:selected', '#c-type').data('target')])
            $('#c-target').val($('#target-render-content').data('value'))
            Formatter.bindEvent($('#target-render-content'))
            $(document).on('change', '#c-type', function() {
                var target = $('option:selected', this).data('target')
                $('#target-render-content').html(htmls[target])
                Formatter.bindEvent($('#target-render-content'))
            })
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});