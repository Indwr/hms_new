<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> Timetable --r</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Teacher Time Table</h3>
                        <div class="box-tools pull-right"></div>
                    </div>

                    <div class="box-body">
                        <?php
if (!empty($timetable)) {
    ?>
                            <table class="table table-stripped">
                                <thead>
                                    <tr>
                                        <?php
foreach ($timetable as $tm_key => $tm_value) {
        ?>
                                            <th class="text text-center"><?php echo $tm_key; ?></th>
                                            <?php
}
    ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
foreach ($timetable as $tm_key => $tm_value) {
        ?>
                                            <td class="text text-center">

                                                <?php
if (!$timetable[$tm_key]) {
            ?>
                                                    <div class="attachment-block clearfix">
                                                        <b class="text text-center"><?php echo $this->lang->line('not'); ?> <br><?php echo $this->lang->line('scheduled'); ?></b><br>
                                                    </div>
                                                    <?php
} else {

            foreach ($timetable[$tm_key] as $tm_k => $tm_kue) {
                ?>
                                                        <div class="attachment-block clearfix online-timetable" data-subject="<?php echo $tm_kue->subject_name . " (" . $tm_kue->subject_code . ")"; ?>" data-class="<?php echo $tm_kue->class . "(" . $tm_kue->section . ")"; ?>" data-class-id="<?php echo $tm_kue->class_id; ?>" data-section-id="<?php echo $tm_kue->section_id; ?>" data-time-from="<?php echo $tm_kue->time_from; ?>">
                                                            <b class="text-green"><?php echo $this->lang->line('subject') ?>: <?php echo $tm_kue->subject_name . " (" . $tm_kue->subject_code . ")"; ?>

                                                            </b>
                                                            <br>

                                                            <strong class="text-green"><?php echo $this->lang->line('class') ?>: <?php echo $tm_kue->class . "(" . $tm_kue->section . ")"; ?></strong><br>
                                                            <strong class="text-green"><?php echo $tm_kue->time_from ?></strong>
                                                            <b class="text text-center">-</b>
                                                            <strong class="text-green"><?php echo $tm_kue->time_to; ?></strong><br>

                                                            <strong class="text-green"><?php echo $this->lang->line('room_no'); ?>: <?php echo $tm_kue->room_no; ?></strong><br>

                                                        </div>

                                                        <?php
}
        }
        ?>

                                            </td>
                                            <?php
}
    ?>
                                    </tr>

                                </tbody>
                            </table>

                            <?php
} else {
    ?>
                            <div class="alert alert-info">
                                <?php echo $this->lang->line('no_record_found'); ?>
                            </div>
                            <?php
}
?>



                    </div>


                </div>
                 <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Live Class --r</h3>
                        <div class="box-tools pull-right">
                         </div>
                    </div>

                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {?>
                            <?php echo $this->session->flashdata('msg') ?>
                        <?php }?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('date'); ?>
                                        </th>
                                        <th>Api Used --r
                                        </th>
                                        <th>Created By --r
                                        </th>

                                        <th><?php echo $this->lang->line('class'); ?>
                                        </th>

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

                                                    <?php echo $this->customlib->dateyyyymmddToDateTimeformat($conference_value->date); ?></td>
                                                <td class="mailbox-name">

                                                    <?php echo $conference_value->api_type;
        ?>

                                                </td>

                                                <td class="mailbox-name">

                                                    <?php
if ($conference_value->created_id == $logged_staff_id) {
            echo "Self";
        } else {
            echo $conference_value->create_by_name . " " . $conference_value->create_by_surname;
        }
        ?></td>



                                                <td class="mailbox-name">
                                                    <?php echo $conference_value->class . " (" . $conference_value->section . ")";
        ?>

                                                </td>

                                                <td class="mailbox-date pull-right">
                                                    <a data-placement="left" href="<?php echo $return_response->start_url; ?>" class="btn btn-default btn-xs"  target="_blank" >
                                                        <i class="fa fa-sign-in"> Start --r</i>
                                                    </a>
                                                    <?php
