<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">   
   
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bill Payment --r</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">   
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/patientbill/search') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <input name="patient_id" id="patient_id" type="hidden" class="form-control" />

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('patient'); ?></label><small class="req">  *</small>
                                                <select onchange="get_PatientDetails(this.value)"  class="form-control select2" <?php
                                                    //if ($disable_option == true) {
                                                        //echo "disabled";
                                                   // }
                                                    ?> style="width:100%" name='' id="addpatient_id" >
                                                        <option value=""><?php echo $this->lang->line('select') . " " . $this->lang->line('patient') ?></option>
                                                        <?php foreach ($patients as $dkey => $dvalue) {
                                                            ?>
                                                            <option value="<?php echo $dvalue["id"]; ?>" <?php
                                                            if ((isset($patient_select)) && ($patient_select == $dvalue["id"])) {
                                                                echo "selected";
                                                            }
                                                            ?>><?php echo $dvalue["patient_name"] . " (" . $dvalue["patient_unique_id"] . ')' ?></option>   
                                                    <?php } ?>
                                                    </select>
                                                <span class="text-danger"><?php echo form_error('patient_id'); ?></span>
                                            </div> 
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Select modules --r</label>
                                                <select name='select_group' id="" onchange="getopdipd(this.value)" class="form-control"  style="width:100%" >
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>                           
                                                        <option value="<?php echo $this->lang->line('opd')?>" ><?php echo $this->lang->line('opd') ?></option>
                                                        <option value="<?php echo $this->lang->line('ipd')?>" ><?php echo $this->lang->line('ipd') ?></option>                                                    
                                                </select>    
                                                <span class="text-danger"><?php echo form_error('select_group'); ?></span>
                                            </div> 
                                        </div>
                                         <!--  <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                        <?php echo $this->lang->line('opd') . " / " .$this->lang->line('ipd')." ". $this->lang->line('number'); ?></label><small class="req"> *</small> 
                                                <div>
                                                <select class="form-control" style="width: 100%" name='opdipd_id' id='opdipd_no'>
                                                        <option value=""><?php echo $this->lang->line('select') ?></option>  </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('opdipd_no'); ?></span>
                                            </div>
                                        </div>    -->   
                                        <div class="col-sm-12">  
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_filter" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>  
                            </div>
                           <!--  <div class="col-md-6 col-sm-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('studentfee/search') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_keyword'); ?></label>
                                                <input type="text" name="search_text" class="form-control" value="<?php echo set_value('search_text'); ?>" placeholder="<?php echo $this->lang->line('search_by_patient_name'); ?>">
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_full" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>  
                            </div> -->
                        </div>
                    </div>
                    
                <?php
                if (isset($resultlist)) {
                    ?>
                    <div class="">
                       <div class="box-header ptbnull"></div> 
                        <div class="box-header ptbnull">
                            <!-- <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('list'); ?>
                                <?php echo form_error('student'); ?></h3> -->
                            <div class="box-tools pull-right"></div>
                        </div>
                        <div class="box-body table-responsive">
                            <div class="col-md-12">
                                <div class="sfborder">
                                    <div class="col-md-2">
                                        <img width="115" height="115" class="round5" src="<?php
                                        if (!empty($result['image'])) {
                                            echo base_url() . $result['image'];
                                        } else {
                                            echo base_url() . "uploads/student_images/no_image.png";
                                        }
                                        ?>" alt="No Image">
                                    </div>

                                    <div class="col-md-10">
                                        <div class="row">
                                            <table class="table table-striped mb0 font13">
                                                <tbody>
                                                    <tr>
                                                        <th class="bozero"><?php echo $this->lang->line('name'); ?></th>
                                                        <td> <?php echo $result['patient_name']; ?> </td>

                                                        <th class="bozero"><?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></th>
                                                       <td> <?php echo $result['patient_unique_id']; ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo  $this->lang->line('phone'); ?></th>
                                                        <td> <?php echo $result['mobileno']; ?> </td>
                                                        <th><?php echo $this->lang->line('email'); ?></th>
                                                      <td> <?php echo $result['email']; ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('blood_group'); ?></th>
                                                        <td> <?php echo $result['blood_group']; ?> </td>
                                                        
                                                        <th><?php echo $this->lang->line('address'); ?></th>
                                                         <td> <?php echo $result['address']; ?>
                                                        </td>
                                                    </tr>
                                                 
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="download_label"><?php echo $this->lang->line('patient'); ?> <?php echo $this->lang->line('list'); ?></div>

                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('opd')." / ".$this->lang->line('ipd'); ?></th>
                                        <th><?php echo $this->lang->line('patient'); ?></th>
                                        <th>Select Module --r</th>
                                        
                                        
                                       <!--  <th><?php echo $this->lang->line('consultant')." ".$this->lang->line('charges')."(".$this->lang->line('paid').")"; ?></th>
                                        <th><?php echo $this->lang->line('total')." ".$this->lang->line('charges'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('other')." ".$this->lang->line('charges'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('discount'); ?></th>
                                        <th><?php echo $this->lang->line('payment'); ?></th>
                                        <th><?php echo $this->lang->line('bill'); ?></th> -->
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>            
                                <tbody>    
                                    <?php
                                    $count = 1;
                                    $visit_charge = 0;
                                   // $rvisit_charge = 0;
                                    foreach ($resultlist as $vkey => $patient) {

                                        $visit_charge += $patient['amount'];

                                        //$rvisit_charge += $revisit_details['amount'];
                                        if ($_POST['select_group'] == $this->lang->line('opd')) {
                                            $module_no = $patient['opd_no'];
                                        }elseif ($_POST['select_group'] == $this->lang->line('ipd'))  {
                                            $module_no = $patient['ipd_no'];
                                        }

                                        if ($patient['status'] == 'paid'){
                                            $status = $this->lang->line($patient['status'])  ;
                                            $status ="<a href=".base_url().'admin/patientbill/visitbill/'.$result['id'].'/'.$patient['id']." class='btn btn-sm btn-success dropdown-toggle' >".$this->lang->line($patient['status'])."</a>";    
                                         }else{
                                             // $status = "<a href='' class='btn btn-sm btn-primary dropdown-toggle addpayment' onclick= 'addpaymentModal()' data-toggle='modal'>". $this->lang->line('pay')."</a>" ;
                                        //$status = "<a href='' class='btn btn-sm btn-primary dropdown-toggle ' onclick= '' data-toggle=''>". $this->lang->line('collect')." ".$this->lang->line('bill')."</a>" ;

                                        $status = "<a href=".base_url().'admin/patientbill/visitbill/'.$result['id'].'/'.$patient['id']." class='btn btn-sm btn-primary dropdown-toggle' >".$this->lang->line('collect')." ".$this->lang->line('bill')."</a>";
                                        }
                                        ?>
                                        
                                      
                                        <tr>
                                            <td><?php echo $module_no; ?></td>
                                            <td><?php echo $patient['patient_name']; ?></td>
                                            <td><?php echo ($_POST['select_group']); ?></td>
                                            
                                            <!-- <td><?php echo $visit_charge ; ?></td>
                                            <td><?php echo $patient['charges']; ?></td>

                                           <td class="text-right"><input  type="text" id="other_charge" value="<?php
                                                            if (!empty($result["other_charge"])) {
                                                                echo $result["other_charge"];
                                                            } else {
                                                                echo "0";
                                                            }
                                                            ?>" name="other_charge" style="width: 30%; float: right" class="form-control"></td>
                                            <td class="text-right"><input type="hidden" name="patient_id" value="<?php echo $result["id"] ?>">
                                                             <input type="hidden" name="opd_id" value="<?php echo $result["visitid"] ?>"> 
                                                            <input  type="text" id="discount" value="<?php
                                                            if (!empty($result["discount"])) {
                                                                echo $result["discount"];
                                                            } else {
                                                                echo "0";
                                                            }
                                                            ?>" name="discount" style="width: 30%; float: right" class="form-control"></td> 
                                            <td><?php echo $patient['payment']; ?></td>
                                            <td><?php echo $patient['bill']; ?></td> -->
                                            <td class="pull-right"><?php echo $status; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    $count++;
                                    ?>
                                </tbody>
                            </table>
                        </div><!--./box-body-->
                    </div>
                  </div>  
                    <?php
                }
                ?>
            </div>

        </div> 

        <!-- -->
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

    </section>
</div>
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