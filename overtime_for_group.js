// su dung ki thuat ajax de goi ham php trong javascript

function call_php_function_insert_db(str)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
  // goi ham php qua phuong thuc get 
xmlhttp.open("GET","getuser.php?q="+str,true);
xmlhttp.send();
}

//ham tinh overtime tu form dang ki overtime theo vung(nhom)

function overtime_from_location(staffid,date,thoigianvao,thoigianra,holiday,trangthai) {
      var ot_gio_ra = 0,ot_gio_vao = 0,ot_phut_ra = 0,ot_phut_vao = 0;
      var st_gio_ra = 0,st_gio_vao = 0,st_phut_vao = 0,st_phut_ra = 0;
      var total_ot_vao = 0,total_st_vao = 0,total_ot_ra = 0,total_st_ra = 0;
      var total_ot = 0,total_st = 0;
      var intime =  thoigianvao;
      var outtime = thoigianra;
      var giovao = intime.split(":");
      var giora = outtime.split(":");
      var gio_vao = Number(giovao[0]);// lay phut tu gio vao lam viec
      var phut_vao = Number(giovao[1]);// lay giay tu gio vao lam viec
      var gio_ra = Number(giora[0]);// lay phut tu gio ra ve
      var phut_ra = Number(giora[1]);// lay giay tu gio ra ve
      /* Tinh over time gio vao */
      if ((gio_vao < 8)&&(gio_vao>=6)) {// over time gio_vao OT
          if (phut_vao != 0) {
               ot_gio_vao = 8-(gio_vao + 1);
          }
          else{
               ot_gio_vao = 8 - gio_vao;
          }
      }
      else if (gio_vao < 6) { // Tinh ST gio vao
          if (phut_vao != 0) {
               st_gio_vao = 8-(gio_vao + 3);
               ot_gio_vao = 2;
          }
          else{
               st_gio_vao = 8-(gio_vao + 2);
               ot_gio_vao = 2;
          }
      }
      else{
          ot_gio_vao = 0;
          st_gio_vao = 0;
      }
      /*Tinh over time phut vao*/
      if (st_gio_vao > 0) {
          st_phut_vao = overtime_phut_vao(phut_vao,gio_vao);
          ot_phut_vao = 0;
      }
      else if ((st_gio_vao == 0)&&(ot_gio_vao == 2)) {
          st_phut_vao = overtime_phut_vao(phut_vao,gio_vao);
          ot_phut_vao = 0;
      }
      else{
          ot_phut_vao = overtime_phut_vao(phut_vao,gio_vao);
      }
      /*Tinh tong ot va st cua check in*/
      total_ot_vao = ot_gio_vao + ot_phut_vao;
      total_st_vao = st_gio_vao + st_phut_vao;
      /*Tinh over time gio ra*/
      if ((gio_ra >= 17)&&(gio_ra <= 22)) {
          ot_gio_ra = gio_ra - 17;
      }
      else if (gio_ra > 22) {
          st_gio_ra = gio_ra - 22;
          ot_gio_ra = 5;
      }
      else{
          st_gio_ra = 0;
          ot_gio_ra = 0;
      }
      /*Tinh over time phut ra*/
      if (st_gio_ra > 0) {
          st_phut_ra = overtime_phut_ra(phut_ra,gio_ra);
          ot_phut_ra = 0;
      }
      else if ((st_gio_ra == 0)&&(ot_gio_ra == 5)) {
          st_phut_ra = overtime_phut_ra(phut_ra,gio_ra);
          ot_phut_ra = 0;
      }
      else{
          ot_phut_ra = overtime_phut_ra(phut_ra,gio_ra);
          st_phut_ra = 0;
      }
      /*Tinh tong ot va st check out*/
      total_ot_ra = ot_gio_ra + ot_phut_ra;
      total_st_ra = st_gio_ra + st_phut_ra;
      /*Tinh tong over vao va ra*/
      total_ot = total_ot_ra + total_ot_vao;
      total_st = total_st_ra + total_st_vao;
      var tong_ot_ra = total_ot_ra;
      var tong_st_ra = total_st_ra;
      var tong_ot_vao = total_ot_vao;
      var tong_st_vao = total_st_vao;
      var tong_ot = total_ot;
      var tong_st = total_st;
      var hour_in = gio_vao;
      var hour_out = gio_ra;
      var minutes_vao = phut_vao;
      overtime_output(trangthai,tong_ot_ra,tong_st_ra,tong_ot_vao,tong_st_vao,tong_ot,tong_st,hour_in,hour_out,minutes_vao,date,staffid,holiday);
}

// ham xuat du lieu overtime

