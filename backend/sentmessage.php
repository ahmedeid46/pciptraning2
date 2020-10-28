<?php
include 'contact_SQL.php';
if($_SERVER['REQUEST_METHOD']=='POST') {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    //insert info in  database
    $stmt = $con->prepare("INSERT INTO contact
                        (name,email,message,date)
                        VALUES
                        (:zname,:zemail,:zmsg ,now())");
    $stmt->execute(array(
        'zname' => $name,
        'zemail' => $email,
        'zmsg' => $message
    ));
    global $count ;
    $count= $stmt->rowCount();
    if($count == 1){
        header("Location:../index.php");
    }

}

?>