<?php
include_once('config.php');
$del = $_GET['del'];
$staffid = $_GET['staffid'];
$name = $_GET['name'];
$workday = $_GET['workday'];
$thang = $_GET['thang'];
if($del == 1){
    $sql_delete_overtime = "delete from overtime where staff_id = '$staffid' and workday = '$workday'";
    pg_query($connection, $sql_delete_overtime);
    header("location:search_att.php?id=$staffid&name=$name&month=$thang&back=1");
}
if(isset($_POST['save'])){
    $option = $_POST['radio'];
    $reason = $_POST['reason'];
    $staff_code = $_POST['staffid'];
    $name = $_POST['staffname'];
    $month = $_POST['thang'];
    $ngay = $_POST['ngay'];
    $weekday = $_POST['weekday'];
    $att_mark = $_POST['att_mark'];
    $ot_w = $_POST['ot_w'];
    $st_w = $_POST['st_w'];
    $ot_h = $_POST['ot_h'];
    $st_h = $_POST['st_h'];
    $ot_oh = $_POST['ot_oh'];
    $st_oh = $_POST['st_oh'];
    $detail = $_POST['detail'];
    $in_time = $_POST['intime'];
    $out_time = $_POST['outtime'];
    $status_update = $_POST['status_update'];
    if($status_update > 0){
        $sql_update_overtime = "update overtime
        set att_mark = '$att_mark', wot = '$ot_w', wst = '$st_w', hot = '$ot_h', hst = '$st_h', pot = '$ot_oh', pst = '$st_oh',detailwork = '$detail'
        where staff_id = '$staff_code' and workday = '$ngay'";
        pg_query($connection, $sql_update_overtime);
        header("location:search_att.php?id=$staff_code&name=$name&month=$month&back=1");
    }
    if($option == 'o'){
        // over time
        $sql_insert_overtime = "INSERT INTO overtime(staff_id,workday,weekday,att_mark,wot,wst,hot,hst,pot,pst,detailwork) VALUES ('$staff_code','$ngay','$weekday','$att_mark','$ot_w','$st_w','$ot_h','$st_h','$ot_oh','$st_oh','$detail')";
        $q_result = pg_query($connection, $sql_insert_overtime);
        if(! $q_result){
         print '<script type="text/javascript">';
         print 'alert("Error  ! ")';
         print '</script>';
        }
        else{
            print '<script type="text/javascript">';
            print 'alert("Successfull ! ")';
            print '</script>';
            header("location:search_att.php?id=$staff_code&name=$name&month=$month&back=1");
        }

    }
    if($option == 'b'){//bussiness trips
        $timein = $ngay." ".$in_time;
        $timeout = $ngay." ".$out_time;
        $sql_insert_detail = "insert into overtime(staff_id,workday,detailwork) values('$staff_code','$ngay','$detail')";
        $sql_insert_inout = "insert into inout(staff_id,machine_no,checktime) values('$staff_code','1','$timein'),('$staff_code','1','$timeout')";
        pg_query($connection, $sql_insert_inout);
        pg_query($connection, $sql_insert_detail);
        header("location:search_att.php?id=$staff_code&name=$name&month=$month&back=1");
    }
    if($option == 'fi'){// forgot in
        $timein = $ngay." ".$in_time;
        $sql_insert_inout = "insert into inout(staff_id,machine_no,checktime) values('$staff_code','1','$timein')";
        $sql_insert_detail = "insert into overtime(staff_id,workday,detailwork) values('$staff_code','$ngay','$reason')";
        pg_query($connection, $sql_insert_inout);
        pg_query($connection, $sql_insert_detail);
        header("location:search_att.php?id=$staff_code&name=$name&month=$month&back=1");
    }
    if($option == 'fo'){ // forgot out
        $timeout = $ngay." ".$out_time;
        $sql_insert_inout = "insert into inout(staff_id,machine_no,checktime) values('$staff_code','1','$timeout')";
        $sql_insert_detail = "insert into overtime(staff_id,workday,detailwork) values('$staff_code','$ngay','$reason')";
        pg_query($connection, $sql_insert_inout);
        pg_query($connection, $sql_insert_detail);
        header("location:search_att.php?id=$staff_code&name=$name&month=$month&back=1");
    }
}
?>