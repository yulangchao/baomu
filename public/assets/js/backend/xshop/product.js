requirejs.config({
    paths: {
        vue: 'backend/xshop/libs/vue'
    }
})
define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'vue', 'backend/xshop/libs/CustomFormatter'], function ($, undefined, Backend, Table, Form, Vue, Formatter) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'xshop/product/index' + location.search,
                    add_url: 'xshop/product/add',
                    edit_url: 'xshop/product/edit',
                    del_url: 'xshop/product/del',
                    multi_url: 'xshop/product/multi',
                    table: 'xshop_product',
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
                        {field: 'title', title: __('Title'), cellStyle: function() {return {css: {'max-width': '240px', display: '', overflow: 'hidden', 'text-overflow': 'ellipsis'}}}, formatter: function(val ,row) {
                            return '<div>' + val + '</div><div>' + row.description + '</div>'
                        }},
                        {field: 'description', title: __('Description'), cellStyle: function() {return {css: {'max-width': '240px', display: '', overflow: 'hidden', 'text-overflow': 'ellipsis'}}}, visible: false},
                        {field: 'image', title: __('Image'), events: Table.api.events.images, formatter: Table.api.formatter.images},
                        {field: 'on_sale', title: __('On_sale'), formatter: Table.api.formatter.toggle},
                        {field: 'sold_count', title: __('Sold_count')},
                        {field: 'review_count', title: __('Review_count')},
                        {field: 'home_recommend', title: __('首页推荐'), formatter: Table.api.formatter.toggle},
                        {field: 'category_recommend', title: __('栏目推荐'), formatter: Table.api.formatter.toggle},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'category.name', title: __('Xshopcategory.name')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            var vm = new Vue({
                el: '#app',
                data() {
                    return {
                        attrGroups: [],
                        attrItems: [],
                        productSkus: []
                    }
                },
                computed: {
                    productSkusStr() {
                        return JSON.stringify(this.productSkus)
                    }
                },
                mounted() {
                    
                },
                methods: {
                    setAttrGroupName(i, e) {
                        this.$set(this.attrGroups, i, e.target.value)
                    },
                    addRow() {
                        var val = this.$refs['attr-group-name'].value
                        if ($.trim(val) == '') {
                            Toastr.error("不能为空")
                            return
                        }
                        if (val.indexOf(',') > -1) {
                            Toastr.error("规格子项 " + val + " 不能包含符号‘,’")
                            return
                        }
                        this.attrGroups.push(val)
                        this.$refs['attr-group-name'].value = ''
                    },
                    addChild(index) {
                        var val = this.$refs['attr-item-name-' + index][0].value
                        if ($.trim(val) == '') {
                            Toastr.error("不能为空")
                            return
                        }
                        if (val.indexOf(',') > -1) {
                            Toastr.error("规格 " + val + " 不能包含符号‘,’")
                            return
                        }
                        if (!val) return false
                        if (this.attrItems[index]) {
                            this.attrItems[index].push(val)
                        } else {
                            this.attrItems.push([val])
                        }
                        this.$refs['attr-item-name-' + index][0].value = ""
                        this.generateProducts()
                    },
                    removeChild(i, j) {
                        this.attrItems[i].pop(j)
                        this.generateProducts()
                    },
                    removeGroup(i) {
                        this.attrGroups.pop(i)
                        if (this.attrItems[i])
                            this.attrItems.pop(i)
                        this.generateProducts()
                    },
                    generateProducts() {
                        this.productSkus = this.descartes(this.attrItems)
                    },
                    descartes(array){
                        if (array.length == 0) return []
                        if( array.length < 2 ) {
                            var res = []
                            array[0].forEach(function(v) {
                                res.push([v])
                            })
                            return res
                        }
                        return [].reduce.call(array, function(col, set) {
                            var res = [];
                            col.forEach(function(c) {
                                set.forEach(function(s) {
                                    var t = [].concat( Array.isArray(c) ? c : [c] );
                                    t.push(s);
                                    res.push(t);
                            })});
                            return res;
                        });
                    }
                }
            })
            Controller.api.bindevent();
            window.batchSetAttrs = function(field) {
                $('.attr-' + field).val($('#batch-' + field).val())
            }
        },
        edit: function () {
            Controller.api.bindevent();
            var vm = new Vue({
                el: '#app',
                data() {
                    return {
                        attrGroups: [],
                        attrItems: [],
                        productSkus: [],
                        skus: []
                    }
                },
                computed: {
                    productSkusStr() {
                        return JSON.stringify(this.productSkus)
                    }
                },
                mounted() {
                    skus = JSON.parse(skus)
                    if (skus.length) {
                        this.skus = skus
                        this.attrGroups = this.skus[0].keys.split(',')
                        this.attrItems = JSON.parse(attrItems);
                        this.generateProducts()
                    }
                },
                methods: {
                    setAttrGroupName(i, e) {
                        this.$set(this.attrGroups, i, e.target.value)
                    },
                    addRow() {
                        var val = this.$refs['attr-group-name'].value
                        if ($.trim(val) == '') {
                            Toastr.error("不能为空")
                            return
                        }
                        if (val.indexOf(',') > -1) {
                            Toastr.error("规格 " + val + " 不能包含符号‘,’")
                            return
                        }
                        this.attrGroups.push(val)
                        this.$refs['attr-group-name'].value = ''
                    },
                    addChild(index) {
                        var val = this.$refs['attr-item-name-' + index][0].value
                        if ($.trim(val) == '') {
                            Toastr.error("不能为空")
                            return
                        }
                        if (val.indexOf(',') > -1) {
                            Toastr.error("规格子项 " + val + " 不能包含符号‘,’")
                            return
                        }
                        if (!val) return false
                        if (this.attrItems[index]) {
                            this.attrItems[index].push(val)
                        } else {
                            this.attrItems.push([val])
                        }
                        this.$refs['attr-item-name-' + index][0].value = ""
                        this.generateProducts()
                    },
                    removeChild(i, j) {
                        this.attrItems[i].pop(j)
                        this.generateProducts()
                    },
                    removeGroup(i) {
                        this.attrGroups.pop(i)
                        this.attrItems.pop(i)
                        this.generateProducts()
                    },
                    batchSetAttr($type) {
                        for(var i = 0; i < this.skus.length; i ++) {
                            this.$set(this.skus[i], $type, this.$refs['input-' + $type].value)
                        }
                    },
                    generateProducts() {
                        this.productSkus = this.descartes(this.attrItems)
                    },
                    descartes(array){
                        if (array.length == 0) return []
                        if( array.length < 2 ) {
                            var res = []
                            array[0].forEach(function(v) {
                                res.push([v])
                            })
                            return res
                        }
                        return [].reduce.call(array, function(col, set) {
                            var res = [];
                            col.forEach(function(c) {
                                set.forEach(function(s) {
                                    var t = [].concat( Array.isArray(c) ? c : [c] );
                                    t.push(s);
                                    res.push(t);
                            })});
                            return res;
                        });
                    }
                }
            })
            window.batchSetAttr = function(field) {
                $('.attr-' + field).val($('#batch-' + field).val())
            }
        },
        select: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'xshop/product/index' + location.search,
                    add_url: 'xshop/product/add',
                    edit_url: 'xshop/product/edit',
                    del_url: 'xshop/product/del',
                    multi_url: 'xshop/product/multi',
                    table: 'xshop_product',
                }
            });

            var ids = Fast.api.query('ids')

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
                        {field: 'title', title: __('Title'), cellStyle: function() {return {css: {'max-width': '240px', display: 'block', overflow: 'hidden', 'text-overflow': 'ellipsis'}}}},
                        {field: 'description', title: __('Description'), visible: false},
                        {field: 'image', title: __('Image'), events: Table.api.events.images, formatter: Table.api.formatter.images},
                        {field: 'on_sale', title: __('On_sale'), formatter: Formatter.status, source: {0: {text: '下架', color: 'gray'}, 1: {text: '上架', color: 'green'}}},
                        {field: 'price', title: __('Price'), operate:'BETWEEN', visible: false},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'category.name', title: __('Xshopcategory.name')},
                        {
                            field: 'operate', title: __('Operate'), events: {
                                'click .btn-chooseone': function (e, value, row, index) {
                                    var multiple = Backend.api.query('multiple');
                                    multiple = multiple == 'true' ? true : false;
                                    Fast.api.close({row: row, multiple: multiple});
                                },
                            }, formatter: function () {
                                return '<a href="javascript:;" class="btn btn-danger btn-chooseone btn-xs"><i class="fa fa-check"></i> ' + __('Choose') + '</a>';
                            }
                        }
                    ]
                ]
            });
            $(table).on('post-body.bs.table', function() {
                if (typeof ids == 'string') {
                    ids = ids.split(',')
                    $.each(ids, function(i, v) {
                        ids[i] = parseInt(v)
                    })
                }
                table.bootstrapTable('checkBy', {
                    field: 'id', values: ids
                })
            })

            // 选中多个
            $(document).on("click", ".btn-choose-multi", function () {
                var dataArr = new Array();
                $.each(table.bootstrapTable("getAllSelections"), function (i, j) {
                    dataArr.push(j.id);
                });
                var multiple = Backend.api.query('multiple');
                multiple = multiple == 'true' ? true : false;
                Fast.api.close({data: dataArr, multiple: multiple});
            });
            
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        show: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'xshop/product/index' + location.search,
                    add_url: 'xshop/product/add',
                    edit_url: 'xshop/product/edit',
                    del_url: 'xshop/product/del',
                    multi_url: 'xshop/product/multi',
                    table: 'xshop_product',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                templateView: true,
                // search: false,
                // commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title'), cellStyle: function() {return {css: {'max-width': '240px', display: 'block', overflow: 'hidden', 'text-overflow': 'ellipsis'}}}},
                        {field: 'description', title: __('Description'), visible: false},
                        {field: 'image', title: __('Image'), events: Table.api.events.images, formatter: Table.api.formatter.images},
                        {field: 'on_sale', title: __('On_sale'), formatter: Formatter.status, source: {0: {text: '下架', color: 'gray'}, 1: {text: '上架', color: 'green'}}},
                        {field: 'price', title: __('Price'), operate:'BETWEEN', visible: false},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'category.name', title: __('Xshopcategory.name')},
                        {
                            field: 'operate', title: __('Operate'), events: {
                                'click .btn-chooseone': function (e, value, row, index) {
                                    var multiple = Backend.api.query('multiple');
                                    multiple = multiple == 'true' ? true : false;
                                    Fast.api.close({row: row, multiple: multiple});
                                },
                            }, formatter: function () {
                                return '<a href="javascript:;" class="btn btn-danger btn-chooseone btn-xs"><i class="fa fa-check"></i> ' + __('Choose') + '</a>';
                            }
                        }
                    ]
                ]
            });
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});