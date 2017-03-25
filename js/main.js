function add_hrs(empID)
{
  var f;
    
  if (document.getElementById("form_"+empID) == null) 
  {
    f = document.createElement("form");
    f.setAttribute('method',"post");
    f.setAttribute('action',"../send_hrs.php");
    f.setAttribute('id',"form_"+empID);
    
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

  var date_label = document.createElement("label");
  date_label.innerHTML = "DATE: ";
    
  var date = document.createElement("input"); 
  date.setAttribute('type',"date");
  date.setAttribute('placeholder',"yyyy-mm-dd");
  date.setAttribute('name',"date[]");
    
  var start_label = document.createElement("label");
  start_label.innerHTML = "FROM: ";
  
  var start = document.createElement("input"); 
  start.setAttribute('type',"time");
  start.setAttribute('name',"start[]");
  
  var end_label = document.createElement("label");
  end_label.innerHTML = "TO: ";
  
  var end = document.createElement("input"); 
  end.setAttribute('type',"time");
  end.setAttribute('name',"end[]");
  
  var submit = document.createElement("input"); 
  submit.setAttribute('type',"submit");
  submit.setAttribute('value',"Submit");
  submit.setAttribute('id',"submit_" + empID);

  var br = document.createElement("br");

  f.appendChild(date_label);
  f.appendChild(date);
  f.appendChild(start_label);
  f.appendChild(start);
  f.appendChild(end_label);
  f.appendChild(end);
  f.appendChild(br);

  if (document.getElementById("submit_" + empID) != null) 
  {
    var child = document.getElementById("submit_"+empID);
    child.parentNode.removeChild(child);
  } 

  f.appendChild(submit);
  
  var fdiv =  document.createElement("div");
  fdiv.setAttribute('class', "form-group");
  
  fdiv.appendChild(f);
  
  document.getElementById(empID).appendChild(fdiv);
}




function addItemToUsersList(itemId)
{
 
}
