<?php
/* 框架全局配置变量 */
$InitPHP_conf = array();
/*********************************基础配置*****************************************/
/**
 * 站点URL配置
 * 必选参数
 */
$InitPHP_conf['url'] = 'http://ccc.com';
$InitPHP_conf['root_dir'] = str_replace("\\",'/',dirname(__FILE__));
/**
 * 是否开启调试
 */
$InitPHP_conf['is_debug'] = false; //开启-正式上线请关闭
$InitPHP_conf['show_all_error'] = false; //是否显示所有错误信息，必须在is_debug开启的情况下才能显示
/**
 * 日志目录
 */
$InitPHP_conf['log_dir'] = './data/logs/'; //日志目录,必须配置
/**
 * 路由访问方式
 * 1. 如果为true 则开启path访问方式，否则关闭
 * 2. default：index.php?m=user&c=index&a=run
 * 3. rewrite：/user/index/run/?id=100
 * 4. path: /user/index/run/id/100
 * 5. html: user-index-run.htm?uid=100
 * 6. 开启PATH需要开启APACHE的rewrite模块，详细使用会在文档中体现
 */
$InitPHP_conf['isuri'] = 'path';
/**
 * 是否开启输出自动过滤
 * 1. 对多人合作，安全性可控比较差的项目建议开启
 * 2. 对HTML进行转义，可以放置XSS攻击
 * 3. 如果不开启，则提供InitPHP::output()函数来过滤
 */
$InitPHP_conf['isviewfilter'] = false;

/*********************************DAO数据库配置*****************************************/
/**
 * Dao配置参数
 * 1. 你可以配置Dao的路径和文件（类名称）的后缀名
 * 2. 一般情况下您不需要改动此配置
 */
$InitPHP_conf['dao']['dao_postfix']  = 'Dao'; //后缀
$InitPHP_conf['dao']['path']  = 'library/dao/'; //后缀
/**
 * 数据库配置
 * 1. 根据项目的数据库情况配置
 * 2. 支持单数据库服务器，读写分离，随机分布的方式
 * 3. 可以根据$InitPHP_conf['db']['default']['db_type'] 选择mysql mysqli（暂时支持这两种）
 * 4. 支持多库配置 $InitPHP_conf['db']['default']
 * 5. 详细见文档
 */
$InitPHP_conf['db']['driver']   = 'mysqli'; //选择不同的数据库DB 引擎，一般默认mysqli,或者mysql
//default数据库配置 一般使用中 $this->init_db('default')-> 或者 $this->init_db()-> 为默认的模型
$InitPHP_conf['db']['default']['db_type']                   = 0; //0-单个服务器，1-读写分离，2-随机
$InitPHP_conf['db']['default'][0]['host']                   = '127.0.0.1'; //主机
$InitPHP_conf['db']['default'][0]['username']               = 'root'; //数据库用户名
$InitPHP_conf['db']['default'][0]['password']               = 'root'; //数据库密码
$InitPHP_conf['db']['default'][0]['database']               = 'baihedai20161'; //数据库
$InitPHP_conf['db']['default'][0]['charset']                = 'utf8'; //数据库编码
$InitPHP_conf['db']['default'][0]['pconnect']               = 0; //是否持久链接

/*********************************Service配置*****************************************/
/**
 * Service配置参数
 * 1. 你可以配置service的路径和文件（类名称）的后缀名
 * 2. 一般情况下您不需要改动此配置
 */
$InitPHP_conf['service']['service_postfix']  = 'Service'; //后缀
$InitPHP_conf['service']['path'] = 'library/service/'; //service路径

/*********************************Controller配置*****************************************/
/**
 * Controller控制器配置参数
 * 1. 你可以配置控制器默认的文件夹，默认的后缀，Action默认后缀，默认执行的Action和Controller
 * 2. 一般情况下，你可以不需要修改该配置参数
 * 3. $InitPHP_conf['ismodule']参数，当你的项目比较大的时候，可以选用module方式，
 * 开启module后，你的URL种需要带m的参数，原始：index.php?c=index&a=run, 加module：
 * index.php?m=user&c=index&a=run , module就是$InitPHP_conf['controller']['path']目录下的
 * 一个文件夹名称，请用小写文件夹名称
 */
