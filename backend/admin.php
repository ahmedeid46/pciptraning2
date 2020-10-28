<?php
/*
==========================================
==manage members
==========================================
*/
ob_start();
session_start();
$pagetitle = 'Member';
function redirectHome($theMsg ,$url = null , $seconds = 3){

    if ( $url !== null && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !==''){
        $url = $_SERVER['HTTP_REFERER'];
        $link = 'previous page';
    }else{
        $url = 'index.php';
        $link = 'Home Page';
    }

    echo $theMsg;
    echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds seconds....</div>";
    header("refresh:$seconds;url=$url");
    exit();
}
if (isset($_SESSION['username'])) {
    include 'init.php';
    if (isset($_GET['do'])) {
        $do = $_GET['do'];
    } else {
        $do = 'Manage';
    }
    if ($do == 'Manage') {//manage page


        //select all users with out admin
        $stmt = $con ->prepare("SELECT * FROM user ORDER BY id DESC ");
        /*
         ==================================
        == execute the statement
         ===================================
        */
        $stmt->execute();
        /*
          ==================================
         == assign to variable
          ===================================
        */
        $rows = $stmt-> fetchAll();


        ?>
        <h1 class="text-center">Manage Admins</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Name</td>
                        <td>Email</td>
                        <td>control</td>
                    </tr>
                    <?php
                    foreach ($rows as $row){
                        echo "<tr>";
                        echo '<td>'.$row['id'].'</td>';
                        echo '<td>'.$row['name'].'</td>';
                        echo '<td>'.$row['email'].'</td>';
                        echo "<td>
                                         <a href='admin.php?do=Edit&userid=".$row['id']. "'class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                         <a href='admin.php?do=delete&userid=".$row['id']. "' class='btn btn-danger confirm'><i class='fas fa-trash-alt'></i> Delete</a>";
                        echo "</td>";
                        echo "</tr>";

                    }
                    ?>


                </table>
            </div>
            <a href='admin.php?do=add' class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
        </div>

    <?php } elseif ($do == 'add') {//add members pag ?>

        <h1 class="text-center">Add New Admin</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=insert" method="post">

                <div class='form-group form-group-lg'>
                    <label class="col-sm-2 control-label">username</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="username" class="form-control" autocomplete="off" required='required'
                               placeholder="UserName to login into shop"/>
                    </div>
                </div>

                <div class='form-group form-group-lg'>
                    <label class="col-sm-2 control-label">password</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="password" name="password" class="password form-control" autocomplete="new-password"
                               placeholder="password must be hard & complex" required='required'/>
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>
                </div>

                <div class='form-group form-group-lg'>
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="email" name="email" class="form-control" required='required'
                               placeholder="email must be vaild"/>
                    </div>
                </div>
                <div class='form-group form-group-lg'>
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Member" class="btn btn-primary btn-lg"/>
                    </div>
                </div>

            </form>
        </div>
        <?php
    } elseif ($do == 'insert') { //insert Member page

        echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo " <h1 class='text-center'> Insert New Admin</h1>";

            $username = $_POST['username'];
            $pass =$_POST['password'];
            $email = $_POST['email'];

            $hashpass=sha1($pass);
            // validate form
            $formErrors = array();

            if (strlen($username) < 4) {
                $formErrors[] = 'username can\'t be less than <strong> 4 characters</strong></div>';
            }
            if (strlen($username) > 20) {
                $formErrors[] = 'username can\'t be more than<strong> 20 characters</strong>';
            }
            if (empty($username)) {
                $formErrors[] = 'username can\'t Be<strong> Empty</strong>';
            }
            if (empty($pass) ) {
                $formErrors[] = 'pass can\'t be less than <strong> empty </strong></div>';
            }

            if (empty($email)) {
                $formErrors[] = 'email can\'t Be <strong>Empty</strong>';
            }

            //loop into Errors array and echo it
            foreach ($formErrors as $error) {
                echo '<div class=\'alert alert-danger\'>' . $error . "</div>" . "<br/>";
            }

            //check if there's no error proceed the Update operation

            if (empty ($formErrors)) {
                $stmt = $con->prepare("SELECT name FROM user WHERE id = ?");
                $stmt->execute(array($username));
                $check = $stmt->rowCount();
                // Check If User Exist IN Database
                if($check == 1){
                    $theMsg= '<div class="alert alert-danger">sorry username is Exist</div>';
                    redirectHome($theMsg,'back');
                }else{

                    //insert info in  database
                    $stmt = $con->prepare("INSERT INTO users
                             (name,password,email,)
                             VALUES
                             (:zuser, :zpass, :zmail");
                    $stmt->execute(array(
                        'zuser'=> $username,
                        'zpass'=>$hashpass ,
                        'zmail'=>$email,
                    ));

                    $theMsg= '<div class=\'alert alert-success\'>' . $stmt->rowCount() . ' record update' . '</div>';
                    redirectHome($theMsg);

                }
            }
        }else{
            echo "<div class='container'>";
            $theMsg = "<div class='alert alert-danger'>Sorry You Can't Browse This Page Directly</div>";
            redirectHome($theMsg,'back');
            echo '<div>';
        }
        echo '</div>';
    } elseif ($do == 'Edit') { //Edit page
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stmt = $con->prepare("SELECT * FROM user WHERE id = ? LIMIT  1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($stmt->rowCount() > 0) {
            ?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=update" method="post">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">
                    <div class='form-group form-group-lg'>
                        <label class="col-sm-2 control-label">username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" class="form-control"
                                   value='<?php echo $row['name']; ?>' autocomplete="off" required='required'/>
                        </div>
                    </div>

                    <div class='form-group form-group-lg'>
                        <label class="col-sm-2 control-label">password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpassword" value='<?php echo $row['password']; ?>'/>
                            <input type="password" name="newpassword" class="form-control"
                                   autocomplete="new-password"
                                   placeholder="Leave Blank If You Don't want to change"/>
                        </div>
                    </div>

                    <div class='form-group form-group-lg'>
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" class="form-control" required='required'
                                   value='<?php echo $row['email']; ?>'/>
                        </div>
                    </div>
                    <div class='form-group form-group-lg'>
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="save" class="btn btn-primary btn-lg"/>
                        </div>
                    </div>

                </form>
            </div>
        <?php } else {
            echo '<div class="container">';
            $theMsg= '<div class="alert alert-danger">theres no such id</div>';
            redirectHome($theMsg);
            echo '</div>';
        }

    } elseif ($do == 'update') {
        echo " <h1 class='text-center'> Edit Member</h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $_POST['userid'];
            $username = $_POST['username'];
            $email = $_POST['email'];

            //password trick
            $pass = '';
            if (empty($_POST['newpassword'])) {
                $pass = $_POST['oldpassword'];
            } else {
                $pass = sha1($_POST['newpassword']);
            }

            // validate form
            $formErrors = array();

            if (strlen($username) < 4) {
                $formErrors[] = 'username can\'t be less than <strong> 4 characters</strong></div>';
            }
            if (strlen($username) > 20) {
                $formErrors[] = 'username can\'t be more than<strong> 20 characters</strong>';
            }

            if (empty($username)) {
                $formErrors[] = 'username can\'t Be<strong> Empty</strong>';
            }

            if (empty($email)) {
                $formErrors[] = 'email can\'t Be <strong>Empty</strong>';
            }


            //loop into Errors array and echo it
            foreach ($formErrors as $error) {
                echo '<div class=\'alert alert-danger\'>' . $error . "</div>" . "<br/>";
            }

            //check if there's no error proceed the Update operation

            if (empty ($formErrors)) {
                //update database
                $stmt2= $con->prepare("SELECT
                                                *
                                            FROM 
                                                user 
                                            WHERE 
                                               name = ? 
                                            AND 
                                                id != ?");
                $stmt2->execute(array($username,$id));
                $count = $stmt2-> rowCount();

                if ($count == 1){
                    $theMsg= '<div class="alert alert-danger">sorry username is Exist</div>';
                    redirectHome($theMsg,'back');

                } else {
                    $stmt = $con->prepare("UPDATE user SET name = ?,password= ? , email = ?  WHERE id = ?");
                    $stmt->execute(array($username, $pass, $email, $id));
                    $theMsg= '<div class=\'alert alert-success\'>' . $stmt->rowCount() . ' record update' . '</div>';
                    redirectHome($theMsg,'back');
                }
            }

        } else {
            $theMsg = "<div class='alert alert-danger'>Sorry You Can't Browse This Page Directly</div>";
            redirectHome($theMsg);

        }

        echo '</div>';
    }elseif ($do == 'delete') {
        echo " <h1 class='text-center'> Delete Member</h1>";
        echo "<div class='container'>";
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stmt = $con->prepare("SELECT id FROM user WHERE id = ?");
        $stmt->execute(array($userid));
        $check = $stmt->rowCount();

        if ($check > 0) {
            $stmt = $con->prepare('DELETE FROM user WHERE id = :iduser ');
            $stmt->bindparam(':iduser', $userid);
            $stmt->execute();
            $theMsg = '<div class=\'alert alert-success\'>' . $stmt->rowCount() . ' record Delete' . '</div>';
            redirectHome($theMsg, 'back');
        } else {

            $theMsg = '<div class="alert alert-danger">theres no such id</div>';
            redirectHome($theMsg);
        }
        echo "</div>";
    }

    include 'temb/footer.php';
} else {

    header('Location:index.php');
    exit();
}
ob_end_flush();