<?php $this->load->view('Layouts/header') ?>



<!-- section start -->
<section>
    <div class="container-fluid">
        <div class="taskPageSize taskPageSizeDashboard">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-container list-menu-view">
                        <div class="page-content">
                            <div class="main-container">
                                <div class="container-fluid">


                                    <div class="col-md-3 drop-me">

                                        <?php $this->load->view('Layouts/leftsidebar') ?>

                                    </div>

                                    <div class="col-md-9">
                                        <div class="login-formmea">
                                            <div class="box-widget widget-module">
                                                <div class="widget-container">
                                                    <div class=" widget-block">
                                                        <?php
                                                        if ($this->session->flashdata('msg') != '') {
                                                            echo '<div class="alert alert-success alert-dismissible">
                		                              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                		                              <strong>' . $this->session->flashdata('msg') . '</strong> 
                		                            </div>';
                                                        }
                                                        if ($this->session->flashdata('err') != '') {
                                                            echo '<div class="alert alert-danger alert-dismissible">
                		                              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                		                              <strong>' . $this->session->flashdata('err') . '</strong> 
                		                            </div>';
                                                        }
                                                        ?>

                                                        <form autocomplete="off" action="<?= base_url('importData'); ?>" id="get_form_data" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                                            <div class="row">

                                                                <input type="hidden" class="form-control" name="user_id" id="user_id" value="<?= $_SESSION['isUserSession']['user_id'] ?>" placeholder="User ID" title="Data Person Name" required="" readonly>
                                                                <input type="hidden" class="form-control" name="source" id="source" value="Import" placeholder="source" title="source" required="" readonly>

                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-md-2">
                                                                                <label class="col-form-label col-form-label-sm" for="File">Import CSV</label>
                                                                            </div>
                                                                            <div class="col-md-10">
                                                                                <input type="file" class="form-control form-control-sm" name="csv_file" id="csv_file" value="" title="Import CSV" placeholder="Import CSV" required="" accept=".csv">
                                                                                Ex. Allowed .csv file only  | Download <a href="<?= base_url('sampleCSV')?>">Sample CSV</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-10"></div>
                                                                    <div class="col-md-2">
                                                                        <button type="submit" class="btn btn-primary" id="add_query" title="Import Old Data" style="float: right;">Import CSV</button>
                                                                    </div>
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
                            <!--Footer Start Here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<!-- footer -->
<?php $this->load->view('Layouts/footer') ?>
