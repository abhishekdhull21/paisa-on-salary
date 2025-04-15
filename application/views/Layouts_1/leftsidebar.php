<?php 
//echo $this->uri->segment(1);
?>
<div id="sidenav1">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#sideNavbar"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
    </div>
    <div class="collapse navbar-collapse" id="sideNavbar" style="background:#fff !important; padding: 0px !important;">
        <div class="panel-group" id="accordion">
            <?php if (agent == 'CA') { ?>
                    <div class="panel panel-default" >
                        <div class="panel-heading"> 
                            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree"> <i class="fa fa-sign-in"></i>&nbsp;Users<span class="caret"></span></a> </h4>
                        </div>

                        <div id="collapseThree" class="panel-collapse collapse"> 
                            <ul class="list-group"> 
                                <li class="navlink2"><a href="<?= base_url('ums/add-user') ?>"><i class="fa fa-angle-right"></i> Add User</a></li>
                                <li class="navlink2"><a href="<?= base_url('ums') ?>"><i class="fa fa-angle-right"></i> View User</a></li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            <?php if (agent == 'CA') { ?>
                    <div class="panel panel-default" style="border: solid 1px #ddd;">
                        <div class="panel-heading">
                            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseimport"><i class="fa fa-table"></i> Import Data<span class="caret"></span></a> </h4>
                        </div>
                        <div id="collapseimport" class="panel-collapse collapse">
                            <ul class="list-group">
                                <li><a href="<?= base_url('ViewImportData') ?>" class="navlink"><i class="fa fa-angle-right"> Import CSV</i></a></li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>

            <?php if (agent == 'CA' || $user->isAddNewBankMaster == 1) { ?>
                    <div class="panel panel-default" style="border: solid 1px #ddd;">
                        <div class="panel-heading">
                            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><i class="fa fa-table"></i> Add Bank Details<span class="caret"></span></a> </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse">
                            <ul class="list-group">
                                <li><a href="<?= base_url('addBankDetails') ?>" class="navlink"><i class="fa fa-angle-right"></i> Add Bank Lists</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel panel-default" style="border: solid 1px #ddd;">
                        <div class="panel-heading">
                            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour"><i class="fa fa-table"></i> Add Holiday Details<span class="caret"></span></a> </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse">
                            <ul class="list-group">
                                <li><a href="<?= base_url('addHolidayDetails') ?>" class="navlink"><i class="fa fa-angle-right"></i> Add Holiday Lists</a></li>
                            </ul>
                        </div>
                    </div>
                <?php } else if (agent == 'CA') { ?>

                    <div class="panel panel-default" style="border: solid 1px #ddd;">
                        <div class="panel-heading">
                            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapse3"><i class="fa fa-table"></i>&nbsp;Client Company Details<span class="caret"></span></a> </h4>
                        </div>
                        <div id="collapse3" class="panel-collapse collapse">
                            <ul class="list-group">
                                <li><a href="<?= base_url('addCompanyDetails'); ?>" class="navlink"><i class="fa fa-angle-right"></i>&nbsp;Company Lists</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="panel panel-default" style="border: solid 1px #ddd;">
                        <div class="panel-heading">
                            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapse4"><i class="fa fa-table"></i>&nbsp;Dashboard<span class="caret"></span></a> </h4>
                        </div>
                        <div id="collapse4" class="panel-collapse collapse">
                            <ul class="list-group">
                                <li><a href="<?= base_url('adminViewDashboard'); ?>" class="navlink"><i class="fa fa-angle-right"></i>&nbsp;Add Menus</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="menu-hide">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a href=""><span class="glyphicon glyphicon-new-window"></span>Add Company</a> 
                                </h4>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a href=""><span class="glyphicon glyphicon-new-window"></span>External Link</a> </h4>
                            </div>
                        </div>
                    </div>
                 <?php } ?>
                 <?php if (agent == 'CA') { ?>
                 <div class="panel panel-default" >
                        <div class="panel-heading"> 
                            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseNinth" <?php if($this->uri->segment(1)=='support'){echo 'aria-expanded="true"';} ?>> <i class="fa fa-sign-in"></i>&nbsp;Support Mail<span class="caret"></span></a> </h4>
                        </div>
                        <div id="collapseNinth" class="panel-collapse collapse <?php if($this->uri->segment(1)=='support'){echo 'in';} ?>" <?php if($this->uri->segment(1)=='support'){echo 'aria-expanded="true"';} ?>> 
                            <ul class="list-group"> 
                                <li class="navlink2"><a href="<?= base_url('support/personal-details') ?>"><i class="fa fa-angle-right"></i> Personal Details</a></li>
                                <li class="navlink2"><a href="<?= base_url('support/employment-details') ?>"><i class="fa fa-angle-right"></i> Employment Details</a></li>
                                <li class="navlink2"><a href="<?= base_url('support/reference-details') ?>"><i class="fa fa-angle-right"></i> Reference Details</a></li>
                                <li class="navlink2"><a href="<?= base_url('support/docs-details') ?>"><i class="fa fa-angle-right"></i> Docs Details</a></li>
                                <li class="navlink2"><a href="<?= base_url('support/cam-details') ?>"><i class="fa fa-angle-right"></i> CAM Details</a></li>
                                <li class="navlink2"><a href="<?= base_url('support/bank-details') ?>"><i class="fa fa-angle-right"></i> Bank Details</a></li>
                                <li class="navlink2"><a href="<?= base_url('support/transaction-failed') ?>"><i class="fa fa-angle-right"></i> Transaction Failed</a></li>
                                <li class="navlink2"><a href="<?= base_url('support/black-list') ?>"><i class="fa fa-angle-right"></i> Customer Black List</a></li>
								<li class="navlink2"><a href="<?= base_url('support/sysytem-blacklisted-pincode') ?>"><i class="fa fa-angle-right"></i> Sysytem Blacklisted Pincode</a></li>
				<li class="navlink2"><a href="<?= base_url('support/pincode-list') ?>"><i class="fa fa-angle-right"></i> Pincode List</a></li>
                            </ul>
                        </div>
                 </div>
                <?php } ?>
                  <div class="panel panel-default" >
                        <div class="panel-heading"> 
                            <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseTenth" <?php if($this->uri->segment(1)=='blog-list' || $this->uri->segment(1)=='blog' || $this->uri->segment(1)=='seo-list' || $this->uri->segment(1)=='seo'){echo 'aria-expanded="true"';} ?>> <i class="fa fa-sign-out"></i>&nbsp;Blog & SEO<span class="caret"></span></a> </h4>
                        </div>
                        <div id="collapseTenth" class="panel-collapse collapse <?php if($this->uri->segment(1)=='blog-list' || $this->uri->segment(1)=='blog' || $this->uri->segment(1)=='seo-list' || $this->uri->segment(1)=='seo'){echo 'in';} ?>" <?php if($this->uri->segment(1)=='blog-list' || $this->uri->segment(1)=='blog' || $this->uri->segment(1)=='seo-list' || $this->uri->segment(1)=='seo'){echo 'aria-expanded="true"';} ?>> 
                            <ul class="list-group"> 
                                <li class="navlink2"><a href="<?= base_url('blog-list') ?>"><i class="fa fa-angle-right"></i> Blog Manage</a></li>
                                <li class="navlink2"><a href="<?= base_url('seo-list') ?>"><i class="fa fa-angle-right"></i> SEO Manage</a></li>
                            </ul>
                        </div>
                 </div>
        </div>
    </div>
</div>
