<?php

namespace app\admin\model;

use think\Model;

class Allshow extends model
{

		public function getCity($useSina,$ip)
		{
		    if($useSina){
		        $ipurl = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=".$ip;
		        $ipdata=json_decode(file_get_contents($ipurl),true);
		        $data = $ipdata;
				//echo '新浪';
		    }else{
		        $ipurl="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
		        $ip=json_decode(file_get_contents($ipurl));   
		        if((string)$ip->code=='1'){
		           return false;
		        }
		        $data = (array)$ip->data;
				//echo '淘宝';
		    }
		    
		    return $data;   
		}
		//获取省份
		public function getip($ip)
		{
			try{
				 $ipurl="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
			        $ip=json_decode(file_get_contents($ipurl));
			       // $ip=file_get_contents($ipurl);
			       
			      	 var_dump($ip);
			      	if((string)$ip->code=='1'){
				           return false;
				        }else{
				          	$data = (array)$ip->data;
				          	return $data;
				        }
			 } 
			 catch ( exception $act){
			 		return 0;
			 }     

		       //return $ip;
		}

		function sourceType($source)
			{//检测平台
		        if(strpos($source, 'baidu.com')){
						return '百度';
					}else if(strpos($source, 'so.com')){
						return '360';
					}else if(strpos($source, 'sogou.com')){
						return '搜狗';
					}else if(strpos($source, 'sm.cn')){
						return '神马';
					}else if(strpos($source, 'sina.cn')){
						return '新浪';
					}else if(strpos($source, 'ifeng.com')){
						return '凤凰';
					}else if(strpos($source, 'qq.com')){
						return '腾讯';
					}else if($source ==''){
						return '--';
					}
					else {						
							return '--';						
					}
    		}




    	//将页面链接转换成数组
		public function all_url($url)
		{
			
			$arr = parse_url($url);
			//dump($arr);
			return $arr; 

		}

		public function arrayUtil($arr,$key)
		{
				//检查数组中该键值对是否存在有值
				if(isset($arr[$key])){
					return $arr[$key];
				}else{
					return "";
				}
 		}

 		public function convertUrlQuery($query)

			{	//将url中的query转换成二位数组
				
				if(empty($query)){
					return array();
				}
			    $queryParts = explode('&', $query); 
			    $params = array();
			    foreach ($queryParts as $param) 
				{ 
			        $item = explode('=', $param);
					if(!isset($item[1])){
						continue;
					}else{
						$params[$item[0]] = $item[1];
					}
			    } 
			    
			    return $params; 
			}

		public function urldecode($value)
		{
			//将词，单元，计划解码
			$date = [

				 "cid"         => $value['cid'],
				 "utm_medium"  => urldecode($value['utm_medium']),
				 "utm_content" => urldecode($value['utm_content']),
				 "utm_term"    => urldecode($value['utm_term']),			
			];

			return $date;
		}	



	 	public	 function deviceType($ua)
	 	{	//判断设备是否是
	        $agent = strtolower($ua);

	        $is_pc = (strpos($agent, 'windows nt')) ? true : false;

	        $is_iphone = (strpos($agent, 'iphone')) ? true : false;

	        $is_android = (strpos($agent, 'android')) ? true : false;

			$is_oppo = (strpos($agent, 'oppobrowser')) ? true : false;

	        $is_ipad = (strpos($agent, 'ipad')) ? true : false;

			$is_mac = (strpos($agent, 'mac os')) ? true : false;

			$is_linux = (strpos($agent, 'linux')) ? true : false;

	        if($is_pc){
	              return  'PC';

	        }else if($is_iphone){
	              return  'iphone';

	        }else if($is_android||$is_oppo){
	              return  'android';

	        }else if($is_ipad){
	              return  'ipad';

	        }else if($is_mac){
	              return  'PC';

	        }else if($is_linux){

				  return 'PC';
			}else{
				  return 'other';
			}
		}




	//获取搜索词	
    	public function souword($burl){
    		//分解成数组
    		$arr= parse_url($burl);	
    		//将query分解成数组
    		//$a = $this->arrayUtil($arr,'query');
    		$arr_query2 = $this->convertUrlQuery($this->arrayUtil($arr,'query'));
    		
    		//exit;
  	 		$wd="";
			if( $this->arrayUtil($arr_query2,'word')!=""){
				$wd=$arr_query2["word"];
			}else{
				$wd= $this->arrayUtil($arr_query2,'wd');
			}


			if(strstr($burl,"yz.m.sm.cn")||strstr($burl,"m.yz.sm.cn")||strstr($burl,"so.m.sm.cn")){
	
					$wd=$arr_query2["q"];
				}

				if(strstr($burl,"m.sogou.com")||strstr($burl,"sogou.com")){
					$wd=$arr_query2["keyword"];
					if($wd==""){
						$wd=$arr_query2["query"];
					}
				}

				if(strstr($burl,"m.so.com")||strstr($burl,"so.com")){
					
					$wd=$arr_query2["q"];
				}


				// if($wd==""&&!empty($keyword)){
				// 		$wd=$keyword;
				// 	}

					$wd=urldecode($wd);

					if(strstr($wd, '%')){
						$wd=urldecode($wd);
					}

					$wd=preg_replace("/[^\x{4e00}-\x{9fa5}^0-9^A-Z^a-z]+/u", '', $wd);
					$wd=trim($wd);
					if($wd == NULL){
						return '';
						//dump($wd);
					}else{
						return $wd;
					}
					
					//
    	}

