<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



class ReceiptCounter extends AppModel {

    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_trn_receipt_counter';
    public $primaryKey='receipt_id';

}
