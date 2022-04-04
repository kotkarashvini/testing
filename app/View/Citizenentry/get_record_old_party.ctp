<?php
echo $this->Html->script('bootstrap-datepicker.min');
echo $this->Html->css('bootstrap-datepicker3.min');
?>

<script type="text/javascript">

    $(document).ready(function () {
        $('#oldrecord').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });
    });
</script>

<?php
$doc_lang = $this->Session->read('doc_lang');

if (isset($ref_record) && !empty($ref_record)) {
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-weight: bolder"><?php echo __('lbllistofnamesondock'); ?></h3>
                </div>

                <div class="box-body">
                    <table class="table table-striped table-bordered table-hover" id="oldrecord">
                        <thead >
                            <tr class="table_title_red_brown">

                                <th>
                                    <?php echo __('lblfname'); ?>
                                </th>
                                <th>
                                    <?php echo __('lblmname'); ?>
                                </th>
                                <th>
                                    <?php echo __('lbllname'); ?>
                                </th>

                                <th>
                                    <?php echo __('lblaction'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php if($type=='T'){ 
                                 foreach($ref_record as $ref_record1){?> 
                            
                                <tr>
                                    <td><?php echo $ref_record1[0]['party_fname_' . $doc_lang]; ?></td>
                                    <td><?php echo $ref_record1[0]['party_mname_' . $doc_lang]; ?></td>
                                    <td><?php echo $ref_record1[0]['party_lname_' . $doc_lang]; ?></td>
                                   <td> <input type="button" class="btn btn-primary" value="Select" onclick="javascript: return setval('<?php echo $ref_record1[0]['party_id']; ?>', '<?php echo $ref_record1[0]['token_no']; ?>','<?php echo $ref_record1[0]['party_catg_id']; ?>')"></td>
                                </tr>
                             <?php  } }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
    </div>
    <input type="hidden" id="flag" value="Y">


    <?php echo $this->Js->writeBuffer(); ?>
<?php } else { ?>
    <input type="hidden" id="flag"  value="N">
<?php } ?>
