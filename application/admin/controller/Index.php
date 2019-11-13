<?php
namespace app\admin\controller;
use think\Controller;
use \think\Request;
use \think\Validate;
use \think\Session;
use \think\Cookie;
use app\admin\model\Wxshow;
use app\admin\model\Allshow;
use app\admin\model\Usernum;
use think\Loader;


class Index extends controller
{
    public function index()
    {
        // return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
        
        //判断session是否存在，不存在则非法登录,退出到登录界面
        $t_login = Session::has('username');

        //非法登录退出
        if(!$t_login){
            return $this->error('请登录！','login',1);
        }

        //登录正常
        $flag = Session::get('username');
        $this->assign('username',$flag);
         //var_dump($flag);
        return $this->fetch();
    }

   
    //主页展示
     public function index_v3(){
    	return $this->fetch();
    }

   	//登录界面
    public function login()
    {
        Session::delete('username');
    	return $this->fetch();
    }


    //登录验证
     public function tlogin()
    {
    	$date = input('param.');
    	//var_dump($date);
    	//验证验证码
    	$validate = Loader::validate('Validates');
    	$result   = $validate->scene('tlogin')->check($date); 
    	if (!$result) {
			return $this->error($validate->getError(),"login");    
		}

		//登录验证
		$user = new Usernum();
		$data = $user->where('username',$date['username'])->find();	
		//var_dump($data['id']);exit;
		if(($date['username']==$data['username'])&&($date['password']==$data['password'])){
            //登录成功重定向。
            Cookie::set('name','value',60);
            Session::set('username',$data['username']);
            $this->redirect('index');

			//return $this->success('登录成功','index',1);
            //创建session，作为查询索引
            //
		}else{
			return $this->error("账号或密码错误","login",2); 
		}

    }


    //创建账户
    //

    public function loginal()
    {
        Session::delete('username');
        return $this->fetch();
    }

    //管理员登录
     public function tloginal()
    {
        $date = input('param.');
        //var_dump($date);
        //验证验证码
        $validate = Loader::validate('Validates');
        $result   = $validate->scene('tlogin')->check($date); 
        if (!$result) {
            return $this->error($validate->getError(),"login");    
        }

       

        $data = [
            'username'=>'wangli',
            'password'=>'wangli'
        ];
        //var_dump($data['id']);exit;
        if(($date['username']==$data['username'])&&($date['password']==$data['password'])){
            //登录成功重定向。
            //Cookie::set('name','value',60);
            Session::set('username',$data['username']);
            $this->redirect('table_data_tables_al');

            //return $this->success('登录成功','index',1);
            //创建session，作为查询索引
            //
        }else{
            return $this->error("账号或密码错误","loginal",2); 
        }

    }



    public function table_data_tables_al()
    {
        return $this->fetch();
    }




    //数据表展示
    public function table_data_tables()
    {
    	$date = new Allshow;

        //获取连接地址并且去重
        $url = $date->whereTime('time','today')->distinct(true)->column('location');
        //var_dump($url);
        $this->assign('url',$url);

        $wl_flag = 0;
        $this->assign('wl_flag',$wl_flag);
        //获取全部数据
    	// $data = $date->select();
        //获取100条数据 
        $data = $date->whereTime('time','today')->group('user_ip')->paginate(500);
        $page = $data->render();
    	$this->assign('datas',$data);
        $this->assign('page', $page);	
    	return $this->fetch();
    }


     //数据表展示
    public function table_bootstrap()
    {
    	return $this->fetch();
    }


    //用户删除无用信息
    public function user_del()
    {
    	$data = request()->param();	
		//$id= $data['id'];
		$allshow = new Allshow;
		$flag =$allshow->where('id',$data['id'])->delete();
		echo $flag;
		exit;

    }


