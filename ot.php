<?php
function hoursToMinutes($hours)
   {
	if (strstr($hours, ':'))
	{
		# Split hours and minutes.
		$separatedData = split(':', $hours);

		$minutesInHours    = $separatedData[0] * 60;
		$minutesInDecimals = $separatedData[1];

		$totalMinutes = $minutesInHours + $minutesInDecimals;
	}
	else
	{
		$totalMinutes = $hours * 60;
	}

	return $totalMinutes;
   }

# Transform minutes like "105" into hours like "1:45".
function minutesToHours($minutes)
    {
	$hours          = floor($minutes / 60);
	$decimalMinutes = $minutes - floor($minutes/60) * 60;
	# Put it together.
	$hoursMinutes = sprintf("%d:%02.0f:00", $hours, $decimalMinutes);
	return $hoursMinutes;
    }
function ot($outtime)
  {
       	if ((hoursToMinutes('17:00')<hoursToMinutes($outtime))&&(hoursToMinutes($outtime)<=hoursToMinutes('22:00')))
	{
		# Split hours and minutes.
		$separatedData_outtime = split(':', $outtime);
                $hour_outtime    = $separatedData_outtime[0]*60;
		$minute_outtime = $separatedData_outtime[1];
		if($minute_outtime < 15){
				$ot = 0;
				}
		elseif((15<=$minute_outtime)&&($minute_outtime<30)){
				$ot = 15;
				}
		elseif((30<=$minute_outtime)&&($minute_outtime<45)){
				$ot = 30;
				}
		elseif((45<=$minute_outtime)&&($minute_outtime<60)){
				$ot = 45;
				}
		else {
				$ot = 60;
			}
		$total_minute = ($hour_outtime + $ot) - hoursToMinutes('17:00');
		$total_hour = $total_minute;
	}
	else{
		$total_hour = 0;
		}
        return $total_hour;
   }
function st($outtime)
	{
	if ((hoursToMinutes('22:00')<=hoursToMinutes($outtime))&&(hoursToMinutes($outtime)<=hoursToMinutes('24:00')))
		{
		$separatedData_outtime = split(':', $outtime);
                $hour_outtime    = $separatedData_outtime[0]*60;
		$minute_outtime = $separatedData_outtime[1];
		if($minute_outtime < 15){
				$ot = 0;
				}
		elseif((15<=$minute_outtime)&&($minute_outtime<30)){
				$ot = 15;
				}
		elseif((30<=$minute_outtime)&&($minute_outtime<45)){
				$ot = 30;
				}
		elseif((45<=$minute_outtime)&&($minute_outtime<60)){
				$ot = 45;
				}
		else {
				$ot = 60;
			}
		$total_minute = ($hour_outtime + $ot) - hoursToMinutes('22:00');
		$total_hour = $total_minute;
		}
	else{
		$total_hour = 0;
		}
        return $total_hour;
	}
function blank_space($time)
	{
		if ($time <> '0:00'){
			return $time;
			}
		else{
			return ' ';
			}
	}
?>
