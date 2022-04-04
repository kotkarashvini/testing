<script type="text/javascript">
    $(document).ready(function ()
    {
        $('#reload').click(function ()
        {
            var captcha = $("#captcha_image");
            captcha.attr('src', captcha.attr('src') + '?' + Math.random());
            return false;

        });
    });
</script>

<?php echo $this->Form->create('propertyscreennew', array('id' => 'propertyscreennew', 'class' => 'form-vertical')); ?> 

<div class="row">
    <div class="col-lg-12">
        <div class="col-sm-12">      
            <?php echo $this->element("Property/screen"); ?>
        </div>


        <div class="col-md-12 center">
            <div class="box box-primary">
                <div class="box-body"> 
                    <div class="row">
                        <div class="col-md-3">
                        </div>  
                        <div class="col-md-3">
                            <?php echo $this->Html->image(array('controller' => 'Users', 'action' => 'get_captcha'), array('id' => 'captcha_image', 'class' => 'img-rounded img-thumbnail')); ?> 
                        </div>    
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <label> <?php echo __('lblentercaptcha'); ?> </label>
                        </div>  
                        <div class="col-md-3">
                            <div class="input-group">
                                <?php echo $this->Form->input('captcha', array('label' => false, 'id' => 'captcha', 'class' => 'form-control')); ?> 
                                <span class="input-group-addon cursor_pointer " id="reload"><i class="fa fa-refresh  text-success"></i></span>
                            </div>
                            <span class="form-error" id="captcha_error"> </span>
                        </div>    
                    </div> 
                    <br><br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group-sm">
                                <button type="button" class="btn btn-info" id="btnSubmit" name="btnSubmit" onclick="location.href = '<?php echo $this->Html->url(array('controller' => 'Property', 'action' => 'propertyscreennew')); ?>';"><?php echo __('lblnewvaluation'); ?></button>
                                <button id="btnSave" type="Button"  name="btnSave" class="btn btn-primary" onclick="javascript: return formsave();"><?php echo __('lblsaveandcal'); ?></button>
                                <button id="btnView" type="Button"   class="btn btn-primary" onclick="javascript: return formview('<?php echo base64_encode($valuation_id); ?>');" ><?php echo __('lblview'); ?></button>
                                <button  id="btnviewsd" class="btn btn-primary " type="button" onclick="viewsd();"><span class="fa-spin"></span><?php echo __('lblsdview'); ?>  </button>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModalsdview" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('lblviewSD'); ?></h4>
            </div>
            <div class="modal-body" id="stampduty_modal_body">
                <p>Loading ...... Please Wait!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('lblclose'); ?></button>
            </div>
        </div>

    </div>
</div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer(); ?>

<script language="JavaScript" type="text/javascript">
<?php if (isset($valuation_id) && is_numeric($valuation_id)) { ?>
        $("#btnviewsd").prop("disabled", false);
<?php } else { ?>
        $("#btnviewsd").prop("disabled", true);
<?php } ?>
    function viewsd() {

<?php if (isset($valuation_id) && is_numeric($valuation_id)) { ?>

            var frm = {};
            frm["article_id"] = 68;
            frm["village_id"] = $("#village_id").val();
            frm["val_id"] = <?php echo $valuation_id; ?>;
            frm["token_no"] = 999999;
            $.post('<?php echo $this->webroot; ?>calculateFees', {frm: frm}, function (data)
            {
                $("#stampduty_modal_body").html(data);
                $("#myModalsdview").modal('show');

            });
<?php }
?>
    }

    var vwflag = "<?php echo $pdfflag; ?>";
    if (vwflag == 1) {
        $("#btnPdf").prop("disabled", false);
        $("#btnView").prop("disabled", false);
        ruleChangeEvent();
        selectrule();
        document.getElementById('btnView').click();
    } else {
        $("#btnPdf").prop("disabled", true);
        $("#btnView").prop("disabled", true);
    }

</script> 