    public function select_date(){

        $data = request()->param(); 
        //var_dump($data);
        // $wl_flag = 1;
        // $this->assign('wl_flag',$wl_flag);

        $date = new Allshow;
        $url = $date->distinct(true)->whereTime('time','week')->column('location');
        //var_dump($url);
        $this->assign('url',$url);

        $allshow = new Allshow;
        if($data['web']!= 1){
            //时间为空查询改域名的全部
           if($data['star']==""||$data['end']==""){
             $flag =$allshow
                ->where('location',$data['web'])
                ->whereTime('time','today')
				->group('user_ip')
                ->select();

                $wl_flag = 1;
                $this->assign('wl_flag',$wl_flag);

                $array = [  
                            'url'=>$data['web'],
                            'star'=>"",
                            'end'=>"",
                        ];

             $this->assign('datatime',$array);

           } else{
            //时间不为空查询时间和域名指定条数
            $flag =$allshow
                ->where('location',$data['web'])
                ->whereTime('time','between',[$data['star'],$data['end']])
				->group('user_ip')
                ->select();
                 $wl_flag = 1;
                 $this->assign('wl_flag',$wl_flag);

                 $array = [  
                            'url'=>$data['web'],
                            'star'=>$data['star'],
                            'end'=>$data['end'],
                        ];
				$url = $date->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
                //var_dump($url);
                $this->assign('url',$url);
                $this->assign('datatime',$array);


           }            
                $this->assign('datas',$flag);

        }else{

                //查询全部
            if($data['star']==""||$data['end']==""){
               $flag =$allshow->whereTime('time','today')->group('user_ip')->select(); 

                 $wl_flag = 0;
                $this->assign('wl_flag',$wl_flag);

                $array = [  
                            'url'=>$data['web'],
                            'star'=>"",
                            'end'=>"",
                        ];

             $this->assign('datatime',$array);
              $this->assign('datas',$flag);
            }else{
             $flag =$allshow
                ->whereTime('time','between',[$data['star'],$data['end']])
				->group('user_ip')
                ->select(); 

                $wl_flag = 1;
                $this->assign('wl_flag',$wl_flag);
                // if($data['web'] == 1){
                //     $data['web'] = "全部";
                // }
                $array = [  
                            'url'=>$data['web'],
                            'star'=>$data['star'],
                            'end'=>$data['end'],
                        ];    
				$url = $date->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
                //var_dump($url);
                $this->assign('url',$url);
                $this->assign('datatime',$array);
                $this->assign('datas',$flag);
            }
               
        }
         return $this->fetch('table_data_tables');
         //halt($flag);       
    }



    //总表2
    public function table_data_tables2(){

        $date = new Allshow;
        $date2 = new Wxshow;
        //获取连接地址并且去重
        $url = $date->distinct(true)->whereTime('time','today')->column('location');
        //var_dump($url);
        // $this->assign('url',$url);
        // $num = count($url);
        // //var_dump($num);
        $wl_flag = 0;
        $this->assign('wl_flag',$wl_flag);
        $wl_flags = 1;
        $this->assign('wl_flags',$wl_flags);
        //连接*****
        //$url = $date->distinct(true)->column('location');
        //var_dump($url);
        $this->assign('url',$url);
        //连接*****
        //
        //查询链接数据
        $test = $date2->state($url);
        //var_dump($test);
        $this->assign('data3',$test); 
        return $this->fetch();
    }

    //选择总表2
     public function select_date2(){

        $data = request()->param(); 
        //var_dump($data);
        $this->assign('datatime',$data);

        $date = new Allshow;
        $date2 = new Wxshow;
        $url = $date->whereTime('time','week')->distinct(true)->column('location');
        $this->assign('url',$url); 
        if($data['web']!='1'){

             if($data['star']==""||$data['end']==""){
                $wl_flag = 1;
                //没有key值
                $wl_flags = 0;
                $this->assign('wl_flag',$wl_flag);
                $this->assign('wl_flags',$wl_flags);

                  $array = [  
                            'url'=>$data['web'],
                            'star'=>"",
                            'end'=>"",
                        ];    

                $this->assign('datatime',$array);


                $test = $date2->state3($data['web']);
                //var_dump($test);
                $this->assign('url',$url); 
                $this->assign('data2',$test); 
                    //var_dump($test);
                return $this->fetch('table_data_tables2');

            }else{

                $wl_flag = 1;
                //没有key值
                $wl_flags = 0;
                 $this->assign('wl_flags',$wl_flags);
                $this->assign('wl_flag',$wl_flag);      
                $test = $date2->state4($data['web'],$data['star'],$data['end']);
                $this->assign('url',$url); 
                $this->assign('data2',$test); 

                  $array = [  
                            'url'=>$data['web'],
                            'star'=>$data['star'],
                            'end'=>$data['end'],
                        ];    

                $this->assign('datatime',$array);
				$url = $date->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
                //var_dump($url);
                $this->assign('url',$url);
                return $this->fetch('table_data_tables2');
            }

        }else{

            if($data['star']==""||$data['end']==""){
                $wl_flag = 0;
                $wl_flags = 1;
                 $this->assign('wl_flags',$wl_flags);
                $this->assign('wl_flag',$wl_flag);
                $test = $date2->state($url);
               // var_dump($test);
                $this->assign('url',$url); 
                $this->assign('data3',$test); 
                return $this->fetch('table_data_tables2');

            }else{

                $wl_flag = 1;
                $wl_flags = 1;
                 $this->assign('wl_flags',$wl_flags);
                $this->assign('wl_flag',$wl_flag);      
                $test = $date2->state2($url,$data['star'],$data['end']);


                  $array = [  
                            'url'=>$data['web'],
                            'star'=>$data['star'],
                            'end'=>$data['end'],
                        ];    
				$url = $date->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
                //var_dump($url);
                $this->assign('url',$url);
                $this->assign('datatime',$array);

                $this->assign('url',$url); 
                $this->assign('data3',$test); 
                return $this->fetch('table_data_tables2');
            }
        }
         
    }


