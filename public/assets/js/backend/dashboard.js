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
            $.ajax({
                url: 'chatbot/manage/create',
                type: 'get',
                success: function (ret) {
                    if (true) {
                        var data = ret
                        if (data.create_bot) {
                            Layer.open({
                                title: __('Create A Bot'),
                                //maxHeight: 600,
                                //width:800,
                                area: ['500px', '300px'],
                                content: '<form id="add-form" class="form-horizontal form-ajax" role="form" data-toggle="validator" method="POST" action="">'
                                    +'<div class="form-group">'
                                        // +'<label for="twitter" class="control-label col-xs-12 col-sm-2">Token:</label>'
                                        +'<div class="col-xs-12 col-sm-12">'
                                            +'<span>'+__('Please enter the API token of your bot. Once the bot has been registered, you can use that bot to manage your group. ')+'<br> <a href="https://core.telegram.org/bots#creating-a-new-bot" target="_blank">'+__('How do I create a bot?')+'</a><br>  <a href="https://t.me/TokenManBot" target="_blank">'+__('Or click here to ask for TokenManBot.')+'</a></span>'
                                        +'</div>'
                                    +'</div>'
                                    +'<div class="form-group">'
                                        // +'<label for="twitter" class="control-label col-xs-12 col-sm-2">Token:</label>'
                                        +'<div class="col-xs-12 col-sm-12">'
                                            +'<input type="text" class="form-control" id="token" name="token" value="" data-rule="required:twitter"  placeholder="'+ __('Please Enter Bot Token')+'"/>'
                                        +'</div>'
                                    +'</div>'

                                +'</form>',
                                // btn: ['去下载更新', '忽略此次更新', '不再提示'],
                                // btn2: function (index, layero) {
                                //     localStorage.setItem("ignoreversion", ret.data.newversion);
                                // },
                                // btn3: function (index, layero) {
                                //     localStorage.setItem("ignoreversion", "*");
                                // },
                                success: function (layero, index) {
                                    //$(".layui-layer-btn0", layero).attr("href", "").attr("target", "_blank");
                                },
                                yes: function(index, layero){
                                    //do something
                                    //alert($("#token").val())
                                    var token = $("#token").val();
                                    if (token == '' || token == "undefined") {
                                        alert(__('Please Enter Bot Token'));
                                            return false;
                                    }
                                    //layer.close(index); //如果设定了yes回调，需进行手工关闭
                                    $.ajax({
                                        url: 'chatbot/manage/create',
                                        type: 'post',
                                        dataType: 'json',
                                        data:{"token":token},
                                        success: function (ret) {
                                            //alert("dasddasd")
                                            // var data = JSON.stringify(ret)
                                            var data = ret;
                                            //alert(data)
                                            if (data.code === 0) {
                                                alert(data.msg);
                                                return false;
                                            }else{
                                                Toastr.success("添加成功 请前往我的机器人查看");
                                                layer.close(index);
                                            }
                                            //$("#news-list").html(Template("newstpl", {news: ret.rows}));
                                        }
                                    });
                                 }
                            });
                        }
                    }
                    //$("#discussion-list").html(Template("discussiontpl", {news: ret.discussionlist.slice(0,6)}));
                }
            });





            // Layer.prompt({
            //     formType: 0,
            //     //value: 'Please Input Robot Token',
            //     title: 'Enter Bot Token',
            //     placeholder:'asdasd',
            //     width:600,
            //     area: ['800px', '350px']
            // }, function(value, index, elem){
            //     alert(value); //得到value
            //     //layer.close(index);
            // });
        }
    };

    return Controller;
});
