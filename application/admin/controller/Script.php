<?php

namespace app\admin\controller;

use app\admin\model\AdminLog;
use app\common\controller\Backend;
use app\common\controller\TwitterAPITimeline;
use think\Config;
use think\Hook;
use think\Validate;

/**
 * 后台首页
 * @internal
 */
class Script extends Backend
{

    protected $noNeedLogin = ['twitter', 'activity_push', 'twitterlog', 'group_user_count', 'tokenmanpush'];
    protected $noNeedRight = ['twitter', 'activity_push'];
    protected $layout = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->botTwitterModel = model('BotTwitter');
        $this->botTwitterLogModel = model('BotTwitterLog');
        $this->groupActivityModel = model('GroupActivity');
        $this->groupUserCountModel = model('GroupUserCount');
        $this->groupUserModel = model('GroupUser');
        $this->chatGroupModel = model('ChatGroup');
        $this->codesModel = model('Codes');
        $this->chatbot = model('ChatBot');
        $this->userTokenManLogModel = model('userTokenManLog');
        $this->TokenManPushLogModel = model('TokenManPushLog');
    }

    /**
     * twitter脚本 每1分钟执行一次
     */
    public function twitter()
    {
        //获取需要Twitter推送的机器人
        $list = $this->botTwitterModel
                ->where('status', '=', 1)
                ->select();

        foreach ($list as $key => $value) {

            if ($value['twitter']) {
                $twitter = $this->getTweet($value['twitter']);
                if ($twitter) {
                    $row = $this->chatbot->get(['id' => $value['chat_bot_id']]);
                    // if ($params['type'] == 1) {
                    //     $result = $this->sendMessage($row['chat_id'], $params['content']);
                    // }
                    if ($row && !empty($row['token'])) {
                        $_SESSION['think']['token'] = $row['token'];
                    }

                    foreach ($twitter as $kkey => $vvalue) {
                        if ($vvalue['tweet']) {
                            //查询是否已推送Twitter
                            //推送Twitter消息
                            $result = $this->sendMessage($row['chat_id'], strip_tags($vvalue['tweet']));
                            //保存Twitter消息
                            $params['content'] = strip_tags($vvalue['tweet']);
                            $params['chat_bot_id'] = $value['chat_bot_id'];
                            $params['twitter_id'] = $vvalue['id'];
                            $params['twitter'] = $value['twitter'];
                            $params['started_at'] = time();
                            $params['stoped_at'] = time();


                            $this->botTwitterLogModel->create($params);
                        }
                    }
                }
            }
        }

        echo true;
    }


    /**
     * tokenmanpush
     */
    public function tokenmanpush()
    {

        //查询推送最后信息

        $date = strtotime(date("Y-m-d", time()) ." 00:00:00");
        $sort = ["user_token_log_id desc"];
        $order = [];
        $pushLog = $this->TokenManPushLogModel
                ->where('date', '=', $date)
                ->order($sort, $order)
                ->limit(0, 1)
                ->select();

        $user_token_log_id = ($pushLog && isset($pushLog[0]) && isset($pushLog[0]['user_token_log_id'])) ? (int)$pushLog[0]['user_token_log_id'] : 0;

        $sort = ["id asc"];
        $order = [];
        $list = $this->userTokenManLogModel
                ->where('id', '>', $user_token_log_id)
                ->order($sort, $order)
                ->limit(0, 120)
                ->select();

        foreach ($list as $key => $value) {
            //echo $key%2;
            if ($key%2 === 0) {
                sleep(1);
            }
            // exit;
            //   sleep(1);
            $tokenManPushLog = $this->TokenManPushLogModel->get(['from_id' => $value['from_id'], 'date' => $date]);
            if (!$tokenManPushLog) {
                $row = $this->chatbot->get(['id' => $value['chat_bot_id']]);
                if ($row && !empty($row['token'])) {
                    $_SESSION['think']['token'] = $row['token'];
                }
                // $button_text = "Joining the ZOS group";
                // $button = json_encode (array (
                //      'inline_keyboard' => array (
                //          array (array (
                //              'text' => $button_text,
                //              'url' => 'https://t.me/ZOSWorld'
                //          ))
                //      )
                //  ));
                $result = $this->sendMessage($value['from_id'], "基于TRON开发的预测币BFC是TRON的重点项目，为庆祝TronDEX 2.0重磅上线 + BFC早鸟预售开募，现在官方电报群展开糖果空投，现在入群即得20BFC，邀请好友还能得到更多糖果！
活动链接：http://m.name-technology.fun/Index/code/6725ddf7aceb52d2

Good News and hot bounty program inbound！
TRXMarket is launching in hours! BFC is just open Presale ! To celebrate, BFC will launch a bounty program worth millions TRX, don’t miss out. Get 20BFC for join in our telegroup, invite more get more! 
Link：http://m.name-technology.fun/Index/code/6725ddf7aceb52d2", "");

                //$result = $this->sendMessage($row['chat_id'], strip_tags($vvalue['tweet']));
                //保存Twitter消息
                $params['chat_bot_id'] = $value['chat_bot_id'];
                $params['from_id'] = $value['from_id'];
                $params['created_at'] = time();
                $params['result'] = $result ? json_encode($result) : "";
                $params['date'] = $date;
                $params['user_token_log_id'] = $value['id'];
                $this->TokenManPushLogModel->create($params);
            }
        }

        echo true;exit;
    }


    /**
     * twitter 重新推送脚本 每1分钟执行一次
     */
    public function twitterlog()
    {
        //获取需要Twitter推送的机器人
        $list = $this->botTwitterLogModel
                ->where('timing', '>', 0)
                ->where('stoped_at', '>', time())
                ->select();

        foreach ($list as $key => $value) {
            $time = time();
            if (($value['tasked_at'] + $value['timing']) <  time()) {
                //进行推送 更新推送时间
                $row = $this->botTwitterLogModel->get(['id' => $value['id']]);

                $chatbot = $this->chatbot->get(['id' => $value['chat_bot_id']]);

                if ($chatbot && !empty($chatbot['token'])) {
                    $_SESSION['think']['token'] = $chatbot['token'];
                    $this->sendMessage($chatbot['chat_id'], $value['content']);
                }
                $params['tasked_at'] = time();
                $params['tasked_count'] = $row['tasked_count'] + 1;
                echo $row->save($params);
            }

            // tasked_at == 0 第一次推送
            // tasked_at != 0  tasked_at+timing< time()  判断是否为推送区段
            //1  + 60

            // if ($value['twitter']) {
            //     $twitter = $this->getTweet($value['twitter']);
            //     if ($twitter) {
            //         $row = $this->chatbot->get(['id' => $value['chat_bot_id']]);
            //         // if ($params['type'] == 1) {
            //         //     $result = $this->sendMessage($row['chat_id'], $params['content']);
            //         // }
            //         if ($row && !empty($row['token'])) {
            //             $_SESSION['think']['token'] = $row['token'];
            //         }
            //
            //         foreach ($twitter as $kkey => $vvalue) {
            //             if ($vvalue['tweet']) {
            //                 //查询是否已推送Twitter
            //                 //推送Twitter消息
            //                 $result = $this->sendMessage($row['chat_id'], strip_tags($vvalue['tweet']));
            //                 //保存Twitter消息
            //                 // $params['content'] = strip_tags($vvalue['tweet']);
            //                 // $params['chat_bot_id'] = $value['chat_bot_id'];
            //                 // $params['twitter_id'] = $vvalue['id'];
            //                 // $params['twitter'] = $value['twitter'];
            //                 // $this->botTwitterLogModel->create($params);
            //             }
            //         }
            //     }
            // }
        }

        echo true;
    }


    /**
     * 获取群用户成员数
     */
     public function group_user_count()
     {
         //获取所有群列表
         $chatGroupList = $this->chatGroupModel
                 ->where('status', '=', 1)
                 ->select();

        foreach ($chatGroupList as $key => $value) {
            $chatbot = $this->chatbot->get(['id' => $value['chat_bot_id']]);

            if ($chatbot && !empty($chatbot['token'])) {
                $_SESSION['think']['token'] = $chatbot['token'];
                //获取群人数
                $groupUserCount = $this->getChatMembersCount($value['chat_id']);
                $chatGroup = $this->chatGroupModel->get(['id' => $value['id']]);
                $chatGroup->group_user_count = $groupUserCount ? (int)$groupUserCount : 0;
                $chatGroup->save();

                //添加群人数实时统计
                $params['chat_id'] = $value['chat_id'];
                $params['count'] = $chatGroup->group_user_count;
                $params['time'] = strtotime(date("Y-m-d", time()) ." ".date("h", time()) . ":00:00");
                $this->groupUserCountModel->create($params);
            }
        }
        echo true;

    }

    /**
     * 活动进行期间 TokenMan 消息推送
     */
     public function activity_push()
     {
         //
         $list = $this->groupActivityModel
                 ->where('status', '=', 0)
                 ->where('stoped_at', '>=', time())
                 ->select();

         foreach ($list as $key => $value) {
             //获取活动举办的群id
             if ($value['id']) {
                 $chatBotList = $this->codesModel->field('chat_bot_id,count(1) as count')
                 ->where('activity_id', '=', $value['id'])
                 ->group('chat_bot_id')
                 ->select();

                 foreach ($chatBotList as $ckey => $cvalue) {
                         $row = $this->chatbot->get(['id' => $cvalue['chat_bot_id']]);
                         if ($row && !empty($row['token'])) {
                             $_SESSION['think']['token'] = $row['token'];
                         }
                         $button_text = "TokenMan";
                         $button = json_encode (array (
                                    'inline_keyboard' => array (
                                        array (array (
                                            'text' => $button_text,
                                            'url' => 'http://t.me/TokenManBot'
                                        ))
                                    )
                                ));
                         $result[] = $this->sendMessage($row['chat_id'], "Click the button to contact TokenMan and check your reward at any time. \n 点击按钮联系 TokenMan, 获取邀请奖励动态", "", $button);
                         echo json_encode($result);exit;
                 }
             }
         }
          echo json_encode($result);exit;
         //echo true;
     }

    public function getTweet($tweet)
    {
        //require_once($_SERVER['DOCUMENT_ROOT'].'/twitter-timeline-php/_utils/twitter-api-oauth.php');
        $settings = array(
            'consumer_key'              => "me6hx8LtKpUi4vxcahSxV2Tnd",
            'consumer_secret'           => "Z45ALgHaYdvTjC9qsoxEZsKgyIH93MJdZCc6aHI6XMCBlmBWd0",
            'oauth_access_token'        => "975985060217217024-H7asqHY4DoDKzHIfS1y0mIzgtYdaFpk",
            'oauth_access_token_secret' => "3uf7bwiH0x8wknVgmfuIG544EjGP4i7A5LUOei5hOkBV3"
        );

        $tweetCount      = 10;
        $twitterUsername = $tweet;
        $url             = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield        = '?screen_name=' . $twitterUsername . '&count=' . $tweetCount;

        $twitter      = new TwitterAPITimeline($settings);
        $json         = $twitter->setGetfield($getfield)->buildOauth($url, @$requestMethod)->performRequest();
        $twitter_data = json_decode($json, true);   // Create an array with the fetched JSON data

        $data = array();

        foreach($twitter_data as $feed)
        {
            $photos = array();
            $media  = @$feed['entities']['media'];
            if($media) foreach($media as $img) $photos[] = $img['media_url'];

            if ((time() - strtotime(@$feed['created_at'])) <= 60) {
                $data[] = array('id' => $feed['id'], 'tweet'=>$feed['text'],'date'=>strtotime($feed['created_at']),'img'=>$photos);
            }
        }

        return $data;
    }



    function formatTweet($tweet)
    {
        $linkified = '@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@';
        $hashified = '/(^|[\n\s])#([^\s"\t\n\r<:]*)/is';
        $mentionified = '/(^|[\n\s])@([^\s"\t\n\r<:]*)/is';

        $prettyTweet = preg_replace(
            array(
                $linkified,
                $hashified,
                $mentionified
            ),
            array(
                '<a href="$1" class="link-tweet" target="_blank">$1</a>',
                '$1<a class="link-hashtag" href="https://twitter.com/search?q=%23$2&src=hash" target="_blank">#$2</a>',
                '$1<a class="link-mention" href="http://twitter.com/$2" target="_blank">@$2</a>'
            ),
            $tweet
        );

        return $prettyTweet;
    }


}
