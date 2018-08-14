define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template', 'upload'], function ($, undefined, Backend, Table, Form, Template, Upload) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'guide/manage/index',
                    add_url: 'guide/manage/add',
                    edit_url: 'guide/manage/edit',
                    del_url: 'guide/manage/del',
                    multi_url: 'guide/manage/multi',
                }
            });

            var table = $("#table");
            var searchList = {1: __('Common message type'), 2:__('Code invitations type'), 3:__('Graph and text reply type'), 4:__('File reply type'),5:__('Code invitations type')};
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
                        {field: 'id', title: 'ID'},
                        {field: 'title', title: __('Title')},
                        // {field: 'created_at', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        {field: 'created_at', title: __('Createtime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange'},

                        // {field: 'username', title: __('Username')},
                        // {field: 'nickname', title: __('Nickname')},
                        // {field: 'groups_text', title: __('Group'), operate:false, formatter: Table.api.formatter.label},
                        // {field: 'email', title: __('Email')},
                        // {field: 'status', title: __("Status"), formatter: Table.api.formatter.status},
                        // {field: 'logintime', title: __('Login time'), formatter: Table.api.formatter.datetime},
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