    public function table_data_tables3(){

        $json_url="static/json/menu.json";//文件名称和路径
                    // 写入文件
        if(!file_exists($json_url)){
            fopen($json_url,"w");
        }

        $rs = file_get_contents($json_url);
        $user = Session::get('username');

        $url = '<script id="kw_tongji" src="http://fzxt.bcysyy.cn/tp8/kw2.js?sign='.md5($user).'" charset="UTF-8"></script>';
        $this->assign('url',$url);
        $this->assign('rs',$rs);
        return $this->fetch();
    }


    public function yuming(){
        $data = request()->param(); 
        //var_dump($data);
        //echo $data['yuming'];

        $json_url="static/json/menu.json";//文件名称和路径
                    // 写入文件
        if(!file_exists($json_url)){
            fopen($json_url,"w");
        }

        $rs = file_put_contents($json_url, $data['yuming']);

        echo 1;
    }



    public function table_data_tables4(){
        //微信号操作次数
        $Allshow = new Allshow;

        //分时段展示

         $wx2 =  $Allshow->wxshowtimes2();
         $wx3 =  $Allshow->showtime24();
         //var_dump($wx3);
         $this->assign('time_wx',$wx3);
         $this->assign('times_wxs',$wx2);

         //展示整个微信数据
        $wx =  $Allshow->wxshowtimes();
        
        $wl_flag = 0; 
        $url = $Allshow->whereTime('time','week')->distinct(true)->column('location');
        //var_dump($url);
        $this->assign('url',$url);
        $this->assign('wl_flag',$wl_flag);
        $this->assign('wx',$wx);
         
        //var_dump($wx); 
        return $this->fetch();
    }


    public function table_data_tables41(){
        //微信号操作次数
        $Allshow = new Allshow;

        //分时段展示

         $wx2 =  $Allshow->wxshowtimes2();
         $wx3 =  $Allshow->showtime24();
         //var_dump($wx3);
         $this->assign('time_wx',$wx3);
         $this->assign('times_wxs',$wx2);

        
        return $this->fetch();
    }


    public function select_table_data_tables41()
    {
        $Allshow = new Allshow;
        $data = request()->param(); 
        //去到首末未的#
        $data["wxhao"] = rtrim($data["wxhao"],"#");
        $data["wxhao"] = ltrim($data["wxhao"],"#");
        $wx = explode('#', $data["wxhao"]);

        foreach ($wx as $key => $value) {
            $wxx[]= trim($value);
        }
        //一维数组去重
        $result_01 = array_flip($wxx);
        $result_02 = array_flip($result_01);
        $result    = array_merge($result_02);
        
        $wx2 =  $Allshow->wxshowtimes21($result);

         $wx3 =  $Allshow->showtime24();
         //var_dump($wx3);
         $this->assign('time_wx',$wx3);
         $this->assign('times_wxs',$wx2);
        //var_dump($wx2);
         return $this->fetch('table_data_tables41');

    }

