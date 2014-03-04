<?php
    include_once('phpToPDF.php') ;
    //Code to generate PDF file from specified URL
    phptopdf_url('http://att.kinden.com.vn/admin/print_all.php?thang=11','pdf/', 'att_record.pdf');
	echo "<a href='pdf/att_record.pdf'>Download PDF</a>";
?> 