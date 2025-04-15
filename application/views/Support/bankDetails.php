<?php $this->load->view('Layouts/header'); ?>
<section class="ums">
    <div class="container-fluid">
        <div class="taskPageSize taskPageSizeDashboard">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-container list-menu-view">
                        <div class="page-content">
                            <div class="main-container">
                                <div class="container-fluid">
                                    <?php if (agent == 'CA') { ?>
                                        <div class="col-md-3 drop-me">
                                            <?php $this->load->view('Layouts/leftsidebar') ?>
                                        </div>
                                    <?php } ?>
                                    <div class="col-md-9">
                                        <div class="login-formmea" style="margin-bottom: 10px;">
                                            <div class="box-widget widget-module">
                                                <div class="widget-head clearfix">
                                                    <span class="h-icon"><i class="fa fa-th"></i></span>
                                                    <h4>Search lead id? </h4>
                                                </div>
                                                <div class="widget-container">
                                                    <div class=" widget-block">
                                                        
                                                        <?php
                                                        if ($this->session->flashdata('message') != '') {
                                                            echo '<div class="alert alert-success alert-dismissible">
                		                              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                		                              <strong>' . $this->session->flashdata('message') . '</strong> 
                		                            </div>';
                                                        }
                                                        else if($this->session->flashdata('error') != '') {
                                                            echo '<div class="alert alert-danger alert-dismissible">
                		                              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                		                              <strong>' . $this->session->flashdata('error') . '</strong> 
                		                            </div>';
                                                        }
                                                        ?>

                                                        <form id="leadIddata" autocomplete="off" action="<?= base_url('support/searchBankId'); ?>" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <input type="text" class="form-control" name="lead_id" id="lead_id" required="" value="<?php if(isset($_POST['lead_id']) && $_POST['lead_id']!=''){echo $_POST['lead_id'];} ?>" placeholder="Please enter lead id*" onkeypress="if (isNaN(String.fromCharCode(event.keyCode)))
                                                                            return false;">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <button type="submit" id="search_lead_id" class="button btn">Search LEAD ID</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(!empty($status)){ ?>
                                        <div class="login-formmea">
                                            <div class="box-widget widget-module">
                                                <div class="widget-head clearfix">
                                                    <span class="h-icon"><i class="fa fa-th"></i></span>
                                                    <h4>Update Bank Details</h4>
                                                </div>
                                                <div class="widget-container">
                                                    <div class=" widget-block">
                                                        <form autocomplete="off" action="" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                                            <input type="hidden" name="lead_id" id="lead_id" value="<?php if(isset($leadInfo['lead_id']) && $leadInfo['lead_id']!=''){echo $leadInfo['lead_id'];}?>" />
                                                               <div class="row">
                                                                  <div class="col-md-6">
                                                                    <label for="account_id">Bank Account <span class="span" style="color:red;">*</span></label>                                                          
                                                                    <input type="text" class="form-control" name="account" id="account_id" maxlength="22" value="<?php if(isset($leadInfo['account']) && $leadInfo['account']!=''){echo $leadInfo['account'];}else{echo $_POST['account'];} ?>" onkeypress="if (isNaN(String.fromCharCode(event.keyCode)))
                                                                            return false;">
                                                                 </div>
                                                                 <div class="col-md-6">
                                                                    <label for="beneficiary_name_id">Beneficiary Name <span class="span" style="color:red;">*</span></label>      
                                                                    <input type="text" class="form-control" name="beneficiary_name" id="beneficiary_name_id" value="<?php if(isset($leadInfo['beneficiary_name']) && $leadInfo['beneficiary_name']!=''){echo $leadInfo['beneficiary_name'];}else{echo $_POST['beneficiary_name'];} ?>">
                                                                </div>                                                                 
                                                                <p>&nbsp</p> 
                                                                <div class="col-md-6">
                                                                    <label for="ifsc_code_id">IFSC Code <span class="span" style="color:red;">*</span></label>                   
                                                                    <input type="text" class="form-control" maxlength="15"  name="ifsc_code" id="ifsc_code_id" value="<?php if(isset($leadInfo['ifsc_code']) && $leadInfo['ifsc_code']!=''){echo $leadInfo['ifsc_code'];}else{echo $_POST['ifsc_code'];} ?>">
                                                                </div>                                                               
                                                                <div class="col-md-6">
                                                                    <label for="bank_name_id">Bank Name <span class="span" style="color:red;">*</span></label>                  
                                                                    <input type="text" required class="form-control" name="bank_name" id="bank_name_id" value="<?php if(isset($leadInfo['bank_name']) && $leadInfo['bank_name']!=''){echo $leadInfo['bank_name'];}else{echo $_POST['bank_name'];} ?>">
                                                                </div>                                                               
                                                                <p>&nbsp</p>
                                                                <div class="col-md-6">
                                                                    <label for="branch_id">Branch <span class="span" style="color:red;">*</span></label>                          
                                                                    <input type="text" class="form-control" name="branch" id="branch_id" value="<?php if(isset($leadInfo['branch']) && $leadInfo['branch']!=''){echo $leadInfo['branch'];}else{echo $_POST['branch'];} ?>">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="account_type_id">Account Type <span class="span" style="color:red;">*</span></label>                                                          
                                                                    <select class="form-control" name="account_type" id="account_type_id">
                                                                        <option value="">Select Type</option>
                                                                        <?php if(count($bank_type_list)>0){foreach($bank_type_list as $key => $bank_typeVal) {?>
                                                                         <option value="<?=$bank_typeVal['m_bank_type_name']?>"<?php if(isset($leadInfo['account_type']) && $leadInfo['account_type']==$bank_typeVal['m_bank_type_name']){echo 'Selected="Selected"';} ?>><?=$bank_typeVal['m_bank_type_name']?></option>  
                                                                        <?php }} ?>
                                                                    </select>
                                                                </div>
                                                                <p>&nbsp</p>
                                                                <div class="col-md-6">
                                                                    <label for="account_status_id">Account Status </label>                                                        
                                                                    <input type="text" class="form-control" name="account_status" id="account_status_id" value="<?php if(isset($leadInfo['account_status']) && $leadInfo['account_status']!=''){echo $leadInfo['account_status'];}else{echo $_POST['account_status'];} ?>">
                                                                </div>                                                              
                                                                <div class="col-md-6"> 
                                                                    <label for="lead_followup_remark">Lead Followup Remark </label>                                       
                                                                    <textarea class="form-control" name="lead_followup_remark" id="lead_followup_remark" style="width:100% !important;height:50px !important;" autocomplete="off" placeholder="Please enter lead followup remark."></textarea>
                                                                </div>
                                                                                                                                
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <button type="button" class="button-add btn update_bank_details">Update Bank Details</button>
                                                                </div>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
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
