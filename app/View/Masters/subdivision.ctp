<noscript>  <meta http-equiv="refresh" content="1; URL=cterror.html" /></noscript>

<script>
    $(document).ready(function () {
    var hfupdateflag = "<?php echo $hfupdateflag; ?>";
            if (hfupdateflag == 'Y')
    {
    $('#btnadd').html('Save');
    }
    if ($('#hfhidden1').val() == 'Y')
    {
    $('#tabledivisionnew').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    } else {
    $('#tabledivisionnew').dataTable({
    "iDisplayLength": 10,
            "aLengthMenu": [[5, 10, 15, 20, - 1], [5, 10, 15, 20, "All"]]
    });
    }
    var actiontype = document.getElementById('actiontype').value;
            if (actiontype == '2') {
    $('.tdsave').show();
            $('.tdselect').hide();
            $('#subdivision_name_en').focus();
    }
    });</script>
<script>
            function formadd() {
            document.getElementById("actiontype").value = '1';
                    document.getElementById("hfaction").value = 'S';
            }
    function formupdate(<?php
foreach ($languagelist as $langcode) {
    ?>
    <?php echo 'subdivision_name_' . $langcode['mainlanguage']['language_code']; ?>,
<?php } ?> id) {
    document.getElementById("actiontype").value = '2';
<?php
foreach ($languagelist as $langcode) {
    ?>
        $('#subdivision_name_<?php echo $langcode['mainlanguage']['language_code']; ?>').val(subdivision_name_<?php echo $langcode['mainlanguage']['language_code']; ?>);
<?php } ?>
    $('#hfupdateflag').val('Y');
            $('#hfid').val(id);
            $('#btnadd').html('Save');
            return false;
    }
</script> 
<?php echo $this->Form->create('subdivision', array('id' => 'subdivision', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <center><h3 class="box-title headbolder"><?php echo __('lbladmsubdivision'); ?></h3></center>
                <div class="box-tools pull-right">
                    <a  href="<?php echo $this->webroot; ?>helpfiles/Subdivision/subdivision_<?php echo $laug; ?>.html" class="btn btn-small btn-info" target="_blank"> <i class="fa fa-info-circle"></i>  <?php echo __('Help'); ?> </a>
                </div> 
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-lg-12">
                            <?php
                            foreach ($languagelist as $key => $langcode) {
                                ?>
                                <div class="col-md-3">
                                    <label><?php echo __('lbladmsubdivision') . "  (" . $langcode['mainlanguage']['language_name'] . ")"; ?>
                                        <span style="color: #ff0000">*</span>
                                    </label>    
                                    <?php echo $this->Form->input('subdivision_name_' . $langcode['mainlanguage']['language_code'], array('label' => false, 'id' => 'subdivision_name_' . $langcode['mainlanguage']['language_code'], 'class' => 'form-control input-sm', 'type' => 'text', 'maxlength' => '100')) ?>
                                    <span id="<?php echo 'subdivision_name_' . $langcode['mainlanguage']['language_code'] . '_error'; ?>" class="form-error">
                                        <?php echo $errarr['subdivision_name_' . $langcode['mainlanguage']['language_code'] . '_error']; ?>
                                    </span>
                                </div>
                            <?php } ?>
                            <?php echo $this->Form->input('csrftoken', array('label' => false, 'id' => 'csrftoken', 'type' => 'hidden', 'class' => 'form-control', 'value' => $this->Session->read("csrftoken"))); ?>
                        </div>
                    </div>
                </div>
                <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
                <div class="row center">
                    <div class="form-group">
                        <div class="col-sm-12 tdselect">
                            <button id="btnadd" name="btnadd" class="btn btn-info " onclick="javascript: return formadd();">
                                <span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;<?php echo __('lblbtnAdd'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-primary">

            <div class="box-body">
                
                    <table id="tabledivisionnew" class="table table-striped table-bordered table-hover">  
                        <thead >  
                            <tr>  

                                <?php foreach ($languagelist as $langcode) { ?>
                                    <th class="center"><?php echo __('lblsubdiv') . "  (" . $langcode['mainlanguage']['language_name'] . ")"; ?></th>
                                <?php } ?>
                                <th class="center width10"><?php echo __('lblaction'); ?></th>
                            </tr>  
                        </thead>
                        <tbody>
                            <?php foreach ($subdivisionrecord as $subdivisionrecord1): ?>
                                <tr>
                                    <?php
                                    foreach ($languagelist as $langcode) {
                                        ?>
                                        <td ><?php echo $subdivisionrecord1['subdivision']['subdivision_name_' . $langcode['mainlanguage']['language_code']]; ?></td>
                                    <?php } ?>
                                    <td >
                                        <button id="btnupdate" name="btnupdate"  type="button" data-toggle="tooltip" title="Edit" class="btn btn-default "  
                                                onclick="javascript: return formupdate(
                                                <?php foreach ($languagelist as $langcode) { ?>
                                                                    ('<?php echo $subdivisionrecord1['subdivision']['subdivision_name_' . $langcode['mainlanguage']['language_code']]; ?>'),
                                                <?php } ?>
                                                                ('<?php echo $subdivisionrecord1['subdivision']['id']; ?>'));">
                                            <span class="glyphicon glyphicon-pencil"></span></button>
                                        <?php
                                        $newid = $this->requestAction(
                                                array('controller' => 'Masters', 'action' => 'encrypt', $subdivisionrecord1['subdivision']['id'], $this->Session->read("randamkey"),
                                        ));
                                        ?>

                                        <a <?php echo $this->Html->Link($this->Html->tag('span', '', array('class' => 'glyphicon glyphicon-remove')), array('action' => 'subdivision_delete', $newid), array('escape' => false, 'data-toggle' => 'tooltip', 'title' => __('Delete'), 'class' => "btn btn-default"), array('Are you sure?')); ?></a>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                            <?php unset($districtrecord1); ?>
                        </tbody>
                    </table>
                    <?php if (!empty($districtrecord)) { ?>
                        <input type="hidden" value="Y" id="hfhidden1"/><?php } else { ?>
                        <input type="hidden" value="N" id="hfhidden1"/><?php } ?>
               
            </div>
        </div>

    </div>
    <input type='hidden' value='<?php echo $actiontypeval; ?>' name='actiontype' id='actiontype'/>
    <input type='hidden' value='<?php echo $hfactionval; ?>' name='hfaction' id='hfaction'/>
    <input type='hidden' value='<?php echo $hfid; ?>' name='hfid' id='hfid'/>
    <input type='hidden' value='<?php echo $hfupdateflag; ?>' name='hfupdateflag' id='hfupdateflag'/>
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

