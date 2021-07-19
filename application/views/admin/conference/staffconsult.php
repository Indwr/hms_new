<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('live_consult'); ?></h3>
                        <div class="box-tools addmeeting">
                           <?php
if ($this->rbac->hasPrivilege('live_consultation', 'can_add')) {
    ?>
        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-online-timetable"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?> </button>
                            <?php }?>

                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {?>
                            <?php echo $this->session->flashdata('msg') ?>
                        <?php }?>
                        <div class="table-responsive">
                        <div class="download_label"><?php echo $this->lang->line('live_consult'); ?></div>
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('consult') . ' ' . $this->lang->line('title'); ?></th>
                                        <!-- <th><?php echo $this->lang->line('description'); ?></th> -->
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('api_used'); ?></th>
                                        <th><?php echo $this->lang->line('created_by'); ?> </th>
                                        <th><?php echo $this->lang->line('created_for'); ?></th>
                                        <th><?php echo $this->lang->line('patient'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
if (empty($conferences)) {
    ?>

                                        <?php
} else {
    foreach ($conferences as $conference_key => $conference_value) {
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
                                                   <?php echo $this->lang->line($conference_value->api_type); ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php
if ($conference_value->created_id == $logged_staff_id) {
            echo $this->lang->line('self');
        } else {
            $name = ($conference_value->create_by_surname == "") ? $conference_value->create_by_name : $conference_value->create_by_name . " " . $conference_value->create_by_surname;

            echo $name . " (" . $conference_value->create_by_role_name . ": " . $conference_value->create_by_employee_id . ")";
        }
        ?>
                                                </td>
                                                    <td class="mailbox-name">
                                                        <?php
$name = ($conference_value->create_for_surname == "") ? $conference_value->create_for_name : $conference_value->create_for_name . " " . $conference_value->create_for_surname;
        echo $name . " (" . $conference_value->create_for_role_name . ": " . $conference_value->create_for_employee_id . ")";
        ?>
                                                    </td>
                                                <td class="mailbox-name">
                                                     <?php

        $name = ($conference_value->patient_name == "") ? $conference_value->patient_name : $conference_value->patient_name;
        echo $name . " (" . $conference_value->patient_unique_id . ")";
        ?>

                                                </td>
                                              <td class="mailbox-name">
                                                <form class="chgstatus_form" method="POST" action="<?php echo site_url('admin/conference/chgstatus') ?>">
                                                    <input type="hidden" name="conference_id" value="<?php echo $conference_value->id; ?>">
                                                 <select class="form-control chgstatus_dropdown" name="chg_status">
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
                                                <td class="mailbox-date relative text-right" width="90">
                                                    <?php
if ($conference_value->status == 0) {
            ?>

 <a data-placement="left" href="#" class="btn btn-sm label-success start-mr-20" data-toggle="modal" data-target="#modal-chkstatus" data-id="<?php echo $conference_value->id; ?>">
                                <span class="label" ><i class="fa fa-video-camera"></i> <?php echo $this->lang->line('start') ?></span></a>
    <?php
}
        ?>

                                                    <?php
if ($conference_value->api_type != 'self') {
            ?>
                                                        <?php
if ($this->rbac->hasPrivilege('live_consultation', 'can_delete')) {
                ?>
                                                            <a data-placement="left" href="<?php echo base_url(); ?>admin/conference/delete/<?php echo $conference_value->id . "/" . $return_response->id; ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                            <?php
}
            ?>

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
                            </table><!-- /.table -->
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

<div class="modal fade" id="modal-credential" data-backdrop="static">
    <div class="modal-dialog">
        <form id="form-addcredential" action="<?php echo site_url('admin/conference/addcredential'); ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> <?php echo $this->lang->line('zoom_credential'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="zoom_api_key"><?php echo $this->lang->line('zoom_api_key') ?><small class="req"> *</small></label>
                            <input type="text" class="form-control" id="zoom_api_key" name="zoom_api_key">
                            <span class="text text-danger" id="title_error"></span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label for="zoom_api_secret"><?php echo $this->lang->line('zoom_api_secret'); ?><small class="req"> *</small></label>
                            <input type="text" class="form-control" id="zoom_api_secret" name="zoom_api_secret">
                            <span class="text text-danger" id="title_error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" value="reset" id="submit-btn-credential" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Updating..."><?php echo $this->lang->line('reset') ?></button>
                    <button type="submit" class="btn btn-primary" value="save" id="submit-btn-credential" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><?php echo $this->lang->line('save') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-online-timetable"  role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pt4" data-dismiss="modal">&times;</button>
                <div class="row">
                    <div class="col-sm-6 col-xs-6">
                        <div class="form-group15">
                            <div>
                                <select onchange="get_PatientDetails(this.value)"  class="form-control select2" <?php
if ($disable_option == true) {

}
?> style="width:100%" name='' id="addpatient_id" >
                                    <option value=""><?php echo $this->lang->line('select') . " " . $this->lang->line('patient') ?></option>
                                    <?php foreach ($patients as $dkey => $dvalue) {
    ?>
                                        <option value="<?php echo $dvalue["id"]; ?>" <?php
if ((isset($patient_select)) && ($patient_select == $dvalue["id"])) {
        echo "selected";
    }
    ?>><?php echo $dvalue["patient_name"] . " (" . $dvalue["patient_unique_id"] . ')' ?></option>
                                <?php }?>
                                </select>
                            </div>
                            <span class="text-danger"><?php echo form_error('refference'); ?></span>
                        </div>
                    </div><!--./col-sm-8-->
                    <div class="col-sm-4 col-xs-5">
                        <div class="form-group15">
                            <?php if ($this->rbac->hasPrivilege('patient', 'can_add')) {?>
                                <a data-toggle="modal" id="add" onclick="holdModal('myModalpa')" class="modalbtnpatient"><i class="fa fa-plus"></i>  <span><?php echo $this->lang->line('new') . " " . $this->lang->line('patient') ?></span></a>
                            <?php }?>
                        </div>
                    </div><!--./col-sm-4-->
                </div><!-- ./row -->
            </div><!--./modal-header-->
            <div class="modal-body pt0 pb0">
                <div class="">
                    <form id="form-addconference" accept-charset="utf-8" action="<?php echo base_url() . "admin/conference/addByOther" ?>" enctype="multipart/form-data" method="post">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                            <input name="patient_id" id="patient_id" type="hidden" class="form-control" />
                            <input name="email" id="pemail" type="hidden" class="form-control" />
                            <input name="mobileno" id="mobnumber" type="hidden" class="form-control" />
                            <input name="patient_name" id="patientname" type="hidden" class="form-control" />
                            <input type="hidden" class="form-control" id="password" name="password">
                            <div class="row row-eq">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div id="ajax_load"></div>
                                    <div class="row ptt10" id="patientDetails" style="display:none">
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                            <ul class="singlelist">
                                                <li class="singlelist24bold">
                                                    <span id="listname"></span></li>
                                                <li>
                                                    <i class="fas fa-user-secret" data-toggle="tooltip" data-placement="top" title="Guardian"></i>
                                                    <span id="guardian"></span>
                                                </li>
                                            </ul>
                                            <ul class="multilinelist">
                                                <li>
                                                    <i class="fas fa-venus-mars" data-toggle="tooltip" data-placement="top" title="Gender"></i>
                                                    <span id="genders" ></span>
                                                </li>
                                                <li>
                                                    <i class="fas fa-tint" data-toggle="tooltip" data-placement="top" title="Blood Group"></i>
                                                    <span id="blood_group"></span>
                                                </li>
                                                <li>
                                                    <i class="fas fa-ring" data-toggle="tooltip" data-placement="top" title="Marital Status"></i>
                                                    <span id="marital_status"></span>
                                                </li>
                                            </ul>
                                            <ul class="singlelist">
                                                <li>
                                                    <i class="fas fa-hourglass-half" data-toggle="tooltip" data-placement="top" title="Age"></i>
                                                    <span id="age"></span>
                                                </li>                                                 <li>
                                                    <i class="fa fa-phone-square" data-toggle="tooltip" data-placement="top" title="Phone"></i>
                                                    <span id="listnumber"></span>
                                                </li>
                                                <li>
                                                    <i class="fa fa-envelope" data-toggle="tooltip" data-placement="top" title="Email"></i>
                                                    <span id="email"></span>
                                                </li>
                                                <li>
                                                    <i class="fas fa-street-view" data-toggle="tooltip" data-placement="top" title="Address"></i>
                                                    <span id="address" ></span>
                                                </li>
                                                <li>
                                                    <b><?php echo $this->lang->line('any_known_allergies') ?> </b>
                                                    <span id="allergies" ></span>
                                                </li>
                                                <li>
                                                    <b><?php echo $this->lang->line('remarks') ?> </b>
                                                    <span id="note"></span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                            <div class="pull-right">
                                                <?php
$file = "uploads/patient_images/no_image.png";
?>
                                                <img class="modal-profile-user-img img-responsive" src="<?php echo base_url() . $file ?>" id="image" alt="User profile picture">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-eq ptt10">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                <?php echo $this->lang->line('consult') . " " . $this->lang->line('title'); ?></label>
                                                <div><input class="form-control" type='text' name='title' />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('title'); ?></span>
                                            </div>
                                        </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('consult') . " " . $this->lang->line('date'); ?></label><small class="req"> *</small>
                                            <input id="date" name="date" value='<?php echo set_value('date', date($this->customlib->getSchoolDateFormat(true, true))); ?>' placeholder="" type="text" class="form-control datetime"   />
                                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                                        </div>
                                    </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="duration"><?php echo $this->lang->line('consult_duration_minutes'); ?><small class="req"> *</small></label>
                                                <input type="number" class="form-control" id="duration" name="duration">
                                                <span class="text-danger"><?php echo form_error('duration'); ?></span>
                                            </div>
                                        </div>
                                         <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                <?php echo $this->lang->line('opd') . "/" . $this->lang->line('ipd'); ?></label>
                                                <div>
                                                    <select name='select_group' id="" onchange="getopdipd(this.value)" class="form-control"  style="width:100%" >
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <option value="<?php echo $this->lang->line('opd') ?>" ><?php echo $this->lang->line('opd') ?></option>
                                                        <option value="<?php echo $this->lang->line('ipd') ?>" ><?php echo $this->lang->line('ipd') ?></option>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('case'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                        <?php echo $this->lang->line('opd') . " / " . $this->lang->line('ipd') . " " . $this->lang->line('number'); ?></label><small class="req"> *</small>
                                                <div>
                                                <select class="form-control select2" style="width: 100%" name='opdipd_id' id='opdipd_no'>
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>  </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('opdipd_no'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('consultant') . " " . $this->lang->line('doctor'); ?></label><small class="req"> *</small>
                                                <div><select name='staff_id' id="consultant_doctor" onchange="" class="form-control select2" <?php
if ($disable_option == true) {
    echo "disabled";
}
?> style="width:100%"  >
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($doctors as $dkey => $dvalue) {
    ?>
                                                            <option value="<?php echo $dvalue["id"]; ?>" <?php
if ((isset($doctor_select)) && ($doctor_select == $dvalue["id"])) {
        echo "selected";
    }
    ?>><?php echo $dvalue["name"] . " " . $dvalue["surname"] ?></option>
                                                    <?php }?>
                                                    </select>
                                                    <?php if ($disable_option == true) {?>
                                                        <input type="hidden" name="staff_id" value="<?php echo $doctor_select ?>">
                                                    <?php }?>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('staff_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group">
                                            <label for="class" class="displayblock"><?php echo $this->lang->line('host_video'); ?><small class="req"> *</small></label><br>
                                            <label class="radio-inline"><input type="radio" name="host_video"  value="1" checked><?php echo $this->lang->line('enable'); ?></label>
                                            <label class="radio-inline"><input type="radio" name="host_video" value="0" ><?php echo $this->lang->line('disabled'); ?> </label>
                                             <span class="text-danger"><?php echo form_error('host_video'); ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group">
                                            <label for="class" class="displayblock"><?php echo $this->lang->line('client_video'); ?><small class="req"> *</small></label><br>
                                            <label class="radio-inline"><input type="radio" name="client_video"  value="1" checked><?php echo $this->lang->line('enable'); ?></label>
                                            <label class="radio-inline"><input type="radio" name="client_video" value="0" ><?php echo $this->lang->line('disabled'); ?></label>
                                            <span class="text-danger"><?php echo form_error('client_video'); ?></span>
                                             </div>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                            <label for="description"><?php echo $this->lang->line('description') ?></label>
                                            <textarea class="form-control" name="description" id="description"></textarea>
                                        </div>
                                        </div>
                                        </div>
                                    </div><!--./row-->
                                </div><!--./col-md-4-->
                            </div><!--./row-->
                            <div class="row">
                                <div class="box-footer">
                                    <div class="pull-right mrminus8">
                                        <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><?php echo $this->lang->line('save') ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!--./row-->
            </div>
        </div>
    </div>
<!-- Modal -->
<script type="text/javascript">
      $(function () {
        $('#easySelectable').easySelectable();
        $('.select2').select2()
    })

    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }

    $('#myModalpa').on('hidden.bs.modal', function (e) {
        $(this).find('#formaddpa')[0].reset();
    });

    function getopdipd(opdipd_group) {
        var pid = $('#patient_id').val();
        var div_data = "";
        $('#opdipd_no').html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
        $("#opdipd_no").select2("val", '1');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/conference/getopdipd',
            type: "POST",
            data: {opdipd_group: opdipd_group,patient_id: pid},
            dataType: 'json',
            success: function (res) {
                $.each(res, function (i, obj)
                {
                    var sel = "";
                    div_data += "<option value=" + obj.id + ">" + obj.opdipd_no + "</option>";
                });
                $("#opdipd_no").html("<option value=''>Select</option>");
                $('#opdipd_no').append(div_data);
                $("#opdipd_no").select2().select2('val', '');
            }
        });
    }

    $('#modal-credential').on('shown.bs.modal', function (e) {
            var $modalDiv = $(e.delegateTarget);
            $.ajax({
                type: "POST",
                url: base_url + 'admin/conference/getcredential',
                data: {},
                dataType: "JSON",
                beforeSend: function () {
                    $modalDiv.addClass('modal_loading');
                },
                success: function (data) {
                    $('#zoom_api_key').val(data.zoom_api_key);
                    $('#zoom_api_secret').val(data.zoom_api_secret);
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

      $("form#form-addcredential").submit(function (event) {
            event.preventDefault();
            var $form = $(this),
                    url = $form.attr('action');
            var $button = $form.find("button[type=submit]:focus");
            var formData = $form.serializeArray();
            formData.push({name: 'button', value: $button.val()});
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                dataType: "JSON",
                beforeSend: function () {
                    $button.button('loading');
                },
                success: function (data) {
                    if (data.status == 0) {
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        $('#modal-credential').modal('hide');
                        successMsg(data.message);
                    }
                    $button.button('reset');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $button.button('reset');
                },
                complete: function (data) {
                    $button.button('reset');
                }
            });
        })

        $('#modal-chkstatus').on('shown.bs.modal', function (e) {
            var $modalDiv = $(e.delegateTarget);
            var id=$(e.relatedTarget).data('id');

            $.ajax({
                type: "POST",
                url: base_url + 'admin/conference/getlivestatus',
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
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });

    $('#modal-online-timetable').on('shown.bs.modal', function (e) {
        $("#patient_id").prop("selectedIndex", 0);
        var password = makeid(5);
        $('#password').val("").val(password);
    })

    //===========================form submit==========
    $("form#form-addconference").submit(function (event) {
        event.preventDefault();
        var $form = $(this),
            url = $form.attr('action');
        var $button = $form.find("button[type=submit]:focus");
        $.ajax({
            type: "POST",
            url: url,
            data: $form.serialize(),
            dataType: "JSON",
            beforeSend: function () {
                $button.button('loading');
            },
            success: function (data) {
                if (data.status == 0) {
                    var message = "";
                    $.each(data.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('#modal-online-timetable').modal('hide');
                    successMsg(data.message);
                    window.location.reload(true);
                }
                $button.button('reset');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $button.button('reset');
            },
            complete: function (data) {
                $button.button('reset');
            }
        });
    })
    //================================================
    function makeid(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }
    $('#modal-online-timetable').on('hidden.bs.modal', function () {

       $(this).find("input,textarea,select").not("input[type=radio]")
                    .val('')
                    .end();
                    $(this).find("input[type=checkbox], input[type=radio]")
                    .prop('checked', false);
                    $('input:radio[name="host_video"][value="1"]').prop('checked', true);
                    $('input:radio[name="client_video"][value="1"]').prop('checked', true);
    });

    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).on('change', '#role_id', function (e) {
        $('#staff_id').html("");
        var role_id = $(this).val();
        getEmployeeName(role_id)
    });

    function getEmployeeName(role) {
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "POST",
            url: base_url + "admin/staff/getEmployeeByRole",
            data: {'role': role},
            dataType: "JSON",
            beforeSend: function () {
                $('#staff_id').html("");
                $('#staff_id').addClass('dropdownloading');
            },
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<option value='" + obj.id + "'>" + obj.name + " " + obj.surname + "</option>";
                });
                $('#staff_id').append(div_data);
            },
            complete: function () {
                $('#staff_id').removeClass('dropdownloading');
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).on('change','.chgstatus_dropdown',function(){
        $(this).parent('form.chgstatus_form').submit()
    });

    $("form.chgstatus_form").submit(function(e) {
        
    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');
    $.ajax({
           type: "POST",
           url: url,
           data: form.serialize(), // serializes the form's elements.
           dataType:"JSON",
           success: function(data)
           {
               if (data.status == 0) {
                    var message = "";
                    $.each(data.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }
           }
        });
});
</script>
<script type="text/javascript">
    (function ($) {
        //selectable html elements
        $.fn.easySelectable = function (options) {
            var el = $(this);
            var options = $.extend({
                'item': 'li',
                'state': true,
                onSelecting: function (el) {

                },
                onSelected: function (el) {

                },
                onUnSelected: function (el) {

                }
            }, options);
            el.on('dragstart', function (event) {
                event.preventDefault();
            });
            el.off('mouseover');
            el.addClass('easySelectable');
            if (options.state) {
                el.find(options.item).addClass('es-selectable');
                el.on('mousedown', options.item, function (e) {
                    $(this).trigger('start_select');
                    var offset = $(this).offset();
                    var hasClass = $(this).hasClass('es-selected');
                    var prev_el = false;
                    el.on('mouseover', options.item, function (e) {
                        if (prev_el == $(this).index())
                            return true;
                        prev_el = $(this).index();
                        var hasClass2 = $(this).hasClass('es-selected');
                        if (!hasClass2) {
                            $(this).addClass('es-selected').trigger('selected');
                            el.trigger('selected');
                            options.onSelecting($(this));
                            options.onSelected($(this));
                        } else {
                            $(this).removeClass('es-selected').trigger('unselected');
                            el.trigger('unselected');
                            options.onSelecting($(this))
                            options.onUnSelected($(this));
                        }
                    });
                    if (!hasClass) {
                        $(this).addClass('es-selected').trigger('selected');
                        el.trigger('selected');
                        options.onSelecting($(this));
                        options.onSelected($(this));
                    } else {
                        $(this).removeClass('es-selected').trigger('unselected');
                        el.trigger('unselected');
                        options.onSelecting($(this));
                        options.onUnSelected($(this));
                    }
                    var relativeX = (e.pageX - offset.left);
                    var relativeY = (e.pageY - offset.top);
                });
                $(document).on('mouseup', function () {
                    el.off('mouseover');
                });
            } else {
                el.off('mousedown');
            }
        };
    })(jQuery);

</script>
<script type="text/javascript">
    function get_PatientDetails(id) {
        var base_url = "<?php echo base_url(); ?>backend/images/loading.gif";
        $("#ajax_load").html("<center><img src='" + base_url + "'/>");
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/patientDetails',
            type: "POST",
            data: {id: id},
            dataType: 'json',
            success: function (res) {
                if (res) {
                    $("#ajax_load").html("");
                    $("#patientDetails").show();
                    $('#patient_unique_id').html(res.patient_unique_id);
                    $('#patient_id').val(res.id);
                    $('#listname').html(res.patient_name);
                    $('#guardian').html(res.guardian_name);
                    $('#listnumber').html(res.mobileno);
                    $('#email').html(res.email);
                    $('#mobnumber').val(res.mobileno);
                    $('#pemail').val(res.email);
                    $('#patientname').val(res.patient_name);
                    if (res.age == "") {
                        $("#age").html("");
                    } else {
                        if (res.age) {
                            var age = res.age + " " + "Years";
                        } else {
                            var age = '';
                        }
                        if (res.month) {
                            var month = res.month + " " + "Month";
                        } else {
                            var month = '';
                        }
                        if (res.dob) {
                            var dob = "(" + res.dob + ")";
                        } else {
                            var dob = '';
                        }

                        $("#age").html(age + "," + month + " " + dob);
                    }

                    $('#doctname').val(res.name + " " + res.surname);
                    $("#bp").html(res.bp);
                    $("#symptoms").html(res.symptoms);
                    $("#known_allergies").html(res.known_allergies);
                    $("#address").html(res.address);
                    $("#note").html(res.note);
                    $("#height").html(res.height);
                    $("#weight").html(res.weight);
                    $("#genders").html(res.gender);
                    $("#marital_status").html(res.marital_status);
                    $("#blood_group").html(res.blood_group);
                    $("#allergies").html(res.known_allergies);
                    $("#image").attr("src", '<?php echo base_url() ?>' + res.image);
                } else {
                    $("#ajax_load").html("");
                    $("#patientDetails").hide();
                }
            }
        });
    }

    $(document).ready(function() {
    $('.test_ajax').DataTable({
        "processing": true,
        "serverSide": true,
         "createdRow": function( row, data, dataIndex ) {

        },
        "ajax": {
            "url": base_url+"admin/conference/liveconsult_search",
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
<?php $this->load->view('admin/patient/patientaddmodal')?>