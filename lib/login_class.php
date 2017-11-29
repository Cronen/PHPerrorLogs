<?php

class login_md {

    function __construct() {
        if (isset($_POST['login_btn'])) {
            $this->usename = $_POST['uname'];
            $this->pass = $_POST['pword'];
        }
        if (isset($_POST['reset_pword_btn'])) {
            $this->email = $_POST['e-mail'];
        }
    }

    /* returns login html */

    function getLoginHtml() {
        ob_start();
        ?>
        <div class="col-md-12">
            <form name="login_form" id="login_form" method="POST">
                <input type="text" class="input-field" name="uname" placeholder="Brugernavn"><br>
                <input type="password" class="input-field" name="pword" placeholder="Password"><br>
                <input type="submit" id="submit_btn" class="btn btn-info" name="login_btn" value="Login to account"><br>
            </form>
        </div>

        <?php
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }

//tjek om logget ind
    function loginCheck() {
        if ($_SESSION['logged_in'] == true) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * * succes = true when logging in and validated
     * * returns array(bool succes, string error) 
     */

    function login() {
        if (!isset($_REQUEST['login_btn']))
            return array(false, '');

        if (empty($this->usename) || empty($this->pass)) {
            return array(false, 'Brugernavn og password er påkrævet');
        } else {
            $validated = $this->validateLogin($this->usename, $this->pass);
            $msg = ($validated) ? '' : 'Sorry, wrong username or password';

            return array($validated, $msg);
        }
    }

//logs out user and resets session
	function logout()
	{
		session_start();
		session_unset();
		session_destroy();

		header("Location: /");
		exit();
	}
//returns bool success
    function validateLogin($username, $password) {

        if ($username == "admin" && $password == "root") {
            //set session
            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = "admin";

            return true;
        }

        return false;
    }

// function resetPassword()
// {
// if(!isset($_REQUEST['reset_pword_btn']))
// return array(false, '');
// if(empty($this->email))
// {
// return array(false, 'indtast en email'); 
// }
// else
// {
// $validated = $this->validateEmail($this->email);
// if($validated === false)
// return array(false, 'email eksiterer ikke');
// else
// {
// $mail = new mail_md();
// $mail->sendMail($this->email);
// return array(true,'Der er sendt en email afsted');
// }
// }
// }

}
?>