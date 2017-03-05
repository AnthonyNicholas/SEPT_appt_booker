<html>
    
    <div class="container">

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button aria-expanded="false" class="navbar-toggle collapsed" data-target="#navbar" data-toggle="collapse" type="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="mainpage.php"><span class="red">Appointment Booker</span></a>
            </div>
                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url_for('quote') }}">Book Appointment</a></li>
                        <li><a href="{{ url_for('buy') }}">Add Employee</a></li>
                        <li><a href="{{ url_for('sell') }}">Set Employee Times</a></li>
                        <li><a href="{{ url_for('history') }}">View Calander</a></li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="{{ url_for('logout') }}">Log In</a></li>
                        <li><a href="{{ url_for('logout') }}">Log Out</a></li>
                    </ul>
            </div>
        </div>
    </nav>

</html>