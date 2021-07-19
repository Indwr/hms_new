<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <?php
$image = $result['image'];
if (!empty($image)) {

    $file = $result['image'];
} else {

    $file = "uploads/patient_images/no_image.png";
}
?>
                        <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url() . $file ?>" alt="User profile picture">
                        <h3 class="profile-username text-center"><?php echo $result['patient_name']; ?></h3>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('patient') . " " . $this->lang->line('id') ?></b> <a class="pull-right text-aqua"><?php echo $result['patient_unique_id']; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('gender'); ?></b> <a class="pull-right text-aqua"><?php echo $result['gender']; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('marital_status'); ?></b> <a class="pull-right text-aqua"><?php echo $result['marital_status']; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('phone'); ?></b> <a class="pull-right text-aqua"><?php echo $result['mobileno']; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('email'); ?></b> <a class="pull-right text-aqua"><?php echo $result['email']; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('address'); ?></b> <a class="pull-right text-aqua"><?php echo $result['address']; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('age') ?></b> <a class="pull-right text-aqua"><?php
if (!empty($result['age'])) {
    echo $result['age'] . " " . $this->lang->line('year') . " ";
}if (!empty($result['month'])) {
    echo $result['month'] . " " . $this->lang->line('month');
}
?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('guardian_name'); ?></b> <a class="pull-right text-aqua"><?php echo $result['guardian_name']; ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true"><i class="far fa-caret-square-down"></i> <?php echo $this->lang->line('visits'); ?></a></li>
                        <li><a href="#diagnosis" data-toggle="tab" aria-expanded="true"><i class="fas fa-diagnoses"></i> <?php echo $this->lang->line('diagnosis'); ?></a></li>
                        <li><a href="#timeline" data-toggle="tab" aria-expanded="true"><i class="far fa-calendar-check"></i> <?php echo $this->lang->line('timeline'); ?></a></li>
                        
                        <?php if  ($this->module_lib->hasActive('zoom_live_meeting')) { ?>
                            <li><a href="#live_consult" data-toggle="tab" aria-expanded="true"><i class="fa fa-video-camera ftlayer"></i> <?php echo $this->lang->line('live_consult'); ?></a></li>
                        <?php  } ?>
                        
                    </ul>
                    <div class="impbtnview">
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                    <thead>
                                    <th><?php echo $this->lang->line('opd_no'); ?></th>
                                    <th><?php echo $this->lang->line('appointment') . " " . $this->lang->line('date'); ?></th>
                                    <th><?php echo $this->lang->line('consultant'); ?></th>
                                    <th><?php echo $this->lang->line('refference'); ?></th>
                                    <th><?php echo $this->lang->line('symptoms'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('action') ?></th>
                                    </thead>
                                    <tbody>
                                        <?php

function chkexist($prescription_detail, $opdid)
{
    foreach ($prescription_detail as $key => $value) {
        if (!empty($value["opd_id"])) {
            return true;

        } else {
            return false;
        }
    }
}

if (!empty($opd_details)) {
    foreach ($opd_details as $key => $value) {
        ?>
                                                <tr>
                                                    <td><a href="<?php echo base_url() . "patient/dashboard/visitDetails/" . $result["id"] . "/" . $value["id"] ?>"><?php echo $value['opd_no']; ?></a>
                                                    </td>
                                                    <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($value['appointment_date'])); ?></td>
                                                    <td><?php echo $value["name"] . " " . $value["surname"];
        ?></td>
                                                    <td><?php echo $value['refference']; ?></td>
                                                    <td><?php echo nl2br($value['symptoms']); ?></td>
                                                    <td class="pull-right">
                                                        <?php if ($value["prescription"] == 'yes') {
            ?>
                                                            <?php if (chkexist($prescription_detail, $value["id"])) {?>
                                                                <span data-toggle="modal" data-target="#prescriptionview">
                                                                    <a href="#" class="btn btn-default btn-xs" data-toggle='tooltip' onclick="view_prescription('<?php echo $value["id"] ?>', '<?php echo $value["id"] ?>')" title="<?php echo $this->lang->line('view') . " " . $this->lang->line('prescription'); ?>">
                                                                        <i class="fas fa-file-prescription"></i>
                                                                    </a></span>
                                                            <?php }
        }
        ?>
                                                        <a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('show'); ?>" onclick="getRecord('<?php echo $result["id"]; ?>', '<?php echo $value["id"]; ?>')" >
                                                            <i class="fa fa-reorder"></i>
                                                        </a>

                                                    </td>
                                                </tr>
                                                <?php
}
}
?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Diagnosis -->
                        <div class="tab-pane" id="diagnosis">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                    <th><?php echo $this->lang->line('report') . " " . $this->lang->line('type'); ?></th>
                                    <th><?php echo $this->lang->line('report') . " " . $this->lang->line('date'); ?></th>
                                    <th><?php echo $this->lang->line('description'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('download'); ?></th>
                                    </thead>
                                    <tbody>
                                        <?php
if (!empty($diagnosis_detail)) {
    foreach ($diagnosis_detail as $diagnosis_key => $diagnosis_value) {
        ?>
                                                <tr>
                                                    <td><?php echo $diagnosis_value["report_type"] ?></td>
                                                    <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($diagnosis_value['report_date'])) ?></td>
                                                    <td><?php echo $diagnosis_value["description"] ?></td>
                                                    <td class="pull-right">
                                                        <?php if (!empty($diagnosis_value["document"])) {?>
                                                            <a href="<?php echo base_url() . "patient/dashboard/report_download/" . $diagnosis_value["document"] ?>" ><i class="fa fa-download"></i></a>
        <?php }?>
                                                    </td>
                                                </tr>
                                                <?php
}
}
?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Timeline -->
                        <div class="tab-pane" id="timeline">
                            <div class="timeline-header no-border">
                                <div id="timeline_list">
                                    <?php
