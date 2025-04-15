<?php $this->load->view('Layouts/header') ?>

<?php
$umsActionUrl = 'ums/add-user';
if ($update_flag == true && !empty($enc_user_id)) {
    $umsActionUrl = "ums/edit-user/" . $enc_user_id;
}
//traceObject($user_data);
?>
<!-- section start -->

<section class="ums">

    <div class="container-fluid">

        <div class="taskPageSize taskPageSizeDashboard">

            <div class="row">

                <div class="col-md-12">

                    <div class="page-container list-menu-view">

                        <div class="page-content">

                            <div class="main-container">

                                <div class="container-fluid">

                                    <div class="col-md-2 drop-me">
                                        <?php $this->load->view('Layouts/leftsidebar') ?>
                                    </div>

                                    <div class="col-md-10 div-right-sidebar">

                                        <div class="login-formmea">

                                            <div class="box-widget widget-module">

                                                <div class="widget-head clearfix">

                                                    <span class="h-icon"><i class="fa fa-th"></i></span>
                                                    <?php if ($update_flag == true) { ?>
                                                        <h4>Update User</h4>
                                                    <?php } else { ?>
                                                        <h4>Add User</h4>
                                                    <?php } ?>

                                                </div>

                                                <div class="widget-container">

                                                    <div class=" widget-block">

                                                        <div class="row">
                                                            <?php if (!empty($this->session->flashdata('success_msg'))) { ?>
                                                                <div class="alert alert-success alert-dismissible">
                                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                                    <?= $this->session->flashdata('success_msg'); ?>
                                                                </div>
                                                            <?php } else if (!empty($this->session->flashdata('errors_msg'))) { ?>
                                                                <div class="alert alert-danger alert-dismissible">
                                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                                    <?= $this->session->flashdata('errors_msg'); ?>
                                                                </div>
                                                            <?php } ?>
                                                        </div>


                                                        <form id="formData" method="post" enctype="multipart/form-data" action="<?= base_url($umsActionUrl) ?>">

                                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />



                                                            <div class="row">

                                                                <!--<div class="col-md-6">

                                                                    <label>Login Username</label>

                                                                    <input type="text" class="form-control" name="user_name" id="login_user_name" value="<?= !empty($user_data["user_name"]) ? $user_data["user_name"] : "" ?>"/>

                                                                </div>-->

                                                                <div class="col-md-6">

                                                                    <label><span class="span">*</span> Name</label>

                                                                    <input type="text" class="form-control" name="name" id="user_name" value="<?= !empty($user_data["name"]) ? $user_data["name"] : "" ?>" required/>

                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">

                                                                    <label><span class="span">*</span> Email</label>

                                                                    <input type="email" class="form-control" name="email" id="email" onchange="IsEmail(this)" value="<?= !empty($user_data["email"]) ? $user_data["email"] : "" ?>" required/>

                                                                </div>



                                                                <div class="col-md-6">

                                                                    <label><span class="span">*</span> Mobile</label>

                                                                    <input type="text" class="form-control" name="mobile" id="mobile" value="<?= !empty($user_data["mobile"]) ? $user_data["mobile"] : "" ?>" required/>

                                                                </div>
                                                            </div>


                                                            <div class="row">

                                                                <div class="col-md-6">

                                                                    <label><span class="span">&nbsp;</span> Dialer ID</label>

                                                                    <input type="text" class="form-control" name="user_dialer_id" id="user_dialer_id" value="<?= !empty($user_data["user_dialer_id"]) ? $user_data["user_dialer_id"] : "" ?>" />

                                                                </div>
                                                                <div class="col-md-6">

                                                                    <label><span class="span">&nbsp;</span> User Status</label>
                                                                    <select class="form-control" name="user_status_id" id="user_status_id">
                                                                        <option value="">Select</option>
                                                                        <?php
                                                                        if (!empty($master_user_status)) {
                                                                            foreach ($master_user_status as $status_key => $status_value) {
                                                                                ?>
                                                                                <option <?= (!empty($user_data["user_status_id"]) && $status_key == $user_data["user_status_id"]) ? 'selected="selected"' : "" ?> value="<?= $status_key ?>"><?= $status_value ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>

                                                                    </select>


                                                                </div>
                                                            </div>
                                                            <div class="row">

                                                                <div class="col-md-12">

                                                                    <button type="submit" class="button-add btn btn-ifo" id="adminSaveUser">Save</button>

                                                                    <a class="button-add btn btn-ifo" href="<?= base_url('ums') ?>" role="button">Cancel</a>

                                                                </div>

                                                            </div>
                                                        </form>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>

<?php $this->load->view('Layouts/footer') ?>