    public function select_table_data_tables4(){
        //微信号操作次数
        $Allshow = new Allshow;
        $data = request()->param(); 
         //var_dump($data);
        $this->assign('datatime',$data);
        $url = $Allshow->whereTime('time','week')->distinct(true)->column('location');
        //var_dump($url);
        $this->assign('url',$url);
       // 
        
        if($data['web']!='1'){
                  if($data['star']==""||$data['end']==""){
                    $wl_flag = 1;
                    $this->assign('wl_flag',$wl_flag);
                    $wx =  $Allshow->wxshowtimes_1($data['web']);
                    $this->assign('wx',$wx);

                     $array = [  
                            'url'=>$data['web'],
                            'star'=>"",
                            'end'=>"",
                        ];    

                  $this->assign('datatime',$array);

                    return $this->fetch('table_data_tables4');

                }else{
                    $wl_flag = 1;
                    $this->assign('wl_flag',$wl_flag);      
                     $wx =  $Allshow->wxshowtimes1_11($data['star'],$data['end'],$data['web']);
                    $this->assign('wx',$wx);
                     $array = [  
                            'url'=>$data['web'],
                            'star'=>$data['star'],
                            'end'=>$data['end'],
                        ];    
				$url = $Allshow->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
                //var_dump($url);
                $this->assign('url',$url);
                  $this->assign('datatime',$array);
                    return $this->fetch('table_data_tables4');
                }
            }else{

                if($data['star']==""||$data['end']==""){
                    $wl_flag = 1;
                    $this->assign('wl_flag',$wl_flag);
                    $wx =  $Allshow->wxshowtimes();
                    $this->assign('wx',$wx);
                     $array = [  
                            'url'=>$data['web'],
                            'star'=>"",
                            'end'=>"",
                        ];    

                  $this->assign('datatime',$array);
                    return $this->fetch('table_data_tables4');

                }else{
                    $wl_flag = 1;
                    $this->assign('wl_flag',$wl_flag);      
                     $wx =  $Allshow->wxshowtimes1($data['star'],$data['end']);
                    $this->assign('wx',$wx);
                     $array = [  
                            'url'=>$data['web'],
                            'star'=>$data['star'],
                            'end'=>$data['end'],
                        ];    
					$url = $Allshow->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
                //var_dump($url);
                $this->assign('url',$url);
                  $this->assign('datatime',$array);
                    return $this->fetch('table_data_tables4');
                }

            }  

        
    }


    //********************关键词分析***************************

    public function table_data_tables5(){
        
        

        $Allshow = new Allshow;
        $url = $Allshow->whereTime('time','week')->distinct(true)->column('location');
        //var_dump($data);
        $this->assign('url',$url);

        $method = "utm_term";
        $wx =  $Allshow->keyword($method);
        $wxkey = $Allshow->wxkeyword($method);
        $wl_flag = 0;
        $this->assign('wl_flag',$wl_flag);
        $arr = $Allshow->datecheck($wxkey,$wx);
        
        $this->assign('arrs',$arr);

        return $this->fetch();
    }

