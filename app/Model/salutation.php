<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of salutation
 *
 * @author nic
 */
class salutation extends AppModel{
    //put your code here
    public $useDbConfig = 'ngprs';
    public $useTable = 'ngdrstab_mst_salutation';
    public $primaryKey = 'salutation_id';

    public function get_duplicate($languagelist) {
        $duplicate['Table'] = 'ngdrstab_mst_salutation';
        $duplicate['PrimaryKey'] = 'salutation_id';
        $fields = array();
        foreach ($languagelist as $language) {
            array_push($fields, 'salutation_desc_' . $language['mainlanguage']['language_code']);
        }
        $duplicate['Fields'] = $fields;
        return $duplicate;
    }

    public function fieldlist($languagelist) {
        $fieldlist = array();
        foreach ($languagelist as $language) {
            if ($language['mainlanguage']['language_code'] == 'en') {
                $fieldlist['salutation_desc_' . $language['mainlanguage']['language_code']]['text'] = 'is_required,is_alphacommadot';
            } else {
                $fieldlist['salutation_desc_' . $language['mainlanguage']['language_code']]['text'] = 'unicoderequired_rule_' . $language['mainlanguage']['language_code'];;
            }
        }
        return $fieldlist;
    }
}