if (empty($timeline_list)) {
    ?>
                                        <br/>
                                    <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                    <?php } else {
    ?>
                                        <ul class="timeline timeline-inverse">
                                            <?php
foreach ($timeline_list as $key => $value) {
        ?>
                                                <li class="time-label">
                                                    <span class="bg-blue">    <?php
echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['timeline_date']));
        ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa fa-list-alt bg-blue"></i>
                                                    <div class="timeline-item">

                                                        <?php if (!empty($value["document"])) {?>
                                                            <span class="time"><a class="defaults-c text-right" data-toggle="tooltip" title="" href="<?php echo base_url() . "patient/dashboard/download_patient_timeline/" . $value["id"] . "/" . $value["document"] ?>" data-original-title="Download"><i class="fa fa-download"></i></a></span>
        <?php }?>
                                                        <h3 class="timeline-header text-aqua"> <?php echo $value['title']; ?> </h3>
                                                        <div class="timeline-body">
        <?php echo $value['description']; ?>

                                                        </div>

                                                    </div>
                                                </li>
                                            <?php }?>
                                            <li><i class="fa fa-clock-o bg-gray"></i></li>
<?php }?>

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- -->


                          <div class="tab-pane" id="live_consult">

                            <div class="download_label"><?php echo $result['patient_name'] . " " . $this->lang->line('opd') . " " . $this->lang->line('details'); ?></div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                    <th><?php echo $this->lang->line('consult') . ' ' . $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('created_by'); ?> </th>
                                        <th><?php echo $this->lang->line('created_for'); ?></th>
                                        <th><?php echo $this->lang->line('patient'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </thead>
                                    <tbody>
                                        <?php
if (empty($opdconferences)) {
    ?>

                                        <?php
} else {
    foreach ($opdconferences as $conference_key => $conference_value) {

        $return_response = json_decode($conference_value->return_response);
        ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $conference_value->title; ?></a>

                                                    <div class="fee_detail_popover" style="display: none">
                                                        <?php
if ($conference_value->description == "") {
            ?>
                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                            <?php
} else {
            ?>
                                                            <p class="text text-info"><?php echo $conference_value->description; ?></p>
                                                            <?php
}
        ?>
                                                    </div>
                                                </td>

                                                <td class="mailbox-name">
                                                <?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($conference_value->date)) ?>

                                                    </td>
                                                 <td class="mailbox-name">

                                                    <?php

        $name = ($conference_value->create_by_surname == "") ? $conference_value->create_by_name : $conference_value->create_by_name . " " . $conference_value->create_by_surname;

        if ($name == 'Super Admin') {
            echo $name;
            # code...
        } else {
            echo $name . " (" . $conference_value->create_by_role_name . ": " . $conference_value->create_by_employee_id . ")";
        }

        ?></td>
                                                <td class="mailbox-name">
                                                    <?php

        $name = ($conference_value->create_for_surname == "") ? $conference_value->create_for_name : $conference_value->create_for_name . " " . $conference_value->create_for_surname;
        echo $name . " (" . $conference_value->create_for_role_name . ": " . $conference_value->create_for_employee_id . ")";

        ?>
                                                </td>
                                                <td class="mailbox-name">
                                                     <?php

        $name = ($conference_value->patient_name == "") ? $conference_value->patient_name : $conference_value->patient_name;
        echo $name . " (" . $conference_value->patient_unique_id . ")";?>

                                                </td>
                                              <td class="mailbox-name">
                                                <form class="chgstatus_form"  method="POST" action="<?php echo site_url('admin/conference/chgstatus') ?>">
                                                    <input type="hidden" name="conference_id"  value="<?php echo $conference_value->id; ?>">
                                                 <select class="form-control chgstatus_dropdown" disabled name="chg_status">
                                                     <option value="0" <?php if ($conference_value->status == 0) {
            echo "selected='selected'";
        }
        ?>><?php echo $this->lang->line('awaited'); ?></option>
                                                     <option value="1" <?php if ($conference_value->status == 1) {
            echo "selected='selected'";
        }
        ?>><?php echo $this->lang->line('cancelled'); ?> </option>
                                                     <option value="2" <?php if ($conference_value->status == 2) {
            echo "selected='selected'";
        }
        ?>><?php echo $this->lang->line('finished'); ?> </option>
                                                 </select>
                                                </form>
                                                </td>
                                                <td class="mailbox-date pull-right">
                                                    <?php
if ($conference_value->status == 0) {
            ?>
                                        <a data-placement="left" href="#" class="btn btn-xs label-success p0" data-toggle="modal" data-target="#modal-chkstatus" data-id="<?php echo $conference_value->id; ?>">
                                                      <span class="label" ><i class="fa fa-video-camera"></i> <?php echo $this->lang->line('join') ?></span>
                                            <?php
}
        ?>



                                                </td>
                                            </tr>
                                            <?php
}
}
?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="modal-chkstatus"  class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
    <form id="form-chkstatus" action="" method="POST">
        <div class="modal-content">
            <div class="">
                <button type="button" class="close modalclosezoom" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body" id="zoom_details">

            </div>
        </div>
    </form>
    </div>
