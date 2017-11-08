<!DOCTYPE html>
<?php
/*
 * Tilføjes når vi har sat alt koden op til det og alt slettes på siden.  
 */
//session start
session_start();
require_once('includes/header.php');
?>
<html>
    <head>
        <title>Test</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style>
            .spinner {
                margin: 50px;
                animation: spin 2s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    </head>
    <body>
        <div>
            <h2>Hey bois, This is but a test - This is a test for pull</h2>
            <h1> Hello - This change has been made on a different PC- new change again</h1>
        </div>
        <img class="spinner" src="https://media1.giphy.com/avatars/100soft/WahNEDdlGjRZ.gif" alt="W3Schools.com">
    </body>
</html>
