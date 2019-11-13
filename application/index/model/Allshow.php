<?php

namespace app\index\model;

use think\Model;

class Allshow extends Model
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
			
				 //$ipurl="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
				 $ipurl="http://restapi.amap.com/v3/ip?key=yourkey&ip=".$ip;
				 
				 //try{
			       // $ip=json_decode(file_get_contents($ipurl));
			        $ip=json_decode(file_get_contents($ipurl),true);
			    
			    	//判断ip是否正确,如果不正确就输出--
			    	if($ip['status'] =="0" || is_array($ip['adcode'])){

			    			echo "aaaaaaaa";
							 $ip['province'] = '--';
							 $ip['city']   = '--';	
					}

					
			      	 var_dump($ip);
			      	 
			      	 return $ip;
			      	// if((string)$ip->code=='1'){
				      //      return false;
				      //   }else{
				      //     	$data = (array)$ip->data;
				      //     	return $data;
				      //   }
			 
			
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
			//
			if( isset($value['utm_medium']) && isset($value['utm_content']) && isset($value['utm_term']) ){
				$date = [

						 //"cid"         => $value['cid'],
						 "utm_medium"  => urldecode($value['utm_medium']),
						 "utm_content" => urldecode($value['utm_content']),
						 "utm_term"    => urldecode($value['utm_term']),			
						];
			}else{
					$date = [

						 //"cid"         => $value['cid'],
						 "utm_medium"  => '',
						 "utm_content" => '',
						 "utm_term"    => '',			
					];
			}
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

}


