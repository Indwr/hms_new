<style>

    .ui-accordion{border: 1px solid #f4f4f4;}    
    .panel-heading {
        padding: 0;
        border: 0;
    }
    .panel-title > a,
    .panel-title > a:active {
        display: block;
        padding: 15px;
        color: #555;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        word-spacing: 3px;
        text-decoration: none;
    }
    .panel-heading a:before {
        font-family: 'FontAwesome';
        content: "\f106";
        float: right;
        transition: all 0.5s;
    }
    .panel-heading.active a:before {
        -webkit-transform: rotate(180deg);
        -moz-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .accordianheader {
        color: #000;
        background: #fff;
        padding: 10px 10px;
        margin-bottom: 0px;
        border-top: 1px solid #ddd;
        position: relative;
        overflow: hidden;
        outline: 0;
        cursor: pointer;
    }
    .accordianbody {
        background: #f4f4f4;
    }

    .accordianbody i {
        color: #fff !important;
        position: absolute;
        right: 20px;
        top: 14px;

        -webkit-transition: all 300ms ease-in 0s;
        -moz-transition: all 300ms ease-in 0s;
        -o-transition: all 300ms ease-in 0s;
        transition: all 300ms ease-in 0s;
    }
    .ui-state-active i {
        color: #000;
        -webkit-transform: rotate(180deg);
        -moz-transform: rotate(180deg);
        -o-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        transform: rotate(180deg);
        -webkit-transition: all 300ms ease-in 0s;
        -moz-transition: all 300ms ease-in 0s;
        -o-transition: all 300ms ease-in 0s;
        transition: all 300ms ease-in 0s;
    }
 
    .notigybg{background: #fafafa; padding: 10px; overflow: hidden;font-weight: bold;}
    .notifyleft{width: 8%; float: left;}
    .notifymiddle{width: 70%; float: left;}
    .notifyright{width: 18%; float: left;}
    .noteangle{position: absolute; right:20px; font-size: 18px; top:20px;}
    .note-content{padding-left: 8.5%;padding-bottom: 15px;}

    .noteDM10{padding-top: 10px;}
    .unreadbg{background:#e1eeff;}
    .readbg{background:#fff;}
    .accordianheader{-webkit-transition: all 0.5s ease 0s;
                     -moz-transition: all 0.5s ease 0s;
                     -ms-transition: all 0.5s ease 0s;
                     -o-transition: all 0.5s ease 0s;
                     transition: all 0.5s ease 0s;}
    .accordianheader:focus,
    .accordianheader:visited,
    .accordianheader:hover{background:#f5f5f5;}
    @media(max-width:767px){
        .notifyleft{width: 60px;}
        .notifymiddle{width: 40%;}
        .notifyright{width: 20%;}
        .noteDM10{padding-top: 0px;}
        .note-content{padding-left: 70px;}
    }
</style>
<script>
    function updateStatus(id) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/systemnotification/updateStatus/',
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
                        <div id="accordion" class="accordionclick">
                            <div class="notigybg">   
                                <div class="notifyleft" style=""><?php echo $this->lang->line('type'); ?></div>
                                <div class="notifymiddle" style=""><?php echo $this->lang->line('subject'); ?></div>
                                <div class="notifyright" style=""><?php echo $this->lang->line('date'); ?></div>
                            </div>   
                            <!-- yeah, yeah, I spelled Accordion wrong,  do something about it.  - G  -->
                            <?php if (empty($notifications)) { 
							
                            } else {
                               
                                foreach ($notifications as $result) {
                                    if ((!empty($result['readdone'])) && ($result['readdone'] == 'no')) {
                                        $class = "readbg";
                                    } else {
                                        $class = "unreadbg";
                                    }
                                    ?>
                                    <div class="accordianheader  <?php echo $class ?>" data-noticeid="<?php echo $result['id'] ?>">
                                        <div class="notifyleft">
                                            <div class="bellcircle">
                                                <?php
                                                if ($result['notification_type'] == 'opd') {
                                                    $class = $notificationicon['opd'];
                                                } if ($result['notification_type'] == 'ipd') {
                                                    $class = $notificationicon['ipd'];
                                                } if ($result['notification_type'] == 'appointment') {
                                                    $class = $notificationicon['appointment'];
                                                } if ($result['notification_type'] == 'ot') {
                                                    $class = $notificationicon['ot'];
                                                } if ($result['notification_type'] == 'salary') {
                                                    $class = $notificationicon['salary'];
                                                }
                                                ?>     
                                                <i class="<?php echo $class; ?>" style="transform: rotate(0deg); color: #fff;"></i>               
                                            </div>
                                        </div><!--./notifyleft-->
                                        <div class="notifymiddle noteDM10"><?php 	
										
										$keyval = $this->lang->line($result['notification_title']);
										if(empty($keyval)){
											echo $result['notification_title'];
										} else{
											echo $keyval;
										}
										?>									
										</div>
                                        <div class="notifyright noteDM10"><?php echo date($this->customlib->getHospitalDateFormat(true, true), strtotime($result['date'])); ?></div>

                                        <div class="noteangle" style=""><i class="fa fa-angle-down" ></i>
                                        </div>
                                    </div>
                                    <div class="accordianbody" style="position: relative;">
                                        <div class="note-content"> 	<?php echo $result['notification_desc'];?>									
                                            <?php 
											// $str  	= 		$result['notification_desc'];									
											// $explodedata 	= 		(explode(",",$str));								
											
											// if(empty($keyval)){												
												// echo  $str;
												
											// }else{
												// if(!empty($explodedata[1])){
													// $lankey 		=		$explodedata[0];
													// $url			=		$explodedata[1];
													// $patientopdipd 	=		$explodedata[2];
												// }
												// echo $this->lang->line($lankey);
												?>
												<a href='<?php //echo base_url().$url; ?>'><?php //echo $patientopdipd; ?></a>
												
												<?php
											//}
											
											?>							
											
                                        </div>
                                    </div>
                                    <?php
                                   
                                }
                            }
                            ?>
                        </div><!--.#accordion-->
                        <br /> <br />
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

    $(document).ready(function () {
        $(".accordianheader").click(function () {
         
        });
    });
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