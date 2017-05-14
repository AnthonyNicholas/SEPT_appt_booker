<?php
class SetupView
{

    public function printHtml()
    {
        ?>

    <div class="container">
        <div class = "jumbotron jumbotron-fluid">
            <div class = "text-primary">
                <h2>Setup Your New Business</h2>
                <h4>
                    Here you provide the name of your business and a description.<br>
                    Next, you will be logged into the main site from your owners account.<br>
                    There, you will be able to specify your business opening times,<br> 
                    define your business' services,<br> 
                    and add your employees and their working times.<br>  
                </h4>
            </div>
        </div>
    
        <div class = "jumbotron jumbotron-fluid">
            
            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                
                
                 <div class="form-group">
                    <label class="control-label col-sm-3" for="fname">Your name </label>
                    <div class="col-sm-9">
                        <input name="fname" type="text" class="form-control" id="fname" placeholder="First" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3" for="lname"> </label>
                    <div class="col-sm-9">
                        <input name="lname" type="text" class="form-control" id="lname" placeholder="Last" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3" for="email">Business Name </label>
                    <div class="col-sm-9">
                        <input name="name" type="text" class="form-control" id="name" required>
                    </div>
                </div>

                 <div class="form-group">
                    <label class="control-label col-sm-3" for="appDesc">Description* </label>
                    <div class="col-sm-9">
                        <textarea class="form-control" rows="5" name="desc"></textarea>
                    </div>
                </div>
                
                <div class="form-group"> 
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-default">Submit</button>
                    </div>
                </div>

            </form>
        </div>
        </div>
        
    <?php

    }
}