function overtime_output(status,total_ot_ra,total_st_ra,total_ot_vao,total_st_vao,total_ot,total_st,gio_vao,gio_ra,phut_vao,ngay,staffid,holiday){
     var weekday = ngay.getDay();
     //var holiday = document.update_worktime.ngayle.value;
     var total_ot_h = 0, total_st_h = 0;
     var total_ot_oh = 0, total_st_oh = 0;
     var h = 0, oh = 0;
     if ((weekday == '0')||(weekday == '6')) {//hien over time cua ca ngay t7 va chu nhat
          if ((gio_ra <= 17)&&(gio_vao >= 8)) {
                    if ((gio_ra > 12)&&(gio_vao < 12)) {
                         if (phut_vao != 0) {
                              h = gio_ra - (gio_vao + 2); 
                         }
                         else{
                              h = gio_ra - (gio_vao + 1);
                          }
                    }
                    else{
                         if (phut_vao != 0) {
                              h = gio_ra - (gio_vao + 1); 
                         }
                         else{
                              h = gio_ra - gio_vao;
                         }
                    }
               }
          else if ((gio_ra <= 17)&&(gio_vao < 8)) {
                    if ((gio_ra > 12)&&(gio_vao < 12)) {
                         if (phut_vao != 0) {
                              h = gio_ra - 9; 
                         }
                         else{
                              h = gio_ra - 8;
                          }
                    }
                    else{
                         if (phut_vao != 0) {
                              h = gio_ra - 8; 
                         }
                         else{
                              h = gio_ra - 7;
                         }
                    }
               }
          else if ((gio_ra > 17)&&(gio_vao >=8)) {
                    if ((gio_ra > 12)&&(gio_vao < 12)) {
                         if (phut_vao != 0){
                              h = 17 - (gio_vao + 2); 
                         }
                         else{
                              h = 17 - (gio_vao + 1);
                          }
                    }
                    else{
                         if (phut_vao != 0){
                              h = 17 - (gio_vao + 1); 
                         }
                         else{
                              h = 17 - gio_vao;
                         }
                    }
               }
          else{
                   h = 8;
               }
          total_ot_h = h+total_ot;
          total_st_h = total_st;
          //document.update_worktime.ot_h.value = total_ot_h;
          //document.update_worktime.st_h.value = total_st_h;
          if(weekday == '6'){
               att = 'AH';
          }
          else{
               att = 'H';
          }
          overtime_to_db(staffid,ngay,'',att,'','',total_ot_h,total_st_h,'','','');
     }
      /*Doi voi ngay SH lam tuong tu nhu thu 7 va chu nhat*/
     else if (holiday == 1) {
          if ((gio_ra <= 17)&&(gio_vao >= 8)) {
                    if ((gio_ra > 12)&&(gio_vao < 12)) {
                         if (phut_vao != 0) {
                              oh = gio_ra - (gio_vao + 2); 
                         }
                         else{
                              oh = gio_ra - (gio_vao + 1);
                          }
                    }
                    else{
                         if (phut_vao != 0) {
                              oh = gio_ra - (gio_vao + 1); 
                         }
                         else{
                              oh = gio_ra - gio_vao;
                         }
                    }
               }
          else if ((gio_ra <= 17)&&(gio_vao < 8)) {
                    if ((gio_ra > 12)&&(gio_vao < 12)) {
                         if (phut_vao != 0) {
                              oh = gio_ra - 9; 
                         }
                         else{
                              oh = gio_ra - 8;
                          }
                    }
                    else{
                         if (phut_vao != 0) {
                              oh = gio_ra - 8; 
                         }
                         else{
                              oh = gio_ra - 7;
                         }
                    }
               }
          else if ((gio_ra > 17)&&(gio_vao >=8)) {
                    if ((gio_ra > 12)&&(gio_vao < 12)) {
                         if (phut_vao != 0){
                             oh = 17 - (gio_vao + 2); 
                         }
                         else{
                              oh = 17 - (gio_vao + 1);
                          }
                    }
                    else{
                         if (phut_vao != 0){
                              oh = 17 - (gio_vao + 1); 
                         }
                         else{
                              oh = 17 - gio_vao;
                         }
                    }
               }
          else{
                   oh = 8;
               }
          total_ot_oh = oh+total_ot;
          total_st_oh = total_st;
          //document.update_worktime.ot_oh.value = total_ot_oh;
          //document.update_worktime.st_oh.value = total_st_oh;
          //document.update_worktime.att_mark.value = 'SH';
          overtime_to_db(staffid,ngay,'','SH','','',total_ot_oh,total_st_oh,'','','');
          
     }
     else{ // hien over time ngay thuong
          switch (status) {
               case 1:{
                     //document.update_worktime.ot_w.value = total_ot_ra;
                     //document.update_worktime.st_w.value = total_st_ra;
                     //document.update_worktime.att_mark.value = 'O';
                     overtime_to_db(staffid,ngay,'','O',total_ot_ra,total_st_ra,'','','','','');
                     break;
               }
               case 2:{
                    // document.update_worktime.ot_w.value = total_ot_vao;
                    // document.update_worktime.st_w.value = total_st_vao;
                    // document.update_worktime.att_mark.value = 'O';
                    overtime_to_db(staffid,ngay,'','O',total_ot_vao,total_st_vao,'','','','','');
                     break;
               }
               case 3:{
                    //document.update_worktime.ot_w.value = total_ot;
                    //document.update_worktime.st_w.value = total_st;
                   // document.update_worktime.att_mark.value = 'O';
                   overtime_to_db(staffid,ngay,'','O',total_ot,total_st,'','','','','');
                    break;
               }
          }
          
     }
}
// ham xuat du lieu vao DB
function overtime_to_db(staffid,workday,weekday,att_mark,wot,wst,hot,hst,pot,pst,detailwork){
     //code
     //alert(staffid + workday + weekday + att_mark + wot + wst + hot + hst + pot + pst + detailwork);
}
