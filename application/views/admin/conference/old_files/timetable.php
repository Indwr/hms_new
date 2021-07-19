<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> Live classes --r</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Live Class --r</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-credential"><i class="fa fa-plus"></i> Add Credential --r</button>
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-online-timetable"><i class="fa fa-plus"></i> Add</button>
                        </div>
                    </div>

                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg') ?>
                        <?php } ?>
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
<div class="modal fade" id="modal-credential">
    <div class="modal-dialog">
        <form id="form-addcredential" action="<?php echo site_url('admin/conference/addcredential'); ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Zoom Credential--r</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="form-group col-xs-10 col-sm-12 col-md-12 col-lg-12">
                            <label for="zoom_api_key">zoom_api_key --r<small class="req"> *</small></label>
                            <input type="password" class="form-control" id="zoom_api_key" name="zoom_api_key">
                            <span class="text text-danger" id="title_error"></span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-xs-10 col-sm-12 col-md-12 col-lg-12">
                            <label for="zoom_api_secret">zoom_api_secret --r<small class="req"> *</small></label>
                            <input type="password" class="form-control" id="zoom_api_secret" name="zoom_api_secret">
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

                        <div class="form-group col-xs-10 col-sm-12 col-md-12 col-lg-12">
                            <label for="class">Class --r<small class="req"> *</small></label>
                            <select  id="class_id" name="class_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($classlist as $class) {
                                    ?>
                                    <option value="<?php echo $class['id'] ?>"<?php
                                    if (set_value('class_id') == $class['id']) {
                                        echo "selected=selected";
                                    }
                                    ?>><?php echo $class['class'] ?></option>
                                            <?php
                                        }
                                        ?>
                            </select>
                            <span class="text text-danger" id="class_error"></span>
                        </div>
                        <div class="form-group col-xs-10 col-sm-12 col-md-12 col-lg-12">
                            <label for="section">Section --r<small class="req"> *</small></label>
                            <select  id="section_id" name="section_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                            </select>
                            <span class="text text-danger" id="section_error"></span>
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
    var datetime_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'DD', 'm' => 'MM', 'Y' => 'YYYY']) ?>';
    $('#meeting_date').datetimepicker({
        format: datetime_format + " HH:mm",
        showTodayButton: true,
        ignoreReadonly: true
    });

    $('#modal-online-timetable').on('shown.bs.modal', function (e) {
        $("#class_id").prop("selectedIndex", 0);
        $("#section_id").find('option:not(:first)').remove();
        var password = makeid(5);
        $('#password').val("").val(password);

    });

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

    //===========================form submit==========
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

</script>