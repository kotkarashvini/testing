<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of timeslot
 *
 * @author nic
 */
class identifire_type extends AppModel{
    //put your code here
    
     public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_identifier_type';
    public $primaryKey = 'type_id';
    
}
