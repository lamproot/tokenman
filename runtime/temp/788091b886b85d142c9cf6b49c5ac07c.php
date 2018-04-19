<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:102:"/Library/WebServer/documents/www/lua/tokenman/public/../application/admin/view/keyword/manage/add.html";i:1524137556;s:88:"/Library/WebServer/documents/www/lua/tokenman/application/admin/view/layout/default.html";i:1524119755;s:85:"/Library/WebServer/documents/www/lua/tokenman/application/admin/view/common/meta.html";i:1524119755;s:87:"/Library/WebServer/documents/www/lua/tokenman/application/admin/view/common/script.html";i:1524119755;}*/ ?>
<!DOCTYPE html>
<html lang="<?php echo $config['language']; ?>">
    <head>
        <meta charset="utf-8">
<title><?php echo (isset($title) && ($title !== '')?$title:''); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/assets/img/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/assets/css/backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.css?v=<?php echo \think\Config::get('site.version'); ?>" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.js"></script>
  <script src="/assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  <?php echo json_encode($config); ?>
    };
</script>
    </head>

    <body class="inside-header inside-aside <?php echo defined('IS_DIALOG') && IS_DIALOG ? 'is-dialog' : ''; ?>">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    <?php echo __('Dashboard'); ?>
                                    <small><?php echo __('Control panel'); ?></small>
                                </h1>
                            </section>
                            <?php if(!IS_DIALOG): ?>
                            <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> <?php echo __('Dashboard'); ?></a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                    <?php foreach($breadcrumb as $vo): ?>
                                    <li><a href="javascript:;" data-url="<?php echo $vo['url']; ?>"><?php echo $vo['title']; ?></a></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                            <!-- END RIBBON -->
                            <?php endif; ?>
                            <div class="content">
                                <form id="add-form" class="form-horizontal form-ajax" role="form" data-toggle="validator" method="POST" action="">
  <div class="form-group">
      <label for="cmd" class="control-label col-xs-12 col-sm-2"><?php echo __('Cmd'); ?>:</label>
      <div class="col-xs-12 col-sm-8">
          <input type="text" class="form-control" id="cmd" name="row[cmd]" value="" data-rule="required;cmd" placeholder="DEMO: /demo "/>

      </div>
  </div>

  <div class="form-group">
      <label for="type" class="control-label col-xs-12 col-sm-2"><?php echo __('Type'); ?>:</label>
      <div class="col-xs-12 col-sm-8">
          <!-- <?php echo $groupList; ?> -->
          <select class="form-control selectpicke" name="row[type]" tabindex="-98"><option value="1" selected="selected">普通消息类型</option><option value="3">图文回复类型</option><option value="4">文件类型</option><option value="5">图文连续类型</option></select>
      </div>
  </div>

  <div class="form-group">
      <label for="content" class="control-label col-xs-12 col-sm-2"><?php echo __('Content'); ?>:</label>
      <div class="col-xs-12 col-sm-8">

          <textarea class="form-control" id="content" name="row[content]" rows="8" cols="80" data-rule="required;content" ></textarea>
          <span class="n-msg">Optional. For text messages, the actual UTF-8 text of the message, 0-4096 characters.</span>
          <span class="n-msg">Optional. Caption for the audio, document, photo, video or voice, 0-200 characters</span>
      </div>
  </div>

  <div class="form-group hide" id="row_url">
      <label for="c-local" class="control-label col-xs-12 col-sm-2"><?php echo __('Upload'); ?>:</label>
      <div class="col-xs-12 col-sm-8">
          <input type="text" name="row[url]" id="c-local" class="form-control"/>
          <span class="n-msg">file pdf,gif,zip,jpg,jpeg,png</span>
      </div>
  </div>

  <div class="form-group hide" id="row_file">
      <label for="c-local" class="control-label col-xs-12 col-sm-2"></label>
      <div class="col-xs-12 col-sm-8">
          <button id="plupload-local" class="btn btn-primary plupload" data-input-id="c-local" data-url="<?php echo url('ajax/upload'); ?>"><i class="fa fa-upload"></i> <?php echo __("Upload to local"); ?></button>
      </div>
  </div>

    <div class="form-group hidden layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-success btn-embossed disabled"><?php echo __('OK'); ?></button>
            <button type="reset" class="btn btn-default btn-embossed"><?php echo __('Reset'); ?></button>
        </div>
    </div>
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/require<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js" data-main="/assets/js/require-backend<?php echo \think\Config::get('app_debug')?'':'.min'; ?>.js?v=<?php echo $site['version']; ?>"></script>
    </body>
</html>