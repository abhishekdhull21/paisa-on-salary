<?php $this->load->view('Layouts/header'); 
 $MasterPincodeActionUrl = "support/add-pincode";
?>
<section class="ums">

    <div class="container-fluid">

        <div class="taskPageSize taskPageSizeDashboard" style="margin-top:30px">

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
                                          <div class="row">
                                        <div class="login-formmea">
                                            <div class="box-widget widget-module">
                                                <div class="widget-head clearfix">
                                                    <span class="h-icon"><i class="fa fa-th"></i></span>
                                                    <h4>
                                                   Add Pincode
                                                    :: <a href="<?=base_url('support/pincode-list'); ?>"><b><i title="Back" class="fa fa-arrow-left" aria-hidden="true"></i></b></a>
                                                    </h4>
                                                </div>
                                                <div class="widget-container">
                                                    <div class=" widget-block">
                                                       
                                                        <form id="formData" method="post" enctype="multipart/form-data" action="<?=base_url($MasterPincodeActionUrl)?>">
                                                            <input type="hidden" name="m_pincode_id" value="#" />
                                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="m_pincode_value"><span class="span">*</span>Pincode</label>
                                                                    <input type="text" maxlength="6" class="form-control" name="m_pincode_value" id="m_pincode_value" value="" onkeypress="if (isNaN(String.fromCharCode(event.keyCode)))
                                                                            return false;" required/>
                                                                </div>                                                                                                                 
                                                            </div> 
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <button type="submit" class="button-add btn btn-ifo" id="adminSaveseo">Save</button>
                                                                    <a class="button-add btn btn-ifo" href="<?= base_url('support/pincode-list') ?>" role="button">Cancel</a>
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
    </div>
</section>
<?php $this->load->view('Layouts/footer'); ?>
<?php $this->load->view('Support/support_js'); ?>
