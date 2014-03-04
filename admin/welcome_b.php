
<html lang="en">

<head>
	<meta charset="utf-8"/>
	<title>Dashboard Attendance Management</title>
	
	<link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="js/jquery-1.5.2.min.js" type="text/javascript"></script>
	<script src="js/hideshow.js" type="text/javascript"></script>
	<script src="js/jquery.tablesorter.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery.equalHeight.js"></script>
	<script type="text/javascript">
	$(document).ready(function() 
    	{ 
      	  $(".tablesorter").tablesorter(); 
   	 } 
	);
	$(document).ready(function() {

	//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});
    </script>
    <script type="text/javascript">
    $(function(){
        $('.column').equalHeight();
    });
</script>

</head>


<body>

	<header id="header">
		<hgroup>
			<h1 class="site_title"><a href="index.html"><img src="image/logo.gif" width="200" height="100"></a></h1>
			<h2 class="section_title">Dashboard Attendance Record Management</h2><!--<div class="btn_view_site"><a href="http://att.kinden.com.vn">View Site</a></div>-->
		</hgroup>
	</header> <!-- end of header bar -->
	
	<section id="secondary_bar">
		<?php
		     	session_start();
			$username = $_SESSION['id'];
			include("config.php");
			$sql_staff = "select * from staff where staff_id = '$username'";
			$result_staff = pg_query($connection,$sql_staff);
			$row_staff = pg_fetch_array($result_staff);
			$staffid = $row_staff['staff_id'];
			$staffname = $row_staff['name'];
		?>
		<div class="user">
			<p><?php echo $staffid . '-' . $staffname;?></p>
			<!-- <a class="logout_user" href="#" title="Logout">Logout</a> -->
		</div>
		<div class="breadcrumbs_container">
			<article class="breadcrumbs"><a href="index.html">Website Admin</a> <div class="breadcrumb_divider"></div> <a class="current">Dashboard</a></article>
		</div>
	</section><!-- end of secondary bar -->
	
	<aside id="sidebar" class="column">
		<form class="quick_search">
			<input type="text" value="Quick Search" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
		</form>
		<hr/>
		<h3>Staff Management</h3>
		<ul class="toggle">
			<li class="icn_new_article"><a href="welcome.php?1">Staff information</a></li>
			<li class="icn_edit_article"><a href="#">Edit staff</a></li>
			<!--<li class="icn_categories"><a href="#">Categories</a></li>
			<li class="icn_tags"><a href="#">Tags</a></li>-->
		</ul>
		<h3>Attendance Statistic</h3>
		<ul class="toggle">
			<li class="icn_add_user"><a href="welcome.php?12">Location statistic</a></li>
			<!--<li class="icn_view_users"><a href="#">View Users</a></li>
			<li class="icn_profile"><a href="#">Your Profile</a></li>-->
		</ul>
		<h3>Holiday</h3>
		<ul class="toggle">
			<li class="icn_folder"><a href="welcome.php?2">Input Holiday and SH</a></li>
			<!--<li class="icn_photo"><a href="#">Gallery</a></li>
			<li class="icn_audio"><a href="#">Audio</a></li>
			<li class="icn_video"><a href="#">Video</a></li>-->
		</ul>
		<h3>Leave</h3>
		<ul class="toggle">
			<li class="icn_settings"><a href="welcome.php?3">Input Leave</a></li>
			<li class="icn_security"><a href="welcome.php?31">Leave Statistic</a></li>
		</ul>
		<h3>OverTime For Group</h3>
		<ul class="toggle">
			<li class="icn_settings"><a href="welcome.php?4">Approve OT For Group</a></li>
			<!--<li class="icn_jump_back"><a href="#">Logout</a></li>-->
		</ul>
		<h3>IN-OUT For Group</h3>
		<ul class="toggle">
			<li class="icn_settings"><a href="welcome.php?5">Approve IN-OUT For Group</a></li>
			<!--<li class="icn_jump_back"><a href="#">Logout</a></li>-->
		</ul>
		<h3>Print and Submit</h3>
		<ul class="toggle">
			<li class="icn_settings"><a href="#">Print Attendance record</a></li>
			<li class="icn_security"><a href="#">Submit Attendance record</a></li>
			<!--<li class="icn_jump_back"><a href="#">Logout</a></li>-->
		</ul>
		<h3>LOGOUT</h3>
		<ul class="toggle">
			<li class="icn_jump_back"><a href="index.php">Logout</a></li>
		</ul>
		<footer>
			<hr />
			<p><strong>Kinden Viet Nam IT Team</p>
			<!--<p>Theme by <a href="http://www.medialoot.com">MediaLoot</a></p>-->
		</footer>
	</aside><!-- end of sidebar -->
	
	<section id="main" class="column">
		
		<!--<h4 class="alert_info">Welcome to the free MediaLoot admin panel template, this could be an informative message.</h4>-->
		
		<article class="module width_full">
		
			<!--<header><h3>Stats</h3></header>-->
			<div class="module_content">
				<!-- Content  -->
							<?php
							session_start();
							include("config.php");
							include("ot.php");
							$QS = $_SERVER["QUERY_STRING"];
							$month = $_POST['month'];
							if ($QS == 1)
								   {
									include('userinfo.php');
									}
							elseif ($QS == 2)
								 {
									include('holiday.php');
								 }
							elseif ($QS == 12)
								 {
									include('statistic.php');
								 }
							elseif($QS == 13){
								 include('search_att.php');
							}
							elseif($QS == 4){
								 include('overtime.php');
							}
							elseif($QS == 5){
								  include('in_out_for_group.php');
							}
							elseif($QS == 31){
								include('leaves_statistic.php');
							}
							else
								{
									include('leaves.php');
								}
							?>
				<!-- Content  -->
				<div class="clear"></div>
			</div>
		</article><!-- end of stats article -->
		<div class="spacer"></div>
	</section>


</body>

</html>