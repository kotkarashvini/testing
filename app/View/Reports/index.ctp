<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
    $('#tabledivisionnew').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    });</script>
<script>
            //document.getElementById("hfupdateflag").value = 'S';
                    function formadd() {
                    document.getElementById("actiontype").value = '1';
                            document.getElementById("hfaction").value = 'S';
                    }

            function formupdate(<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'report_name_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?> id) {
            document.getElementById("actiontype").value = '1';
<?php
foreach ($languagelist as $langcode) {
    ?>
//        alert(census_code_changedate);
                $('#report_name_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(report_name_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
                    $('#hfupdateflag').val('Y');
                    $('#hfid').val(id);
                    $('#btnadd').html('Save');
                    return false;
            }
</script> 

<?php echo $this->Form->create('index', array('id' => 'index', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">

        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lblreport'); ?></h3></center>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                            <?php
                            foreach ($languagelist as $key => $langcode) {
                                ?>
                                <div class="col-md-3">
                                    <label><?php echo __('lblreport') . "  (" . $langcode['mainlanguage']['language_name'] . ")"; ?>
                                        <span style="color: #ff0000">*</span>
                                    </label>    
                                    <?php echo $this->Form->input('report_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'report_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '255')) ?>
                                    <span id="<?php echo 'report_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                        <?php echo $errarr['report_name_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                    </span>
                                </div>

                            <?php } ?>
                    </div>
                </div>
                </div>
              <div class="box-body">
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-info "  onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                            </button>
                        </div>
                        <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'value' => $this->Session->read("csrftoken"))); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-body">
                    <table id="tabledivisionnew" class="table table-striped table-bordered table-hover">  
                        <thead>  
                            <tr>  
                                 <th class="center width10"><?php echo __('lblid'); ?></th>
                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('lblreport') . " ( " . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                   
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($reportrecord as $reportrecord1): ?>
                                <tr>
                                    <td ><?php echo $reportrecord1['Reports']['report_id']; ?></td>
                                    <?php
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td ><?php echo $reportrecord1['Reports']['report_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                         
                                    <?php } ?>
                                    <td >
                                        <button id="btnupdate" name="btnupdate"  type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "  onclick="javascript: return formupdate(
                                        <?php foreach ($languagelist as $langcode) { ?>
                                                                ('<?php echo $reportrecord1['Reports']['report_name_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                        <?php } ?>
                                             
                                                            ('<?php echo $reportrecord1['Reports']['report_id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span></button>

                                       <?php
                                        $newid = $this->requestAction(
                                                array('controller' => 'Reports', 'action' => 'encrypt', $reportrecord1['Reports']['report_id'], $this->Session->read("randamkey"),
                                        ));
                                        ?>
                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'report_delete',  $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php unset($reportrecord1); ?>
                        </tbody>
                    </table> 
                    <?php if (!empty($reportrecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
            </div>
        </div>
    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='S' name='hfupdateflag' id='hfupdateflag'/>
</div>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

