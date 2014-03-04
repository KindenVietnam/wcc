function clear() {
         document.form_insert_in_out.time_in.value = '';
         document.form_insert_in_out.time_out.value = '';
}
function in_out(status)
{
      clear();
      switch (status) {
        //case  
              case 1:{
                    document.form_insert_in_out.time_in.value = '8:00:00';
                    break;
              }
              case 2:{
                     document.form_insert_in_out.time_out.value = '17:00:00';
                     break;
                }
              case 3:{
                  document.form_insert_in_out.time_in.value = '8:00:00';
                  document.form_insert_in_out.time_out.value = '17:00:00';
                  break;
              }
      }

}