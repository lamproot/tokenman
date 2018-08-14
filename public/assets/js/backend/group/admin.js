define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template', 'upload'], function ($, undefined, Backend, Table, Form, Template, Upload) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'group/admin/index',
                    add_url: '',
                    edit_url: '',
                    del_url: '',
                    multi_url: '',
                }
            });

            var table = $("#table");
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
showToggle:false,
                showColumns:false,
                showExport:false,
                showSearchButton:false,
                columns: [
                    [
                        //{field: 'state', checkbox: true, },
                        {field: 'user.id', title: 'ID'},
                        {field: 'user.username', title: __('用户名')},
                        {field: 'user.first_name', title: __('姓名')},
                        {field: 'user.is_bot', title: __('是否机器人')},
                        {field: 'status', title: __('权限')},
                        {field: 'operate', title: __('Operate'), events: {
                            'click .btn-chooseone': function (e, value, row, index) {
                                // var multiple = Backend.api.query('multiple');
                                // multiple = multiple == 'true' ? true : false;
                                // Fast.api.close({url: row.url, multiple: false});
                                //alert(index)

                                $.ajax({
                                    url: 'antispam/white/add',
                                    data:{"from_id":row.user.id, "from_username":row.user.username, "type":1},
                                    type: 'post',
                                    dataType: 'json',
                                    success: function (ret) {
                                        //$("#table").bootstrapTable('refresh');
                                        Fast.api.close();
                                        alert("已添加至白名单")
                                        //this.window.
                                    }
                                });
                            },
                        }, formatter: function (value, row, index) {
                            if (!row.whitestatus) {
                                return '<a href="javascript:;" class="btn btn-danger btn-chooseone btn-xs"><i class="fa fa-check"></i> ' + __('AddWhite') + '</a>';
                            }else{
                                return '已添加至白名单';
                            }
                        }}
                    ]
                ],
                search: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

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
