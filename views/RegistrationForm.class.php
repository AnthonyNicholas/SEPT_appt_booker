<?php
/**
 * The registration form class will print out the html form specifically for the
 * type of user being registered. Maybe we could have a printHtml method for each
 * owners and customers in the same class?
 */
class RegistrationForm
{

    public function printHtml()
    {
    ?>
    <div class="reg-form cust">
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
           <table id="form_table"> 
                <tr>
                <td><label class="label"> Email <span class="star"> * </span> </label></td> 
                <td><input name="email" type="email" class="text_input" placeholder="Email" required> <br></td>
                </tr>                
                <tr>
                <td><label class="label"> First name <span class="star"> * </span> </label></td> 
                <td><input name="fname" type="text" class="text_input" placeholder="First Name" required> <br></td>
                </tr>    
                <tr>
                <td><label class="label"> Last name <span class="star"> * </span> </label></td> 
                <td><input name="lname" type="text" class="text_input" placeholder="Last Name" required> <br></td>
                </tr>    
                <tr>
                <td><label class="label"> Address <span class="star"> * </span> </label></td> 
                <td><input name="address" type="text" class="text_input" placeholder="Address" required> <br></td>
                </tr>
                <tr>
                <td><label class="label"> Phone <span class="star"> * </span> </label></td> 
                <td><input name="phone" type="text" class="text_input" placeholder="12345678" required> <br></td>
                </tr>
                <tr>
                <td><label class="label"> Password <span class="star"> * </span> </label></td> 
                <td><input name="pword" type="text" class="text_input" placeholder="Password" required> <br></td>
                </tr>
                <tr>
                <td><label class="label"> Retype Password <span class="star"> * </span> </label></td> 
                <td><input name="pword2" type="text" class="text_input" placeholder="Retype Password" required> <br></td>
                </tr>
                <tr>
                <td id="submit_row" colspan="2">
                <input type="submit" class="submit" value="SUBMIT">
                </td>
                </tr>
                </table>
        </form>
    </div>
    <?php
    }

}
