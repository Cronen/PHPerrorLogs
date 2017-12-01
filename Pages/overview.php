<?php
$overview = new dashboardfunc();

if (isset($_REQUEST['logout_btn']))
    {
     unset($_SESSION['user_name'], $_SESSION['logged_in']);
     header("Location: index.php");
    }
?>


<div class="container-fluid">
    
    <div class="col-md-12">
        
        <form>
        <span>Du er logget ind som: 
         <?php
         if(isset($_SESSION['user_name'])){ echo $_SESSION['user_name'];}
         ?>
         <button name="logout_btn" class="btn-info btn-xs glyphicon glyphicon-off" value="Logout"></button>
        </span>
        </form>
    <br>
        <?php $overview->loadscriptdatetime();?>

        <center>
        <h1>Vælg at sortere efter:</h1>
        <button data-action="asc" onclick="pro_sort_level($(this));" class="btn-primary sort-button">Level</button>
        <button data-action="asc" onclick="pro_sort_date($(this));" class="btn-primary sort-button">Dato</button>
        <button data-action="asc" onclick="pro_sort_site($(this));" class="btn-primary sort-button">Site</button>
        </center>
    </div>
</div>

<br>


<div class="content-wrapper col-md-12">
    
</div>

<!-- Modal -->
<div id="postponeModal" class="modal fade" role="dialog">
  
</div>