</div>

<div class="modal fade" id="prescriptionview" tabindex="-1" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='edit_deleteprescription'>
                        <a href="#" id='print_id' data-toggle="modal" ><i class="fa fa-print"></i></a>
                    </div>
                </div>
                <h4 class="box-title"><?php echo $this->lang->line('prescription'); ?></h4>
            </div>
            <div class="modal-body pt0 pb0" id="getdetails_prescription">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('my_details'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <form id="view" accept-charset="utf-8" method="get">
                            <div class="table-responsive">
                                <table class="table mb0 table-striped table-bordered examples">
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></th>
                                        <td width="35%"><span id='patient_id'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('name'); ?></th>
                                        <td width="35%"><span id="patient_name"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('gender'); ?></th>
                                        <td width="35%"><span id='gender'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('age'); ?></th>
                                        <td width="35%"><span id="age"></span><span id="month"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('guardian_name') ?></th>
                                        <td width="35%"><span id='guardian_name'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('contact'); ?></th>
                                        <td width="35%"><span id="contact"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('email'); ?></th>
                                        <td width="35%"><span id='email'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('appointment') . " " . $this->lang->line('date'); ?></th>
                                        <td width="35%"><span id="appointment_date"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('symptoms'); ?></th>
                                        <td width="35%"><span id='symptoms'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('any_known_allergies'); ?></th>
                                        <td width="35%"><span id="known_allergies"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('case'); ?></th>
                                        <td width="35%"><span id='case'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('casualty'); ?></th>
                                        <td width="35%"><span id="casualty"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('consultant') . " " . $this->lang->line('doctor'); ?></th>
                                        <td width="35%"><span id='cons_doctor'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('refference'); ?></th>
                                        <td width="35%"><span id="refference"></span></td>
                                    </tr>
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('amount') . ' (' . $currency_symbol . ')'; ?></th>
                                        <td width="35%"><span id='amount'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('payment') . " " . $this->lang->line('mode'); ?></th>
                                        <td width="35%"><span id="payment_mode"></span></td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div><!--./col-md-12-->
                </div><!--./row-->
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    function getRecord(id, opdid) {
        var active = "<?php echo $result["is_active"] ?>";
        $.ajax({
            url: '<?php echo base_url(); ?>patient/dashboard/getDetails',
            type: "POST",
            data: {patient_id: id, opd_id: opdid, active: active},
            dataType: 'json',
            success: function (data) {
                $("#patient_id").html(data.patient_unique_id);
                $("#patient_name").html(data.patient_name);
                $("#gender").html(data.gender);
                $("#casualty").html(data.casualty);
                $("#contact").html(data.mobileno);
                $("#email").html(data.email);
                var age = '';
                var month = '';
                if (data.age != '') {
                    age = data.age + ' Years ';
                }

                if (data.month != '') {
                    month = data.month + ' Month ';
                }
                var doc = data.name + ' '+data.surname ;
                $("#age").html(age);
                $("#month").html(month);
                $("#guardian_name").html(data.guardian_name);
                $("#appointment_date").html(data.appointment_date);
                $("#case").html(data.case_type);
                $("#symptoms").html(data.symptoms);
                $("#known_allergies").html(data.known_allergies);
                $("#refference").html(data.refference);
                $("#cons_doctor").html(doc);
                $("#amount").html(data.amount);
                $("#tax").html(data.tax);
                $("#payment_mode").html(data.payment_mode);
                $("#opdid").val(data.opdid);
                $("#address").val(data.address);
                $("#note").val(data.note);
                $("#updateid").val(id);
                holdModal('viewModal');
            },
        });
    }
    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }
    function view_prescription(id, opdid) {
        $.ajax({
            url: '<?php echo base_url(); ?>patient/prescription/getPrescription/' + id + '/' + opdid,
            success: function (res) {
                $("#edit_deleteprescription").html("<a href='#' onclick='print(" + id + "," + opdid + ")' id='print_id' data-toggle='modal' ><i class='fa fa-print'></i></a>");
                $("#getdetails_prescription").html(res);

                holdModal('prescriptionview');
            },
            error: function () {
                alert("Fail")
            }
        });
    }

     $('#modal-chkstatus').on('shown.bs.modal', function (e) {
            var $modalDiv = $(e.delegateTarget);

              var id=$(e.relatedTarget).data('id');


            $.ajax({
                type: "POST",
                url: '<?php echo site_url("patient/dashboard/getlivestatus") ?>',
                data: {'id':id},
                dataType: "JSON",
                beforeSend: function () {
                $('#zoom_details').html("");
                    $modalDiv.addClass('modal_loading');
                },
                success: function (data) {
                   $('#zoom_details').html(data.page);
                    $modalDiv.removeClass('modal_loading');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $modalDiv.removeClass('modal_loading');
                },
                complete: function (data) {
                    $modalDiv.removeClass('modal_loading');
                }
            });
        })

    function popup(data) {
        var base_url = '<?php echo base_url() ?>';
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
        return true;
    }

    $('#print_id').show();
    function print(id, opdid) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'patient/prescription/getPrescription/' + id + '/' + opdid,
            type: 'POST',
            data: {payslipid: id},
            success: function (result) {
                $("#testdata").html(result);
                popup(result);
            }
        });
    }
</script>