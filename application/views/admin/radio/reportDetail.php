<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$genderList = $this->customlib->getGender();
?>
<style type="text/css">
    #easySelectable {/*display: flex; flex-wrap: wrap;*/}
    #easySelectable li {}
    #easySelectable li.es-selected {background: #2196F3; color: #fff;}
    .easySelectable {-webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;}
</style>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('radiology') . " " . $this->lang->line('test') . " " . $this->lang->line('reports'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('radiology') . " " . $this->lang->line('test') . " " . $this->lang->line('reports'); ?></div>
                    <table class="table table-striped table-bordered table-hover test_ajax" id="testreport" cellspacing="0" width="100%">
                            <thead >
                                <tr>
                                    <th><?php echo $this->lang->line('bill') . " " . $this->lang->line('no'); ?></th>
                                    <th><?php echo $this->lang->line('reporting') . " " . $this->lang->line('date'); ?></th>
                                    <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('test') . " " . $this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('short') . " " . $this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('refference') . " " . $this->lang->line('doctor'); ?></th>
                                    <th><?php echo $this->lang->line('description'); ?></th>
                                    <th class="" ><?php echo $this->lang->line('applied') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>                                                    
            </div>
        </div>  
    </section>
</div>

<div class="modal fade" id="addParametervalueModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pt4" data-dismiss="modal">&times;</button>
                <div class="row">
                    <div class="col-sm-4">
                        <div>   
                            <select onchange="get_PatientDetails(this.value)" disabled="" style="width: 100%" class="form-control select2" id="addpatientidd" name='' >
                                <option value=""><?php echo $this->lang->line('select') . " " . $this->lang->line('patient') ?></option>
                                    <?php foreach ($patients as $dkey => $dvalue) {
                                    ?>
                                    <option value="<?php echo $dvalue["id"]; ?>" <?php
                                    if ((isset($patient_select)) && ($patient_select == $dvalue["id"])) {
                                        echo "selected";
                                    }
                                    ?>><?php echo $dvalue["patient_name"] . " (" . $dvalue["patient_unique_id"] . ")" ?></option>   
                                    <?php } ?>
                            </select> 
                        </div>
                    </div><!--./col-sm-9-->  
                </div><!--./row-->  
            </div>
            <form id="parameteradd" enctype="multipart/form-data" accept-charset="utf-8"  method="post" class="" >    
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <input type="hidden" name="id" id="preport_id" >
                        <input type="hidden" name="patient_id_radio" id="patientid_radio" >
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('reporting') . " " . $this->lang->line('date'); ?></label>
                                    <input type="text" id="pedit_report_date" name="reporting_date" class="form-control date">
                                    <span class="text-danger"><?php echo form_error('reporting_date'); ?></span>
                                </div>
                            </div> 
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="exampleInputFile">
                                        <?php echo $this->lang->line('refferal') . " " . $this->lang->line('doctor'); ?></label>
                                    <div>
                                        <select class="form-control select2" style="width: 100%" onchange="get_Docname(this.value)" name='consultant_doctor'  id="pedit_consultant_doctor">
                                            <option value="<?php echo set_value('consultant_doctor'); ?>"><?php echo $this->lang->line('select') ?></option>
                                            <?php foreach ($doctors as $dkey => $dvalue) {
                                                ?>
                                                <option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["name"] . " " . $dvalue["surname"] ?></option>   
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <span class="text-danger"><?php echo form_error('consultant_doctor'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('test') . " " . $this->lang->line('report'); ?></label>
                                    <input type="file"  class="filestyle form-control" data-height="40" name="radiology_report">
                                    <span class="text-danger"><?php echo form_error('radiology_report'); ?></span>
                                </div>
                            </div> 
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('charge') . " " . $this->lang->line('category'); ?></label>
                                    <input type="text" class="form-control" readonly="" id="pcharge_category_html" >

                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('code'); ?></label>
                                    <input type="text" readonly="" class="form-control" id="pcode_html" >
                                    <span class="text-danger" ></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('standard') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')'; ?></label>
                                    <input type="text" readonly="" class="form-control" id="pcharge_html" >
                                    <span class="text-danger" ></span>
                                </div>
                            </div> 
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('apply') . " " . $this->lang->line('charge') . ' (' . $currency_symbol . ')'; ?></label>
                                    <input type="text"  name="apply_charge" class="form-control" id="papply_charge" >
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="description"><?php echo $this->lang->line('description'); ?></label> 
                                  <!--   <small class="req"> *</small>  -->
                                    <textarea name="description" id="pedit_description" class="form-control" ></textarea>
                                    <span class="text-danger"><?php echo form_error('description'); ?>
                                    </span>
                                </div> 
                            </div>


                        </div><!--./row--> 
                    </div><!--./row--> 

                </div> 
                <div class="col-md-12" style="clear:both;" >
                         <div class="" id="parameterdetails" > </div>           
                </div>     
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" id="parameteraddbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right" ><?php echo $this->lang->line('save'); ?>
                        </button>
                    </div>
                </div>
            </form>     



        </div>
    </div>    
</div>
<div class="modal fade" id="viewModalReport"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip" title="<?php echo $this->lang->line('clase'); ?>" data-dismiss="modal">&times;</button>
                <div class="modalicon"> 
                    <div id='edit_deletereport'>
                        <a href="#"  data-target="#edit_prescription"  data-toggle="modal" title="" data-original-title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>

                        <a href="#" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <h4 class="box-title"><?php echo $this->lang->line('bill') . " " . $this->lang->line('details'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div id="reportdatareport"></div>
            </div>
        </div>
    </div>    
</div>

<div class="modal fade" id="viewModalbill"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip" title="<?php echo $this->lang->line('clase'); ?>" data-dismiss="modal">&times;</button>
                <div class="modalicon"> 
                    <div id='edit_deletebill'>
                        <a href="#"  data-target="#edit_prescription"  data-toggle="modal" title="" data-original-title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>

                        <a href="#" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <h4 class="box-title"><?php echo $this->lang->line('bill') . " " . $this->lang->line('details'); ?></h4> 
            </div>
            <div class="modal-body pt0 pb0">
                <div id="reportdatabill"></div>
            </div>
        </div>
    </div>    
</div>

<script type="text/javascript">
    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }

    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();
    });

    function viewDetailReport(id,radiology_id) {
        $.ajax({
            url: '<?php echo base_url() ?>admin/radio/getReportDetails/'  + id +'/'+radiology_id,
            type: "GET",
            data: {id: id},
            success: function (data) {
                $('#reportdatareport').html(data);
                $('#edit_deletereport').html("<?php if ($this->rbac->hasPrivilege('add_radio_patient_test_reprt', 'can_view')) { ?><a href='#' data-toggle='tooltip' onclick='printData(" + id + "," + radiology_id + ")'   data-original-title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a><?php } ?>");
                holdModal('viewModalReport');
            },
        });
    }

    function viewDetailbill(id,radiology_id) {
        $.ajax({
            url: '<?php echo base_url() ?>admin/radio/getBillDetails/' + id +'/'+radiology_id,
            type: "GET",
            data: {id: id},
            success: function (data) {
                $('#reportdatabill').html(data);
                $('#edit_deletebill').html("<?php if ($this->rbac->hasPrivilege('add_radio_patient_test_reprt', 'can_view')) { ?><a href='#' data-toggle='tooltip' onclick='printData(" + id + "," + radiology_id + ")'   data-original-title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a><?php } ?>");
                holdModal('viewModalbill');
            },
        });
    }

    function get_PatientDetails(id) {
        //$("#patient_name").html("patient_name");
        //$("#schedule_charge").html("schedule_charge");

        $.ajax({
            url: '<?php echo base_url(); ?>admin/pharmacy/patientDetails',
            type: "POST",
            data: {id: id},
            dataType: 'json',
            success: function (res) {
                // console.log(res);
                if (res) {
                    $('#patientname_radio').val(res.patient_name);
                    $('#patientid_radio').val(res.id);
                } else {
                    $('#patient_name').val('Null');

                }
            }
        });
    }

    function get_Docname(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/doctName',
            type: "POST",
            data: {doctor: id},
            dataType: 'json',
            success: function (res) {
                //alert(res);
                if (res) {
                    $('#doctname').val(res.name + " " + res.surname);
                    //$('#surname').val(res.surname);

                } else {

                }
            }
        });
    }


    function deleterecord(id) {
        var url = '<?php echo base_url() ?>admin/radio/deleteTestReport/' + id;
        var msg = "<?php echo $this->lang->line('delete_message') ?>";
        delete_recordById(url, msg)
    }

    function editTestReport(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/radio/getRadiologyReport',
            type: "POST",
            data: {id: id},
            dataType: 'json',
            success: function (data) {
                $("#report_id").val(data.id);
                $("#charge_category_html").val(data.charge_category);
                $("#code_html").val(data.code);
                $("#charge_html").val(data.standard_charge);
                if (data.apply_charge == "") {
                    $("#apply_charge").val(data.standard_charge);
                } else {
                    $("#apply_charge").val(data.apply_charge);
                }

                $("#customer_types").val(data.customer_type);
                $("#opdipd").val(data.opd_ipd_no);
                $("#edit_patient_name").val(data.patient_name);
                $("#edit_report_date").val(data.reporting_date);
                $('select[id="edit_consultant_doctor"] option[value="' + data.consultant_doctor + '"]').attr("selected", "selected");
                $("#edit_description").val(data.description);
                $(".select2").select2().select2('val', data.patient_id);
                //  $("#edit_consultant_doctor").select2().select2('val', data.patient_id);
                $("#viewModal").modal('hide');
                holdModal('editTestReportModal');
            },
        })
    }

     function addParametervalue(id,radiology_id) {
        $.ajax({
                    url: '<?php echo base_url(); ?>admin/radio/parameterdetails/' + radiology_id +'/'+id,
                        success: function (res) {
                            
                            $("#parameterdetails").html(res);
                            //holdModal('viewModal');
                        },
                        error: function () {
                            alert("Fail")
                        }
                    });
        $.ajax({
            url: '<?php echo base_url(); ?>admin/radio/getRadiologyReport',
            type: "POST",
            data: {id: id},
            dataType: 'json',
            success: function (data) {
                $("#preport_id").val(data.id);
                $("#pcharge_category_html").val(data.charge_category);
                $("#pcode_html").val(data.code);
                $("#pcharge_html").val(data.standard_charge);
                if (data.apply_charge == "") {
                    $("#papply_charge").val(data.standard_charge);
                } else {
                    $("#papply_charge").val(data.apply_charge);
                }

                $("#pcustomer_types").val(data.customer_type);
                $("#popdipd").val(data.opd_ipd_no);
                $("#pedit_patient_name").val(data.patient_name);
                $("#pedit_report_date").val(data.reporting_date);
                // $('select[id="pedit_consultant_doctor"] option[value="' + data.consultant_doctor + '"]').attr("selected", "selected");
                $("#pedit_description").val(data.description);
                $("#addpatientidd").select2().select2('val', data.patient_id);
                $("#pedit_consultant_doctor").select2().select2('val', data.consultant_doctor);
                //  $("#edit_consultant_doctor").select2().select2('val', data.patient_id);
                $("#viewModal").modal('hide');
                holdModal('addParametervalueModal');
            },
        })
    }

    $(document).ready(function (e) {
        $("#updatetest").on('submit', (function (e) {
            $("#updatetestbtn").button('loading');
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/radio/updateTestReport',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#updatetestbtn").button('reset');
                },
                error: function () {
                    //  alert("Fail")
                }
            });
        }));
    });

    $(document).ready(function (e) {
        $("#parameteradd").on('submit', (function (e) {
            e.preventDefault();
            $("#parameteraddbtn").button('loading');
            $.ajax({
                url: '<?php echo base_url(); ?>admin/radio/parameteraddvalue',
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#parameteraddbtn").button('reset');
                },
                error: function () {
                    //  alert("Fail")
                }
            });
        }));
    });

</script>
<script type="text/javascript">
    $(document).ready(function() {
    $('.test_ajax').DataTable({
        "processing": true,
        "serverSide": true,
        "createdRow": function( row, data, dataIndex ) {
           // $(row).children(':nth-child(7)').addClass('pull-right');
        },
        "ajax": {
            "url": base_url+"admin/searchdatatable/radiologyreport_search",
            "type": "POST"
        },
           responsive: 'true',
            dom: "Bfrtip",
         buttons: [

                {
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',
                   
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    title: $('.download_label').html(),
                    exportOptions: {
                        columns: ':visible'
                        
                    }
                },

                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    title: $('.download_label').html(),
                        customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' );
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size','inherit');
                },
                    exportOptions: {
                        columns: ':visible'
                    }
                },

                {
                    extend: 'colvis',
                    text: '<i class="fa fa-columns"></i>',
                    titleAttr: 'Columns',
                    title: $('.download_label').html(),
                    postfixButtons: ['colvisRestore']
                },
            ]
    });
});
</script>
