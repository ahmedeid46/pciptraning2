<?php
ob_start();
session_start();
if (isset($_SESSION['username'])) {
    header('Location:dashboard.php');
}
$pagetitle = "login";
$nonNavbar = "";
include 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashpass = sha1($password);

    $stmt = $con->prepare("SELECT
                          name,email,password,id
                           FROM
                            user
                           WHERE
                             email = ?
                           AND
                              password = ?
                           LIMIT  1");
    $stmt->execute(array($username, $hashpass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if ($count > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['ID'] = $row['id'];
        header('Location:dashboard.php ');
        exit();
    }
}
?>
<form class="login" action='<?php echo $_SERVER['PHP_SELF']; ?>' method="post">
    <i class="fas fa-user-shield logo"></i>
    <h4 class="text-center"> Admin Login </h4>
    <input class="form-control input-lg" type="text" name="user" placeholder="username" autocomplete="off">
    <input class="form-control input-lg" type="password" name="pass" placeholder="password" autocomplete="new-password">
    <input class="btn btn-parimary btn-block btn-lg" type="submit" value="login">
</form>
<?php include 'temb/footer.php';
ob_end_flush();
?>