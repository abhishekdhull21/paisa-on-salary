<?php
    if (empty($_SESSION['isUserSession']['user_id'])) {
        $this->session->set_flashdata('err', "Session Expired, Try once more.");
        return redirect(base_url());
    } else {
        $where = ['company_id' => 1, 'product_id' => 1];
        $logo = $this->db->select('link, url')->where($where)->from('logo')->get()->row();
//    $userDetails = $this->db->select('users.*, user_role_permission.urp_is_lms_user_add')
//            ->where('users.user_id', $_SESSION['isUserSession']['user_id'])
//            ->from('users')
//            ->join('user_role_permission', 'user_role_permission.urp_user_id = users.user_id')
//            ->get()
//            ->row();

        $user_roles = $this->db->select('UR.user_role_user_id, UR.user_role_type_id, MRT.role_type_labels, MRT.role_type_name, MRT.role_type_heading')
                ->where('UR.user_role_user_id', $_SESSION['isUserSession']['user_id'])
                ->where(['UR.user_role_active' => 1, 'UR.user_role_deleted' => 0])
                ->from('user_roles UR')
                ->join('master_role_type MRT', 'MRT.role_type_id = UR.user_role_type_id')
                ->get()
                ->result();

        $userDetails = $this->db->select('users.*')
                ->where('users.user_id', $_SESSION['isUserSession']['user_id'])
                ->from('users')
                ->join('user_roles UR', 'UR.user_role_user_id = users.user_id')
                ->where(['UR.user_role_active' => 1, 'UR.user_role_deleted' => 0])
                ->get()
                ->row();
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <title>Bharat Loan LOS | LMS</title>
                <meta charset="utf-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="icon" href="<?= base_url('public/front'); ?>/images/fav.png" type="image/*" />
                <link rel="stylesheet" href="<?= base_url('public/front'); ?>/css/bootstrap.css?v=1.2" type="text/css">
                <link rel="stylesheet" href="<?= base_url('public/'); ?>css/font-awesome.css" type="text/css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css">
                <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.7/summernote.css">
                <link rel="stylesheet" href="<?= base_url('public/front'); ?>/css/layout.css?v=1.2" type="text/css">
                <link rel="stylesheet" href="<?= base_url('public/front'); ?>/css/components.css?v=1.2" type="text/css">
                <link rel="stylesheet" href="<?= base_url('public/front'); ?>/css/common-styles.css?v=1.2" type="text/css">
                <link rel="stylesheet" href="<?= base_url('public/'); ?>css/plugins.css?v=1.2" type="text/css"> 
                <link rel="stylesheet" href="<?= base_url('public/front'); ?>/css/datepicker.min.css?v=1.2" rel="stylesheet">
                <link rel="stylesheet" href="<?= base_url('public/front'); ?>/css/style.css?v=1.3"/>
                <link rel="stylesheet" href="<?= base_url('public/front'); ?>/css/ace-responsive-menu.css?v=1.2">

                <link rel="stylesheet" href="<?= base_url('public/front'); ?>/css/pages.css?v=1.2" type="text/css">
                <link rel="stylesheet" href="<?= base_url('public/front'); ?>/css/responsive.css?v=1.2" type="text/css">
                <link rel="stylesheet" href="<?= base_url('public/front'); ?>/css/matmix-iconfont.css?v=1.2" type="text/css">
                <link rel="stylesheet" href="<?= base_url('public/front'); ?>/css/roboto_font.css?v=1.2" type="text/css"> 
                <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css"/>


                <link rel="stylesheet" href="<?= base_url('public/front/css/dataTable/dataTables_1.10.25.min.css') ?>"> <!-- datatable -->
                <link rel="stylesheet" href="<?= base_url('public/front'); ?>/dist/accordion.css">
                <?php
                if (!empty($javascript_files)) {
                    echo $javascript_files;
                }
                ?>
                
                <style type="text/css">


            .loader1 {
                border:6px solid #e52255;
                /* Light grey */
                border-top: 6px solid #005c8d;
                /* Dark Green */
                border-radius: 50%;
                width: 200px;
                height: 200px;
                animation: spinloader 2s linear infinite;
                position: relative;
                top: 40%;
                left: 41%;
                padding: 6px;
            }

            .loader1 img {
                height: 180px;
                width: 180px;
                animation: spinlogo 2s linear infinite;
                border-radius: 100%;
            }

            @keyframes spinloader {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            @keyframes spinlogo {
                0% {
                    transform: rotate(360deg);
                }

                100% {
                    transform: rotate(0deg);
                }
            }


            .my-loaders {
                height: 100vh !important;
                width: 100% !important;
                top: 0 !important;
                position: fixed !important;
                background: #8d8d8dd9 !important;
                left: 0 !important;
                padding: 10px !important;
                z-index: 9999 !important;
            }


        </style>
            </head>
            <body>
<!--                <div id="cover">                     
                    <div class="loader">
                        <div class="loader_inner">BHARAT L</div>
                        <div class="circle_1">
                            <div class="circle_2"></div>
                        </div>
                        <div class="loader_inner_1">AN</div>
                    </div>
                </div>-->
                
                <div id="cover" class="my-loaders">
                        <div class="loader1">
                            <div class="loaderr">
                                <img src="/public/front/images/final.gif">
                            </div>
                        </div>
                    </div>

                <div class="navbar-wrapper" style="background: #fff; box-shadow: 0 0 7px #c7c7c7;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-2 col-xs-12 top_naman">
                                <a>
                                    <a href="<?= base_url(); ?>"><img src="<?= LMS_COMPANY_LOGO ?>" alt="logo" height="70"> <!---<?= base_url('public/front'); ?>/img/dhanvikas-logo.png--->

                                    </a>
                            </div>
                            <div class="col-md-5 col-xs-6 nav-hide">
                                <nav>
                                    <div class="menu-toggle">
                                        <button type="button" id="menu-btn">
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                        </button>
                                    </div>
                                    <ul id="respMenu" class="ace-responsive-menu" data-menu-style="horizontal">

                                        <li><a href="<?= base_url('dashboard') ?>" class="logout-lac" title="Dashboard"><i class="fa fa-home"></i>&nbsp;Home</a></li>
                                        <?php if (agent != "OL" && $_SESSION['isUserSession']['user_id'] != 265) { ?>
                                            <li><a href="<?= base_url('search') ?>" class="logout-lac" title="Search"><i class="fa fa-search"></i> &nbsp;Search</a></li>
                                        <?php } ?>
                                        <li><a href="<?= base_url('logout'); ?>" class="logout-lac" title="Logout"><i class="fa fa-sign-out"></i>&nbsp; Logout</a> </li> 
                                        <li><a href="<?= base_url('myProfile') ?>" class="logout-lac" title="<?= $userDetails->user_id ?>"><?= $_SESSION['isUserSession']['name'] ?></a></li>

                                    </ul>
                                </nav>
                            </div>
                            <div class="col-md-7 col-xs-6   col-sm-8 text-right nav-hidee my-ul">
                                <style>
                                    .my-ul ul {
                                    }
                                    .my-ul ul {
                                        float: right;
                                        margin-left: -10px;
                                    }
                                    .my-ul ul li {
                                        float: left;
                                        border-right: solid 1px #ccc;
                                        padding: 20px 10px;
                                    }
                                    .drop-menu {
                                        width: 100% !important;
                                        float: left !important;
                                        padding: 0px !important;
                                        border-top: solid 1px #ccc;
                                        border-right: none !important;
                                    }
                                    .drop-menu a{
                                        color: #747373 !important;
                                        font-size: 12px;
                                    }
                                    .drop-menu a:hover{
                                        color: #000 !important;
                                    }
                                    .dropdown-menu {
                                        padding-top: 0px !important;
                                        margin-top: 16px !important;
                                        box-shadow: 0 0 7px #ccc;
                                    }
                                    .my-ul ul .fa
                                    {
                                        color:#e52255 !important;
                                    }
                                    .noti-ellips
                                    {
                                        position: absolute;
                                        border-radius: 100%;
                                        font-size: 8px;
                                        top: -11px;
                                        z-index: 9999;
                                        background: #e48d48;
                                        color: #fff;
                                        width: 17px;
                                        height: 18px;
                                        float: left;
                                        line-height:19px;
                                        left: 7px;
                                        text-align: center;
                                    }
                                </style>
                                <ul>
                                    <li><a href="<?= base_url('dashboard') ?>" class="logout-lac" title="Dashboard"><i class="fa fa-home"></i></a>  </li>
                                    <?php if (agent != "OL" && $_SESSION['isUserSession']['user_id'] != 265) { ?>
                                        <li> <a href="<?= base_url('search') ?>" class="logout-lac" title="Search"><i class="fa fa-search"></i> </a></li>
                                    <?php } ?>
                                    <?php if (agent == 'CA') { ?>
                                            <!--<li><a href="<?= base_url('adminViewUser'); ?>" class="logout-lac" title="Setting"><i class="fa fa-gear"></i></a></li>-->
                                        <li><a href="<?= base_url('ums'); ?>" class="logout-lac" title="Setting"><i class="fa fa-gear"></i></a></li>
                                    <?php } ?>
                                    <li><a href="<?= base_url('logout'); ?>" class="logout-lac" title="Logout"><i class="fa fa-sign-out"></i></a>  </li>
                                    <li><a href="<?= base_url('myProfile') ?>" class="logout-lac" title="<?= $userDetails->user_id ?>"><?= $_SESSION['isUserSession']['name'] ?></a></li>

                                    <?php //if ($_SESSION['isUserSession']['user_id'] == 45) { ?>
                                    <li style="padding:13px;">
                                        <select class="form-control" id="role_permission" onchange="defaultLoginRole(<?= $_SESSION['isUserSession']['user_id'] ?>, this)" style="height: 34px;border-radius: 30px;">
                                            <?php foreach ($user_roles as $role) { ?>
                                                <option value="<?= $role->user_role_type_id ?>" <?php
                                                if ($role->user_role_type_id == $_SESSION['isUserSession']['role_id']) {
                                                    echo "selected";
                                                }
                                                ?>><?= $role->role_type_name ?></option>
                                                    <?php } ?>
                                        </select>
                                    </li>
                                    <?php //}   ?>
                                </ul>
                            </div>
                            <div class="col-md-3 col-xs-6 col-sm-4">
                                <a href="<?= WEBSITE_URL ?>" target="_blank">
                                    <img class="img-rounded img-responsive right-logo" src="<?= LMS_BRAND_LOGO ?>" width="160" height="70" alt="logo">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end header -->
            <?php } ?>
