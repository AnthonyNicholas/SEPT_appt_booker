<?php
/**
 * The main page class will print out the html form specifically for the
 * type of user logged in. Maybe we could have a printHtml method for each
 * owners and customers in the same class?
 */
class MainPageView
{

    public function printHtml($fName, $lName)
    {
    ?>
    <div class="welcome-msg">
        <?php echo $fName . " " . $lName; ?>
    </div>
    <?php
    }

}
