<script>
    $(document).ready(function () {
        $('#tablelist123').dataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]]
        });

    });
</script>

<div class="form-group">
    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
           
          
                <table id="tablelist123" class="table table-striped table-bordered table-hover">  
               
                    <thead> 
                        <tr> 
                            <?php if(isset($column)){ foreach ($column as $col) { ?>

                                <th class="center"><?php echo __(str_replace("%20"," ",$col));
                                ?></th>
                            <?php }}
                                ?>
                        </tr>  
                    </thead>

                    <?php
                    if(isset($record)){
                    foreach ($record as $rec) {
                        ?>
                        <tr>
                            <?php foreach ($colrec as $col1) { ?>
                                <td ><?php echo $rec[0][$col1]; ?></td>                                        
                    <?php }} ?>
                        </tr>
                    <?php }
                    ?>


                </table> 
        </div>

    </div>
</div>


<?php echo $this->Js->writeBuffer(); ?>




