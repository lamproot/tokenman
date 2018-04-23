define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template', 'upload'], function ($, undefined, Backend, Table, Form, Template, Upload) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'activity/codemanage/index?activity_id='+$_GET['activity_id'],
                    add_url: '',
                    edit_url: '',
                    del_url: '',
                    multi_url: 'activity/codemanage/multi',
                }
            });

            var table = $("#table");
            var statusList = {0: __('默认'), 1:__('已申请Code码'), 2:__('已加入群聊'), 3:__('已激活'),"-1":__('退出群聊')};
            //var statusList = {1: __('Common message type'), 2:__('Code invitations type'), 3:__('Graph and text reply type'), 4:__('File reply type'),5:__('Code invitations type')};

            //默认 0 1-已申请用户码 2-已加入群聊 3-已在群里确认用户码 -1-已退出群聊
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pageSize:100,
                pageList:[100,200,300,400,500],
                showExport:false,
                columns: [
                    [
                        //{field: 'state', checkbox: true, },
                        {field: 'id', title: 'ID', operate: false},
                        {field: 'from_id', title: __('from_id')},

                        {field: 'eth', title: __('eth')},
                        {field: 'code', title: __('code')},
                        {field: 'parent_code', title: __('parent_code')},
                        {field: 'status', title: __('status'), searchList: statusList, formatter: function (value, row, index) {
                            if (row.status && statusList[row.status]) {
                                return statusList[row.status];
                            }else{
                                return "";
                            }
                        }},
                        {field: 'from_username', title: __('from_username')},
                        {field: 'invited', title: __('invited')},
                        {field: 'first_name', title: __('first_name')},
                        {field: 'last_name', title: __('last_name')},
                        {field: 'created_at', title: __('Createtime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange'},
                        {field: 'operate', title: __('Operate'), table: table,
                            events: Table.api.events.operate,

                            //formatter: Table.api.formatter.operate
                            formatter: function (value, row, index) {
                              buttons: [{
                                    name: 'detail',
                                    text: __('Detail'),
                                    icon: 'fa fa-list',
                                    classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                    url: 'activity/codemanage/user?parent_code'
                                }]
                                //return '<a href="/admin/activity/codemanage/user?parent_code=' +row.code+ '">查看邀请用户</a>'
                            }
                        }
                        //{field: 'operate', title: __('Operate'), table: table,

                            // formatter: function (value, row, index) {
                            //     buttons: [{
                            //             name: 'detail',
                            //             text: __('Code User List'),
                            //             icon: 'fa fa-list',
                            //             classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                            //             url: 'activity/codemanage/user',
                            //             // url:formatter:function(value, row, index){
                            //             //     return "user?parent_code="+index
                            //             // },
                            //             // method:'get'
                            //         }]
                            // }
                      //  }
                    ]
                ],
                search: false
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

        },
        user: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'activity/codemanage/user',
                    add_url: '',
                    edit_url: '',
                    del_url: '',
                    multi_url: '',
                }
            });

            var table = $("#table");
            var statusList = {0: __('默认'), 1:__('已申请Code码'), 2:__('已加入群聊'), 3:__('已激活'),"-1":__('退出群聊')};
            //var statusList = {1: __('Common message type'), 2:__('Code invitations type'), 3:__('Graph and text reply type'), 4:__('File reply type'),5:__('Code invitations type')};

            //默认 0 1-已申请用户码 2-已加入群聊 3-已在群里确认用户码 -1-已退出群聊
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pageSize:100,
                pageList:[100,200,300,400,500],
                showExport:false,
                columns: [
                    [
                        //{field: 'state', checkbox: true, },
                        {field: 'id', title: 'ID', operate: false},
                        {field: 'from_id', title: __('from_id')},
                        {field: 'eth', title: __('eth')},
                        {field: 'code', title: __('code')},
                        {field: 'parent_code', title: __('parent_code')},
                        {field: 'status', title: __('status'), searchList: statusList, formatter: function (value, row, index) {
                            if (row.status && statusList[row.status]) {
                                return statusList[row.status];
                            }else{
                                return "";
                            }
                        }},
                        {field: 'from_username', title: __('from_username')},
                        {field: 'first_name', title: __('first_name')},
                        {field: 'last_name', title: __('last_name')},
                        // {field: 'created_at', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        {field: 'created_at', title: __('Createtime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange'},

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
        },
        api: {
            formatter: {
                // thumb: function (value, row, index) {
                //     if (row.logo) {
                //         var style = row.storage == 'upyun' ? '!/fwfh/120x90' : '';
                //         return '<a href="' + row.logo + '" target="_blank"><img src="' + row.logo + style + '" alt="" style="max-height:90px;max-width:120px"></a>';
                //     } else {
                //         return '<a href="' + row.logo + '" target="_blank">' + __('None') + '</a>';
                //     }
                // },
                activity_user: function (value, row, index) {
                    if (row.type && row.type == 0) {
                        return '<a href="activity/codemanage/user?activity_id=' +row.id+ '">查看活动用户</a>';
                    } else {
                        return '<a href="activity/codemanage/user?activity_id=' +row.id+ '">查看活动用户</a>';
                    }
                }
            }
        }
    };
    return Controller;
});

var $_GET = (function(){
    var url = window.document.location.href.toString();
    var u = url.split("?");
    if(typeof(u[1]) == "string"){
        u = u[1].split("&");
        var get = {};
        for(var i in u){
            var j = u[i].split("=");
            get[j[0]] = j[1];
        }
        return get;
    } else {
        return {};
    }
})();