    public function select_table_data_tables5(){
        //微信号操作次数
        //
        
         $Allshow = new Allshow;
        $url = $Allshow->whereTime('time','week')->distinct(true)->column('location');
        //var_dump($data);
        $this->assign('url',$url);

        $Allshow = new Allshow;
         $method = "utm_term";
        $data = request()->param(); 
         //var_dump($data);
        $this->assign('datatime',$data);

         if($data['web']!='1'){

                  if($data['star']==""||$data['end']==""){
                    $wl_flag = 1;
                    $this->assign('wl_flag',$wl_flag);

                    $wx =  $Allshow->keyword_1($method,$data['web']);
                    $wxkey = $Allshow->wxkeyword_1($method,$data['web']);

                    $arr = $Allshow->datecheck($wxkey,$wx);
                    $this->assign('arrs',$arr);
                     $array = [  
                            'url'=>$data['web'],
                            'star'=>"",
                            'end'=>"",
                        ];    

                  $this->assign('datatime',$array);
                    // $this->assign('wx',$wx);
                    // $this->assign('wxkeys',$wxkey);
                    return $this->fetch('table_data_tables5');

                    }else{
                    $wl_flag = 1;
                    $this->assign('wl_flag',$wl_flag);      
                     $wx =  $Allshow->keyword1_1($data['star'],$data['end'],$method,$data['web']);
                     $wxkey = $Allshow->wxkeyword1_1($data['star'],$data['end'],$method,$data['web']);
                      $arr = $Allshow->datecheck($wxkey,$wx);
                    $this->assign('arrs',$arr);

                    $array = [  
                            'url'=>$data['web'],
                            'star'=>$data['star'],
                            'end'=>$data['end'],
                        ];    
					
					$url = $Allshow->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
					//var_dump($url);
					$this->assign('url',$url);
                  $this->assign('datatime',$array);

                    // $this->assign('wx',$wx);
                    // $this->assign('wxkeys',$wxkey);
                    return $this->fetch('table_data_tables5');
                     }
            }else{


                    if($data['star']==""||$data['end']==""){
                        $wl_flag = 0;
                        $this->assign('wl_flag',$wl_flag);

                        $wx =  $Allshow->keyword($method);
                        $wxkey = $Allshow->wxkeyword($method);

                        $arr = $Allshow->datecheck($wxkey,$wx);
                        $this->assign('arrs',$arr);
                        $array = [  
                            'url'=>$data['web'],
                            'star'=>"",
                            'end'=>"",
                        ];    

                    $this->assign('datatime',$array);

                        // $this->assign('wx',$wx);
                        // $this->assign('wxkeys',$wxkey);
                        return $this->fetch('table_data_tables5');

                    }else{
                            $wl_flag = 1;
                            $this->assign('wl_flag',$wl_flag);      
                             $wx =  $Allshow->keyword1($data['star'],$data['end'],$method);
                             $wxkey = $Allshow->wxkeyword1($data['star'],$data['end'],$method);
                              $arr = $Allshow->datecheck($wxkey,$wx);
                            $this->assign('arrs',$arr);
                              $array = [  
                                        'url'=>$data['web'],
                                        'star'=>$data['star'],
                                        'end'=>$data['end'],
                                     ];    
							
							$url = $Allshow->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
							//var_dump($url);
							$this->assign('url',$url);
                             $this->assign('datatime',$array);
                            // $this->assign('wx',$wx);
                            // $this->assign('wxkeys',$wxkey);
                            return $this->fetch('table_data_tables5');
                     }


            }

       
    }

    //********************单元分析***************************
    


    public function table_data_tables6(){
        //微信号操作次数
       $Allshow = new Allshow;
       $url = $Allshow->whereTime('time','week')->distinct(true)->column('location');
        //var_dump($data);
        $this->assign('url',$url);
        $method = "utm_content";
        $wx =  $Allshow->keyword($method);
        $wxkey = $Allshow->wxkeyword($method);
        $wl_flag = 0;
        $this->assign('wl_flag',$wl_flag);
         $arr = $Allshow->datecheck($wxkey,$wx);
         $this->assign('arr',$arr);
       //  $this->assign('wx',$wx);
       // // var_dump($wxkey);
       //  $this->assign('wxkeys',$wxkey);
        //var_dump($wx);
        return $this->fetch();
    }

    public function select_table_data_tables6_1(){
        //微信号操作次数
        $Allshow = new Allshow;
         $method = "utm_content";
        $data = request()->param(); 
         //var_dump($data);
        $this->assign('datatime',$data);

          if($data['star']==""||$data['end']==""){
            $wl_flag = 0;
            $this->assign('wl_flag',$wl_flag);
            $wx =  $Allshow->keyword($method);
            $wxkey = $Allshow->wxkeyword($method);
             $arr = $Allshow->datecheck($wxkey,$wx);
             $this->assign('arr',$arr);
            // $this->assign('wx',$wx);
            // $this->assign('wxkeys',$wxkey);
            return $this->fetch('table_data_tables6');

        }else{
            $wl_flag = 1;
            $this->assign('wl_flag',$wl_flag);      
             $wx =  $Allshow->keyword1($data['star'],$data['end'],$method);
             $wxkey = $Allshow->wxkeyword1($data['star'],$data['end'],$method);
            $arr = $Allshow->datecheck($wxkey,$wx);
            $this->assign('arrs',$arr);
            // $this->assign('wx',$wx);
            // $this->assign('wxkeys',$wxkey);
			$url = $Allshow->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
							
			$this->assign('url',$url);
            return $this->fetch('table_data_tables6');
        }

       
    }
    

