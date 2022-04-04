<script>
    $(document).ready(function () {

        $('.date').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            calendarWeeks: true,
            //orientation: "top left",
            autoclose: true,
            todayHighlight: true
        });

    });


</script>

<script>

    $(function () {
        var host = '<?php echo $this->webroot; ?>';

        $('#go').click(function () {
            $.ajax({
                url: '<index_register_pdf>',
                success: function (data) {
                    var blob = new Blob([data]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Dossier_" + new Date() + ".pdf";
                    link.click();
                }
            });

            $('form').submit();
        });
    });
</script>


<?php echo $this->Form->create('rpt_token_submitted_doc', array('id' => 'rpt_token_submitted_doc')); ?>

<div class = "box box-primary">
    <div class = "box-header with-border">
        <center><h3 class = "box-title headbolder">Total Token Registered</h3></center>
    </div>
    <div class="box-body">

         <div class="row" id="divDate">
                    <div class="form-group">
                        <div class="col-sm-2"></div>
                        <label for="Valuation No" class="control-label col-sm-2"> <?php echo __('lblgetrecordby'); ?> </label>            
                        <div class="col-sm-2"><?php echo $this->Form->input("from", array('id' => 'from', 'legend' => false, 'class' => 'date form-control', 'label' => false)); ?>
                        
                        <span id="from_error" class="form-error"><?php //echo $errarr['from_error'];  ?></span>
                     
                        
                        </div>
                        <div class="col-sm-2"><?php echo $this->Form->input("to", array('id' => 'to', 'legend' => false, 'class' => 'date form-control', 'label' => false)); ?>
                        
                        <span id="to_error" class="form-error"><?php //echo $errarr['to_error'];  ?></span>
                      
                        
                        </div>
                    </div>
                </div>

        <div  class="rowht">&nbsp;</div>

    </div> 
    <div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div><div  class="rowht">&nbsp;</div>
    <div class="row center">
        <div class="form-group">
            <div class="col-sm-1"></div>
            <button id="go" class="btn btn-primary" type="submit"> Go </button>
              <?php echo $this->Form->input('csrftoken', array('label' => false, 'type' => 'hidden', 'value' => $this->Session->read('csrftoken'))); ?>
            <input type="hidden" id="actiontype" name="hfaction" class="btn btn-primary">
        </div>
    </div>


</div>    

<?php if ($pdf_flag) { ?>
    <iframe src="<?php echo $this->webroot ?>cash_details_pdf" width="100%" height="500px">
    <?php
}
?>
