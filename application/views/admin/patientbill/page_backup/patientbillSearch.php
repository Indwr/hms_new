<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <li>
                            <a href="<?php echo base_url(); ?>admin/" class="active"><?php echo $this->lang->line('opd') ; ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>admin/" class="active"><?php echo $this->lang->line('ipd')  ; ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>admin/" class="active"><?php echo $this->lang->line('pathology')  ; ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>admin/" class="active"><?php echo $this->lang->line('radiology') ; ?></a>
                        </li>
                         <li>
                            <a href="<?php echo base_url(); ?>admin/" class="active"><?php echo $this->lang->line('operation_theatre') . " " . $this->lang->line('theatre') ; ?></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>admin/" class="active"><?php echo $this->lang->line('blood_bank') ; ?></a>
                        </li>
                         <li>
                            <a href="<?php echo base_url(); ?>admin/patientbill/searchambulance" class="active"><?php echo $this->lang->line('ambulance') ; ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-10">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('record') ; ?></h3>
                        <div class="box-tools pull-right">
                            <?php
if ($this->rbac->hasPrivilege('birth_death_customfields', 'can_add')) {
    ?>
                                <a data-toggle="modal" onclick="holdModal('myModal')" class="btn btn-primary btn-sm birth_record"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></a>
                            <?php }?>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('birth') . " " . $this->lang->line('details') . " " . $this->lang->line('list'); ?></div>
                        <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('fields') . " " . $this->lang->line('name'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
if (empty($resultlist)) {
    ?>

                                    <?php
} else {
    $count = 1;
    foreach ($resultlist as $birth) {
        ?>
                                        <tr class="">
                                            <td>
                                                <?php echo $birth['name']; ?>
                                                <div class="rowoptionview">
                                                    <?php
if ($this->rbac->hasPrivilege('birth_death_customfields', 'can_edit')) {
            ?>
                                                        <a href="#" onclick="getRecord('<?php echo $birth['id'] ?>')" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php }if ($this->rbac->hasPrivilege('birth_death_customfields', 'can_delete')) {?>
                                                        <a  class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="delete_recordById('<?php echo base_url(); ?>admin/birthordeathcustom/deletecustom/<?php echo $birth['id']; ?>', '<?php echo $this->lang->line('delete_message'); ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    <?php }?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
$count++;
    }
}
?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="myPaymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('add') . " " . $this->lang->line('payment'); ?></h4> 
            </div>
            <form id="add_payment" accept-charset="utf-8" method="post" class="" >    
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></label><small class="req"> *</small> 
                                    <input type="text" name="amount" id="amount" class="form-control">    
                                    <input type="hidden" name="patient_id" id="payment_patient_id" class="form-control">
                                    <input type="hidden" name="total" id="total" class="form-control">
                                    <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                </div>
                            </div>
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('payment') . " " . $this->lang->line('mode'); ?></label> 
                                    <select class="form-control" name="payment_mode">

                                                <?php foreach ($payment_mode as $key => $value) {
                                                    ?>
                                            <option value="<?php echo $key ?>" <?php
                                                if ($key == 'cash') {
                                                    echo "selected";
                                                }
                                                ?>><?php echo $value ?></option>
<?php } ?>
                                    </select>    
                                    <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small> 
                                    <input type="text" name="payment_date" id="date" class="form-control date">
                                    <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('attach_document'); ?></label>
                                    <input type="file" class="filestyle form-control"   name="document">
                                    <span class="text-danger"><?php echo form_error('document'); ?></span> 
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('note'); ?></label> 
                                    <input type="text" name="note" id="note" class="form-control"/>
                                </div>
                            </div>

                        </div>
                    </div>
                </div> 
                <div class="box-footer">    
                    <button type="submit" id="add_paymentbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>   
            </form>
        </div>
    </div> 
</div>
<!-- -->
<script src="" type="text/javascript" charset="utf-8" async defer>
        
    $(function () {
        $('#easySelectable').easySelectable();
        $('.select2').select2()
    //stopPropagation();
    })
     $("#addpatient_id").click(function(){  
    $('#select2-addpatient_id-container').html("");
    //$('#formadd').trigger("reset");
   // $("#patientDetails").hide();
});

</script>
<script type="text/javascript">
    /*
     Author: mee4dy@gmail.com
     */
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
        //$("#schedule_charge").html("schedule_charge");
        //var base_url = "<?php echo base_url(); ?>backend/images/loading.gif";
        //$("#ajax_load").html("<center><img src='" + base_url + "'/>");
       // var password = makeid(5)
        //$('#guardian_name').html("Null");
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/patientDetails',
            type: "POST",
            data: {id: id},
            dataType: 'json',
            success: function (res) {
                //console.log(res);

                if (res) {

                   // $("#ajax_load").html("");
                  //  $("#patientDetails").show();
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
                   
                } 
            }
        });
    }

     function getopdipd(opdipd_group) {        
        var pid = $('#patient_id').val();        
        var div_data = "";
        $('#opdipd_no').html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
       // $("#opdipd_no").select2("val", '1');
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
               // $("#opdipd_no").select2().select2('val', '');
            }
        });
    }

     function addpaymentModal() {
       
        holdModal('myPaymentModal');
    }

    $(".addpayment").click(function(){      
    $('#add_payment').trigger("reset");
    $(".dropify-clear").trigger("click");
    });

    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }
    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
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
                }
            });
        }
    }

    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });
</script>