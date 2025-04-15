<?php
$uri = $this->uri->segment(1);
$stage = $this->uri->segment(2);

       // echo "<pre>". $totalcount; print_r($leadDetails->result()); exit;
?>
<?php $this->load->view('Layouts/header') ?>
<span id="response" style="width: 100%;float: left;text-align: center;padding-top:-20%;"></span>
<section>
    <div class="width-my">
        <div class="container-fluid">
            <div class="taskPageSize taskPageSizeDashboard">
                <div class="alertMessage">
                    <div class="alert alert-dismissible alert-success msg">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Thanks!</strong>
                        <a href="#" class="alert-link">Add Successfully</a>
                    </div>
                    <div class="alert alert-dismissible alert-danger err">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Failed!</strong>
                        <a href="#" class="alert-link">Try Again.</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="padding: 0px !important;">
                        <div class="page-container list-menu-view">
                            <div class="page-content">
                                <div class="main-container">
                                    <div class="container-fluid">
                                        <div class="col-md-12">
                                            <div class="login-formmea">
                                                <div class="box-widget widget-module">
                                                    <div class="widget-head clearfix">
                                                        <span class="h-icon"><i class="fa fa-th"></i></span>
                                                        <span class="inner-page-tag">Leads </span> 
                                                        <span class="counter inner-page-box"><?= $totalcount; ?></span>
                                                        <?php if ($stage == "S1" || $stage == "S4") { ?>
                                                            <a  class="btn inner-page-box checkDuplicateItem" id="checkDuplicateItem" style="background: #0d7ec0 !important;">Duplicate</a>
                                                            <a  class="btn inner-page-box" id="allocate" style="background: #0d7ec0 !important;">Allocate</a> 
                                                        <?php } if($uqickCall=='button') {?>

                                                         <div class="tb_search">
                                                            <button class="btn btn-success" onclick="getLeadValue()">Quick Call</button>  </div>
                                                        <?php } ?>
                                                        <div class="tb_search">
                                                            <input type='text' class="form-control" id='txt_searchall' placeholder='Enter search text'>
                                                        </div>
                                                    </div>

                                                    <div class="widget-container">
                                                        <div class=" widget-block">
                                                            <div class="row">
                                                                <div class="table-responsive">
                                                                    <!-- data-order='[[ 0, "desc" ]]'  dt-table -->
                                                                    <table class="table table-striped table-bordered table-hover"  style="border: 1px solid #dde2eb">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="whitespace data-fixed-columns"><b>Sr No.</b></th>
                                                                                <th class="whitespace"><b>Action</b></th>
                                                                                <th class="whitespace"><b>Name</b></th>
                                                                                <th class="whitespace"><b>Email</b></th>
                                                                                <th class="whitespace"><b>Mobile</b></th>
                                                                                <th class="whitespace"><b>Loan Amount</b></th>
                                                                                <th class="whitespace"><b>City</b></th>
                                                                                <th class="whitespace"><b>Initiated&nbsp;On</b></th>

                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                                if ($totalcount > 0) {
                                                                                $sn = 1;
                                                                                foreach ($leadDetails->result() as $row) :
                                                                            ?>
                                                                                    <tr>
                                                                                        <td class="whitespace data-fixed-columns">
                                                                                           <?php   if($uqickCall=='button') {?><input type="checkbox" name="quickCall_id[]" class="quickCall_id" id="quickCall_id" value="<?= $row->cust_enquiry_id; ?>">
                                                                                            <?php } ?>
                                                                                            <?= $row->cust_enquiry_id ?>
                                                                                        </td>
                                                                                        <td class="whitespace">
                                                                                            <a href="<?= base_url("getEnquiryDetails/" . $this->encrypt->encode($row->cust_enquiry_id)) ?>" id="viewLeadsDetails">
                                                                                                <span class="glyphicon glyphicon-edit" style="font-size: 20px;" disabled></span>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td class="whitespace"><?= ($row->cust_enquiry_name) ? strtoupper($row->cust_enquiry_name) : "-" ?></td>
                                                                                        <td class="whitespace"><?= ($row->cust_enquiry_email) ? strtoupper($row->cust_enquiry_email) : '-' ?></td>
                                                                                        <td class="whitespace"><?= ($row->cust_enquiry_mobile) ? $row->cust_enquiry_mobile : '-' ?></td>
                                                                                        <td class="whitespace"><?= ($row->cust_enquiry_loan_amount) ? strtoupper($row->cust_enquiry_loan_amount) : '-' ?></td>
                                                                                        <td class="whitespace"><?= ($row->cust_enquiry_city_name) ? strtoupper($row->cust_enquiry_city_name) : "-" ?></td>
                                                                                        <td class="whitespace"><?= date('d-m-Y H:i', strtotime($row->cust_enquiry_created_datetime)) ?></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                endforeach;
                                                                            } else {
                                                                                ?>
                                                                                <tr>
                                                                                    <th colspan="13" class="whitespace data-fixed text-center"><b style="color: #b73232;">No Record Found...</b></th>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                    <?= $links; ?>
                                                                </div>
                                                            </div>
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
    </div>
</section>
<?php $this->load->view('Layouts/footer') ?>
<?php $this->load->view('Tasks/main_js.php') ?>
<script type="text/javascript">

    $(document).ready(function () {
        $('#txt_searchall').keyup(function () {
            var search = $(this).val().toUpperCase();
            $('table tbody tr').hide();
            var len = $('table tbody tr:not(.notfound) td:contains("' + search + '")').length;
            if (len > 0) {
                $('table tbody tr:not(.notfound) td:contains("' + search + '")').each(function () {
                    $(this).closest('tr').show();
                    $('.price-counter').text(len);
                });
            } else {
                $('.notfound').show();
                $('.price-counter').text(len);
            }
        });
    });

</script>
