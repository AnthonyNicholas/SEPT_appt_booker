<?php

class AddSkillsView
{
    public function printHtml($employees, $types)
    {
        echo "<script src=\"js/main.js\"></script>";
        
        ?>
        
        <div class="container">
            <div class = "jumbotron jumbotron-fluid">
                <h3>Employee Skills</h3><br>
                Add more skills to an Employee    
            </div>
        </div>

        <div class="container">
        <div class = "jumbotron jumbotron-fluid">
            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                
                 <div class="form-group">
                 <label class="control-label col-sm-3" for="emp_search">Select employee: </label>
                  <div class="col-sm-9">
                    <input id="emp_search" type="text" list="emps" class="form-control" oninput="choose_emp(<?php echo htmlspecialchars(json_encode($employees));?>,<?php echo htmlspecialchars(json_encode($types));?>)"/>
                     <input hidden id="emp_chosen" name="employee" value="0"/>
                      <datalist id="emps">
                        <?php
                        for ($i=0; $i<count($employees); $i++)
                            echo "<option id=\"opt_".$employees[$i]['empID']."\">".$employees[$i]['fName']." ".$employees[$i]['lName']."</option>";
                        ?>
                      </datalist>
        
                    </div>
                  </div> <!-- end row-->

 
 
                <div class="form-group">
                    <label class="control-label col-sm-3" for="select_skills">Select Employee Skills: </label>
                    <div class="col-sm-9">
                        
                        
                        <input id="select_skills" type="text" list="types" class="form-control" oninput="add_skill(<?php echo htmlspecialchars(json_encode($types));?>,<?php echo htmlspecialchars(json_encode($employees));?>)"/>
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
                                    <button type =\"button\" class=\"btn btn-default btn-sm btn-circle\" onclick=\"remove_skill(".$value->get_id().", ".htmlspecialchars(json_encode($employees)).", ".htmlspecialchars(json_encode($types)).")\">
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
                        <button id="submit_skills" type="submit" class="btn btn-default" disabled>Add Skills to Employee</button>
                    </div>
                </div>
            </form>
        </div>
        </div>

        
        <?php
    }    
    
    public function printSuccess()
    {
        echo "<div class = \"text-success\"><h3>Operation completed successfully</h3></br></div>"; 
    }
}