<?php
/**
 * User: Blesswell Kapalamula
 * Date: 7/17/2019
 * Time: 1:27 PM
 * This example uses a PDO database connection included in the config file
 */
include("config.php");
include("classes/CustomTable.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dynamic table</title>
    <!--add bootstrap css-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <!--Datatable-->
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <!--where to manage your data-->
    <input type="hidden" name="script_url" id="script_url" value="dao/manage_students.php"/>
    <?php
    $page_header="List of students";
    $sql = "SELECT * FROM tbl_student";/*Can be a select from a table, view or procedure*/
    $application_id=isset($_POST["hidden_id"])?$_POST["hidden_id"]:NULL;
    /*use 'exampleTable' to initialise datatable*/
    $custom_table = new CustomTable($page_header, "exampleTable", false, false);
    /*
     * Add action columns
     */
    $action_array = array
    (
        array("header_text"=>"Edit", "function"=>"addEdit(this);", "icon_class"=>"text-success", "icon"=>"fa-pencil"),
        array("header_text"=>"Delete", "function"=>"deleteItem(this);", "icon_class"=>"text-danger", "icon"=>"fa-bitbucket")
    );
    $custom_table->setActionArray($action_array);
    $custom_table->getCustomTable($db_conn, $sql, NULL);//array of parameters based on the index in the query, pass empty array if no params
    ?>

    <!--add bootstrap css-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

     <!--Datatable-->
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <script src="js/BlessKFunctionsJs.js?blessknocache=<?php echo time(); ?>"></script>
</div>
</body>
</html>