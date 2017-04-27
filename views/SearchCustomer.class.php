<?php

class SearchCustomer
{
    
    public function printHtml()
    {
        ?>

        <div class="container">
        <div class = "jumbotron jumbotron-fluid">
            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                <div class="form-group">
                    <label class="control-label col-sm-3" for="email">Enter Customer Email:*</label>
                    <div class="col-sm-9">
                        <input name="email" type="email" class="form-control" placeholder="example@email.com" required>
                    </div>
                </div>
                <div class="form-group"> 
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-default">Book</button>
                    </div>
                </div>
            </form>
        </div>
        </div>

        
        <?php
    }
    
}