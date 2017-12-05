<?php
$overview = new dashboard_func();

if (isset($_REQUEST['logout_btn']))
{
    //unset($_SESSION['user_name'], $_SESSION['logged_in']);
    session_destroy();
    header("Location: index.php");
}
?>

<div class="container-fluid">
    
    <div class="dash-board">
        
        <div class="dashboard-sort col-md-8">
            <h3>Vælg at sortere efter:</h3>
            <button data-action="asc" onclick="pro_sort_level($(this));" class="btn-primary sort-button">Level</button>
            <button data-action="asc" onclick="pro_sort_date($(this));" class="btn-primary sort-button">Dato</button>
            <button data-action="asc" onclick="pro_sort_site($(this));" class="btn-primary sort-button">Site</button>
        </div>
        
        <div class="dashboard-script col-md-4">
             <form>
                <span>Logget ind som: 
                 <?php
                    if(isset($_SESSION['user_name']))
                    { 
                        echo $_SESSION['user_name'];
                    }
                 ?>
                    <button name="logout_btn" class="btn-info btn-xs glyphicon glyphicon-off" value="Logout"></button>
                </span>
                 <br>
                <?php 
                    $overview->loadscript_datetime();
                ?>
                 <br>
                 <button class="btn-info" onclick="pro_scriptlog();">Se scriptlog (det er en dummy-button, inden i spørger om den virker)</button>
            </form>

        </div>
    </div>
</div>

<br>


<div class="content-wrapper col-md-12">
    
</div>

<!-- Modal -->
<div id="postponeModal" class="modal fade" role="dialog">
  
</div>