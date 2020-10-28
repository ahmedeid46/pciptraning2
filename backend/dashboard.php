<?php
ob_start(); // output Buffering start
session_start();
if (isset($_SESSION['username'])) {
    $pagetitle = "dashboard";
    include 'init.php';
    // start dashboard page

?>
    <div class="home-stats">
        <div class="container home-stats text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users fa-lg"></i>
                        <div class="info">
                            Total Member
                            <span><a href="admin.php">
                                    <?php
                                    $stmt2 = $con->prepare("SELECT COUNT(id) FROM user");
                                    $stmt2->execute();
                                    echo $stmt2->fetchColumn(); ?>
                                </a></span>
                        </div>
                   </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fas fa-comment-alt"></i>
                        <div class="info">
                                Total Contact
                            <span><a href="contact.php"><?php
                                                            $stmt2 = $con->prepare("SELECT COUNT(id) FROM contact");
                                                            $stmt2->execute();
                                                            echo $stmt2->fetchColumn(); ?>
                            </a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    


<?php
    // end dashboard page
    include 'temb/footer.php';
} else {

    header('Location:index.php');
    exit();
}
ob_end_flush();
