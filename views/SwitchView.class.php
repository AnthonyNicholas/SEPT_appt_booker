<?php
class SwitchView
{
    public function printHtml($businesses)
    {
        ?>
        
        <div class="container">
  
    
        <div class = "jumbotron jumbotron-fluid">
            
            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                
                
        <div class="form-group">
        <div> <h3 class="text-primary text-center"> SWITCH TO AN EXISTING BUSINESS </h3> </div>
        <div> <h5 class="text-warning text-center"> after switching you will be redirected to login </h5> </div>
        </div>
                
                
            <?php
        while ($business = $businesses->fetch_assoc())
        {
            echo "<div class=\"form-group\"> 
                    
                        <button type=\"submit\" value=\"1\" name=\"".$business['dbname']."\" class=\"btn btn-default btn-block btn-lg btn-primary\">".$business['name']."</button>
              
                </div>";
        }
        ?>
        
        <div class="form-group">
        <div> <h3 class="text-primary text-center"> OR </h3> </div>
        
        </div>
                            
        <?php
        echo "<div class=\"form-group\"> 
                    
                        <button type=\"submit\" name=\"new\" class=\"btn btn-default btn-block btn-lg btn-info\">New Business...</button>
                  
                </div>";
        
        
                ?>
                

            </form>
        </div>
        </div>
        
        <?php
    }
}