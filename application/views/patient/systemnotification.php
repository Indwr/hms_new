<?php
$this->config->load("mailsms");
$this->notificationicon = $this->config->item('notification_icon');
?>
<script>
    function updateStatus(id) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'patient/systemnotifications/updateStatus/',
            type: 'POST',
            data: {id: id},
            dataType: "json",
            success: function (res) {

            }
        })
    }

    $(function () {
        $(".accordianheader").click(function () {
            var id = $(this).attr("data-noticeid");
            $(this).addClass('readbg');
            updateStatus(id);           
        });
    });
</script>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('notifications'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div id="accordion">
                            <div class="sysnbg">
                                <div class="sysm-main sysmleft font-weight-bold"><?php echo $this->lang->line('type'); ?></div>
                                <div class="sysm-main sysmmiddle font-weight-bold"><?php echo $this->lang->line('subject'); ?></div>
                                <div class="sysm-main sysmlast font-weight-bold"><?php echo $this->lang->line('date'); ?></div>
                            </div>
                            <!-- yeah, yeah, I spelled Accordion wrong,  do something about it.  - G  -->
                            <?php if (empty($notifications)) {?>
                                <?php
} else {
    $count = 1;
    $color = "";
    foreach ($notifications as $result) {

        if ((!empty($result['read'])) && ($result['read'] == 'no')) {
            $class = "readbg";
        } else {
            $class = "unreadbg";
        }
        ?>
                                    <div class="accordianheader <?php echo $class ?>" data-noticeid="<?php echo $result['id'] ?>">
                                        <div class="sysm-main sysmleft">
                                            <div class="bellcircle">
                                                <?php
if ($result['notification_type'] == 'opd') {
            $class = $notificationicon['opd'];
        }if ($result['notification_type'] == 'ipd') {
            $class = $notificationicon['ipd'];
        }if ($result['notification_type'] == 'appointment') {
            $class = $notificationicon['appointment'];
        }if ($result['notification_type'] == 'ot') {
            $class = $notificationicon['ot'];
        }if ($result['notification_type'] == 'salary') {
            $class = $notificationicon['salary'];
        }
        ?>
                                                <i class="<?php echo $class; ?>" style="transform: rotate(0deg); color: #fff;"></i>
                                            </div>
                                        </div>
                                        <div class="sysm-main sysmmiddle sysmtop10"><?php echo $result['notification_title']; ?></div>
                                        <div class="sysm-main sysmlast sysmtop10"><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($result['date'])); ?></div>
                                        <div class="sysmangle"><i class="fa fa-angle-down" ></i>
                                        </div>
                                    </div>
                                    <div class="accordianbody relative">
                                        <div class="sysbottomcontent">
                                            <?php echo $result['notification_desc']; ?>
                                        </div>
                                    </div>
                                    <?php
$count++;
    }
}
?>
                            <!--./accordianbody-->

                            <!-- <div class="accordianheader">
                                 <div style="width: 8%; float: left">
                                     <div class="bellcircle"><i class="fa fa-bell-o" style="transform: rotate(0deg); color: #fff;"></i></div>
                                 </div>
                                 <div style="width: 70%; float: left;padding-top: 10px;">OPD Visit Created-1</div>
                                 <div style="width: 22%; float: left;padding-top: 10px;">09/13/2019 10:05 AM</div>
                                 <div style="position: absolute; right:20px; font-size: 18px;"><i class="fa fa-angle-down" ></i></div>
                             </div>
                             <div class="accordianbody" style="position: relative;">
                                <div style="padding-left: 9%;">
                                 Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</div>
                             </div>--><!--./accordianbody-->


                        </div><!--.#accordion-->




                        <br /> <br />

               <!-- <table class="notetable table table-border table-hover" width="100%">

                    <tr>
                        <th width="8%">Type</th>
                        <th width="70%">Subject</th>
                        <th  width="22%">Date</th>
                    </tr>
                        <?php if (empty($notifications)) {?>
                            <?php
} else {
    $count = 1;
    $color = "";
    foreach ($notifications as $result) {
        ?>

                                      <tr class="<?php echo $color ?>">
                                          <div>
                                           <td>
                                                                <div class="bellcircle"><i class="fa fa-bell-o"></i></div>
                                                            </td>

                                                            <td>
                                                                <p class="accordion" id="<?php echo $result["id"] ?>"><b><?php echo $result['notification_title']; ?></b></p>

                                                                <div class="panel">
                                                                  <p><?php echo $result['notification_desc']; ?></p>
                                                                </div>
                                                            </td>
                                                            <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($result['date'])); ?></td>
                                        </div>    </tr>
                                <?php
$count++;
    }
}
?>
                </table>  -->

                        <ul class="pagination">
<?php echo $this->pagination->create_links(); ?>

                        </ul>
                    </div>
                </div>
            </div><!--./row-->
    </section>
</div>


<script src="<?php echo base_url() ?>backend/js/Chart.bundle.js"></script>
<script src="<?php echo base_url() ?>backend/js/utils.js"></script>
<script type="text/javascript">

    $("#accordion").accordion({
        heightStyle: "content",
        active: true,
        collapsible: true,
        header: ".accordianheader"
    });
    // $('.panel-collapse').on('show.bs.collapse', function () {
    //    $(this).siblings('.panel-heading').addClass('active');
    //  });

    //  $('.panel-collapse').on('hide.bs.collapse', function () {
    //    $(this).siblings('.panel-heading').removeClass('active');
    //  });


// $(function() {
//     $(".accordion-toggle").on('click', function() {
//      $(this).removeClass('fa-angle-up').addClass('fa-angle-down');
//      $(this).addClass('collapsed').addClass('collapsed');

//        //$(".fa-angle-up").toggleClass("rotate");
//        //$('.accordion-toggle').removeClass('selected');
//     });
// });

// function toggleIcon(e) {
//     $(e.target)
//         .prev('.panel-heading')
//         .find(".more-less")
//         .toggleClass('fa-angle-up fa-angle-down');
// }

    // $(document).on('show','.accordion', function (e) {
    //      //$('.accordion-heading i').toggleClass(' ');
    //      //$(e.target).prev('.accordion-heading').addClass('accordion-opened');
    // });

    // $(document).on('hide','.accordion', function (e) {
    //     $(this).find('.accordion-heading').not($(e.target)).removeClass('accordion-opened');
    //     //$('.accordion-heading i').toggleClass('fa-chevron-right fa-chevron-down');
    // });
    //    $('.panel-collapse').on('show.bs.collapse', function () {
    //   $(this).siblings('.panel-heading').addClass('active');
    // });

    // $('.panel-collapse').on('hide.bs.collapse', function () {
    //   $(this).siblings('.panel-heading').removeClass('active');
    // });
</script>
<script type="text/javascript">
    $(document).ready(function () {

        $(document).on('click', '.close_notice', function () {
            var data = $(this).data();
            $.ajax({
                type: "POST",
                url: base_url + "admin/notification/read",
                data: {'notice': data.noticeid},
                dataType: "json",
                success: function (data) {
                    if (data.status == "fail") {

                        errorMsg(data.msg);
                    } else {
                        successMsg(data.msg);
                    }

                }
            });
        });
    });
</script>
<!-- https://bootsnipp.com/snippets/Q6zjv -->