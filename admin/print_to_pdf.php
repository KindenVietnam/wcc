<?php
		// generate pdf file
		parse_str($_SERVER['argv'][1], $_GET); 
		$month = $_GET['thang'];
		require_once('tcpdf.php');
		include_once('config.php');  
		set_time_limit(0);
        $sql_staffid = "select staff_id from staff where staff_id >= '1000' and staff_id <= '1999' order by staff_id asc";
        $result_staffid = pg_query($connection, $sql_staffid);
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// array to contain staffid
		while($row_staffid = pg_fetch_object($result_staffid))       
		    {
				   // $_GET['month'] = $month;
                   // $_GET['staffid'] = $row_staffid->staff_id;
				   // include('in_pdf.php'); 
				   $manhanvien = $row_staffid->staff_id;
				   if($month == 1){
					   $last_m = 12;
					   $last_y = date("Y")-1;
					}
					else{
						$last_m = $month-1;
						$year = date("Y");
						$last_y = date("Y");
					}
					
					$khoang_1 = date("$last_y-$last_m-21");
					$khoang_2 = date("Y-$month-20");
					$nam_hien_tai = date('Y');
					// Include the main TCPDF library (search for installation path).
					require_once('tcpdf.php');
					// create new PDF document
					//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

					// set default header data
					$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 005', PDF_HEADER_STRING);

					// set header and footer fonts
					// set default monospaced font
					$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

					// set margins
					$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
					$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
					//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

					// set auto page breaks
					$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

					// set image scale factor
					//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

					// set some language-dependent strings (optional)
					if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
						require_once(dirname(__FILE__).'/lang/eng.php');
						$pdf->setLanguageArray($l);
					}

					// ---------------------------------------------------------

					// set font
					$pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
					$pdf->SetFont('times', '', 8);

					// add a page

					$pdf->AddPage();

					// set cell padding
					$pdf->setCellPaddings(0, 0, 0, 0);

					// set cell margins
					$pdf->setCellMargins(0, 0, 0, 0);

					// set color for background
					$pdf->SetFillColor(255, 255, 255);

					// funcion view on time current 
					$ngay_thang = date("F Y");
					$todayDate = date("Y-m-d");// current date
					$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "-1 month");
					$chuoi1 = "From 21 ";
					$chuoi2 = date('F', $dateOneMonthAdded);
					$chuoi3 = " to 20 ";
					$chuoi4 = date("F Y")  ;


					$txt = '';
					// Multicell test

					$pdf->SetFont('times', 'B', 8);
					$pdf->MultiCell(10, 6, 'Date'.$txt, 0, 'C', 0, 1, '23', '34', true);
					$pdf->MultiCell(25, 18, ''.$txt, 1, 'C', 0, 1, '15', '27', true);
					$pdf->MultiCell(15, 9, 'Attendance Mark '.$txt, 0, 'C', 0, 1, '40', '32', true);
					$pdf->MultiCell(15, 18, ''.$txt, 1, 'C', 0, 1, '40', '27', true);
					$pdf->MultiCell(20, 3, 'Overtime '.$txt, 0, 'C', 0, 0, '75', '28', true);
					$pdf->MultiCell(60, 6, ''.$txt, 1, 'C', 0, 0, '55', '27', true);
					$pdf->MultiCell(20, 3, 'Weekday '.$txt, 0, 'C', 0, 0, '55', '34', true);
					$pdf->MultiCell(20, 6, ''.$txt, 0, 'C', 0, 0, '55', '33', true);
					$pdf->MultiCell(20, 3, 'Holiday '.$txt, 0, 'C', 0, 0, '75', '34', true);
					$pdf->MultiCell(20, 6, ''.$txt, 1, 'C', 0, 0, '75', '33', true);
					$pdf->MultiCell(20, 3, 'Public Holiday'.$txt, 0, 'C', 0, 0, '95', '34', true);
					$pdf->MultiCell(20, 6, ''.$txt, 1, 'C', 0, 0, '95', '33', true);


					$pdf->MultiCell(10, 3, 'OT'.$txt, 0, 'C', 0, 0, '55', '40', true);
					$pdf->MultiCell(10, 6, ''.$txt, 1, 'C', 0, 0, '55', '39', true);

					$pdf->MultiCell(10, 3, 'ST'.$txt, 0, 'C', 0, 0, '65', '40', true);
					$pdf->MultiCell(10, 6, ''.$txt, 1, 'C', 0, 0, '65', '39', true);

					$pdf->MultiCell(10, 3, 'OT'.$txt, 0, 'C', 0, 0, '75', '40', true);
					$pdf->MultiCell(10, 6, ''.$txt, 0, 'C', 0, 0, '75', '39', true);

					$pdf->MultiCell(10, 3, 'ST'.$txt, 0, 'C', 0, 0, '85', '40', true);
					$pdf->MultiCell(10, 6, ''.$txt, 1, 'C', 0, 0, '85', '39', true);

					$pdf->MultiCell(10, 3, 'OT'.$txt, 0, 'C', 0, 0, '95', '40', true);
					$pdf->MultiCell(10, 6, ''.$txt, 1, 'C', 0, 0, '95', '39', true);


					$pdf->MultiCell(10, 3, 'ST'.$txt, 0, 'C', 0, 0, '105', '40', true);
					$pdf->MultiCell(10, 6, ''.$txt, 1, 'C', 0, 0, '105', '39', true);

					$pdf->MultiCell(15, 9, 'Job-Number'.$txt, 0, 'C', 0, 1, '115', '32', true);
					$pdf->MultiCell(15, 18, ''.$txt, 1, 'C', 0, 1, '115', '27', true);
					$pdf->MultiCell(25, 9, 'Details of work'.$txt, 0, 'C', 0, 1, '130', '34', true);
					$pdf->MultiCell(25, 18, ''.$txt, 1, 'C', 0, 1, '130', '27', true);
					$pdf->MultiCell(20, 3, 'Working Time '.$txt, 0, 'C', 0, 0, '165', '28', true);
					$pdf->MultiCell(40, 6, ''.$txt, 1, 'C', 0, 0, '155', '27', true);
					$pdf->MultiCell(20, 3, 'IN '.$txt, 0, 'C', 0, 0, '155', '37', true);
					$pdf->MultiCell(20, 12, ''.$txt, 1, 'C', 0, 0, '155', '33', true);
					$pdf->MultiCell(20, 3, 'OUT'.$txt, 0, 'C', 0, 0, '175', '37', true);
					$pdf->MultiCell(20, 12, ''.$txt, 1, 'C', 0, 0, '175', '33', true);

					$vitri_mang=array("15","25","40","55","65","75","85","95","105","115","130","155","175");
					$chieungang=array("10","15","15","10","10","10","10","10","10","15","25","20","20");
					$y=0;

					$sql_bang_cham_cong = "select * from bang_cham_cong where staff_id = '$manhanvien' and  '$khoang_1' <= date and date <= '$khoang_2' order by date asc";
					//$sql_bang_cham_cong   = "select * from bang_cham_cong where staff_id = '$manhanvien' and date >= '$khoang_1' and date <= '$khoang_2' order by date asc";
					$g=0;
					$result_bang_cham_cong = pg_query($connection, $sql_bang_cham_cong);
					while($rows_bang_cham_cong = pg_fetch_array($result_bang_cham_cong))
					{
					// paint rows
							   
								$g=45+$y;
								$ngay = date("j",strtotime($rows_bang_cham_cong['date']));
								$pdf->SetFont('times', '', 8);
								$pdf->MultiCell(10, 3, $ngay, 1, 'C', 0, 1, '15', $g, true);
								$pdf->MultiCell(15, 3, $rows_bang_cham_cong['weekday'], 1, 'C', 0, 1, '25', $g, true);
								$pdf->MultiCell(15, 3, $rows_bang_cham_cong['att_mark'], 1, 'C', 0, 1, '40', $g, true);
								$pdf->MultiCell(10, 3, $rows_bang_cham_cong['w_ot'], 1, 'C', 0, 1, '55', $g, true);
								$pdf->MultiCell(10, 3, $rows_bang_cham_cong['w_st'], 1, 'C', 0, 1, '65', $g, true);
								$pdf->MultiCell(10, 3, $rows_bang_cham_cong['h_ot'], 1, 'C', 0, 1, '75', $g, true);
								$pdf->MultiCell(10, 3, $rows_bang_cham_cong['h_st'], 1, 'C', 0, 1, '85', $g, true);
								$pdf->MultiCell(10, 3, $rows_bang_cham_cong['sh_ot'], 1, 'C', 0, 1, '95', $g, true);
								$pdf->MultiCell(10, 3, $rows_bang_cham_cong['sh_st'], 1, 'C', 0, 1, '105', $g, true);
								$pdf->MultiCell(15, 3, '', 1, 'C', 0, 1, '115', $g, true);
								$pdf->MultiCell(25, 3, $rows_bang_cham_cong['detailwork'], 1, 'C', 0, 1, '130', $g, true);
								$pdf->MultiCell(20, 3, $rows_bang_cham_cong['in_time'], 1, 'C', 0, 1, '155', $g, true);
								$pdf->MultiCell(20, 3, $rows_bang_cham_cong['out_time'], 1, 'C', 0, 1, '175', $g, true);

					$y = $y+4;// buoc nhay 4
					}

					$sql_bang_tong_overtime = "select * from bang_tong_overtime where staff_id = '$manhanvien' and date_part('year',date) = '$nam_hien_tai' and date_part('month',date) = '$month'";

					$result_bang_tong_overtime = pg_query($connection, $sql_bang_tong_overtime);
					$rows_bang_tong_overtime = pg_fetch_array($result_bang_tong_overtime);
					// paint for late-in and Early
					$pdf->SetFont('times', 'B', 8);
					$pdf->MultiCell(40, 3, 'Total(hrs)'.$txt, 1, 'C', 0, 1, '15', $g+4, true);
					$pdf->MultiCell(10, 3, $rows_bang_tong_overtime['t_w_ot'], 1, 'C', 0, 1, '55', $g+4, true);
					$pdf->MultiCell(10, 3, $rows_bang_tong_overtime['t_w_st'], 1, 'C', 0, 1, '65', $g+4, true);
					$pdf->MultiCell(10, 3, $rows_bang_tong_overtime['t_h_ot'], 1, 'C', 0, 1, '75', $g+4, true);
					$pdf->MultiCell(10, 3, $rows_bang_tong_overtime['t_h_st'], 1, 'C', 0, 1, '85', $g+4, true);
					$pdf->MultiCell(10, 3, $rows_bang_tong_overtime['t_sh_ot'], 1, 'C', 0, 1, '95', $g+4, true);
					$pdf->MultiCell(10, 3, $rows_bang_tong_overtime['t_sh_st'], 1, 'C', 0, 1, '105', $g+4, true);
					$pdf->MultiCell(15, 3, ''.$txt, 1, 'C', 0, 1, '115', $g+4, true);

					// late-in times this period 
					$sql_bang_count_early_lately = "select * from count_early_lately where staff_id= '$manhanvien' and date_part ('year',date) = '$nam_hien_tai' and date_part('month',date) = '$month'"; 
					$result_bang_count_early_lately = pg_query($connection,$sql_bang_count_early_lately);
					$rows_bang_count_early_lately = pg_fetch_array($result_bang_count_early_lately);
					$pdf->SetFont('times', 'B', 8);
					$pdf->MultiCell(60, 3, 'Late-In and Early-Out Times this period'.$txt, 1, 'L', 0, 1, '15', '178', true);
					$early_lately = $rows_bang_count_early_lately['early'] + $rows_bang_count_early_lately['lately'];
					$pdf->MultiCell(10, 3, $early_lately, 1, 'C', 0, 1, '75', '178', true);
					$pdf->MultiCell(60, 3, 'Forgot Times this period'.$txt, 1, 'L', 0, 1, '15', '182', true);
					$pdf->MultiCell(10, 3, $forgot, 1, 'C', 0, 1, '75', '182', true);

					// Status of working
					$sql_bang_tong_cham_cong = "select * from bang_tong_cham_cong where staff_id = '$manhanvien' and date_part('year',date) = '$nam_hien_tai' and date_part('month',date) = '$month'";

					$result_bang_tong_cham_cong = pg_query($connection, $sql_bang_tong_cham_cong);
					$rows_bang_tong_cham_cong = pg_fetch_array($result_bang_tong_cham_cong);
					$pdf->SetFont('times', 'B', 8);
					$pdf->MultiCell(40, 0, 'Status of working'.$txt, 0, 'L', 0, 1, '15', '190', true);
					$pdf->SetFont('times', '', 8);
					$pdf->MultiCell(60, 0, ''.$txt, 1, 'C', 0, 1, '25', '194', true);
					$pdf->MultiCell(10, 0, 'Days'.$txt, 1, 'L', 0, 1, '85', '194', true);
					$pdf->MultiCell(60, 0, 'O: Attendance'.$txt, 1, 'L', 0, 1, '25', '198', true);
					$pdf->MultiCell(10, 0, $rows_bang_tong_cham_cong['t_att'], 1, 'C', 0, 1, '85', '198', true);

					$pdf->MultiCell(60, 0, 'X: Absent'.$txt, 1, 'L', 0, 1, '25', '202', true);
					$pdf->MultiCell(10, 0, $rows_bang_tong_cham_cong['t_absent'], 1, 'C', 0, 1, '85', '202', true);
					$pdf->MultiCell(60, 0, 'L: Leaves '.$txt, 1, 'L', 0, 1, '25', '206', true);
					$pdf->MultiCell(10, 0, $rows_bang_tong_cham_cong['t_leaves'], 1, 'C', 0, 1, '85', '206', true);
					$pdf->MultiCell(60, 0, 'OH: Holiday'.$txt, 1, 'L', 0, 1, '25', '210', true);
					$pdf->MultiCell(10, 0, $rows_bang_tong_cham_cong['t_oh'], 1, 'C', 0, 1, '85', '210', true);
					$pdf->MultiCell(60, 0, 'SH: Other Special holiday'.$txt, 1, 'L', 0, 1, '25', '214', true);
					$pdf->MultiCell(10, 0, $rows_bang_tong_cham_cong['t_sh'], 1, 'C', 0, 1, '85', '214', true);
					$pdf->MultiCell(60, 0, 'Total Working days'.$txt, 1, 'L', 0, 1, '25', '218', true);
					$pdf->MultiCell(10, 0, $rows_bang_tong_cham_cong['t_workingday'], 1, 'C', 0, 1, '85', '218', true);
					//$pdf->MultiCell(60, 0, 'Staff in charge of Administration Dep'.$txt, 0, 'L', 0, 1, '25', '238', true);
					//job-performance
					/*$pdf->SetFont('times', 'B', 8);
					$pdf->MultiCell(100, 0, '<2> Job-Performance Condition (Evaluated by your Manager)'.$txt, 0, 'L', 0, 1, '105', '190', true);
					$pdf->SetFont('times', '', 8);
					$pdf->MultiCell(100, 0, 'Only in case of change for his/her working conditions,Enter here'.$txt, 0, 'L', 0, 1, '105', '194', true);
					$pdf->MultiCell(45, 0, 'Working Condition'.$txt, 1, 'C', 0, 1, '105', '198', true);
					$pdf->MultiCell(55, 0, ''.$txt, 1, 'C', 0, 1, '150', '198', true);
					$pdf->MultiCell(45, 0, 'Efficiency & Quality'.$txt, 1, 'C', 0, 1, '105', '202', true);
					$pdf->MultiCell(55, 0, ''.$txt, 1, 'C', 0, 1, '150', '202', true);
					$pdf->MultiCell(40, 0, 'Check by:'.$txt, 0, 'L', 0, 1, '105', '210', true);
					$pdf->MultiCell(60, 0, 'Manager of your Department(Section Manager)'.$txt, 0, 'L', 0, 1, '110', '214', true); */
					$pdf->MultiCell(40, 0, 'Approved by:'.$txt, 0, 'L', 0, 1, '105', '226', true);
					/*$pdf->MultiCell(40, 0, 'Project Manager:'.$txt, 0, 'L', 0, 1, '110', '238', true);
					$pdf->MultiCell(40, 0, 'ADM. General Manager'.$txt, 0, 'C', 0, 1, '130', '238', true);*/

					// paint Header

					$sql_ten_nhan_vien = "select * from staff where staff_id = '$manhanvien'";
					$result_ten_nhanvien = pg_query($connection,$sql_ten_nhan_vien);
					$rows_ten_nhan_vien  = pg_fetch_array($result_ten_nhanvien);

					$pdf->SetFont('times', 'B', 14);
					$pdf->MultiCell(60, 0, 'Attendance Record'.$txt, 0, 'L', 0, 1, '15', '6', true);
					$pdf->MultiCell(40, 0,  $ngay_thang, 0, 'L', 0, 1, '105', '6', true);
					$pdf->SetFont('times', 'B', 8);
					$pdf->MultiCell(60, 0, $chuoi1.$chuoi2.$chuoi3.$chuoi4, 0, 'R', 0, 1, '134','8', true);
					$pdf->SetFont('times', 'B', 10);
					$pdf->MultiCell(40, 0, 'Applicant'.$txt, 0, 'L', 0, 1, '15', '12', true);
					$pdf->MultiCell(20, 0, $rows_ten_nhan_vien['staff_id'],0, 'C', 0, 1, '55', '12', true);
					$pdf->MultiCell(60, 0, 'System Management Section'.$txt, 0, 'L', 0, 1, '75', '12', true);
					$pdf->MultiCell(80, 0, $rows_ten_nhan_vien['name'], 0, 'L', 0, 1, '75', '17', true);
					$pdf->MultiCell(80, 0, "Applicant's Signature".$txt, 0, 'L', 0, 1, '130', '17', true);

					$pdf->Ln(4);

					$pdf->lastPage();
				   
				   // END
			}	   
		$pdf->Output('att.pdf','F');
?>