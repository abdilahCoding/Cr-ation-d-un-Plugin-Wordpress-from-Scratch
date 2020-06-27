<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * 
 * @package           form
 *
 * @wordpress-plugin
 * Plugin Name:       form
 * Plugin URI:        fomr.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            abdilah
 * License:           GPL-2.0+
 * Text Domain:       form
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

















































//Creation de la connection avec la base de donné de wordpress
require_once(ABSPATH . 'wp-config.php');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($conn, DB_NAME);



//la creation du tableau 
function newTable()
{

    global $conn;

    $sql = "CREATE TABLE form(id int NOT NULL PRIMARY KEY AUTO_INCREMENT, firstname varchar(255) NOT NULL, lastname varchar(255) NOT NULL, email varchar(255) NOT NULL, phone int NOT NULL, msg varchar(255) NOT NULL)";
    $res = mysqli_query($conn, $sql);
    return $res;
}

//Creation du Table si la connection est établie
if ($conn == true){

    newTable();
}


//Fonction pour laisser ou supprimer des champs du formulaire
function form($atts){
    $prenom= "";
    $nom= "";
    $mail= "";
    $tel= "";
    $msg= "";

    extract(shortcode_atts(
        array(
            'firstname' => 'true',
            'lastname' => 'true',
            'email' => 'true',
            'phone' => 'true',
            'message' => 'true'
            
    ), $atts));

    if($firstname== "true"){
        $prenom = 'First name: <input type="text" name="firstname" placeholder="Enter your name" required style="margin-bottom:10px;width:500px;"><br>';
    }

    if($lastname== "true"){
        $nom = 'Last name: <input type="text" name="lastname" required placeholder="Enter your lastname" style="margin-bottom:10px;width:500px;""><br>';
    }

    if($email== "true"){
        $mail = 'Email:<input type="email" name="email" placeholder="Enter your email" required style="margin-bottom:10px;margin-left:34px;width:500px;"><br>';
    }
    if($phone== "true"){
        $tel = 'phone: <input type="number" name="phone" required placeholder="Enter your phone Number" style="margin-bottom:10px;;margin-left:25px;width:500px;"><br>';
    }

    if($message== "true"){
        $msg = 'Message: <textarea name="msg" placeholder="Type your Message"></textarea><br>';
    }



    echo '<form method="POST"  >' .$prenom.$nom.$mail.$tel.$msg. '<input style="margin-top : 20px;" value="Send" type="submit" name="submit"></form>';
}



//Shortcode du plugin
add_shortcode('contactForm', 'form');



// Fonction d'envoi des informations au base de donnée
    function sendToDB($firstname,$lastname,$email,$phone,$msg)
    {
        global $conn;

    $sql = "INSERT INTO form(firstname,lastname,email,phone, msg) VALUES ('$firstname','$lastname','$email','$phone','$msg')";
    $res = mysqli_query($conn , $sql);
    
    return $res;
    }



//L'envoi des informations au base de donnée 
    if(isset($_POST['submit'])){

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $msg = $_POST['msg'];
        

        sendToDB($firstname,$lastname,$email,$phone,$msg);
    
    }




    add_action("admin_menu", "addMenu");
    function addMenu()
    {
        add_menu_page("form", "from", 4, "form", "adminMenu","dashicons-email");
    }

function adminMenu()
{
    echo '  <div style="font-size : 20px; display : flex; flex-direction : column;">
    <h1 style="color:blue;">
      Contact Form
    </h1>
  
    <h4>
      This form contain this  fields :
    </h4>
    <ul>
      <li>firstname</li>
      <li>lastname</li>
      <li>email</li>
      <li>phone</li>
      <li>message</li>
    </ul>
  
    <h1>
      compy The shortcode [FormCode]
    </h1>
  
  
  
  </div>';


}

?>