<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$genderList = $this->customlib->getGender();
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('patient_login_credential'); ?></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('patient_login_credential'); ?></div>
                        <table class="table table-striped table-bordered table-hover test_ajax" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('id'); ?></th>
                                    <th><?php echo $this->lang->line('patient') . " " . $this->lang->line('name'); ?></th>
                                    <th><?php echo $this->lang->line('username'); ?></th>
                                    <th><?php echo $this->lang->line('password'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <?php
                                if (empty($credential)) {
                                    ?>
                                              
                                    <?php
                                } else {
                                    $count = 1;
                                    foreach ($credential as $user) {
                                        ?>
                                        <tr class="">
                                            <td><?php echo $user['patient_unique_id']; ?></td>
                                            <td><?php echo $user['patient_name']; ?></td>
                                            <td><?php echo $user['username']; ?></td>
                                            <td><?php echo $user['password']; ?></td>
                                            <?php
                                            $count++;
                                        }
                                    }
                                    ?> -->
                            </tbody>
                        </table>
                    </div>
                </div>                                                    
            </div>                                                                                                                                          
        </div>  
    </section>
</div>
<script type="text/javascript">
    
    $(document).ready(function() {
    $('.test_ajax').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": base_url+"admin/patient/patientcredential_search",
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
</script>>