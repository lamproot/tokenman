define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template', 'upload'], function ($, undefined, Backend, Table, Form, Template, Upload) {

    var Controller = {
        manage: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'group/user/manage',
                    add_url: 'group/user/add',
                    // edit_url: 'group/user/edit',
                    del_url: 'group/user/del',
                    multi_url: '',
                }
            });

            var table = $("#table");

            var typeList = {0: __(''), 1:__('Join'), 2:__('Out'), "-1":__('Blank')};
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url+"?ids="+Searchdata['ids'],
                columns: [
                    [
                        {field: 'state', checkbox: true, },
                        {field: 'id', title: 'ID',},
                        // {field: 'chat_bot_id', title: __('chat_bot_id')},
                        // {field: 'chat_id', title: __('chat_id')},
                        //{field: 'type', title: __('type')},

                        // {field: 'from_id', title: __('from_id')},
                        {field: 'from_username', title: __('from_username')},
                        {field: 'first_name', title: __('first_name')},
                        {field: 'last_name', title: __('last_name')},
                        {field: 'type', title: __('type'), typeList: typeList, formatter: function (value, row, index) {
                            if (typeList[row.type]) {
                                //row.type = row.type ? row.type : 0;
                                return typeList[row.type];
                            }else{
                                return "";
                            }
                        },operate:false},
                        {field: 'created_at', title: __('Createtime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange'},

                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: function (value, row, index) {

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
                Layer.open({
                    title: __('Activate Bot'),
                    content:'<p>激活方式 <br/> 1.确认添加机器人为群管理员<br/> 2.发送激活机器人命令 /activatebot'+
                                '<br/>'+
                            '</p>',
                    area: ['500px', '500px']
                });
            };
            //
            //
            // $("a[data-toggle='keyword_demo']").on('click', function () {
            //     keyword_demo('', true);
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