    	public function time24(){
    		date_default_timezone_set("Asia/Shanghai");

				$a  = time();
				$now  = strtotime(date("Y-m-d H",$a).":00:00")+3600;
				$start = strtotime('-1 days'); //获得往前推24小时的时间点。
				for ($i=$now; $i>=$start; $i-=3600)  //3600秒是按每小时生成一条，如果按天或者月份换算成秒即可
				{
					$chuodate[]= $i;
				    $date[] = date('Y-m-d H:i',$i); //存储过去24内每个小时的节点
				}
				$chuodate = array_reverse($chuodate);
				//var_dump($date);
				return $chuodate;
				//var_dump($date);

    	}

    	public function showtime24(){
    		date_default_timezone_set("Asia/Shanghai");

				$a  = time();
				$now  = strtotime(date("Y-m-d H",$a).":00:00")+3600;
				$start = strtotime('-1 days')+3600; //获得往前推24小时的时间点。
				for ($i=$now; $i>=$start; $i-=3600)  //3600秒是按每小时生成一条，如果按天或者月份换算成秒即可
				{
					$j = $i-3600;
					$chuodate[]= [$i,$j];
				    $date[] = date('Y-m-d H:i',$i); //存储过去24内每个小时的节点
				}
				$chuodate = array_reverse($chuodate);
				//var_dump($date);
				return $chuodate;
				//var_dump($date);

    	}

    	//微信号操作次数查询今天全部微信号
    	public function wxshowtimes(){
    		$wx = $this->distinct(true)->column('copy_content');
    		
			 $wx1 = $this->whereTime('time','today')->group('user_ip')->column('copy_content');
     		$date = array_count_values($wx1); 
    		//var_dump($date);
    		return $date;
    		
    	}

    	public function wxshowtimes2(){
    		$start = strtotime('-1 days'); 
    		$wx = $this->distinct(true)->whereTime('time','>',$start)->column('copy_content');
			//$wx1 = $this->whereTime('time','today')->column('copy_content');

    		//var_dump($wx);

    		//一个微信号查24遍
    		foreach ($wx as $key => $value) {
    			$chuodate =$this->time24();
		    		for($i=0; $i<count($chuodate)-1;$i++){
		    			$wx2 = $this->whereTime('time','between',[$chuodate[$i], $chuodate[$i+1]])->where('copy_content',$value)->group('user_ip')->column('copy_content');

		    			if(empty($wx2)){
							$date = count($wx2);
		    			}else{
		    				$date = count($wx2);
		    			}

		    			 $allarry[$value][] = $date;
		    			
		    		}
    		}
    // 		$chuodate =$this->time24();
    // 		for($i=0; $i<count($chuodate)-1;$i++){
    // 			$wx2 = $this->whereTime('time','between',[$chuodate[$i+1], $chuodate[$i]])->column('copy_content');
    // 			$date = array_count_values($wx2); 
    // 			if($date==""){
    // 				$date[]=0;
    // 			}else{
				// //var_dump($date);
				// //
    // 				$allarry[] = $date;
    // 			}
    // 		}
		
    		//var_dump($allarry);
    		return $allarry;
    		
    	}


    	public function wxshowtimes21($wx){
    		$start = strtotime('-1 days'); 
    		//$wx = $this->distinct(true)->whereTime('time','>',$start)->column('copy_content');
			

    		//一个微信号查24遍
    		foreach ($wx as $key => $value) {
    			$chuodate =$this->time24();
		    		for($i=0; $i<count($chuodate)-1;$i++){
		    			$wx2 = $this->whereTime('time','between',[$chuodate[$i], $chuodate[$i+1]])->where('copy_content',$value)->group('user_ip')->column('copy_content');

		    			if(empty($wx2)){
							$date = count($wx2);
		    			}else{
		    				$date = count($wx2);
		    			}

		    			 $allarry[$value][] = $date;
		    			
		    		}
    		}
    		return $allarry;
    		
    	}

    	

