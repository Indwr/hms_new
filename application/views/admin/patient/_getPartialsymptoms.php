<?php
if (!empty($sectionList)) {
    foreach ($sectionList as $section_key => $section_value) {
        ?>
             <li class='option'><label class='checkbox'><input type='checkbox'  name='symptoms_title' value='<?php echo $section_value->symptoms_title."\n".$section_value->description ; ?>'> <?php echo $section_value->symptoms_title ?></label></li>
        <?php
    }
}
?>
<script type="text/javascript">
	
	$("input[name=symptoms_title]").change(function() {
  updateAllChecked();
});

$("input[name=addall]").change(function() {
  if (this.checked) {
    $("input[name=symptoms_title]").prop('checked', true).change();
  } else {
    $("input[name=symptoms_title]").prop('checked', false).change();
  }
});

function updateAllChecked() {
  $('#symptoms_description').val('');
  $("input[name=symptoms_title]").each(function() {
    if (this.checked) {
      let old_text = $('#symptoms_description').val() ? $('#symptoms_description').val() + '\n\n' : '';
     // let eold_text = $('#esymptoms').val() ? $('#esymptoms').val() + '\n\n' : '';
      $('#symptoms_description').val(old_text + $(this).val());
      $('#esymptoms').val(old_text + $(this).val());

    }
  })
}
</script>