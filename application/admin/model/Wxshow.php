<?php

namespace app\admin\model;

use think\Model;

class Wxshow extends Model
{

	 public function state($url)
	{

		$date = new Allshow;
		$arr=[];
		for($i=0;$i<count($url);$i++){

            //浏览次数
            $data2 = $this->where('location','=',$url[$i])->whereTime('time','today')->count();
                //$this->assign('data2',$data2);
            //操作次数1
            $data = $date->where('location','=',$url[$i])->whereTime('time','today')->count();

            //操作次数2
           // $data3 = $date->where('location','=',$url[$i])->whereTime('time','today')->where('souword','<>','')->count();
            //$data4 = $date->where('location','=',$url[$i])->whereTime('time','today')->where('souword','=','')->count();

            //var_dump($data4);
            if($data2 ==0){
            	$zhuan =0.000;
            	$zhuan2 =0.000;       	
            }else{
            	//转化率1
            $num = $data/$data2;
            $zhuan =number_format($num,5)*100;
             //转化率2
             //$num2 = $data3/$data2;
           // $zhuan2 =number_format($num2,3);

            }
            
            //复制次数
            //$data4 = $date->where('location','=',$url[$i])->whereTime('time','today')->where('souword','<>','')->where('user_type','=','3')->count();

            //点击次数
            //$data5 = $date->where('location','=',$url[$i])->whereTime('time','today')->where('souword','<>','')->where('user_type','=','2')->count();

            //ip个数column('name')
           $data6 =count($date->where('location','=',$url[$i])->whereTime('time','today')->distinct(true)->column('user_ip'));
            
            $wl_date = [
            		'url'=>$url[$i],
                    //总数
                    'data'=>$data2,
                     //操作次数1
                    'data2'=>$data,
                    //操作次数2
                   // 'data3'=>$data3,
                    //'data4'=>$data4,
                    //'data5'=>$data5,
                    'data6'=>$data6,
                    'zhuan'=>$zhuan."%",
                    //'zhuan2'=>$zhuan2                  
                    ];
                    
             array_push($arr, $wl_date);   
            
        }
		
        return $arr;
	}




     public function state2($url,$star,$end)
    {

        $date = new Allshow;
        $arr=[];
        for($i=0;$i<count($url);$i++){

            //浏览次数
            $data2 = $this->where('location','=',$url[$i])->whereTime('time','between',[$star, $end])->count();
                //$this->assign('data2',$data2);
            //操作次数1
            $data = $date->where('location','=',$url[$i])->whereTime('time','between',[$star, $end])->count();

            //操作次数2
          //  $data3 = $date->where('location','=',$url[$i])->where('souword','<>','')->whereTime('time','between',[$star, $end])->count();

            if($data2 ==0){
                $zhuan =0.000;
                $zhuan2 =0.000;         
            }else{
                //转化率1
            $num = $data/$data2;
            $zhuan =number_format($num,5)*100;
             //转化率2
            // $num2 = $data3/$data2;
           // $zhuan2 =number_format($num2,3);

            }
            
            //复制次数
           // $data4 = $date->where('location','=',$url[$i])->whereTime('time','between',[$star, $end])->where('souword','<>','')->where('user_type','=','3')->count();

            //点击次数
           // $data5 = $date->where('location','=',$url[$i])->whereTime('time','between',[$star, $end])->where('souword','<>','')->where('user_type','=','2')->count();

            //ip个数column('name')
           $data6 =count($date->where('location','=',$url[$i])->whereTime('time','between',[$star, $end])->distinct(true)->column('user_ip'));
            
            $wl_date = [
                    'url'=>$url[$i],
                    //总数
                    'data'=>$data2,
                     //操作次数1
                    'data2'=>$data,
                    //操作次数2
                    //'data3'=>$data3,
                    //'data4'=>$data4,
                    //'data5'=>$data5,
                    'data6'=>$data6,
                    'zhuan'=>$zhuan."%",
                    //'zhuan2'=>$zhuan2                  
                    ];
                    
             array_push($arr, $wl_date);   
            
        }
        
        return $arr;
    }



     public function state3($url)
    {

        $date = new Allshow;
        $arr=[];
       

            //浏览次数
            $data2 = $this->where('location','=',$url)->whereTime('time','today')->count();
                //$this->assign('data2',$data2);
            //操作次数1
            $data = $date->where('location','=',$url)->whereTime('time','today')->count();

            if($data2 ==0){
                $zhuan =0.000;
                $zhuan2 =0.000;         
            }else{
                //转化率1
            $num = $data/$data2;
            $zhuan =number_format($num,5)*100;
             

            }
            

            //ip个数column('name')
           $data6 =count($date->where('location','=',$url)->whereTime('time','today')->distinct(true)->column('user_ip'));
            
            $wl_date = [
                    'url'=>$url,
                    //总数
                    'data'=>$data2,
                     //操作次数1
                    'data2'=>$data,
                   
                    'data6'=>$data6,
                    'zhuan'=>$zhuan."%",
                                   
                    ];
                    
             //array_push($arr, $wl_date);   
            
        
        
        return $wl_date;
    }




     public function state4($url,$star,$end)
    {

        $date = new Allshow;
        $arr=[];
       

            //浏览次数
            $data2 = $this->where('location','=',$url)->whereTime('time','between',[$star, $end])->count();
                //$this->assign('data2',$data2);
            //操作次数1
            $data = $date->where('location','=',$url)->whereTime('time','between',[$star, $end])->count();

            if($data2 ==0){
                $zhuan =0.000;
                $zhuan2 =0.000;         
            }else{
                //转化率1
            $num = $data/$data2;
            $zhuan =number_format($num,5)*100;
             

            }
            

            //ip个数column('name')
           $data6 =count($date->where('location','=',$url)->whereTime('time','between',[$star, $end])->distinct(true)->column('user_ip'));
            
            $wl_date = [
                    'url'=>$url,
                    //总数
                    'data'=>$data2,
                     //操作次数1
                    'data2'=>$data,
                   
                    'data6'=>$data6,
                    'zhuan'=>$zhuan."%",
                                   
                    ];
                    
             //array_push($arr, $wl_date);   
            
        
        
        return $wl_date;
    }


}


