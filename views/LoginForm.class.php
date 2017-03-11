<?php
/**
 * The login form class will print out the html form specifically for the
 * type of user logging in. Maybe we could have a printHtml method for each
 * owners and customers in the same class?
 */
class LoginForm
{
    
    public function printHtml()
    {
    ?>
    <div class="login-form-cust">
        <form action="" method="post">
            <table class="login-form-table">
                <tr>
                    <td><input name="email" type="email" class="text_input" placeholder="Email"></td>   
                </tr>
                <tr>
                    <td><input name="pword" type="password" class="text_input" placeholder="Password"></td>   
                </tr>
                <tr>
                    <td><input type="submit" class="submit" value="SUBMIT"></td>   
                </tr>
            </table>
        </form>
    </div>
    <?php
    }
}