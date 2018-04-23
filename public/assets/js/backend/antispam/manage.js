define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template', 'upload'], function ($, undefined, Backend, Table, Form, Template, Upload) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'antispam/manage/index',
                    add_url: 'antispam/manage/add',
                    edit_url: 'antispam/manage/edit',
                    del_url: 'antispam/manage/del',
                    multi_url: 'antispam/manage/multi',
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
                        {field: 'word', title: __('屏蔽关键词')},
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


            //关键词管理
            var keyword_demo = function (ignoreversion, tips) {
                $.ajax({
                    url: Config.fastadmin.api_url + '/version/check',
                    type: 'post',
                    data: {version: Config.fastadmin.version},
                    dataType: 'jsonp',
                    success: function (ret) {
                        Layer.open({
                            title: '设置参考',
                            content: '<p style="margin-bottom: 20px; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; font-size: 14px; line-height: 32px; font-family: &quot;Microsoft YaHei&quot;, simsun, &quot;Helvetica Neue&quot;, Arial, Helvetica, sans-serif; vertical-align: baseline; color: rgb(102, 102, 102); white-space: normal; background-color: rgb(250, 251, 255);">'+
                                        '<img style="width:100%" src="http://kol-statics.oss-cn-beijing.aliyuncs.com/editor/1651453aa542c932f1041a70bdf833b4fb5c83.png" title="" alt=""/>满足“五零”条件(零编辑、零技术、零体制、零成本、零形式)而实现的“零进入壁垒”的网上个人出版方式，从媒体价值链最重要的三个环节：作者、内容和读者三大层次，实现了“源代码的开放”。并同时在道德规范、运作机制和经济规律等层次，将逐步完成体制层面的真正开放，使未来媒体世界完成从大教堂模式到集市模式的根本转变。<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;博客的出现集中体现了互联网时代媒体界所体现的商业化垄断与非商业化自由，大众化传播与个性化(分众化，小众化)表达，单向传播与双向传播3个基本矛盾、方向和互动。这几个矛盾因为博客引发的开放源代码运动，至少在技术层面上得到了根本的解决。</p>'+
                                    '<p style="margin-bottom: 20px; border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; font-size: 14px; line-height: 32px; font-family: &quot;Microsoft YaHei&quot;, simsun, &quot;Helvetica Neue&quot;, Arial, Helvetica, sans-serif; vertical-align: baseline; color: rgb(102, 102, 102); white-space: normal; background-color: rgb(250, 251, 255);">'+
                                        '<img style="width:100%"  src="https://demo.fastadmin.net/assets/addons/blog/img/thumb.jpg" alt="" draggable="true" duitang_draggable="1" style="border: 0px; vertical-align: bottom; margin-top: 20px; margin-bottom: 20px;"/>'+
                                    '</p>'+
                                    '<p>'+
                                        '<br/>'+
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