     public function select_table_data_tables6(){
        //微信号操作次数
        //
        
         $Allshow = new Allshow;
        $url = $Allshow->whereTime('time','week')->distinct(true)->column('location');
        //var_dump($data);
        $this->assign('url',$url);

        $Allshow = new Allshow;
         $method = "utm_content";
        $data = request()->param(); 
         //var_dump($data);
        $this->assign('datatime',$data);

         if($data['web']!='1'){

                  if($data['star']==""||$data['end']==""){
                    $wl_flag = 1;
                    $this->assign('wl_flag',$wl_flag);

                    $wx =  $Allshow->keyword_1($method,$data['web']);
                    $wxkey = $Allshow->wxkeyword_1($method,$data['web']);

                    $arr = $Allshow->datecheck($wxkey,$wx);
                    $this->assign('arr',$arr);
                    $array = [  
                                'url'=>$data['web'],
                                'star'=>$data['star'],
                                'end'=>$data['end'],
                             ];    

                    $this->assign('datatime',$array);
                    // $this->assign('wx',$wx);
                    // $this->assign('wxkeys',$wxkey);
                    return $this->fetch('table_data_tables6');

                    }else{
                    $wl_flag = 1;
                    $this->assign('wl_flag',$wl_flag);      
                     $wx =  $Allshow->keyword1_1($data['star'],$data['end'],$method,$data['web']);
                     $wxkey = $Allshow->wxkeyword1_1($data['star'],$data['end'],$method,$data['web']);
                      $arr = $Allshow->datecheck($wxkey,$wx);
                    $this->assign('arr',$arr);

                     $array = [  
                                'url'=>$data['web'],
                                'star'=>$data['star'],
                                'end'=>$data['end'],
                             ];    
						
						$url = $Allshow->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
							
						$this->assign('url',$url);
                    $this->assign('datatime',$array);
                    // $this->assign('wx',$wx);
                    // $this->assign('wxkeys',$wxkey);
                    return $this->fetch('table_data_tables6');
                     }
            }else{


                    if($data['star']==""||$data['end']==""){
                        $wl_flag = 1;
                        $this->assign('wl_flag',$wl_flag);

                        $wx =  $Allshow->keyword($method);
                        $wxkey = $Allshow->wxkeyword($method);

                        $arr = $Allshow->datecheck($wxkey,$wx);
                         $array = [  
                                'url'=>$data['web'],
                                'star'=>$data['star'],
                                'end'=>$data['end'],
                             ];    

                         $this->assign('datatime',$array);
                        $this->assign('arr',$arr);
                        
                        // $this->assign('wx',$wx);
                        // $this->assign('wxkeys',$wxkey);
                        return $this->fetch('table_data_tables6');

                    }else{
                            $wl_flag = 1;
                            $this->assign('wl_flag',$wl_flag);      
                             $wx =  $Allshow->keyword1($data['star'],$data['end'],$method);
                             $wxkey = $Allshow->wxkeyword1($data['star'],$data['end'],$method);
                              $arr = $Allshow->datecheck($wxkey,$wx);
                               $array = [  
                                    'url'=>$data['web'],
                                    'star'=>$data['star'],
                                    'end'=>$data['end'],
                                 ];    

                             $this->assign('datatime',$array);
                            $this->assign('arr',$arr);
							$url = $Allshow->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
							
							$this->assign('url',$url);
                            // $this->assign('wx',$wx);
                            // $this->assign('wxkeys',$wxkey);
                            return $this->fetch('table_data_tables6');
                     }


            }

       
    }


    //********************计划分析***************************
    //
    
    public function table_data_tables7(){
        //微信号操作次数
       $Allshow = new Allshow;

       $url = $Allshow->whereTime('time','week')->distinct(true)->column('location');
        //var_dump($data);
        $this->assign('url',$url);
        $method = "utm_medium";
        $wx =  $Allshow->keyword($method);
        $wxkey = $Allshow->wxkeyword($method);
        $wl_flag = 0;
        $this->assign('wl_flag',$wl_flag);
        $arr = $Allshow->datecheck($wxkey,$wx);
         $this->assign('arr',$arr);
       //  $this->assign('wx',$wx);
       // // var_dump($wxkey);
       //  $this->assign('wxkeys',$wxkey);
        //var_dump($wx);
        return $this->fetch();
    }

