define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template', 'upload'], function ($, undefined, Backend, Table, Form, Template, Upload) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'group/manage/index',
                    add_url: 'group/manage/add',
                    edit_url: 'group/manage/edit',
                    del_url: 'group/manage/del',
                    multi_url: '',
                }
            });

            var table = $("#table");

            var searchList = {0: __('Not Activate'), 1:__('Is Activate')};
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [
                        //{field: 'state', checkbox: true, },
                        {field: 'id', title: 'ID'},
                        {field: 'title', title: __('群名称')},
                        {field: 'chat_id', title: __('chat_id')},
                        {field: 'chat_bot_id', title: __('机器人ID')},
                        // {field: 'status', title: __('Status'), searchList: searchList, formatter: function (value, row, index) {
                        //     if (searchList[row.status] && row.status !== 0) {
                        //       //row.type = row.type ? row.type : 0;
                        //       return searchList[row.status];
                        //     }else{
                        //       return '<a href="javascript:;" class="btn btn-info btn-xs sidebar-toggle btn-demo" data-toggle="keyword_demo">'+__('Not Activate')+'</a>';
                        //     }
                        // }},
                        {field: 'status', title: __('Status'), events: {
                            'click .btn-activity': function (e, value, row, index) {
                                keyword_demo('', true);
                            },
                        }, formatter: function (value, row, index) {
                                if (searchList[row.status] && row.status !== 0) {
                                  //row.type = row.type ? row.type : 0;
                                  return searchList[row.status];
                                }else{
                                  return '<a href="javascript:;" class="btn btn-info btn-xs sidebar-toggle btn-activity" >'+__('Not Activate')+'</a>';
                                }
                        }},
                        // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: function (value, row, index) {
                        //     return Table.api.formatter.operate.call(this, value, row, index);
                        // }}
                        {field: 'operate', title: __('Operate'), table: table,
                            events: Table.api.events.operate,
                            buttons: [{
                                    name: 'detail',
                                    text: __('Data Detail'),
                                    icon: 'fa fa-list',
                                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                    url: 'group/manage/detail'
                                }],
                            formatter: Table.api.formatter.operate
                        }
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
