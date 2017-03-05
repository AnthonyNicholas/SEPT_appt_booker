<html>

<?php include('head_standard.php'); ?>

<body>

<?php include('header_cust.php'); ?>
    
    <main>
    <!-- fill viewport -->
    <div class="container-fluid">
        <?php 
            $result = getEmployees();
            printEmpTable($result);
        ?>
    </div>
</main>

<?php include('footer.php'); ?>

</body>
</html>