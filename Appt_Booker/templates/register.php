<html>

    <?php include('head_standard.php'); ?>

<body>

    <?php include('header_plain.php'); ?>
    
    <main>
    <!-- fill viewport -->
    <div class="container-fluid">
        <?php writeMsg(); ?>
        
        <form action="{{ url_for('register') }}" method="post">
            <fieldset>
                <div class="form-group">
                    <input autocomplete="off" autofocus class="form-control" name="username" placeholder="Username" type="text"/>
                </div>
                <div class="form-group">
                    <input class="form-control" name="password" placeholder="Password" type="password"/>
                </div>
                <div class="form-group">
                    <input class="form-control" name="confirmPassword" placeholder="Re-enter password" type="password"/>
                </div>
                <div class="form-group">
                    <button class="btn btn-default" type="submit">Register</button>
                </div>
            </fieldset>
        </form>
    </div>
</main>

<?php include('footer.php'); ?>

</body>
</html>