  <?php
  function count_sat($month){
    $curr_month = $month;
    $last_year = date("Y")-1;
    $curr_year = date("Y");
    if($curr_month == 1){
        $last_month = 12;
        $days_in_last_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $last_year);
    }
    else{
        $last_month = $month - 1;
        $days_in_last_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $curr_year);
    }
    switch ($days_in_last_month) {
        case 31:
            $rows = 51;
            break;
        case 30:
            $rows = 50;
            break;
        case 29:
            $rows = 49;
            break;
        case 28:
            $rows = 48;
            break;
    }
    $count_sat = 0;
    for ($i=21;$i<=$rows;$i++){
	  if ($i <= $days_in_last_month)
	 	{
		  if ($curr_month == 1){
			$date = date("$i-$last_month-$last_year");
			$c_date = date("$last_year-$last_month-$i");
			}
		  else{
			$date = date("$i-$last_month-Y");
			$c_date = date("Y-$last_month-$i");
		      }
		}
	  else{
		  switch ($days_in_last_month){
			  case 31:{
				    $n = $i - 31;
				    $c_date = date("Y-$curr_month-$n");
				    break;
				  }
			  case 30:{
				    $n = $i - 30;
				    $c_date = date("Y-$curr_month-$n");
				    break;
				  }
			  case 29:{
				    $n = $i - 29;
				    $c_date = date("Y-$curr_month-$n");
				    break;
				  }
			  case 28:{
				    $n = $i - 28;
				    $c_date = date("Y-$curr_month-$n");
				    break;
				  }
			}
	      }
	$wday = date("D",strtotime($c_date));
	if(strcmp(trim($wday),'Sat')==0){
	  $count_sat = $count_sat + 1;
	}
    }
    return $count_sat;
  }
?>