<?php
    namespace core\utils;

    	class Model_DateTime
    	{
    		public function ($timestamp)
    		{
    			$now      = time();
    			$today    = strtotime(date('Y-m-d',$now));
    			$ytoday   = $today - 86400;
    			$tomorrow = $today + 86400;

    			$month = array(null,'янв','фев','мар','апр','мая','июн','июл','авг','сен','окт','ноя','дек');

    			if($timestamp >= $today and $timestamp < $tommorow){

    			}
    		}

    		public static function liveTimeFormat($mysql)
            {
                $timestamp = strtotime($mysql);

                $now = getdate();
                $old = getdate($timestamp);

                $today_str    = "сегодня в ";
                $tommorow_str = "вчера в ";
                
                //today
                if(
                	$now['year'] == $old['year'] and 
                	$now['mon']  == $old['mon']  and 
                	$now['mday'] == $old['mday']
                ){
                	if($old['hours']   < 10)  {$old['hours']   = "0".$old['hours'];}
                	if($old['minutes'] < 10)  {$old['minutes'] = "0".$old['minutes'];}

                    $echo = $today_str.$old['hours'].":".$old['minutes'];
                }
                else
                //yesterday
                if(
                	$now['year'] == $old['year'] and 
                	$now['mon'] == $old['mon']   and 
                	$now['mday'] == $old['mday'] + 1
                ){
                	if($old['hours'] < 10)  {$old['hours']   = "0".$old['hours'];}
                	if($old['minutes'] < 10){$old['minutes'] = "0".$old['minutes'];}
                    
                    $echo = $tommorow_str.$old['hours'].":".$old['minutes'];
                }
                else
                //same year
                if($now['year'] == $old['year']){
                        if($old['mday'] < 10)   {$old['mday']    = "0".$old['mday'];}
                        if($old['mon'] < 10)    {$old['mon']     = "0".$old['mon'];}
                        if($old['hours'] < 10)  {$old['hours']   = "0".$old['hours'];}
                        if($old['minutes'] < 10){$old['minutes'] = "0".$old['minutes'];}

                        $echo = $old['mday'].".".$old['mon']." ".$old['hours'].":".$old['minutes'];
                }
                else
                //another year
                {
                    if($old['mday'] < 10)   {$old['mday'] = "0".$old['mday'];}
                    if($old['mon']  < 10)   {$old['mon']  = "0".$old['mon'];}
                    if($old['year'] < 10)   {$old['year'] = "0".$old['year'];}

                    $echo = $old['mday'].".".$old['mon'].".".$old['year'];
                }

                return $echo;
            }
    	}
?>