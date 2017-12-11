<?php
//Instantiering til dashboard_class.php
//$overview = new dashboard_func();
//Simuler logout - Mest for proof of concept. Regner med at du har en selv.
$dashboard = new dashboard_func();

if (isset($_REQUEST['logout_btn'])) {
    session_destroy();
    header("Location: index.php");
}
?>

<div class="container-fluid">

    <div class="dash-board">
        <div class="dashboard-sort col-md-7">
            <h3>Vælg at sortere efter:</h3>
            <button data-action="asc" onclick="pro_sort_level($(this));" class="btn-primary sort-button">Level</button>
            <button data-action="asc" onclick="pro_sort_date($(this));" class="btn-primary sort-button">Dato</button>
            <button data-action="asc" onclick="pro_sort_location($(this));" class="btn-primary sort-button">Site</button>
        </div>

        <div class="dashboard-script col-md-5">
            <form>
                <span>Logget ind som: 
<?php
if (isset($_SESSION['user_name'])) {
    echo $_SESSION['user_name'];
}
?>
                    <button name="logout_btn" class="btn-info btn-xs glyphicon glyphicon-off" value="Logout"></button>
                </span>
                <br>

            </form>
                <b>Scriptet er sidst kørt: </b> 
                <span id="script-time"><?php $dashboard->pro_current_runtime(); ?></span>
                <button data-state="ready" onclick="run_script()" class="btn-xs btn-info" id="refreshbtn"><span class="glyphicon glyphicon-refresh"></span></button>
                <div id="last_run_div"></div> 
<!--                <br>-->
<!--            <span>
                <b>Scriptet er sidst kørt: </b> <div id="last_run_div"></div> 
                <span id="script-time"></span>
                <button data-state="ready" onclick="run_script()" class="btn-xs btn-info" id="refreshbtn"><span class="glyphicon glyphicon-refresh"></span></button>
                <br>
                
            </span>-->
            <button class="btn-info" data-action="pro_scriptlog" data-state="ready" onclick="pro_scriptlog($(this));">Se scriptlog</button>
        </div>
    </div>
</div>

<br>

<!--Div der hentes tables ind i -->
<div class="content-wrapper col-md-12">

</div>

<!-- Modal -->
<div id="postponeModal" class="modal fade" role="dialog">

</div>