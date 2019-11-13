<?php
namespace app\index\controller;
use \think\Controller;
use \think\Request;
use app\index\model\Wxshow;
use app\index\model\Allshow;
use app\index\model\IpLocation;
class Index extends controller
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }


    public function addurl(){
    	//浏览次数统计
   		$request = Request::instance();
		$dates = $request->param();//获取所有参数，最全
		// $params = $_SERVER["HTTP_USER_AGENT"];//获取设备
		$userip =$request->ip();//获取ip
		//var_dump($dates);

		//接收数据
		$date1 = [ 
		  "location"     => $dates['kw_url'],//落地页链接
		  "souword"      => $dates['kw_ref'],//来源连接
		  "time"         => $dates['v'],//时间
		  "userip"       => $userip
		];
		

		$allshow=new Allshow;
		//将页面链接转换成数组
		$url_ary = $allshow->all_url($date1['location']);

		//******************************判断搜索词开始***********************************************
		$souword =$allshow->souword($date1['souword']);//判断搜索词
		// if( $souword == ""){
				
		// 		echo "no souword";	
		// 		die;
		// 	}

		//******************************判断搜索词结束***********************************************
		var_dump($url_ary);
		//
		//******************************检测域名是否绑定开始***********************************************
		//后期连接数据库，运营缓冲
		 $json_url="static/json/menu.json";//文件名称和路径
			                    // 写入文件
        if(!file_exists($json_url)){
            fopen($json_url,"w");
        }

		 $rs = file_get_contents($json_url);

		 $host=explode(",",str_replace('"','',$rs)); 
				
		$shost =in_array($url_ary['host'], $host);

		//var_dump($shost);

		if(!$shost){
			exit;
		}
		//*******************************检测域名是否绑定结束**********************************************
		
		//处理后的落地页
		$urlpaths = $url_ary['scheme']."://".$url_ary['host'].$url_ary['path'];
		
		// 即将单元，词，计划转换成数组
		// 	存在query				
			if(isset($url_ary['query'])){
				$ary = $allshow->convertUrlQuery($url_ary['query']);
				//将单元词和计划解码
				$urldecode_ary = $allshow->urldecode($ary);
				}else{
					$urldecode_ary =  [
									  'utm_medium'=> '',					 
									  'utm_content'=> '',	 
									  'utm_term'=>''
									  ];
					}


			var_dump($urldecode_ary);

			//***************************判断搜索词和单元计划是否存在一个*********************
				if(($souword=="")&&($urldecode_ary['utm_medium']=="")&&($urldecode_ary['utm_content']=="")&&($urldecode_ary['utm_term']==""))
				{
						echo "no souword or utm_medium  utm_content utm_term";	
						//die;
				}
			//***************************判断搜索词和单元计划是否存在一个*********************

			$date4 = [
					'sign_id'=>$dates['kw_sign_id'],
					'souword'=>$souword,
					'time'=>$date1['time'],
					'location'=>$urlpaths,
					'utm_medium'=> $urldecode_ary['utm_medium'],					 
					'utm_content'=> $urldecode_ary['utm_content'],	 
				    'utm_term'=>$urldecode_ary['utm_term']
				];

		//var_dump($date4);

		//数据保存
		$wxshow=new Wxshow;
		$wxshow->data($date4);
		$wxshow->save();


    }





     public function addcopy(){
     	//复制或点击统计
     	$request = Request::instance();
		$dates = $request->param();
   		 $params = $_SERVER["HTTP_USER_AGENT"];

   		 //var_dump($params);
		 $userip =$request->ip();

   		 $date2 = [
				"user_type"     => $dates['type'],
				 "location"     => $dates['kw_url'],
				 "copy_content" => $dates['c'],
				 "souword"      => $dates['kw_ref'],
				 "time"         => $dates['v'],
				 "equipment"    => $params,
				 "userip"       => $userip
			];
		
		$user_url = $date2['location'];


		//检测域名绑定
			$allshow=new Allshow;

			$souword =$allshow->souword($date2['souword']);	//判断是否存在搜索词
			// if( $souword == ""){
			// 	echo "没有搜索词";	
			// 	die;
			// }

			$url_ary = $allshow->all_url($user_url);
			$json_url="static/json/menu.json";//文件名称和路径
				                    // 写入文件
		        if(!file_exists($json_url)){
		            fopen($json_url,"w");
		        }
				 $rs = file_get_contents($json_url);

				 $host=explode(",",str_replace('"','',$rs)); 
				 $shost =in_array($url_ary['host'], $host);
					if(!$shost){
						exit;
					}

		//落地页连接
			$urlpath = $url_ary['scheme']."://".$url_ary['host'].$url_ary['path'];

	
			//
			//
			//即将单元，词，计划转换成数组
			//存在query				
			if(isset($url_ary['query'])){
				$ary = $allshow->convertUrlQuery($url_ary['query']);
				//将单元词和计划解码
				$urldecode_ary = $allshow->urldecode($ary);
				}else{
					$urldecode_ary =  [
									  'utm_medium'=> '',					 
									  'utm_content'=> '',	 
									  'utm_term'=>''
									  ];
			}

			//***************************判断搜索词和单元计划是否存在一个*********************
				if(($souword=="")&&($urldecode_ary['utm_medium']=="")&&($urldecode_ary['utm_content']=="")&&($urldecode_ary['utm_term']==""))
				{
						echo "no souword or utm_medium  utm_content utm_term";	
						//die;
				}
			//***************************判断搜索词和单元计划是否存在一个*********************

			//判断设备
			$equipment = $allshow->deviceType($date2['equipment']);
			// var_dump($equipment);

			//判断ip
			$getip =$allshow->getip($date2['userip']);
			//$test = '123.139.93.145';
			//$getip =$allshow->getip($test);
			
			 var_dump($getip);

			// if($getip =="0"){
			// 	$getip= [
			// 		$getip['province'] => '--',
			// 		$getip['city']   => '--'
			// 	];
			// }
				 

			$sourceType =$allshow->sourceType($date2['souword']);//平台
					
				// dump($sourceType);
				$alldate = [
						"location" => $urlpath,
						'souword' => $souword,
						'copy_content'	=> $date2['copy_content'],
						'sourceType'  =>$sourceType,
						'equipment' =>$equipment,
						'user_type' => $date2['user_type'],
						'user_ip' =>$date2['userip'],
						'utm_medium'=> $urldecode_ary['utm_medium'],			 
						'utm_content'=> $urldecode_ary['utm_content'], 
						'utm_term'=>$urldecode_ary['utm_term'],
						'region' =>$getip["province"],
						'city' =>$getip["city"],
						'sign_id'=>$dates['kw_sign_id'],
						'time' =>$date2['time']
					];

			var_dump($alldate);

			$allshow->data($alldate);
			
			$allshow->save();

    }



    public function test(){

    	$request = Request::instance();
		$dates = $request->param();
   		 $params = $_SERVER["HTTP_USER_AGENT"];

   		 //var_dump($params);
		 $userip =$request->ip();

		 var_dump($userip);

		 // 使用UTFwry数据查询插件
		 // $IpLocation= new IpLocation;
		 // $ip="117.179.58.193";
		 // $date = $IpLocation->getlocation($ip);
		 // var_dump($date);
		 // 
		 
		 //测试高德地图ip库
		 $gaode = new Allshow;
		 $ip='123.139.93.145';
		 $address= $gaode->getip($ip);
		 //var_dump($address);

		
    }	

}