if ($conference_value->created_id == $logged_staff_id) {
            ?>
                                                        <a data-placement="left" href="<?php echo base_url(); ?>admin/conference/delete/<?php echo $conference_value->id . "/" . $return_response->id; ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
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


<!-- Modal -->
<div class="modal fade" id="modal-online-timetable">
    <div class="modal-dialog">
        <form id="form-addconference" action="<?php echo site_url('admin/conference/add'); ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Online Class --r</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="class_id" id="class_id" value="0">
                    <input type="hidden" name="section_id" id="section_id" value="0">
                    <input type="hidden" class="form-control" id="password" name="password">
                    <div class="row">
                        <div class="form-group col-xs-10 col-sm-12 col-md-12 col-lg-12">
                            <label for="title">Metting Title --r<small class="req"> *</small></label>
                            <input type="text" class="form-control" id="title" name="title">
                            <span class="text text-danger" id="title_error"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-xs-10 col-sm-6 col-md-6 col-lg-6">
                            <label for="date">Metting Date --r<small class="req"> *</small></label>
                            <div class='input-group' id='meeting_date'>
                                <input type='text' class="form-control" name="date"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>

                            <span class="text text-danger" id="title_error"></span>
                        </div>
                        <div class="form-group col-xs-10 col-sm-6 col-md-6 col-lg-6">
                            <label for="duration">Duration(min.) --r<small class="req"> *</small></label>
                            <input type="number" class="form-control" id="duration" name="duration">
                            <span class="text text-danger" id="title_error"></span>
                        </div>



                        <div class="clearfix"></div>
                        <div class="form-group col-xs-10 col-sm-6 col-md-6 col-lg-6">
                            <label for="class">Host Video --r<small class="req"> *</small></label>
                            <label class="radio-inline"><input type="radio" name="host_video"  value="1" checked>Enable</label>
                            <label class="radio-inline"><input type="radio" name="host_video" value="0" >Disabled</label>

                            <span class="text text-danger" id="class_error"></span>
                        </div>
                        <div class="form-group col-xs-10 col-sm-6 col-md-6 col-lg-6">
                            <label for="class">Client Video --r<small class="req"> *</small></label>
                            <label class="radio-inline"><input type="radio" name="client_video"  value="1" checked>Enable</label>
                            <label class="radio-inline"><input type="radio" name="client_video" value="0" >Disabled</label>

                            <span class="text text-danger" id="class_error"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-xs-10 col-sm-12 col-md-12 col-lg-12">
                            <label for="description"><?php echo $this->lang->line('description') ?></label>
                            <textarea class="form-control" name="description" id="description"></textarea>

                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><?php echo $this->lang->line('save') ?></button>

                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var datetime_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'DD', 'm' => 'MM', 'Y' => 'YYYY']) ?>';
    $('#meeting_date').datetimepicker({
        format: datetime_format + " HH:mm",
        showTodayButton: true,
        ignoreReadonly: true
    });
    $(document).ready(function () {

        $(document).on('click', '.online-timetable', function (event) {
            console.log($(this).data());
            var password = makeid(5);
            var class_name = $(this).data('class');
            var subject_name = $(this).data('subject');
            var class_id = $(this).data('classId');
            var section_id = $(this).data('sectionId');
            var timeFrom = $(this).data('timeFrom');
            var format_hour = Converttimeformat(timeFrom);
            var d = new Date();
            d.setHours(format_hour.hours, format_hour.minutes, format_hour.second);
            $('#meeting_date').data("DateTimePicker").date(d);
            $('#class_id').val("").val(class_id);
            $('#section_id').val("").val(section_id);
            $('#class').val("").val(class_name);
            $('#title').val("");
            $('#password').val("").val(password);
            $('#modal-online-timetable').modal('show');

        });
    });



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

    function Converttimeformat(time) {


        var hrs = Number(time.match(/^(\d+)/)[1]);
        var mnts = Number(time.match(/:(\d+)/)[1]);
        var format = time.match(/\s(.*)$/)[1];
        if (format == "PM" && hrs < 12)
            hrs = hrs + 12;
        if (format == "AM" && hrs == 12)
            hrs = hrs - 12;
        var hours = hrs.toString();
        var minutes = mnts.toString();
        if (hrs < 10)
            hours = "0" + hours;
        if (mnts < 10)
            minutes = "0" + minutes;

        return {
            hours: hours,
            minutes: minutes,
            second: 0
        };
    }
</script>


