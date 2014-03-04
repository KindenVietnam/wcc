function checkAll(formname, status_checkbox)
{
  var checkboxes = new Array();
  // lay tat ca cac the co kieu la input
  checkboxes = document[formname].getElementsByTagName('input');
 //vong lap duyet cac the co kieu la input
  for (var i=0; i<checkboxes.length; i++)  {
    // neu input type = checkbox thi moi hanh dong check hoac uncheck
    if (checkboxes[i].type == 'checkbox')   {
      checkboxes[i].checked = status_checkbox; // true = check ... false = uncheck
    }
  }
}