$InitPHP_conf['ismodule'] = false; //开启module方式
$InitPHP_conf['controller']['path']                  = 'web/controller/';
$InitPHP_conf['controller']['controller_postfix']    = 'Controller'; //控制器文件后缀名
$InitPHP_conf['controller']['action_postfix']        = ''; //Action函数名称后缀
$InitPHP_conf['controller']['default_controller']    = 'index'; //默认执行的控制器名称
$InitPHP_conf['controller']['default_action']        = 'run'; //默认执行的Action函数
$InitPHP_conf['controller']['module_list']           = array('index', 'admin'); //module白名单
$InitPHP_conf['controller']['default_module']        = 'index'; //默认执行module
$InitPHP_conf['controller']['default_before_action'] = 'before'; //默认前置的ACTION名称
$InitPHP_conf['controller']['default_after_action']  = 'after'; //默认后置ACTION名称

/*********************************View配置*****************************************/
/**
 * 模板配置
 * 1. 可以自定义模板的文件夹，编译模板路径，模板文件后缀名称，编译模板后缀名称
 * 是否编译，模板的驱动和模板的主题
 * 2. 一般情况下，默认配置是最优的配置方案，你可以不选择修改模板文件参数
 */
$InitPHP_conf['template']['template_path']      = 'web/template'; //模板路径
$InitPHP_conf['template']['template_c_path']    = 'data/template_c'; //模板编译路径
$InitPHP_conf['template']['template_type']      = 'htm'; //模板文件类型
$InitPHP_conf['template']['template_c_type']    = 'tpl.php';//模板编译文件类型
$InitPHP_conf['template']['template_tag_left']  = '<!--{';//模板左标签
$InitPHP_conf['template']['template_tag_right'] = '}-->';//模板右标签
$InitPHP_conf['template']['is_compile']         = true;//模板每次编译-系统上线后可以关闭此功能
$InitPHP_conf['template']['driver']             = 'simple'; //不同的模板驱动编译
$InitPHP_conf['template']['theme']              = ''; //模板主题

/*********************************拦截器配置*****************************************/
/**
 * 拦截器配置
 */
$InitPHP_conf['interceptor']['path'] = "web/interceptor"; //拦截器文件夹目录
$InitPHP_conf['interceptor']['postfix'] = "Interceptor"; //拦截器类和文件的后缀名
$InitPHP_conf['interceptor']['rule'] = array( //拦截器规则
    'test' => array(
        'file' => 'test', //文件名称 例如：testInterceptor,则file值为：test
        'regular' =>  array(
            'm' => '*',
            'c' => '/^(hello)$/', //正则表达式
            'a' => '/^(interceptor)$/' //只有helloController的方法interceptor被拦截器拦截
        )//正则表达式
    )
);


/*********************************RPC服务*****************************************/
/**
 * RPC配置
 * RPC分两种，服务提供者-provider和服务使用者-customer
 */
$InitPHP_conf['provider']['allow'] = array(
    "user"
); //允许访问的Service,例如userService,则是user。如果带path，则xxx/user
/*
 * 网络范围表示方法：
 * 通配符:      1.2.3.*
 * CIDR值:     1.2.3.0/24
 * IP段:       1.2.3.0-1.2.3.255
 */
$InitPHP_conf['provider']['allow_ip'] = array(
    "127.0.0.2", "192.168.*.*", "127.0.0.*"
);
$InitPHP_conf['customer'] = array(
    "default" => array( //可以进行分组
        "host" => array("127.0.0.1"), //服务提供者所在的服务器的IP地址，一般是内网IP地址。可以填写多台服务器
        "file" => "/initphp/demo/www/rpc.php" //访问服务的入口文件，例如加上IP地址：http://localhost/rpc.php
    )
);


/*********************************Hook配置*****************************************/
/**
 * 插件Hook配置
 * 1. 如果你需要使用InitPHP::hook() 钩子函数来实现插件功能
 * 2. 详细查看钩子的使用方法
 */
$InitPHP_conf['hook']['path']          = 'hook'; //插件文件夹目录， 不需要加'/'
$InitPHP_conf['hook']['class_postfix'] = 'Hook'; //默认插件类名后缀
$InitPHP_conf['hook']['file_postfix']  = '.hook.php'; //默认插件文件名称
$InitPHP_conf['hook']['config']        = 'hook.conf.php'; //配置文件

/*********************************单元测试*****************************************/
/**
 * 单元测试
 * 1. 使用工具库中的单元测试需要配置
 */
$InitPHP_conf['unittesting']['test_postfix'] = $InitPHP_conf['service']['service_postfix'] . 'Test';
$InitPHP_conf['unittesting']['path'] = 'library/test/';

/*********************************Error*****************************************/
/**
 * Error模板
 * 如果使用工具库中的error，需要配置
 */
$InitPHP_conf['error']['template'] = 'library/helper/error.tpl.php';

