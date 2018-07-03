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

    protected $noNeedLogin = ['twitter', 'activity_push'];
    protected $noNeedRight = ['twitter', 'activity_push'];
    protected $layout = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->botTwitterModel = model('BotTwitter');
        $this->botTwitterLogModel = model('BotTwitterLog');
        $this->groupActivityModel = model('GroupActivity');
        $this->codesModel = model('Codes');
        $this->chatbot = model('ChatBot');
    }

    /**
     * twitter脚本 每30分钟执行一次
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
                            $this->botTwitterLogModel->create($params);
                        }
                    }
                }
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
                 echo json_encode($chatBotList);exit;
             }

             if ($value['chat_bot_id']) {
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
                         $this->botTwitterLogModel->create($params);
                     }
                 }
             }
         }

         echo true;
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

            if ((time() - strtotime($feed['created_at'])) <= 30 * 60) {
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
