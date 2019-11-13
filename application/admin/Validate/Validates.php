<?php
namespace app\admin\Validate;
use think\Validate;

class Validates extends Validate
{
    protected $rule = [
        //类型
		    'username'  => 'require',
		    //标题
		    'password'  => 'require|max:25',
		    //链接
		    
		    //验证
		    'check'  => 'require|captcha',
		    //'captcha|验证码'=>'require|captcha'
		    //邮箱
		    //'email' => 'email'
		    //
		    //验证置顶
		     'datemin'  => 'require',
		     'datemax'  => 'require',
		     'sex'  => 'require',
    ];
     protected $message  =   [
        'username.require' => '请填写用户名',
        'password.require' => '请填写密码',
        'check.require' => '请填写验证码',
        'check.captcha' => '验证码错误', 

        //置顶验证
        'datemin.require' => '请填写开始时间',
        'datemax.require' => '请填写结束时间',
        'sex.require' => '请选择是否置顶',
        	  
    ];


      protected $scene = [
        'tlogin'  =>  ['username','password','check'],
        'zhiding'  =>  ['datemin','datemax','sex'],
    ];
}