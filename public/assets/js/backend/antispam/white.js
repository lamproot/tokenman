define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template', 'upload'], function ($, undefined, Backend, Table, Form, Template, Upload) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'antispam/white/index',
                    add_url: 'group/admin/index',
                    edit_url: '',
                    del_url: 'antispam/white/del',
                    multi_url: 'antispam/white/multi',
                }
            });

            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [
                        //{field: 'state', checkbox: true, },
                        {field: 'id', title: 'ID'},
                        {field: 'from_id', title: __('用户ID')},
                        {field: 'from_username', title: __('用户名称')},
                        // {field: 'created_at', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        {field: 'created_at', title: __('Createtime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: function (value, row, index) {
                            // if(row.id == Config.admin.id){
                            //     return '';
                            // }
                            return Table.api.formatter.operate.call(this, value, row, index);
                        }}
                    ]
                ],
                search: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);


            //关键词管理
            var keyword_demo = function (ignoreversion, tips) {
                $.ajax({
                    url: 'antispam/white/mofa',
                    type: 'post',
                    data: {},
                    dataType: 'json',
                    success: function (ret) {
                        Layer.open({
                            title: __('Mofa'),
                            content:'<p>请私信机器人魔法命令即可获得白名单权限或前往群助手->管理员管理->添加至白名单'+
                                        '<br/>'+ ret.ret.mofa +
                                    '</p>',
                            area: ['500px', '500px']
                        });
                    }, error: function (e) {
                        if (tips) {
                            Toastr.error("发生未知错误:" + e.message);
                        }
                    }
                });
            };
            //
            $("a[data-toggle='keyword_demo']").on('click', function () {
                keyword_demo('', true);
            });

            // $(".selectpicker").on('change', function () {
            //     alert("1212")
            // });

            // $(".selectpicker").change(function () {
            //       alert("asdasd")
            // });

            // $(".selectpicke").change(function () {
            //     alert("sasa")
            // });

        },
        add: function (form) {
            Form.api.bindevent($("form[role=form]"));
        },
        edit: function (form) {
            Form.api.bindevent($("form[role=form]"));
        }
    };
    return Controller;
});