    public function select_table_data_tables7_1(){
        //微信号操作次数
        $Allshow = new Allshow;
         $method = "utm_medium";
        $data = request()->param(); 
         //var_dump($data);
        $this->assign('datatime',$data);

          if($data['star']==""||$data['end']==""){
            $wl_flag = 0;
            $this->assign('wl_flag',$wl_flag);
            $wx =  $Allshow->keyword($method);
            $wxkey = $Allshow->wxkeyword($method);
            $arr = $Allshow->datecheck($wxkey,$wx);
             $this->assign('arr',$arr);
            // $this->assign('wx',$wx);
            // $this->assign('wxkeys',$wxkey);
            return $this->fetch('table_data_tables7');

        }else{
            $wl_flag = 1;
            $this->assign('wl_flag',$wl_flag);      
             $wx =  $Allshow->keyword1($data['star'],$data['end'],$method);
             $wxkey = $Allshow->wxkeyword1($data['star'],$data['end'],$method);
             $arr = $Allshow->datecheck($wxkey,$wx);
            $this->assign('arrs',$arr);
            // $this->assign('wx',$wx);
            // $this->assign('wxkeys',$wxkey);
			$url = $Allshow->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
							
			$this->assign('url',$url);
            return $this->fetch('table_data_tables7');
        }

       
    }


     public function select_table_data_tables7(){
        //微信号操作次数
        //
        
         $Allshow = new Allshow;
        $url = $Allshow->whereTime('time','week')->distinct(true)->column('location');
        //var_dump($data);
        $this->assign('url',$url);

        $Allshow = new Allshow;
         $method = "utm_medium";
        $data = request()->param(); 
         //var_dump($data);
        $this->assign('datatime',$data);

         if($data['web']!='1'){

                  if($data['star']==""||$data['end']==""){
                    $wl_flag = 1;
                    $this->assign('wl_flag',$wl_flag);

                    $wx =  $Allshow->keyword_1($method,$data['web']);
                    $wxkey = $Allshow->wxkeyword_1($method,$data['web']);

                    $arr = $Allshow->datecheck($wxkey,$wx);

                     $array = [  
                                    'url'=>$data['web'],
                                    'star'=>$data['star'],
                                    'end'=>$data['end'],
                                 ];    

                     $this->assign('datatime',$array);

                    $this->assign('arr',$arr);
                    // $this->assign('wx',$wx);
                    // $this->assign('wxkeys',$wxkey);
                    return $this->fetch('table_data_tables7');

                    }else{
                    $wl_flag = 1;
                    $this->assign('wl_flag',$wl_flag);      
                     $wx =  $Allshow->keyword1_1($data['star'],$data['end'],$method,$data['web']);
                     $wxkey = $Allshow->wxkeyword1_1($data['star'],$data['end'],$method,$data['web']);
                      $arr = $Allshow->datecheck($wxkey,$wx);

                        $array = [  
                                    'url'=>$data['web'],
                                    'star'=>$data['star'],
                                    'end'=>$data['end'],
                                 ];    
						$url = $Allshow->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
							
						$this->assign('url',$url);
                     $this->assign('datatime',$array);
                    $this->assign('arr',$arr);
                    // $this->assign('wx',$wx);
                    // $this->assign('wxkeys',$wxkey);
                    return $this->fetch('table_data_tables7');
                     }
            }else{


                    if($data['star']==""||$data['end']==""){
                        $wl_flag = 1;
                        $this->assign('wl_flag',$wl_flag);

                        $wx =  $Allshow->keyword($method);
                        $wxkey = $Allshow->wxkeyword($method);

                        $arr = $Allshow->datecheck($wxkey,$wx);
                         $array = [  
                                    'url'=>$data['web'],
                                    'star'=>$data['star'],
                                    'end'=>$data['end'],
                                 ];    

                        $this->assign('datatime',$array);
                        $this->assign('arr',$arr);
                        // $this->assign('wx',$wx);
                        // $this->assign('wxkeys',$wxkey);
                        return $this->fetch('table_data_tables7');

                    }else{
                            $wl_flag = 1;
                            $this->assign('wl_flag',$wl_flag);      
                             $wx =  $Allshow->keyword1($data['star'],$data['end'],$method);
                             $wxkey = $Allshow->wxkeyword1($data['star'],$data['end'],$method);
                              $arr = $Allshow->datecheck($wxkey,$wx);
                               $array = [  
                                    'url'=>$data['web'],
                                    'star'=>$data['star'],
                                    'end'=>$data['end'],
                                 ];    
							$url = $Allshow->distinct(true)->whereTime('time','between',[$data['star'],$data['end']])->column('location');
							
							$this->assign('url',$url);
                            $this->assign('datatime',$array);
                            $this->assign('arr',$arr);
                            // $this->assign('wx',$wx);
                            // $this->assign('wxkeys',$wxkey);
                            return $this->fetch('table_data_tables7');
                     }


            }

       
    }


}
