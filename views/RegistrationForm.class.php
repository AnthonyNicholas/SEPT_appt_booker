<?php
/**
 * RegistrationForm View
 * The registration form class prints out the html form specifically for the
 * type of user being registered.
 * Authors: Jake
 */
class RegistrationForm
{

    public function printHtml()
    {
    ?>
    
    <div class = "jumbotron jumbotron-fluid">
        Register a new customer account for the booking system below.<br><br>
         <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
           <div class="form-group">
               <label class="control-label col-sm-3" for="email">Email *</label>
               <div class="col-sm-9">
                   <input name="email" type="email" class="form-control" id="email" placeholder="Email" required>
              </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-3" for="fname">First name *</label>
               <div class="col-sm-9">
                   <input name="fname" type="text" class="form-control" id="fname" placeholder="First Name" required>
              </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-3" for="lname">Last name *</label>
               <div class="col-sm-9">
                   <input name="lname" type="text" class="form-control" id="lname" placeholder="Last Name" required>
              </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-3" for="address">Address *</label>
               <div class="col-sm-9">
                   <input name="address" type="text" class="form-control" id="address" placeholder="Address" required>
              </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-3" for="phone">Phone *</label>
               <div class="col-sm-9">
                   <input name="phone" type="text" class="form-control" id="phone" placeholder="12345678" required>
              </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-3" for="password">Password *</label>
               <div class="col-sm-9">
                   <input name="pword" type="password" class="form-control" id="password" placeholder="Paswword" required>
              </div>
            </div>
            <div class="form-group">
               <label class="control-label col-sm-3" for="pword2">Retype password *</label>
               <div class="col-sm-9">
                   <input name="pword2" type="password" class="form-control" id="pword2" placeholder="Retype Password" required>
              </div>
            </div>
            <div class="form-group"> 
                <div class="col-sm-offset-3 col-sm-9">
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <?php
    }

}
