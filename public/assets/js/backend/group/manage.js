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
                            buttons: [
                            {
                                name: 'detail',
                                text: __('Data Detail'),
                                icon: 'fa fa-list',
                                classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                url: 'group/manage/detail'
                            },{
                                name: 'detail',
                                text: __('Group User Data'),
                                icon: 'fa fa-list',
                                classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                url: 'group/user/manage'
                            },{
                                name: 'detail',
                                text: __('Group Bot Config'),
                                icon: 'fa fa-list',
                                classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                url: 'group/manage/config'
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
        },
        detail: function (form) {
            Form.api.bindevent($("form[role=form]"));
        },
        config: function () {

            //================= 封禁功能 ==================
            $(".set_ban_time_button").click(function(){
                var that = this;
                var banTime = $(this).parent().prev().val();
                //设置封禁用户时长Ajax
                $.ajax({
                    url: 'group/manage/botconfig',
                    type: 'post',
                    dataType: 'json',
                    data:{
                        "rule":"set_ban_time",
                        "value":banTime,
                        "chat_bot_id":getrow['chat_bot_id'],
                        "chat_id":getrow['chat_id']
                    },
                    success: function (ret) {

                        if (ret.code === 0) {
                            Toastr.success("设置成功");
                        }else{
                            Toastr.success("设置失败");
                        }

                    }
                });

            });


            //清空时间 初始化
            $(".clear_all_news_time_stop").click(function(){
                $(".clear_all_news_time_count").attr("data-count", 0);
                $(".clear_all_news_time_count").html("");
                $(this).parent().parent().parent().addClass("hide");
                $(this).parent().parent().parent().next().removeClass("hide").addClass("show");
                $.ajax({
                    url: 'group/manage/botconfig',
                    type: 'post',
                    dataType: 'json',
                    data:{
                        "rule":"clear_all_news_time",
                        "value":0,
                        "chat_bot_id":getrow['chat_bot_id'],
                        "chat_id":getrow['chat_id']
                    },
                    success: function (ret) {
                        if (ret.code === 0) {
                            Toastr.success("设置成功");
                        }else{
                            Toastr.success("设置失败");
                        }

                    }
                });
            });



            $(".ban_words_add").click(function(){
                var word = $(this).parent().prev().val();
                if (word == "") {
                    alert("Please enter")
                    return false;
                }

                //添加封禁敏感词Ajax

                var appendHtml = '<li class="selected_tag" itemvalue="'+word+'">'
                +word
                +'<span class="tag_close"><i class="spfont sp-close ban_words_del"></i></span>'
                +'</li>';
                $(this).parent().parent().parent().prev().children('ul').append(appendHtml);
                banWordTagClose();

                var banWord = [];
                $(this).parent().parent().parent().prev().children('ul').children('li').each(function(){
                    banWord.push($(this).attr("itemvalue"));
                })

                updateBotConfigData("set_ban_words", banWord);

            });

            function banWordTagClose(){
                $(".ban_words_del").unbind('click').click(function(){
                    var that = this;
                    //删除封禁敏感词Ajax
                    var banWord = [];
                    $(this).parent().parent().siblings('li').each(function(){
                        banWord.push($(this).attr("itemvalue"));
                    })

                    updateBotConfigData("set_ban_words", banWord);
                    $(this).parent().parent().remove();
                });
            }

            banWordTagClose();

            function updateBotConfigData(rule, data){
                $.ajax({
                    url: 'group/manage/botconfig',
                    type: 'post',
                    dataType: 'json',
                    data:{
                        "rule":rule,
                        "value":"",
                        "data":data.join(),
                        "chat_bot_id":getrow['chat_bot_id'],
                        "chat_id":getrow['chat_id']
                    },
                    success: function (ret) {
                        if (ret.code === 0) {
                            Toastr.success("更新成功");
                        }else{
                            Toastr.success("更新失败");
                        }

                    }
                });
            }
            //================= 封禁功能 ==================

            //================= 全体禁言模式 ===============
            //禁言时间设置
            $(".clear_all_news_time_button").click(function(){
                //禁言时间设置
                var time = $(this).parent().prev().val();

                if (parseInt(time) === 0) {
                    Toastr.error("请选择开启禁言时长");
                    return false;
                }
                var that = this;
                //设置封禁用户时长Ajax
                $.ajax({
                    url: 'group/manage/botconfig',
                    type: 'post',
                    dataType: 'json',
                    data:{
                        "rule":"clear_all_news_time",
                        "value":time,
                        "chat_bot_id":getrow['chat_bot_id'],
                        "chat_id":getrow['chat_id']
                    },
                    success: function (ret) {
                        if (ret.code === 0) {
                            Toastr.success("设置成功");
                            $(".clear_all_news_time_count").attr("data-count", time);
                            countTime();
                            $(that).parent().parent().parent().parent().addClass("hide");
                            $(that).parent().parent().parent().parent().prev().removeClass("hide").addClass("show");
                        }else{
                            Toastr.success("设置失败");
                        }

                    }
                });
            });

            //禁言关键词设置
            $(".clear_all_news_white_button").click(function(){
                var word = $(this).parent().prev().val();
                if (word == "") {
                    alert("Please enter")
                    return false;
                }

                //添加封禁敏感词Ajax

                var appendHtml = '<li class="selected_tag" itemvalue="'+word+'">'
                +word
                +'<span class="tag_close"><i class="spfont sp-close clear_all_news_white_del"></i></span>'
                +'</li>';
                $(this).parent().parent().parent().prev().children('ul').append(appendHtml);
                newsWhiteDel()

                var banWord = [];
                $(this).parent().parent().parent().prev().children('ul').children('li').each(function(){
                    banWord.push($(this).attr("itemvalue"));
                })

                updateBotConfigData("clear_all_news_white", banWord);
            });

            function newsWhiteDel(){
                $(".clear_all_news_white_del").unbind('click').click(function(){
                    var that = this;
                    //删除封禁敏感词Ajax
                    var banWord = [];
                    $(this).parent().parent().siblings('li').each(function(){
                        banWord.push($(this).attr("itemvalue"));
                    })

                    updateBotConfigData("clear_all_news_white", banWord);
                    $(this).parent().parent().remove();
                });
            }

            newsWhiteDel();

            //禁言包含关键词设置
            $(".clear_all_news_reg_white_button").click(function(){
                var word = $(this).parent().prev().val();
                if (word == "") {
                    alert("Please enter")
                    return false;
                }

                //添加封禁敏感词Ajax

                var appendHtml = '<li class="selected_tag" itemvalue="'+word+'">'
                +word
                +'<span class="tag_close"><i class="spfont sp-close clear_all_news_reg_white_del"></i></span>'
                +'</li>';
                $(this).parent().parent().parent().prev().children('ul').append(appendHtml);
                newsRegWhiteDel()

                var banWord = [];
                $(this).parent().parent().parent().prev().children('ul').children('li').each(function(){
                    banWord.push($(this).attr("itemvalue"));
                })

                updateBotConfigData("clear_all_news_reg_white", banWord);
            });

            newsRegWhiteDel();

            function newsRegWhiteDel(){
                $(".clear_all_news_reg_white_del").unbind('click').click(function(){
                    var that = this;
                    //删除封禁敏感词Ajax
                    var banWord = [];
                    $(this).parent().parent().siblings('li').each(function(){
                        banWord.push($(this).attr("itemvalue"));
                    })

                    updateBotConfigData("clear_all_news_reg_white", banWord);
                    $(this).parent().parent().remove();
                });
            }
            newsRegWhiteDel();
            //================= 全体禁言模式 ===============




            $(".btn-switch").click(function(){
                //alert($(this).attr("data-switch-value"))
                var switchName = $(this).attr("data-switch-name");
                var switchValue = $(this).attr("data-switch-value") ? parseInt($(this).attr("data-switch-value")) : 0;
                var switchHtml = $(this).parent().parent().children('h3').html();

                if (switchValue == undefined || switchValue === 0) {
                    //判断相关功能逻辑

                    //根据关键词自动应答
                    if (switchName == "is_keyword_cmd") {
                        //$(this).parent().parent().next().next().show();
                        $(this).parent().parent().next().next().removeClass("hide").addClass("show");
                    }

                    //根据敏感词封禁成员
                    if (switchName == "is_words_ban") {
                        //$(this).parent().parent().next().next().show();
                        $(this).parent().parent().next().next().removeClass("hide").addClass("show");
                    }

                    //全体禁言模式
                    if (switchName == "is_clear_all_news") {
                        //$(this).parent().parent().next().next().show();
                        $(this).parent().parent().next().next().removeClass("hide").addClass("show");
                    }

                    var that = this;
                    //打开
                    $.ajax({
                        url: 'group/manage/botconfig',
                        type: 'post',
                        dataType: 'json',
                        data:{
                        	"rule":switchName,
                        	"value":1,
                        	"chat_bot_id":getrow['chat_bot_id'],

                            "chat_id":getrow['chat_id']
                        },
                        success: function (ret) {

                            if (ret.code === 0) {
                                $(that).attr("data-switch-value", 1);
                                $(that).children('i').removeClass("fa-toggle-off").addClass("fa-toggle-on");
                                Toastr.success("成功开启 "+ switchHtml + " 功能");
                            }else{
                                Toastr.success("开启 "+ switchHtml + " 功能失败");
                            }

                        }
                    });

                }else{
                    //判断相关功能逻辑
                    var that = this;
                    //根据关键词自动应答
                    if (switchName == "is_keyword_cmd") {
                        $(this).parent().parent().next().next().removeClass("show").addClass("hide");
                    }

                    //根据敏感词封禁成员
                    if (switchName == "is_words_ban") {
                        $(this).parent().parent().next().next().removeClass("show").addClass("hide");
                    }

                    //全体禁言模式
                    if (switchName == "is_clear_all_news") {
                        //关闭禁言模式
                        var dataCount = $(".clear_all_news_time_count").attr("data-count");

                        if (dataCount > 0) {
                            Toastr.error("请立即停止禁言时间设置");
                            return false;
                        }

                        $(this).parent().parent().next().next().removeClass("show").addClass("hide");

                    }

                    $.ajax({
                        url: 'group/manage/botconfig',
                        type: 'post',
                        dataType: 'json',
                        data:{
                        	"rule":switchName,
                        	"value":0,
                        	"chat_bot_id":getrow['chat_bot_id'],
                            "chat_id":getrow['chat_id']
                        },
                        success: function (ret) {

                            if (ret.code === 0) {
                                //关闭
                                $(that).attr("data-switch-value", 0);
                                $(that).children('i').removeClass("fa-toggle-on").addClass("fa-toggle-off");
                                Toastr.success("成功关闭 "+ switchHtml + " 功能");

                            }else{
                                Toastr.success("关闭 "+ switchHtml + " 功能失败");
                            }

                        }
                    });
                }


                // data-switch-name
                // data-switch-value
            });
            // $('.btn-switch').on('click', function (e) {
            //     // var options = table.bootstrapTable(tableOptions);
            //     // var typeStr = $(this).attr("href").replace('#','');
            //     // var options = table.bootstrapTable('getOptions');
            //     // options.pageNumber = 1;
            //     // options.queryParams = function (params) {
            //     //     // params.filter = JSON.stringify({type: typeStr});
            //     //     params.type = typeStr;
            //     //
            //     //     return params;
            //     // };
            //     // table.bootstrapTable('refresh', {});
            //     // return false;
            //     alert("dadas")
            //
            // });

            $(".keyword_cmd_add").unbind('click').click(function(){
                $(".keyword_cmd_add_list").append(Template("keyword_cmd_add_tpl"));
                keyword_cmd_add_list_keword_add();
            });

            function keyword_cmd_add_list_keword_add(){
                $(".keyword_cmd_add_list_keword_add").unbind('click').click(function(){
                    var word = $(this).parent().prev().val();
                    if (word == "") {
                        alert("Please enter")
                        return false;
                    }
                    $(this).parent().parent().parent().prev().children('ul').append(Template("keyword_cmd_add_list_keword_add_tpl",{tag:word}));

                    keyword_cmd_add_list_keword_del();
                });
            }
            keyword_cmd_add_list_keword_add();

            function keyword_cmd_add_list_keword_del(){
                $(".keyword_cmd_add_list_keword_del").unbind('click').click(function(){
                    $(this).parent().parent().remove();
                });
            }
            keyword_cmd_add_list_keword_del();

            function keyword_cmd_add_list_save(){
                $(".keyword_cmd_add_list_save").unbind('click').click(function(){
                    alert($(this).parent().parent().parent().parent().html())
                });
            }
            keyword_cmd_add_list_save();

            countTime();
            function countTime() {
                //获取当前时间
                // var date = new Date();
                // var now = date.getTime();
                // //设置截止时间
                // var endDate = new Date("2016-10-22 23:23:23");
                // var end = endDate.getTime();
                //时间差
                var leftTime = $(".clear_all_news_time_count").attr("data-count");

                //定义变量 d,h,m,s保存倒计时的时间
                var d,h,m,s;
                if (leftTime>=0) {
                    d = Math.floor(leftTime/60/60/24);
                    h = Math.floor(leftTime/60/60%24);
                    m = Math.floor(leftTime/60%60);
                    s = Math.floor(leftTime%60);

                    var html = "";
                    if (d) {
                        html = html + d + "D-";
                    }

                    if (h) {
                        html = html + h + "H-";
                    }

                    if (m) {
                        html = html + m + "M-";
                    }

                    if (s) {
                        html = html + s+ "S";
                    }

                    $(".clear_all_news_time_count").html(html);
                    $(".clear_all_news_time_count").attr("data-count", leftTime-1)
                    //递归每秒调用countTime方法，显示动态时间效果
                    setTimeout(countTime,1000);
                }

                //alert(s)
                //将倒计时赋值到div中
                // document.getElementById("_d").innerHTML = d+"天";
                // document.getElementById("_h").innerHTML = h+"时";
                // document.getElementById("_m").innerHTML = m+"分";
                // document.getElementById("_s").innerHTML = s+"秒";

            }


        }
    };
    return Controller;
});
