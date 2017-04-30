<?php
 /**
  * AddEmpOwner View
  * Authors: Jake Williams
  * 
  */
class AddEmpOwner
{
    
    public function printHtml($types)
    {
        echo "<script src=\"js/main.js\"></script>";
        
        ?>
        
        <div class="container">
            <div class = "jumbotron jumbotron-fluid">
                <h3>Add an Employee</h3><br>
                Add an employee into the system by entering their details below.    
            </div>
        </div>

        <div class="container">
        <div class = "jumbotron jumbotron-fluid">
            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                <div class="form-group">
                    <label class="control-label col-sm-3" for="fname">First Name:*</label>
                    <div class="col-sm-9">
                        <input name="fname" type="text" class="form-control" placeholder="Enter first name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="lname">Last Name:*</label>
                    <div class="col-sm-9">
                        <input name="lname" type="text" class="form-control" placeholder="Enter last name" required>
                    </div>
                </div>
                
            
                
                
                
                
                <div class="form-group">
                    <label class="control-label col-sm-3" for="select">Select Employee Skills: </label>
                    <div class="col-sm-9">
                        
                        
                        <input id="select_skills" type="text" list="types" class="form-control" oninput="add_skill(<?php echo htmlspecialchars(json_encode($types));?>)"/>
                      <datalist id="types">
                        
                        <?php //echo '<pre>'; print_r($types); echo '</pre>';
                          foreach ($types as $key => $value)
                            echo "<option id=\"".$value->get_id()."\">".$value->get_appType()."</option>";
                        ?>
                      </datalist>
                      
                      
                    </div>
                </div>
                
                
                
                
                    
                <div class="form-group">
                    
                    <label class="control-label col-sm-3" for="select">Skills </label>
                    <?php
                    echo "<div class=\"col-sm-9\">";
                    
                        foreach ($types as $key => $value)
                        {
                           
                            echo "<div hidden class=\"col-sm-9\" id=\"skill_".$value->get_id()."\"><label>".$value->get_appType()."&nbsp;&nbsp;</label>
                                    <button type =\"button\" class=\"btn btn-default btn-sm btn-circle\" onclick=\"remove_skill(".$value->get_id().")\">
                                        <i class=\"glyphicon glyphicon-minus\"></i>
                                    </button>
                                </div>";    
                                
                            echo "<input hidden id=\"post_skill_".$value->get_id()."\" name=\"skills[".$value->get_id()."]\" value=\"0\">";
                            
                        }
                        echo "</div>";
                    ?>
  
                </div>
                
                
                
                <div class="form-group"> 
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-default">Add Employee</button>
                    </div>
                </div>
            </form>
        </div>
        </div>

        
        <?php
    }
    
    public function printSuccessHtml()    {
        ?>
        <div>
            <p>Employee added successfully.</p>
        </div>
        <?php
    }

}