    	//查询指定连接今天微信号
    	public function wxshowtimes_1($url){
    		//$wx = $this->distinct(true)->column('copy_content');
    		$wx1 = $this->where('location',$url)->whereTime('time','today')->group('user_ip')->column('copy_content');
    		$date = array_count_values($wx1); 
    		return $date;
    		//var_dump($date);
    	}

    	//查询指定日期所有微信号
    	public function wxshowtimes1($star,$end){
    		//$wx = $this->distinct(true)->column('copy_content');
    		$wx1 = $this->whereTime('time','between',[$star, $end])->group('user_ip')->column('copy_content');
    		$date = array_count_values($wx1); 
    		return $date;
    		//var_dump($date);
    	}	

    	//查询指定连接指定时间段微信号
    	public function wxshowtimes1_11($star,$end,$url){
    		//$wx = $this->distinct(true)->column('copy_content');
    		$wx1 = $this->where('location',$url)->whereTime('time','between',[$star, $end])->group('user_ip')->column('copy_content');
    		$date = array_count_values($wx1); 
    		return $date;
    		//var_dump($date);
    	}	


    	//关键字,单元，计划分析

    	public function keyword($method){
    		//$wx = $this->distinct(true)->column('copy_content');
    		$wx1 = $this->whereTime('time','today')->column($method);
    		$date = array_count_values($wx1); 
    		//var_dump($date);
    		return $date;
    		//var_dump($date);
    	}

    	//查询指定连接当天内容
    	public function keyword_1($method,$url){
    		//$wx = $this->distinct(true)->column('copy_content');
    		$wx1 = $this->where('location',$url)->whereTime('time','today')->column($method);
    		$date = array_count_values($wx1); 
    		//var_dump($date);
    		return $date;
    		//var_dump($date);
    	}

    	//查询指定连接,指定时间段的内容
    	public function keyword_11($method,$url,$star,$end){
    		//$wx = $this->distinct(true)->column('copy_content');
    		$wx1 = $this->where('location',$url)->whereTime('time','between',[$star, $end])->column($method);
    		$date = array_count_values($wx1); 
    		//var_dump($date);
    		return $date;
    		//var_dump($date);
    	}


    	public function wxkeyword($method){
    		$key = new Wxshow;
    		$keys = $key->whereTime('time','today')->column($method);
			$keynum = array_count_values($keys);
			//var_dump($keynum);
			return $keynum;
    		
    	}

    	//指定连接并且是当天内容
    	public function wxkeyword_1($method,$url){
    		$key = new Wxshow;
    		$keys = $key->where('location',$url)->whereTime('time','today')->column($method);
			$keynum = array_count_values($keys);
			//var_dump($keynum);
			return $keynum;
    		
    	}

    	public function wxkeyword1($star,$end,$method){
    		$key = new Wxshow;
    		$keys = $key->whereTime('time','between',[$star, $end])->column($method);
			$keynum = array_count_values($keys);
			//var_dump($keynum);
			return $keynum;
    		
    	}
		//指定时间和连接内容
    	public function wxkeyword1_1($star,$end,$method,$url){
    		$key = new Wxshow;
    		$keys = $key->where('location',$url)->whereTime('time','between',[$star, $end])->column($method);
			$keynum = array_count_values($keys);
			//var_dump($keynum);
			return $keynum;
    		
    	}

    	public function keyword1($star,$end,$method){
    		//$wx = $this->distinct(true)->column('copy_content');
    		$wx1 = $this->whereTime('time','between',[$star, $end])->column($method);
    		$date = array_count_values($wx1); 
    		return $date;
    		//var_dump($date);
    	}
    	//指定时间和连接内容
    	public function keyword1_1($star,$end,$method,$url){
    		//$wx = $this->distinct(true)->column('copy_content');
    		$wx1 = $this->where('location',$url)->whereTime('time','between',[$star, $end])->column($method);
    		$date = array_count_values($wx1); 
    		return $date;
    		//var_dump($date);
    	}






    	public function datecheck($wxkey,$wx){
    		  $arr = array();
		        foreach ($wxkey as $key => $value) {

		            $wlfl =array_key_exists($key, $wx);
		            if($wlfl)//存在该键值对
		            {
		                $a = array($key,$value,$wx[$key]);
		                array_push($arr,$a);
		            }else{
		                $a = array($key,$value,"0");
		                array_push($arr,$a);   
		            }
		        }


		         for($i=0; $i< count($arr); $i++){
		            if($arr[$i]['1']==0 || $arr[$i]['2']==0 ){
		                $a = "0%";
		                array_push($arr[$i],$a);
		            }else{
		                $num = $arr[$i]["2"]/$arr[$i]["1"];
		                $a= number_format($num,5)*100;
		                $b = $a."%";
		                array_push($arr[$i],$b);
		            }
		        } 



		        return $arr;
    	}


}