/*********************************缓存，Nosql配置*****************************************/
/**
 * 缓存配置参数
 * 1. 您如果使用缓存 需要配置memcache的服务器和文件缓存的缓存路径
 * 2. memcache可以配置分布式服务器，根据$InitPHP_conf['memcache'][0]的KEY值去进行添加
 * 3. 根据您的实际情况配置
 */
$InitPHP_conf['memcache'][0]   = array('127.0.0.1', '11211');
$InitPHP_conf['cache']['filepath'] = 'data/filecache';   //文件缓存目录
/**
 * MongoDB配置，如果您使用了mongo，则需要配置
 */
$InitPHP_conf['mongo']['default']['server']     = '127.0.0.1';
$InitPHP_conf['mongo']['default']['port']       = '27017';
$InitPHP_conf['mongo']['default']['option']     = array('connect' => true);
$InitPHP_conf['mongo']['default']['db_name']    = 'test';
$InitPHP_conf['mongo']['default']['username']   = '';
$InitPHP_conf['mongo']['default']['password']   = '';
/**
 * Redis配置，如果您使用了redis，则需要配置
 */
$InitPHP_conf['redis']['default']['server']     = '127.0.0.1';
$InitPHP_conf['redis']['default']['port']       = '6379';

/*********************************后台常量*****************************************/
$InitPHP_conf['home']['sitename']='百合贷';
$InitPHP_conf['home']['seoname']='诚信安全的互联网金融网络借贷平台,';
$InitPHP_conf['home']['siteurl']='http://sales.com/';
$InitPHP_conf['home']['keywords']='百合贷,百合贷官网，互联网金融，个人理财，投资理财，p2p网贷，网络借贷，P2C理财，P2P理财，个人理财，网上投资';
$InitPHP_conf['home']['description']='做安全规范、实力更强的互联网金融投资理财平台，丰富多样且本息保障的高收益理财产品，大众化的低投资门槛，全免费的服务体验！';
$InitPHP_conf['home']['masteremail']='service@baihedai.com';
$InitPHP_conf['home']['copyright']='版权信息';
$InitPHP_conf['home']['ver'] = '1.0.7';//版本号
$InitPHP_conf['home']['ver_name'] = '百合贷 正式版'; //版本名称
$InitPHP_conf['home']['ver_date'] = '20161230'; //版本时间
/**
 * Email 配置
 */
$InitPHP_conf['home']['smtpserver']='115.29.167.231';//SMTP服务器
$InitPHP_conf['home']['smtpserverport']=25;//SMTP服务器端口
$InitPHP_conf['home']['smtpusermail']='service@ebaojr.com';//SMTP服务器的用户邮箱
$InitPHP_conf['home']['smtpuser']='service@ebaojr.com';//SMTP服务器的用户帐号
$InitPHP_conf['home']['smtppass']='bwaarVxi(w3A1wJ';//SMTP服务器的用户密码
$InitPHP_conf['home']['mailtype'] = 'html';//邮件格式（HTML/TXT）,TXT为文本邮件
$InitPHP_conf['home']['smtpemailto']='cnhonk@qq.com';//发给谁
/**
 * 上传设置
 */
$InitPHP_conf['home']['ACCESSPRY_SIZE']=2; //附件大小，单位M
$InitPHP_conf['home']['ACCESSPRY_NUM']=300; //上传数量
$InitPHP_conf['home']['ACCESSPRY_TYPE']='pdf,jpg,gif,png';//上传格式
$InitPHP_conf['home']['THUMBNAIL_SWIHCH']=true; //是否缩图
$InitPHP_conf['home']['THUMBNAIL_MAXWIDTH']=220; //缩图最大宽度
$InitPHP_conf['home']['THUMBNAIL_MAXHIGHT']=220; //最大高度
$InitPHP_conf['home']['WATERMARK_SWITCH']=false; //是否打水印
$InitPHP_conf['home']['WATERMARK_PLACE']=5; //水印位置
$InitPHP_conf['home']['WATERMARK_IMAGE']='logo.png'; //水印图片
$InitPHP_conf['home']['WATERMARK_CUTOUT']=true; //缩图方式
/**
 * 汇付天下设置
 */
//$InitPHP_conf['home']['Huifu_HOST']='https://lab.chinapnr.com/muser/publicRequests';//服务器地址
$InitPHP_conf['home']['Huifu_HOST']='http://mertest.chinapnr.com/muser/publicRequests';//服务器地址
$InitPHP_conf['home']['Huifu_MerCustId']='6000060003046378';//商户号
$InitPHP_conf['home']['Huifu_MerId']='530977';//加密号
$InitPHP_conf['home']['LocalHost']='http://www.baihedai.com.cn';
