define(['jquery', 'bootstrap', 'backend', 'addtabs', 'table', 'echarts', 'echarts-theme', 'template'], function ($, undefined, Backend, Datatable, Table, Echarts, undefined, Template) {

    var Controller = {
        index: function () {
            // 基于准备好的dom，初始化echarts实例
            var myChart = Echarts.init(document.getElementById('echart'), 'walden');

            // 指定图表的配置项和数据
            var option = {
                title: {
                    text: '',
                    subtext: ''
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: [__('Sales'), __('Orders')]
                },
                toolbox: {
                    show: false,
                    feature: {
                        magicType: {show: true, type: ['stack', 'tiled']},
                        saveAsImage: {show: true}
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: Orderdata.column
                },
                yAxis: {
                    type: 'value'
                },
                grid: [{
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                }],
                series: [{
                        name: __('Sales'),
                        type: 'line',
                        // smooth: true,
                        // areaStyle: {
                        //     normal: {
                        //     }
                        // },
                        // lineStyle: {
                        //     normal: {
                        //         width: 1.5
                        //     }
                        // },
                        data: Orderdata.paydata
                    },
                    {
                        name: __('Orders'),
                        type: 'line',
                        // smooth: true,
                        // areaStyle: {
                        //     normal: {
                        //     }
                        // },
                        // lineStyle: {
                        //     normal: {
                        //         width: 1.5
                        //     }
                        // },
                        itemStyle : {
                               normal : {
                                   lineStyle:{
                                       color:'#e74c3c'
                                   }
                               }
                           },

                        data: Orderdata.createdata
                    }]
            };

            // 使用刚指定的配置项和数据显示图表。
            myChart.setOption(option);

            //动态添加数据，可以通过Ajax获取数据然后填充
            // setInterval(function () {
            //     Orderdata.column.push((new Date()).toLocaleTimeString().replace(/^\D*/, ''));
            //     var amount = Math.floor(Math.random() * 200) + 20;
            //     Orderdata.createdata.push(amount);
            //     Orderdata.paydata.push(Math.floor(Math.random() * amount) + 1);
            //
            //     //按自己需求可以取消这个限制
            //     if (Orderdata.column.length >= 20) {
            //         //移除最开始的一条数据
            //         Orderdata.column.shift();
            //         Orderdata.paydata.shift();
            //         Orderdata.createdata.shift();
            //     }
            //     myChart.setOption({
            //         xAxis: {
            //             data: Orderdata.column
            //         },
            //         series: [{
            //                 name: __('Sales'),
            //                 data: Orderdata.paydata
            //             },
            //             {
            //                 name: __('Orders'),
            //                 data: Orderdata.createdata
            //             }]
            //     });
            // }, 2000);
            $(window).resize(function () {
                myChart.resize();
            });

            //读取TokenMan的更新信息
            // $.ajax({
            //     url: Config.fastadmin.api_url + '/news/index',
            //     type: 'post',
            //     dataType: 'jsonp',
            //     success: function (ret) {
            //         $("#news-list").html(Template("newstpl", {news: ret.newslist}));
            //     }
            // });

            $.ajax({
                url: 'guide/guide/index',
                type: 'post',
                dataType: 'json',
                success: function (ret) {
                    $("#news-list").html(Template("newstpl", {news: ret.rows}));
                }
            });
            // $.ajax({
            //     url: Config.fastadmin.api_url + '/forum/discussion',
            //     type: 'post',
            //     dataType: 'jsonp',
            //     success: function (ret) {
            //         $("#discussion-list").html(Template("discussiontpl", {news: ret.discussionlist.slice(0,6)}));
            //     }
            // });
        },
        test: function () {

            //================= 封禁功能 ==================
            $(".set_ban_time_button").click(function(){
                var banTime = $(this).parent().prev().val();
                //设置封禁用户时长Ajax
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
            });

            function banWordTagClose(){
                $(".ban_words_del").unbind('click').click(function(){
                    $(this).parent().parent().remove();
                    //删除封禁敏感词Ajax
                });
            }

            banWordTagClose();
            //================= 封禁功能 ==================

            //================= 全体禁言模式 ===============
            //禁言时间设置
            $(".clear_all_news_time_button").click(function(){
                var time = $(this).parent().prev().val();
                //alert(time)
                //封禁时间设置时长Ajax
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
                +'<span class="tag_close"><i class="spfont sp-close ban_words_del"></i></span>'
                +'</li>';
                $(this).parent().parent().parent().prev().children('ul').append(appendHtml);
                banWordTagClose();
            });

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
                +'<span class="tag_close"><i class="spfont sp-close ban_words_del"></i></span>'
                +'</li>';
                $(this).parent().parent().parent().prev().children('ul').append(appendHtml);
                banWordTagClose();
            });
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

                    //打开
                    $(this).attr("data-switch-value", 1);
                    $(this).children('i').removeClass("fa-toggle-off").addClass("fa-toggle-on");
                    Toastr.success("成功开启 "+ switchHtml + " 功能");
                }else{
                    //判断相关功能逻辑

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
                        $(this).parent().parent().next().next().removeClass("show").addClass("hide");
                    }

                    //关闭
                    $(this).attr("data-switch-value", 0);
                    $(this).children('i').removeClass("fa-toggle-on").addClass("fa-toggle-off");
                    Toastr.success("成功关闭 "+ switchHtml + " 功能");
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
        }
    };

    return Controller;
});
