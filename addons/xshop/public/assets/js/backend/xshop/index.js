requirejs.config({
    paths: {
        vue: 'backend/xshop/libs/vue',
        ELEMENT: 'backend/xshop/libs/element-ui',
    },
    shim: {
        ELEMENT: ['vue']
    }
})
define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'jstree', 'vue', 'ELEMENT', 'echarts', 'echarts-theme'], function ($, undefined, Backend, Table, Form, jstree, Vue, ELEMENT, Echarts, undefined) {

    var Controller = {
        index: function() {
            var myChart = Echarts.init(document.getElementById('echart'), 'walden');
            var option = {
                
            }
            myChart.setOption(option);
            $(window).resize(function () {
                myChart.resize();
            });
            Controller.api.bindevent()
        },
        cityselector: function() {
            Vue.use(ELEMENT)
            var vm = new Vue({
                el: '#app',
                data() {
                    return {
                        data: [],
                        selections: [],
                        checkedKeys: [],
                        loading: true
                    }
                },
                created() {
                    var ids = Fast.api.query('ids')
                    if (ids) this.checkedKeys = ids.split(',')
                    this.loadData()
                },
                methods: {
                    loadData() {
                        var l = this.$loading()
                        this.loading = false
                        $.ajax({
                            url: 'xshop/index/citySelector',
                            success: function(res) {
                                l.close()
                                vm.loading = false
                                vm.data = res.data
                            }
                        })
                    },
                    change(data, rows) {
                        this.selections = rows
                    },
                    submit() {
                        Fast.api.close(this.selections)
                    }
                }
            })
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    }
    return Controller
})