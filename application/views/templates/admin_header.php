<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Flood Indicator - Admin</title>

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">

    <!-- Poppins Font -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/poppins.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">

    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.mCustomScrollbar.min.css">

    <!-- Font Awesome JS -->
    <script defer src="<?php echo base_url(); ?>assets/js/solid.js"></script>
    <script defer src="<?php echo base_url(); ?>assets/js/fontawesome.js"></script>

</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <div class="row">
                    <div class="col-md-12">   
                        <img src="<?php echo base_url(); ?>assets/images/logo.png" width="200">
                    </div>
                    
                </div>
            </div>

            <ul class="list-unstyled components">
                <!-- <p>Navigasi</p> -->
                <?php if($active == 'dashboard'):?>
                <li class="active">
                <?php else:?>
                <li>
                <?php endif;?>
                    <a href="<?php echo base_url(); ?>admin_dashboard">Dashboard</a>
                </li>

                <?php if($active == 'create'):?>
                <li class="active">
                <?php else:?>
                <li>
                <?php endif;?>
                    <a href="<?php echo base_url(); ?>create_user">Create new user</a>
                </li>

                <?php if($active == 'profile'):?>
                <li class="active">
                <?php else:?>
                <li>
                <?php endif;?>
                    <a href="<?php echo base_url(); ?>profile">Profile</a>
                </li>


                <?php if($active == 'help'):?>
                <li class="active">
                <?php else:?>
                <li>
                <?php endif;?>
                    <a href="<?php echo base_url(); ?>help">Help</a>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="<?php echo base_url(); ?>" class="download">User Area</a>
                </li>
            </ul>

            <div class="text-center">
               <small> &copy Faris Ramadhan</small>
            </div>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn custom_btn">
                        <i class="fas fa-align-left"></i>
                        <span> Hide Navigation Bar</span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link top_link" href="<?php echo base_url(); ?>logout"><?php echo $this->session->userdata('name'); ?> (Logout)</a>
                            </li>
                        </ul>
                    </div>

                </div>
            </nav>


        <?php if($this->session->flashdata('flashdata_danger')): ?>
            <?php echo '<p class="alert alert-danger flashData">'.$this->session->flashdata('flashdata_danger').'</p>'; ?>
        <?php endif; ?>
        <?php if($this->session->flashdata('flashdata_success')): ?>
        <?php echo '<p class="alert alert-success flashData">'.$this->session->flashdata('flashdata_success').'</p>'; ?>
    <?php endif; ?>