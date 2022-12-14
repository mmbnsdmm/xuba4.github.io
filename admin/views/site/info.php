<?php
/**
 * @var \yii\web\View $this
 * @var double $mysql_size
 * @var string $attachment_dir
 * @var double $attachment_size
 * @var string|array $disable_functions
 */

$this->title = "信息概览";
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-xs-9">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-cog"></i> 环境配置</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>PHP版本</td>
                        <td><?= phpversion(); ?></td>
                    </tr>
                    <tr>
                        <td>Mysql版本</td>
                        <td>
                            主：<?=Yii::$app->db->master?Yii::$app->db->master->dsn:"" ?>
                            从：<?=Yii::$app->db->slave?Yii::$app->db->slave->dsn:"" ?>
                        </td>
                    </tr>
                    <tr>
                        <td>解析引擎</td>
                        <td><?= $_SERVER['SERVER_SOFTWARE']; ?></td>
                    </tr>
                    <tr>
                        <td>数据库大小</td>
                        <td><?= Yii::$app->formatter->asShortSize($mysql_size, 2); ?></td>
                    </tr>
                    <tr>
                        <td>附件目录</td>
                        <td><?=$attachment_dir ?>/</td>
                    </tr>
                    <tr>
                        <td>附件目录大小</td>
                        <td><?= Yii::$app->formatter->asShortSize($attachment_size, 2); ?></td>
                    </tr>
                    <tr>
                        <td>超时时间</td>
                        <td><?= ini_get('max_execution_time'); ?>秒</td>
                    </tr>
                    <tr>
                        <td>客户端信息</td>
                        <td><?= $_SERVER['HTTP_USER_AGENT'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-code"></i> 系统信息</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>系统全称</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>系统简介</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>系统版本</td>
                        <td><?= Yii::$app->version; ?></td>
                    </tr>
                    <tr>
                        <td>Yii2版本</td>
                        <td><?= Yii::getVersion(); ?><?php if (YII_DEBUG) echo ' (开发模式)'; ?></td>
                    </tr>
                    <tr>
                        <td>官网</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>官方QQ群</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>GitHub</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>开发者</td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-lemon-o"></i> PHP信息</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>PHP执行方式</td>
                        <td><?= php_sapi_name(); ?></td>
                    </tr>
                    <tr>
                        <td>扩展支持</td>
                        <td>
                            <?= extension_loaded('gd')
                                ? '<span class="label label-primary">gd支持</span>'
                                : '<span class="label label-default">gd不支持</span>'; ?>
                            <?= extension_loaded('imagick')
                                ? '<span class="label label-primary">imagick支持</span>'
                                : '<span class="label label-default">imagick不支持</span>'; ?>
                            <?= extension_loaded('curl')
                                ? '<span class="label label-primary">curl支持</span>'
                                : '<span class="label label-default">curl不支持</span>'; ?>
                            <?= extension_loaded('fileinfo')
                                ? '<span class="label label-primary">fileinfo支持</span>'
                                : '<span class="label label-default">fileinfo不支持</span>'; ?>
                            <?= extension_loaded('intl')
                                ? '<span class="label label-primary">intl支持</span>'
                                : '<span class="label label-default">intl不支持</span>'; ?>
                            <?= extension_loaded('mbstring')
                                ? '<span class="label label-primary">mbstring支持</span>'
                                : '<span class="label label-default">mbstring不支持</span>'; ?>
                            <?= extension_loaded('intl')
                                ? '<span class="label label-primary">exif支持</span>'
                                : '<span class="label label-default">exif不支持</span>'; ?>
                            <?= extension_loaded('openssl')
                                ? '<span class="label label-primary">openssl支持</span>'
                                : '<span class="label label-default">openssl不支持</span>'; ?>
                            <?= extension_loaded('Zend OPcache')
                                ? '<span class="label label-primary">opcache支持</span>'
                                : '<span class="label label-default">opcache不支持</span>'; ?>
                            <?= extension_loaded('memcache')
                                ? '<span class="label label-primary">memcache支持</span>'
                                : '<span class="label label-default">memcache不支持</span>'; ?>
                            <?= extension_loaded('memcached')
                                ? '<span class="label label-primary">memcached支持</span>'
                                : '<span class="label label-default">memcached不支持</span>'; ?>
                            <?= extension_loaded('redis')
                                ? '<span class="label label-primary">redis支持</span>'
                                : '<span class="label label-default">redis不支持</span>'; ?>
                            <?= extension_loaded('swoole')
                                ? '<span class="label label-primary">swoole支持</span>'
                                : '<span class="label label-default">swoole不支持</span>'; ?>
                            <?= extension_loaded('mongodb')
                                ? '<span class="label label-primary">mongodb支持</span>'
                                : '<span class="label label-default">mongodb不支持</span>'; ?>
                            <?= extension_loaded('amqp')
                                ? '<span class="label label-primary">amqp支持</span>'
                                : '<span class="label label-default">amqp不支持</span>'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>禁用的函数</td>
                        <td >
                            <?php if (is_array($disable_functions)){ ?>
                                <?php foreach ($disable_functions as $function){ ?>
                                    <span class="label label-default"><?= $function; ?></span>
                                <?php } ?>
                            <?php }else{ ?>
                                <span class="label label-default"><?= $disable_functions; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>脚本内存限制</td>
                        <td><?= ini_get('memory_limit'); ?></td>
                    </tr>
                    <tr>
                        <td>文件上传限制</td>
                        <td><?= ini_get('upload_max_filesize'); ?></td>
                    </tr>
                    <tr>
                        <td>Post数据最大尺寸</td>
                        <td><?= ini_get('post_max_size'); ?></td>
                    </tr>
                    <tr>
                        <td>Socket超时时间</td>
                        <td><?= ini_get('default_socket_timeout'); ?> 秒</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
