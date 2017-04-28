
// This function is for handling and displaying an in-line form for each new employee shift
// the owner wants to add
function add_hrs(empID)
{
  var f;
    
  if (document.getElementById("form_"+empID) == null) 
  {
    f = document.createElement("form");
    f.setAttribute('method',"post");
    f.setAttribute('action',"send_hrs.php");
    f.setAttribute('id',"form_"+empID);
    f.setAttribute('class',"form-inline");
    
    var emp = document.createElement("input");
    emp.setAttribute('type', "hidden");
    emp.setAttribute('name', 'empID');
    emp.setAttribute('value', empID);
    f.appendChild(emp);
  } 
  
  else
  {
    f = document.getElementById("form_"+empID);
  }



  var date_group = document.createElement("div");
  date_group.setAttribute('class', "form-group");
  
  var date_label = document.createElement("label");
  date_label.innerHTML = "DATE:&nbsp;";
    
  var date = document.createElement("input"); 
  date.setAttribute('type',"date");
  date.setAttribute('placeholder',"yyyy-mm-dd");
  date.setAttribute('name',"date[]");
  date.setAttribute('class', "form-control");
  date.required = true;
  
  date_group.appendChild(date_label);
  date_group.appendChild(date);
  
  
  
  var start_group = document.createElement("div");
  start_group.setAttribute('class', "form-group");  
    
  var start_label = document.createElement("label");
  start_label.setAttribute('for', "start[]");
  start_label.innerHTML = "&nbsp;&nbsp;&nbsp;FROM:&nbsp;";
  
  var start = document.createElement("input"); 
  start.setAttribute('type',"time");
  start.setAttribute('name',"start[]");
  start.setAttribute('id',"start[]");
  start.setAttribute('class', "form-control");
  start.required = true;
  
  start_group.appendChild(start_label);
  start_group.appendChild(start);
  
  
  
  var end_group = document.createElement("div");
  end_group.setAttribute('class', "form-group");
  
  var end_label = document.createElement("label");
  end_label.innerHTML = "&nbsp;&nbsp;&nbsp;TO:&nbsp;";
  
  var end = document.createElement("input"); 
  end.setAttribute('type',"time");
  end.setAttribute('name',"end[]");
  end.setAttribute('class', "form-control");
  end.required = true;
  
  
  end_group.appendChild(end_label);
  end_group.appendChild(end);
  
  
  
  var submit_group = document.createElement("div");
  submit_group.setAttribute('class', "form-group pull-right");
  var submit = document.createElement("input"); 
  submit.setAttribute('type',"submit");
  submit.setAttribute('value',"Submit");
  submit.setAttribute('id',"submit_" + empID);
  submit.setAttribute('class', "form-control");

  submit_group.appendChild(submit);

  var br = document.createElement("br");
  var br2 = document.createElement("br");
  
  f.appendChild(date_group);
  f.appendChild(start_group);
  f.appendChild(end_group);

/*
  f.appendChild(date_label);
  f.appendChild(date);
  f.appendChild(start_label);
  f.appendChild(start);
  f.appendChild(end_label);
  f.appendChild(end);
  f.appendChild(br);
*/
  if (document.getElementById("submit_" + empID) != null) 
  {
    var child = document.getElementById("submit_"+empID);
    child.parentNode.removeChild(child);
  } 


  f.appendChild(submit_group);
    f.appendChild(br);
  f.appendChild(br2);
  
  var fdiv =  document.createElement("div");
  fdiv.setAttribute('class', "form-group");
  
  fdiv.appendChild(f);
  
  
  document.getElementById(empID).appendChild(fdiv);
  
}

function toggle_emp(emps)
{
  for (var i = 0; i < emps.length; i++)
  {
    if (document.getElementById("emp_search").value == emps[i].fName + " " + emps[i].lName)
      document.getElementById("row_" + emps[i].empID).style.display = 'inline';
    else
      document.getElementById("row_" + emps[i].empID).style.display = 'none';
  }
}

function set_type(types)
{
  setTimeout(function(){
  var a = document.getElementsByClassName('horizontal-calendar-big-link');
  var length = a.length;
  
  for(var i=0; i< length; i++)
  {
    a[i].href = a[i].href.replace(/&?apptype=\d*/, "");
  }
 
  for (var key in types) 
  {
    if (document.getElementById("selectAppType").value == types[key].appType)
    {
      for(var i=0; i< length; i++)
      {
        a[i].href += '&apptype='+types[key].id;
      }
    }
  } 
  
  }, 1000);
}


