function clear() {
     document.update_worktime.att_mark.value = '';
     document.update_worktime.ot_w.value = '';
     document.update_worktime.st_w.value = '';
     document.update_worktime.ot_h.value = '';
     document.update_worktime.st_h.value = '';
     document.update_worktime.ot_oh.value = '';
     document.update_worktime.st_oh.value = '';
     //document.update_worktime.intime.value = '';
     //document.update_worktime.outtime.value = '';
     document.update_worktime.detail.value = '';
}
function leaves()
{
      clear();
      var in_time = document.update_worktime.intime.value;
      var out_time = document.update_worktime.outtime.value;
      if((in_time.length > 0)||(out_time.length > 0)){
            document.update_worktime.att_mark.value = '1/2 L';
            if (in_time.length == 0) {
               document.update_worktime.intime.value = '8:00';
            }
            else{
               document.update_worktime.outtime.value = '12:00';
            }
      }
      else{
             document.update_worktime.att_mark.value = 'L';
        }
}
function congtac() {
      clear();
      document.update_worktime.intime.value = '8:00';
      document.update_worktime.outtime.value = '17:00';
      document.update_worktime.detail.value = 'Bussiness working';
}
function overtime_phut_ra(time_out,gio_out) { // function to compute over time in minutes for checking out
     if (gio_out >= 17) {
               if ((time_out >= 15)&&(time_out < 30)) {
                    var phut_out = 0.25;
                    return phut_out;
               }
               else if ((time_out >=30)&&(time_out < 45 )) {
                    var phut_out = 0.5;
                    return phut_out;
               }
               else if ((time_out >= 45 )&&(time_out < 60 )) {
                    var phut_out = 0.75;
                    return phut_out;
               }
               else{
                    var phut_out = 0;
                    return phut_out;
               }
     }
     else{
          return 0;
     }
}

// BEGIN function:
// Name: overtime_phut_vao
// params: time_in, gio_in
//
// Tinh toan hien thi gio lam them buoi sang hoac (in)

function overtime_phut_vao(time_in,gio_in) { 
var phut_in = 0;
   if ((gio_in < 8) && (gio_in > 5)) {
			   
               if ((time_in <= 45)&&(time_in > 30)) {
                    phut_in = 0.25;
                    }
               else if ((time_in <= 30)&&(time_in > 15 )) {
                    phut_in = 0.5;
                    }
               else {
                    phut_in = 0.75;
			   }
               
	}
	return phut_in;
}

// END function
//

//function overtime(trangthai,thoigianvao,thoigianra) {
function overtime(trangthai) {
      var ot_gio_ra = 0,ot_gio_vao = 0,ot_phut_ra = 0,ot_phut_vao = 0;
      var st_gio_ra = 0,st_gio_vao = 0,st_phut_vao = 0,st_phut_ra = 0;
      var total_ot_vao = 0,total_st_vao = 0,total_ot_ra = 0,total_st_ra = 0;
      var total_ot = 0,total_st = 0;
      var intime =  document.update_worktime.intime.value;
      var outtime = document.update_worktime.outtime.value;
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
      hienthi(trangthai,tong_ot_ra,tong_st_ra,tong_ot_vao,tong_st_vao,tong_ot,tong_st,hour_in,hour_out,minutes_vao);
}
// ham chuyen ket qua vao form
function hienthi(status,total_ot_ra,total_st_ra,total_ot_vao,total_st_vao,total_ot,total_st,gio_vao,gio_ra,phut_vao) {
     var weekday = document.update_worktime.weekday.value;
     var holiday = document.update_worktime.ngayle.value;
	 if(document.getElementById("check_ah").checked == true){
		var ah = 1;
	 }
     var total_ot_h = 0, total_st_h = 0;
     var total_ot_oh = 0, total_st_oh = 0;
     var h = 0, oh = 0;
	if(((weekday == 'Sat')||(weekday == 'Sun'))&&(ah == 1)){//hien over time cua ca ngay t7 va chu nhat
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
          document.update_worktime.ot_h.value = total_ot_h;
          document.update_worktime.st_h.value = total_st_h;
          if(status == 4){
		     if(total_ot_h <= 4){
				document.update_worktime.att_mark.value = '1/2AH';
			 }
             else{
			   document.update_worktime.att_mark.value = 'AH';
			}
		  }
          else{
               document.update_worktime.att_mark.value = 'H';
          }
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
          document.update_worktime.ot_oh.value = total_ot_oh;
          document.update_worktime.st_oh.value = total_st_oh;
          document.update_worktime.att_mark.value = 'H';  
     }
     else{ // hien over time ngay thuong
          switch (status) {
               case 1:{
                     document.update_worktime.ot_w.value = total_ot_ra;
                     document.update_worktime.st_w.value = total_st_ra;
                     document.update_worktime.att_mark.value = 'O';
                     break;
               }
               case 2:{
                     document.update_worktime.ot_w.value = total_ot_vao;
                     document.update_worktime.st_w.value = total_st_vao;
                     document.update_worktime.att_mark.value = 'O';
                     break;
               }
               case 3:{
                    document.update_worktime.ot_w.value = total_ot;
                    document.update_worktime.st_w.value = total_st;
                    document.update_worktime.att_mark.value = 'O';
                    break;
               }
          }
          
     }
}


