<nav class="navbar navbar-inverse">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><i class="fas fa-home"></i>HOME</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li> <a href="admin.php"> <i class="fa fa-users fa-lg"></i> Admin</a></li>
                <li> <a href="contact.php"> <i class="fas fa-comment-alt"></i> Contact</a></li>
                <li> <a href="../index.php"><i class="far fa-window-maximize"></i> Site</a></li>


            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> Admin<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="admin.php?do=Edit&userid=<?php echo $_SESSION['ID']; ?>"><i class="fas fa-user-cog"></i> Edit Profile</a></li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>