<?php

App::uses('Cache', 'Cache');
App::uses('Sanitize', 'Utility');
App::import('Controller', 'FeesJH'); // mention at top
App::import('Controller', 'Property'); // mention at top
App::import('Controller', 'DynamicVariables'); // mention at top
App::import('Controller', 'WebService');
App::import('Controller', 'PBWebService'); // mention at top

class CitizenentryFeesJHController extends FeesJHController {

    //put your code here
    public $components = array(
        'Security', 'RequestHandler', 'Cookie', 'Captcha', 'Cookie',
        'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'Citizenentry', 'action' => 'welcome'),
            'logoutRedirect' => array('controller' => 'Users', 'action' => 'welcomenote'),
            'authError' => 'You must be logged in to view this page.',
            'loginError' => 'Invalid Username or Password entered, please try again.',
            'authorize' => array('Controller')
    ));
//    public $components = array('Security', 'RequestHandler', 'Captcha', 'Cookie');
    public $helpers = array('Js', 'Html', 'Form', 'Paginator');

    public function beforeFilter() {

        $this->loadModel('language');
        $this->Session->renew();
        $langaugelist = $this->language->find('all', array('conditions' => array('state_id' => array(0, $this->Auth->user('state_id')))));
        $this->set('langaugelist', $langaugelist);
//        $this->Security->unlockedActions = array('get_adj_doc_exess_amt', 'setdoc_lang', 'update_sd', 'leaseandlicense', 'appointment', 'getarticlefield', 'genernal_info', 'genernalinfoentry', 'usagecategory_change_event', 'property_details', 'getattributeupdate', 'valuation_entry', 'party_entry', 'taluka_change_event', 'getusagevisibilitynew', 'rulechangeevent', 'getattributeparameter', 'add_property_attribute', 'witness', 'behavioral_patterns', 'article_change_event', 'check_appointmentdate', 'slot_alocation', 'get_time_difference', 'tatkalappoinment', 'tatkal_slot_alocation', 'check_maxappointmentday', 'getdependent_article', 'get_7_12_record', 'check_filevalidation', 'upload_document', 'article_mapping_screen', 'get_valuation_amt', 'getarticledepfeild', 'check_prohibited_prop', 'stamp_duty', 'check_config_prohibition', 'check_execution_date', 'party_address', 'get_instrument', 'get_fees_falc_ids', 'payment', 'get_payment_details', 'final_submit', 'check_land_record_fetching', 'check_7_12_compulsary', 'get_adj_doc_exess_amt_detail', 'is_uid_compulsary', 'is_identity_compulsary', 'identification', 'multiple_property_allowed', 'check_property_count');
//        $this->Auth->allow('welcomenote', 'login', 'add', 'Disclaimer', 'index', 'index1', 'index2', 'registration', 'checkuser', 'viewsingle', 'ViewRegisteruser', 'get_district_name', 'get_captcha', 'aboutus', 'contactus', 'insertuser', 'checkorg', 'sponsordetail_pdf', 'checkcaptcha', 'checkemail', 'send_sms', 'empregistration');
        //$this->Security->unlockedActions = array('get_valuation_id', 'get_party_flag', 'get_adj_doc_exess_amt', 'citizenlogin', 'logout', 'setdoc_lang', 'update_sd', 'leaseandlicense', 'appointment', 'getarticlefield', 'genernal_info', 'genernalinfoentry', 'usagecategory_change_event', 'property_details', 'getattributeupdate', 'valuation_entry', 'party_entry', 'taluka_change_event', 'getusagevisibilitynew', 'rulechangeevent', 'getattributeparameter', 'add_property_attribute', 'witness', 'behavioral_patterns', 'article_change_event', 'check_appointmentdate', 'slot_alocation', 'get_time_difference', 'tatkalappoinment', 'tatkal_slot_alocation', 'check_maxappointmentday', 'getdependent_article', 'get_7_12_record', 'check_filevalidation', 'upload_document', 'article_mapping_screen', 'get_valuation_amt', 'getarticledepfeild', 'check_prohibited_prop', 'stamp_duty', 'check_config_prohibition', 'check_execution_date', 'party_address', 'get_instrument', 'get_fees_falc_ids', 'payment', 'get_payment_details', 'final_submit', 'check_land_record_fetching', 'check_7_12_compulsary', 'get_adj_doc_exess_amt_detail', 'is_uid_compulsary', 'is_identity_compulsary', 'identification', 'multiple_property_allowed', 'check_property_count', 'check_presenter', 'set_presenter', 'is_party_ekyc_auth_compusory', 'party_ekyc_authentication', 'is_party_ekyc_done', 'cancel_appointment', 'set_common_fields', 'validatesurveynumbers', 'taluka_change_event', 'district_change_event', 'get_title', 'set_token_session', 'delete_session', 'document_search', 'get_party_byname', 'payment_webservice', 'search_partyname', 'check_attribute_subpart', 'certificatesissuedetails', 'get_validation_rule', 'delete_party', 'get_party_feilds', 'get_record_old_party', 'advoate_feild_require_flag', 'delete_identifire', 'delete_witness');
        $this->Auth->allow('check_female_exemption', 'forgotpassword_citizen', 'welcomenote', 'citizenlogin', 'login', 'add', 'Disclaimer', 'index', 'index1', 'index2', 'registration', 'checkuser', 'viewsingle', 'ViewRegisteruser', 'get_district_name', 'get_captcha', 'aboutus', 'contactus', 'insertuser', 'checkorg', 'sponsordetail_pdf', 'checkcaptcha', 'checkemail', 'send_sms', 'empregistration', 'district_change_event');
        $laug = $this->Session->read("sess_langauge");

        if (is_null($laug)) {
            $this->Session->write("sess_langauge", 'en');
        }

        if (isset($this->Security)) { //&& isset($this->Auth)) {
            $this->Security->validatePost = false;
            $this->Security->enabled = false;
            $this->Security->csrfCheck = false;
        }
    }

    function consideration_amount_jh() {
        try {

            $this->loadModel('property_details_entry');
            $this->loadModel('fees_calculation');
            $this->loadModel('fees_calculation_detail');

            $lang = $this->Session->read("sess_langauge");
            $doc_lang = $this->Session->read('doc_lang');
            $user_id = $this->Session->read("citizen_user_id");
            $citizen_token_no = $this->Session->read('Selectedtoken');
            $last_status_id = $this->Session->read('last_status_id');
            $this->restrict_edit_after_submit($this->Session->read('Selectedtoken'));
            $article_id = $this->Session->read('article_id');
            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Kindly complete general info tab then proceed further");
                return $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
            }
            $conditions = array('property_details_entry.token_no' => $citizen_token_no);
            $conditions['property_details_entry.user_id'] = $user_id;
            $property_list = $this->property_details_entry->find('all', array(
                'fields' => array(' DISTINCT ON ("property_details_entry"."property_id") "property_details_entry"."property_id" ', 'property_details_entry.*', 'village.village_name_' . $lang, 'district.district_name_' . $lang, 'taluka.taluka_name_' . $lang, 'level1.level_1_desc_' . $lang, 'level1_list.list_1_desc_' . $lang, 'evalrule.evalrule_desc_' . $lang, 'valuation.rounded_val_amt'),
                'joins' => array(
                    array('table' => 'ngdrstab_conf_admblock7_village_mapping', 'alias' => 'village', 'conditions' => array('village.village_id=property_details_entry.village_id')),
                    array('table' => 'ngdrstab_conf_admblock3_district', 'type' => 'left', 'alias' => 'district', 'conditions' => array('district.district_id=property_details_entry.district_id')),
                    array('table' => 'ngdrstab_conf_admblock5_taluka', 'type' => 'left', 'alias' => 'taluka', 'conditions' => array('taluka.taluka_id=property_details_entry.taluka_id')),
                    array('table' => 'ngdrstab_mst_location_levels_1_property', 'type' => 'left', 'alias' => 'level1', 'conditions' => array('level1.level_1_id=property_details_entry.level1_id')),
                    array('table' => 'ngdrstab_mst_loc_level_1_prop_list', 'type' => 'left', 'alias' => 'level1_list', 'conditions' => array('level1_list.prop_level1_list_id=property_details_entry.level1_list_id')),
                    array('table' => 'ngdrstab_trn_valuation', 'type' => 'left', 'alias' => 'valuation', 'conditions' => array('valuation.val_id=property_details_entry.val_id')),
                    array('table' => 'ngdrstab_trn_valuation_details', 'type' => 'left', 'alias' => 'valuationd', 'conditions' => array('valuation.val_id=valuation.val_id')),
                    array('table' => 'ngdrstab_mst_evalrule_new', 'type' => 'left', 'alias' => 'evalrule', 'conditions' => array('evalrule.evalrule_id=valuationd.rule_id'))
                ),
                'conditions' => $conditions, 'order' => 'property_details_entry.property_id'
            ));
            $fee = $this->fees_calculation->find("all", array('conditions' => array('article_id' => $article_id, 'token_no' => $citizen_token_no, 'delete_flag' => 'N')));
            $this->set('fee', $fee);
            $this->set('property_list', $property_list);
            $property_pattern = $this->property_details_entry->get_property_pattern($doc_lang, $citizen_token_no, $user_id);
            $this->set('property_pattern', $property_pattern);
            $this->set('lang', $lang);


            foreach ($property_list as $key => $property) {
                $fieldlist['consideration_amount_' . $property['property_details_entry']['property_id']]['text'] = 'is_numeric';
            }
//pr($fieldlist);exit;
            $this->set("fieldlist", $fieldlist);
            $this->set('result_codes', $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                $data = $this->request->data['consideration_amount'];
                $this->check_csrf_token($data['csrftoken']);
                if ($this->ValidationError($this->validatedata($data, $fieldlist))) {
                    $this->property_details_entry->updateAll(
                            array('exchange_property_flag' => "'N'"), array('token_no' => $citizen_token_no)
                    );
                    $prop_arr = array();
                    foreach ($data as $key => $amount) {
                        $consarr = explode("_", $key);
                        if (count($consarr) == 3 and is_numeric($consarr[2])) {
                            $this->property_details_entry->updateAll(
                                    array('consideration_amount' => $amount), array('property_id' => $consarr[2], 'token_no' => $citizen_token_no)
                            );
                            foreach ($property_list as $key => $property) {
                                if ($property['property_details_entry']['property_id'] == $consarr[2]) {
                                    if ($property['valuation']['rounded_val_amt'] > $amount) {
                                        $prop_arr[$consarr[2]] = $property['valuation']['rounded_val_amt'];
                                    } else {
                                        $prop_arr[$consarr[2]] = $amount;
                                    }
                                }
                            }
                        }
                    }
// flag update
                    $cmpamt = 0;
                    $cmppropid = NULL;
                    foreach ($prop_arr as $pid => $amt) {
                        if ($amt > $cmpamt) {
                            $cmpamt = $amt;
                            $cmppropid = $pid;
                        }
                    }
                    if (!is_null($cmppropid)) {
                        $this->property_details_entry->updateAll(
                                array('exchange_property_flag' => "'Y'"), array('property_id' => $cmppropid, 'token_no' => $citizen_token_no)
                        );
                    }
                    $this->fees_calculation_detail->deleteAll(array('fee_calc_id' => $this->fees_calculation->find("list", array('fields' => 'fee_calc_id,fee_calc_id', 'conditions' => array('article_id' => array($article_id, 9998), 'token_no' => $citizen_token_no, 'delete_flag' => 'N')))));

                    $this->fees_calculation->deleteAll(array('article_id' => array($article_id, 9998), 'token_no' => $citizen_token_no, 'delete_flag' => 'N'));

                    $this->Session->setFlash("Record Saved Successfully!");
                    $this->redirect('consideration_amount_jh');
                }
            }
            $this->set_csrf_token();
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

///-------*--------*-*-------------------*-----------------------*----------Stamp Duty Related----------*--------------------*---------*---------------------
    public function stamp_duty_jh($csrftoken = NULL) {
        // load Model
        try {
            if ($this->referer() != '' && $this->referer() != '/') {
                if (strpos($this->referer(), $this->webroot) == false) {
                    header('Location:../cterror.html');
                    exit;
                }
            }

            $lang = $this->Session->read("sess_langauge");
            $doc_lang = $this->Session->read('doc_lang');
            $user_id = $this->Session->read("citizen_user_id");
            $citizen_token_no = $this->Session->read('Selectedtoken');
            $last_status_id = $this->Session->read('last_status_id');
            $this->restrict_edit_after_submit($this->Session->read('Selectedtoken'));
            
            if (!is_numeric($this->Session->read('Selectedtoken'))) {
                $this->Session->setFlash("Kindly complete general info tab then proceed further");
                return $this->redirect(array('controller' => 'Citizenentry', 'action' => 'genernalinfoentry', $this->Session->read('csrftoken')));
            }

            //load Model
            array_map(array($this, 'loadModel'), array('genernalinfoentry','female_exemption', 'fee_exemption', 'valuation_details', 'party_entry', 'investment_details', 'articledescdetails', 'article', 'article_screen_mapping', 'exemption_article_rules', 'property_details_entry', 'article_fee_rule', 'stamp_duty', 'stamp_duty_adjustment', 'conf_reg_bool_info', 'office', 'conf_article_feerule_items'));

            //-----------------checking Property Flag and property List---------------------------------            
            $property = $this->article_screen_mapping->query('select minorfun_id from ngdrstab_mst_article_screen_mapping where article_id=' . $this->Session->read("article_id") . ' and minorfun_id =2');
            $this->set('identificatontype', ClassRegistry::init('identificatontype')->find('list', array('fields' => array('identificationtype_id', 'identificationtype_desc_' . $doc_lang), 'order' => array('identificationtype_desc_' . $doc_lang => 'ASC'))));
            $property_list = $this->property_details_entry->get_property_list($doc_lang, $citizen_token_no, $user_id);
            if (count($property) > 0 && count($property_list) < 1) {
                $this->Session->setFlash("Kindly add Property ");
                return $this->redirect(array('controller' => 'Citizenentry', 'action' => 'property_details', $this->Session->read('csrftoken')));
            }

            $flag = $this->get_prop_same_usage_flag($citizen_token_no);

            $this->set('prop_flag', $flag);

            $regconf = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 102)));
            if (!empty($regconf)) {
                $this->set('gov_body_flag', $regconf['conf_reg_bool_info']['conf_bool_value']);
            }

            $regconfig = $this->conf_reg_bool_info->find('first', array('conditions' => array('reginfo_id' => 77)));
            if (!empty($regconfig)) {
                $this->set('area_flag', $regconfig['conf_reg_bool_info']['conf_bool_value']);
            }
            $echallan = $this->conf_reg_bool_info->field('conf_bool_value', array('reginfo_id' => 105));

            $result = $this->article_fee_rule->query("select * from ngdrstab_mst_article_fee_rule where common_rule_flag='Y'");
            $pageno = $this->genernalinfoentry->query("select no_of_pages from ngdrstab_trn_generalinformation where token_no=$citizen_token_no");
            foreach ($result as $result1):
                $sd_values = array(
                    'token_no' => $citizen_token_no,
                    'article_id' => 9999,
                    'fee_rule_id' => $result1[0]['fee_rule_id'],
                    'FAJ' => $pageno[0][0]['no_of_pages'], //valuation Amount
                    'FCX' => $this->number_of_pages($citizen_token_no),
                );

                $this->calculate_fees_jh($sd_values);
            endforeach;
            $this->autoRender = True;


//----------------------end of checking property list and property applicable-------------------------------------------------------
            //$user_id = $this->Auth->User("user_id");

            $total_buyer = $this->party_entry->find('all', array('conditions' => array('token_no' => $citizen_token_no), 'joins' => array(
                    array('table' => 'ngdrstab_mst_party_type', 'alias' => 'party_type', 'conditions' => array("party_type.party_type_id=party_entry.party_type_id and party_type_flag='0'"))
            )));
            $this->set('total_buyer', count($total_buyer));
            $total_female_buyer = $this->party_entry->find('all', array('conditions' => array('token_no' => $citizen_token_no, 'gender_id' => 2), 'joins' => array(
                    array('table' => 'ngdrstab_mst_party_type', 'alias' => 'party_type', 'conditions' => array("party_type.party_type_id=party_entry.party_type_id and party_type_flag='0'"))
            )));

            $this->set('total_female_buyer', count($total_female_buyer));

            $state_id = $this->Auth->User("state_id");
            $article_id = $this->Session->read('article_id');

            $formdata = $this->stamp_duty->find('first', array('conditions' => array('state_id' => $state_id, 'token_no' => $citizen_token_no)));
            $feeRuleList = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_' . $lang), 'conditions' => array('article_id' => $article_id)));
            if ($this->Session->read("article_id") == 32) {
                $property_list = $this->property_details_entry->get_property_list_32($doc_lang, $citizen_token_no, $user_id);
                if (empty($property_list)) {
                    $this->Session->setFlash("Kindly fill consideration amount");
                    return $this->redirect(array('action' => 'consideration_amount_jh', $this->Session->read('csrftoken')));
                }
            } else {
                $property_list = $this->property_details_entry->get_property_list($doc_lang, $citizen_token_no, $user_id);
            }
            $property_pattern = $this->property_details_entry->get_property_pattern($doc_lang, $citizen_token_no, $user_id);
            $exemption_flag = $this->article->field('exemption_applicable', array('article_id' => $article_id));
            $investor_flag = $this->article->field('investor_clouse', array('article_id' => $article_id));
            $office_id1 = ClassRegistry::init('genernalinfoentry')->field('office_id', array('token_no' => $this->Session->read("Selectedtoken")));

            $office = ClassRegistry::init('office')->find('list', array('fields' => array('office_id', 'office_name_' . $lang), 'conditions' => array('office_id' => $office_id1), 'order' => array('office_name_' . $lang => 'ASC')));
            $sd_flags = $this->conf_reg_bool_info->find('list', array('fields' => array('reginfo_id', 'conf_bool_value'), 'conditions' => array('state_id' => $state_id)));

            //check for document title adjustmentdocument_title_flags
            $adjustmentdocument_title_flags = $this->articledescdetails->query('select articledescription_id ,articledescription_en,adjustment_flag from ngdrstab_mst_articledescriptiondetail where article_id=? and adjustment_flag=?', array($article_id, 'Y'));
            if (!empty($adjustmentdocument_title_flags)) {
                $adjustmentdocument_flags = $adjustmentdocument_title_flags[0][0]['adjustment_flag'];
            } else {
                $adjustmentdocument_flags = NULL;
            }


            $delay_flag = $sd_flags[6];
            $sd_adj_flag = $sd_flags[8];
            $exemption_rule = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_' . $doc_lang), 'conditions' => array('fee_rule_id' => $this->exemption_article_rules->find('list', array('fields' => array('fee_rule_id'), 'conditions' => array('article_id' => $article_id)))))); //get only Exemption Rules(9998)

            $this->set(compact('lang', 'article_id', 'investor_flag', 'feeRuleList', 'exemption_flag', 'exemption_rule', 'citizen_token_no', 'article_rule', 'property_pattern', 'property_list', 'sd_adj_flag', 'adjustmentdocument_flags', 'delay_flag', 'office'));

            //validations kalyani
            $this->loadModel('NGDRSErrorCode');
            $result_codes = $this->NGDRSErrorCode->find("all");
            $this->set('result_codes', $result_codes);
            $name_format = $this->get_name_format_jh();
            $this->set('name_format', $name_format);
            $laug = $this->Session->read("sess_langauge");
            $this->set('laug', $laug);

            $fieldlist = array();

            $fieldlist = $this->conf_article_feerule_items->stampdutyfields($laug);
            $fieldlist['cons_amt']['text'] = 'is_required,is_numeric';
            // pr($fieldlist);
            $this->set('fieldlist', $fieldlist);
            $this->set('result_codes', $a = $this->getvalidationruleset($fieldlist));

            if ($this->request->is('post')) {
                //  $this->check_csrf_token($this->request->data['frm']['csrftoken']);
                $data = array('online_adj_doc_no' => $this->request->data['frm']['online_adj_doc_no'],
                    'online_adj_doc_date' => $this->request->data['frm']['online_adj_doc_date'],
                    'online_adj_amt' => $this->request->data['frm']['online_adj_amt']);

                //-------------------------------Date 01-March-2017 by shridhar for Stamp Duty Adjustment---------------------------------------------------------
                $frm = $this->request->data['frm'];


                //////////  web service reg fee and mutation fee pdf - devashree  ///////
                if (!isset($echallan)) {
                    if ($echallan == 'Y') {
                        $partydetail = $this->party_entry->find("all", array('conditions' => array('token_no' => $citizen_token_no, 'is_presenter' => 'Y')));

                        $pname = $partydetail[0]['party_entry']['party_fname_en'] . ' ' . $partydetail[0]['party_entry']['party_mname_en'] . ' ' . $partydetail[0]['party_entry']['party_lname_en'];
                        $pmobile = $partydetail[0]['party_entry']['mobile_no'];
                        $distid = $partydetail[0]['party_entry']['district_id'];
                        $talukaid = $partydetail[0]['party_entry']['taluka_id'];

                        $address_en = $partydetail[0]['party_entry']['address_en'];
                        $email_id = $partydetail[0]['party_entry']['email_id'];

                        $talukaf = $this->taluka->find("all", array('conditions' => array('district_id' => $distid, 'taluka_id' => $talukaid)));

                        $talukanm = $talukaf[0]['taluka']['taluka_name_en'];
                        $feetot = $this->request->data['frm']['total'];


                        if ($address_en != '' && $email_id != '') {
                            $this->getregfee_pdf($feetot, $pmobile, $pname, $talukanm);
                            $this->getmutationfee_pdf($feetot, $pmobile, $pname, $talukanm);
                        }
                    }
                }
                /////////////////////////////////////////////////////////
                //print_r($exemption_id); exit;
                $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 88)));
                if (!empty($regconf)) {
                    if ($regconf[0]['conf_reg_bool_info']['info_value'] == 'Y') {
                        if (isset($frm['exemption_fee_rule_id'])) {
                            $exemption_id = $frm['exemption_fee_rule_id'];

                            if ($exemption_id == 197) {
                                $frm['online_adj_doc_date'] = ($frm['online_adj_doc_date']) ? date('Y-m-d', strtotime($frm['online_adj_doc_date'])) : NULL; //31-May-2017
                                $this->check_female_exemption_jh($frm);
                                if ($frm['online_adj_doc_no'] && $frm['online_adj_doc_date'] && $frm['old_data_flag'] === 'Y') {
                                    $adjustable_amount = $this->get_adj_doc_exess_amt_jh($frm['online_adj_doc_no'], $frm['online_adj_doc_date']);
                                    if ($frm['online_adj_amt'] < $adjustable_amount) {
                                        $sd_update_result = $this->check_female_exemption_jh($frm);
                                    } else {
                                        $sd_update_result = 0;
                                        $this->Session->setFlash('Adustment amount shound not be greater than ' . $adjustable_amount);
                                    }
                                } else {
//                        $this->stamp_duty_adjustment->id = $citizen_token_no;
//                        $this->stamp_duty_adjustment->saveField('old_data_flag', $frm['old_data_flag']);
                                    $frm['old_data_flag'] = "'" . $frm['old_data_flag'] . "'";
                                    $this->stamp_duty_adjustment->updateAll(
                                            array('old_data_flag' => $frm['old_data_flag']), array('token_no' => $citizen_token_no)
                                    );
                                    $sd_update_result = $this->check_female_exemption_jh($frm);
                                }
                            } else {
                                $frm['online_adj_doc_date'] = ($frm['online_adj_doc_date']) ? date('Y-m-d', strtotime($frm['online_adj_doc_date'])) : NULL; //31-May-2017
                                $sd_update_result = 1;

                                $this->update_sd_jh($frm);
                                if ($frm['online_adj_doc_no'] && $frm['online_adj_doc_date'] && $frm['old_data_flag'] === 'Y') {
                                    $adjustable_amount = $this->get_adj_doc_exess_amt_jh($frm['online_adj_doc_no'], $frm['online_adj_doc_date']);
                                    if ($frm['online_adj_amt'] < $adjustable_amount) {
                                        $sd_update_result = $this->update_sd_jh($frm);
                                    } else {
                                        $sd_update_result = 0;
                                        $this->Session->setFlash('Adustment amount shound not be greater than ' . $adjustable_amount);
                                    }
                                } else {
//                        $this->stamp_duty_adjustment->id = $citizen_token_no;
//                        $this->stamp_duty_adjustment->saveField('old_data_flag', $frm['old_data_flag']);
                                    $frm['old_data_flag'] = "'" . $frm['old_data_flag'] . "'";
                                    $this->stamp_duty_adjustment->updateAll(
                                            array('old_data_flag' => $frm['old_data_flag']), array('token_no' => $citizen_token_no)
                                    );
                                    $sd_update_result = $this->update_sd_jh($frm);
                                }
                            }
                        } else {
                            $frm['online_adj_doc_date'] = ($frm['online_adj_doc_date']) ? date('Y-m-d', strtotime($frm['online_adj_doc_date'])) : NULL; //31-May-2017
                            $sd_update_result = 1;

                            $this->update_sd_jh($frm);
                            if ($frm['online_adj_doc_no'] && $frm['online_adj_doc_date'] && $frm['old_data_flag'] === 'Y') {
                                $adjustable_amount = $this->get_adj_doc_exess_amt_jh($frm['online_adj_doc_no'], $frm['online_adj_doc_date']);
                                if ($frm['online_adj_amt'] < $adjustable_amount) {
                                    $sd_update_result = $this->update_sd_jh($frm);
                                } else {
                                    $sd_update_result = 0;
                                    $this->Session->setFlash('Adustment amount shound not be greater than ' . $adjustable_amount);
                                }
                            } else {
//                        $this->stamp_duty_adjustment->id = $citizen_token_no;
//                        $this->stamp_duty_adjustment->saveField('old_data_flag', $frm['old_data_flag']);
                                $frm['old_data_flag'] = "'" . $frm['old_data_flag'] . "'";
                                $this->stamp_duty_adjustment->updateAll(
                                        array('old_data_flag' => $frm['old_data_flag']), array('token_no' => $citizen_token_no)
                                );
                                $sd_update_result = $this->update_sd_jh($frm);
                            }
                        }
                    } else {
                        $frm['online_adj_doc_date'] = ($frm['online_adj_doc_date']) ? date('Y-m-d', strtotime($frm['online_adj_doc_date'])) : NULL; //31-May-2017
                        $sd_update_result = 1;

                        $this->update_sd_jh($frm);
                        if ($frm['online_adj_doc_no'] && $frm['online_adj_doc_date'] && $frm['old_data_flag'] === 'Y') {
                            $adjustable_amount = $this->get_adj_doc_exess_amt_jh($frm['online_adj_doc_no'], $frm['online_adj_doc_date']);
                            if ($frm['online_adj_amt'] < $adjustable_amount) {
                                $sd_update_result = $this->update_sd_jh($frm);
                            } else {
                                $sd_update_result = 0;
                                $this->Session->setFlash('Adustment amount shound not be greater than ' . $adjustable_amount);
                            }
                        } else {
//                        $this->stamp_duty_adjustment->id = $citizen_token_no;
//                        $this->stamp_duty_adjustment->saveField('old_data_flag', $frm['old_data_flag']);
                            $frm['old_data_flag'] = "'" . $frm['old_data_flag'] . "'";
                            $this->stamp_duty_adjustment->updateAll(
                                    array('old_data_flag' => $frm['old_data_flag']), array('token_no' => $citizen_token_no)
                            );
                            $sd_update_result = $this->update_sd_jh($frm);
                        }
                    }
                }
//investment details

                if ($investor_flag == 'Y') {
                    $frm['online_invest_doc_date'] = ($frm['online_invest_doc_date']) ? date('Y-m-d', strtotime($frm['online_invest_doc_date'])) : NULL;
                    $this->request->data['frm']['invest_stamp_amount'] = ($this->request->data['frm']['invest_stamp_amount']) ? $this->request->data['frm']['invest_stamp_amount'] : 0;
                    $this->investment_details->deleteAll(array('token_no' => $this->Session->read('Selectedtoken')));

                    $invest = array('online_invest_doc_no' => $frm['online_invest_doc_no'],
                        'online_invest_doc_date' => $frm['online_invest_doc_date'],
                        'invest_stamp_amount' => $this->request->data['frm']['invest_stamp_amount'],
                        'token_no' => $this->Session->read('Selectedtoken'),
                        'state_id' => $this->Auth->User("state_id"),
                        'req_ip' => $_SERVER['REMOTE_ADDR'],
                        'user_type' => $this->Session->read("session_usertype")
                    );

                    $this->investment_details->save($invest);
                }
                $this->set_csrf_token();
//------------------------------------------------------------------------------------------------------------------------------------------------
                if ($sd_update_result == 1) {
                    $this->Session->setFlash('Record Saved Successfully ');

                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                } else if ($sd_update_result == 0) {
                    $this->fee_exemption->query('delete  from ngdrstab_trn_fee_exemption where token_no=?', array($this->Session->read('Selectedtoken')));
                    $this->fee_exemption->query('delete  from ngdrstab_trn_fee_calculation where token_no=? and article_id=?', array($this->Session->read('Selectedtoken'), 9998));
                    $this->female_exemption->query('delete  from ngdrstab_trn_female_exemption where token_no=?', array($this->Session->read('Selectedtoken')));

                    $this->Session->setFlash('!SD update Failed. Female Exemption can be applicable only for Female Party');
                    $this->redirect(array('action' => 'stamp_duty_jh', $this->Session->read('csrftoken')));
                } else {
                    $this->Session->setFlash('!No proper Input');
                    $this->redirect(array('action' => 'stamp_duty_jh', $this->Session->read('csrftoken')));
                }
//                }
            } else {
                $this->check_csrf_token_withoutset($csrftoken);
                if ($formdata) {
                    $this->stamp_duty->id = $formdata['stamp_duty']['token_no'];
                    $dataarray = $this->stamp_duty->read();
                    $this->request->data['frm'] = $dataarray['stamp_duty'];

                    $invstdata = $this->investment_details->find('first', array('conditions' => array('token_no' => $this->Session->read('Selectedtoken'))));

                    if (!empty($invstdata)) {
                        $this->request->data['frm']['online_invest_doc_date'] = date('d-m-Y', strtotime($invstdata['investment_details']['online_invest_doc_date']));
                        $this->request->data['frm']['invest_stamp_amount'] = $invstdata['investment_details']['invest_stamp_amount'];
                        $this->request->data['frm']['online_invest_doc_no'] = $invstdata['investment_details']['online_invest_doc_no'];
                    }
                }
            }
        } catch (Exception $ex) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        //$this->autoRender = TRUE;
    }

    function check_female_exemption_jh($formData = NULL) {
        try {
            $this->autoRender = FALSE;
            $formData = (isset($this->request->data['frm'])) ? $this->request->data['frm'] : $formData;
            array_map(array($this, 'loadModel'), array('fee_exemption', 'ApplicationSubmitted', 'article_fee_rule', 'female_exemption', 'stamp_duty', 'stamp_duty_adjustment', 'party_entry'));
            $formData['user_id'] = $this->Session->read("citizen_user_id");
            $token = $formData['token_no'];
            $exemption_amt = $formData['exemption_amt'];
            $user_id = $formData['user_id'];
            $exemption_rule_id = $formData['exemption_fee_rule_id'];
            // print_r($formData); exit;
            $formData['state_id'] = $this->Auth->User("state_id");
            $formData['req_ip'] = $_SERVER['REMOTE_ADDR'];
            //$formData['user_id'] = $_SERVER['REMOTE_ADDR'];
            //  $formData['created_date'] = date('Y-m-d H:i:s');
            $formData['user_id'] = $this->Session->read("citizen_user_id");
            $formData['online_final_amt'] = ($formData['online_adj_amt']) ? ($formData['online_sd_amt'] - $formData['online_adj_amt']) : $formData['online_sd_amt'];
            $formData['online_final_amt'] += ($formData['late_fee']) ? $formData['late_fee'] : 0; // add alte fee if applicable
            $formData['final_amt'] = $formData['online_final_amt'] + $formData['counter_sd_amt'];

            $formData['online_adj_doc_date'] = ($formData['online_adj_doc_date']) ? date('Y-m-d', strtotime($formData['online_adj_doc_date'])) : NULL;
            $formData['recalculate_flag'] = 'N';

            if ($formData['exemption_fee_rule_id'] == 197) {
                $party_uid = $this->party_entry->query("select uid from ngdrstab_trn_party_entry_new p,
                ngdrstab_mst_party_type pt 
                where p.gender_id=2 and pt.party_type_flag='0' and p.party_type_id=pt.party_type_id and token_no=$token");

                if (!empty($party_uid)) {
                    $uid = $party_uid[0][0]['uid'];
                    //$uid_decrypt=$this->enc($uid); $uid_decrypt=$this->dec($uid); 
                    $this->set_csrf_token();

                    $uid_check = $this->female_exemption->query("select uid,old_doc_flag,token_no,office_name from ngdrstab_trn_female_exemption where uid='$uid'");

                    if (empty($uid_check)) {
                        $uid_insert = $this->female_exemption->query("insert into ngdrstab_trn_female_exemption(token_no,uid,exemption_type,exemption_amt,date,user_id,created_date) values($token,'$uid','Female Exemption',$exemption_amt,now(),$user_id,now())");

                        if ($this->stamp_duty->save($formData)) {
                            if ($formData['online_adj_doc_date'] && $formData['online_adj_doc_date']) {
                                if ($this->stamp_duty_adjustment->save($formData)) {
                                    $this->Session->setFlash('Record Saved Successfully ');

                                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                                } else {
                                    $this->Session->setFlash('!SD update Failed');
                                    $this->redirect(array('action' => 'stamp_duty_jh', $this->Session->read('csrftoken')));
                                }
                            }
                            $this->Session->setFlash('Record Saved Successfully ');

                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                        }
                    } else {
                        $old_doc_flag = $uid_check[0][0]['old_doc_flag'];
                        $old_office_name = $uid_check[0][0]['office_name'];
                        $existing_token_no = $uid_check[0][0]['token_no'];
                        $token_no = $this->Session->read('Selectedtoken');

                        if ($old_doc_flag == 'Y') {
                            $this->fee_exemption->query('delete  from ngdrstab_trn_fee_exemption where token_no=?', array($this->Session->read('Selectedtoken')));
                            $this->fee_exemption->query('delete  from ngdrstab_trn_fee_calculation where token_no=? and article_id=?', array($this->Session->read('Selectedtoken'), 9998));

                            $this->Session->setFlash('Female Exemption Already taken in Old Document for ' . $old_office_name . ' office');
                            $this->redirect(array('action' => 'stamp_duty_jh', $this->Session->read('csrftoken')));
                        } else {
                            if ($existing_token_no == $token_no) {
                                $final_stamp_result = $this->ApplicationSubmitted->query("select final_stamp_flag,stamp2_flag from ngdrstab_trn_application_submitted where token_no='$token_no'");
                                if (empty($final_stamp_result)) {
                                    if ($this->stamp_duty->save($formData)) {
                                        if ($formData['online_adj_doc_date'] && $formData['online_adj_doc_date']) {
                                            if ($this->stamp_duty_adjustment->save($formData)) {
                                                $this->Session->setFlash('Record Saved Successfully ');

                                                $this->redirect(array('controller' => 'Citizenentry', 'action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                                            } else {
                                                $this->Session->setFlash('!SD update Failed4');
                                                $this->redirect(array('action' => 'stamp_duty_jh', $this->Session->read('csrftoken')));
                                            }
                                        }
                                        $this->Session->setFlash('Record Saved Successfully ');

                                        $this->redirect(array('controller' => 'Citizenentry', 'action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                                    }
                                } else {
                                    $final_stamp_flag = $final_stamp_result[0][0]['final_stamp_flag'];
                                    $stamp2_flag = $final_stamp_result[0][0]['stamp2_flag'];
                                    if ($final_stamp_flag == 'N' && $stamp2_flag == 'N') {
                                        $update_female_exm = $this->female_exemption->query("update ngdrstab_trn_female_exemption set exemption_amt=$exemption_amt where token_no=$token_no");
                                        if ($this->stamp_duty->save($formData)) {
                                            if ($formData['online_adj_doc_date'] && $formData['online_adj_doc_date']) {
                                                if ($this->stamp_duty_adjustment->save($formData)) {
                                                    $this->Session->setFlash('Record Saved Successfully ');

                                                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                                                } else {
                                                    $this->Session->setFlash('!SD update Failed4');
                                                    $this->redirect(array('action' => 'stamp_duty_jh', $this->Session->read('csrftoken')));
                                                }
                                            }
                                            $this->Session->setFlash('Record Saved Successfully ');

                                            $this->redirect(array('controller' => 'Citizenentry', 'action' => 'pre_registration_docket', $this->Session->read('csrftoken')));
                                        }
                                    } else {
                                        $this->fee_exemption->query('delete  from ngdrstab_trn_fee_exemption where token_no=?', array($this->Session->read('Selectedtoken')));
                                        $this->fee_exemption->query('delete  from ngdrstab_trn_fee_calculation where token_no=? and article_id=?', array($this->Session->read('Selectedtoken'), 9998));

                                        $this->Session->setFlash('Female Exemption Already taken for ' . $existing_token_no . ' this Token No.');
                                        $this->redirect(array('action' => 'stamp_duty_jh', $this->Session->read('csrftoken')));
                                    }
                                }
                            } else {
                                $this->fee_exemption->query('delete  from ngdrstab_trn_fee_exemption where token_no=?', array($this->Session->read('Selectedtoken')));
                                $this->fee_exemption->query('delete  from ngdrstab_trn_fee_calculation where token_no=? and article_id=?', array($this->Session->read('Selectedtoken'), 9998));

                                $this->Session->setFlash('Female Exemption Already taken for ' . $existing_token_no . ' this Token No.');
                                $this->redirect(array('action' => 'stamp_duty_jh', $this->Session->read('csrftoken')));
                            }
                        }
                    }
                }
            }
        } catch (Exception $ex) {

            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //-------*---------------------------------------------------------------------------------------------------------------------------------------------------
    public function update_sd_jh($formData = NULL) {
        try {
            $this->autoRender = FALSE;

            $formData = (isset($this->request->data['frm'])) ? $this->request->data['frm'] : $formData;
            array_map(array($this, 'loadModel'), array('stamp_duty', 'stamp_duty_adjustment'));
            $formData['state_id'] = $this->Auth->User("state_id");
            $formData['req_ip'] = $_SERVER['REMOTE_ADDR'];
            //$formData['user_id'] = $_SERVER['REMOTE_ADDR'];
            //  $formData['created_date'] = date('Y-m-d H:i:s');
            $formData['user_id'] = $this->Session->read("citizen_user_id");
            $formData['online_final_amt'] = ($formData['online_adj_amt']) ? ($formData['online_sd_amt'] - $formData['online_adj_amt']) : $formData['online_sd_amt'];
            $formData['online_final_amt'] += ($formData['late_fee']) ? $formData['late_fee'] : 0; // add alte fee if applicable
            $formData['final_amt'] = $formData['online_final_amt'] + $formData['counter_sd_amt'];

            $formData['online_adj_doc_date'] = ($formData['online_adj_doc_date']) ? date('Y-m-d', strtotime($formData['online_adj_doc_date'])) : NULL;
            $formData['recalculate_flag'] = 'N';
            if ($this->Session->read("session_usertype") == 'O') {
                unset($formData['user_id']);

                if ($this->Auth->user('office_id')) {

                    $formData['org_user_id'] = $this->Auth->User('user_id');


                    $formData['org_updated'] = date('Y-m-d H:i:s');

                    $formData['org_created'] = date('Y-m-d H:i:s');
                }
            }

            if ($this->stamp_duty->save($formData)) {
                if ($formData['online_adj_doc_date'] && $formData['online_adj_doc_date']) {
                    if ($this->stamp_duty_adjustment->save($formData)) {
                        return 1;
                    } else {
                        return 0;
                    }
                }
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
        exit;
    }

    //-----------------------------1 March 2017 Code Update for get---get_adj_doc_exess_amt(),get_adj_doc_exess_amt_detail(),get_fees_falc_ids()  by Shridhar-------------------------------------------------------------------------------------------------
    function get_adj_doc_exess_amt_jh($adj_doc_no = NULL, $adj_doc_date = NULL) {
        try {

            $this->autoRender = FALSE;
            $adj_doc_no = (isset($_POST['adj_doc_no'])) ? $_POST['adj_doc_no'] : $adj_doc_no;
            $adj_doc_date = (isset($_POST['adj_doc_no'])) ? $_POST['adj_doc_date'] : $adj_doc_date;
            $doc_token_no = $this->Session->read('Selectedtoken');
            if ($adj_doc_no && $adj_doc_date) {
                array_map(array($this, 'loadModel'), array('ApplicationSubmitted', 'stamp_duty_adjustment'));
                $adj_doc_date = date('Y-m-d', strtotime(str_replace('/', '-', $adj_doc_date)));
                $exsAmount = $this->ApplicationSubmitted->find('first', array('fields' => array('amt_paid - amt_to_be_paid AS diff_amt'), 'conditions' => array('doc_reg_no' => $adj_doc_no, 'DATE(doc_reg_date)' => $adj_doc_date)));
                $onlineAdjAmount = $this->stamp_duty_adjustment->find('first', array('fields' => array('SUM(online_adj_amt) as online_adj_amt'), 'conditions' => array('online_adj_doc_no' => $adj_doc_no, 'DATE(online_adj_doc_date)' => $adj_doc_date, 'token_no !=' => $doc_token_no, 'online_adj_amt IS NOT NULL')));
                $counterAdjAmount = $this->stamp_duty_adjustment->find('first', array('fields' => array('SUM(counter_adj_amt) as counter_adj_amt'), 'conditions' => array('counter_adj_doc_no' => $adj_doc_no, 'DATE(counter_adj_doc_date)' => $adj_doc_date, 'token_no !=' => $doc_token_no, 'counter_adj_amt IS NOT NULL')));
                $adjAmount = $onlineAdjAmount['0']['online_adj_amt'] + $counterAdjAmount['0']['counter_adj_amt'];
                return ($exsAmount) ? ($adjAmount ? ($exsAmount[0]['diff_amt'] - $adjAmount) : $exsAmount[0]['diff_amt']) : 0;
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_adj_doc_exess_amt_detail_jh($adj_doc_no = NULL, $adj_doc_date = NULL) {
        try {
//            $this->autoRender = TRUE;
            $adj_doc_no = (isset($_POST['adj_doc_no'])) ? $_POST['adj_doc_no'] : $adj_doc_no;
            $adj_doc_date = (isset($_POST['adj_doc_date'])) ? $_POST['adj_doc_date'] : $adj_doc_date;
            $doc_token_no = $this->Session->read('Selectedtoken');
            if ($adj_doc_no && $adj_doc_date) {
                array_map(array($this, 'loadModel'), array('ApplicationSubmitted', 'stamp_duty_adjustment'));
                $adj_doc_date = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['adj_doc_date'])));
                $doc_payment = $this->ApplicationSubmitted->find('first', array('fields' => array('amt_to_be_paid', 'amt_paid', 'amt_paid - amt_to_be_paid AS diff_amt'), 'conditions' => array('doc_reg_no' => $adj_doc_no, 'DATE(doc_reg_date)' => $adj_doc_date)));
                $exsAmount = $this->ApplicationSubmitted->find('first', array('fields' => array('amt_paid - amt_to_be_paid AS diff_amt'), 'conditions' => array('doc_reg_no' => $_POST['adj_doc_no'], 'DATE(doc_reg_date)' => $adj_doc_date)));
                $onlineAdjAmount = $this->stamp_duty_adjustment->find('first', array('fields' => array('SUM(online_adj_amt) as online_adj_amt'), 'conditions' => array('online_adj_doc_no' => $adj_doc_no, 'DATE(online_adj_doc_date)' => $adj_doc_date, 'token_no !=' => $doc_token_no, 'online_adj_amt IS NOT NULL')));
                $counterAdjAmount = $this->stamp_duty_adjustment->find('first', array('fields' => array('SUM(counter_adj_amt) as counter_adj_amt'), 'conditions' => array('counter_adj_doc_no' => $adj_doc_no, 'DATE(counter_adj_doc_date)' => $adj_doc_date, 'token_no !=' => $doc_token_no, 'counter_adj_amt IS NOT NULL')));
                $adjustedAmount = $onlineAdjAmount['0']['online_adj_amt'] + $counterAdjAmount['0']['counter_adj_amt'];
                $adj_detail = $this->stamp_duty_adjustment->find('all', array('fields' => array('token_no', 'online_adj_amt', 'counter_adj_amt'),
                    'conditions' => array(
                        'AND' => array(
                            'OR' => array('online_adj_doc_no' => $adj_doc_no, 'counter_adj_doc_no' => $adj_doc_no),
                            'OR' => array('DATE(counter_adj_doc_date)' => $adj_doc_date, 'DATE(online_adj_doc_date)' => $adj_doc_date)
                        ),
                        'token_no !=' => $doc_token_no
                    )
                ));
            } else {
                $doc_payment = $adj_detail = NULL;
            }
            $this->set(compact('doc_payment', 'adj_detail', 'adjustedAmount'));
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    //-------------------------------------------------Code by Shridhar for Getting property_id which SD is not calculated------------------
    function get_fees_falc_ids_jh() {
        try {
            $this->autoRender = FALSE;
            if ($this->request->data['doc_token_no']) {
                $doc_token_no = $this->request->data['doc_token_no'];
                $user_id = $this->Session->read("citizen_user_id");
                $article_id = $this->Session->read("article_id");
                if ($article_id == 32) {
                    $property_ids = ClassRegistry::init('property_details_entry')->find('list', array('fields' => array('property_id'), 'conditions' => array('token_no' => $doc_token_no, 'fee_calc_id is NULL', 'val_id !=' => 0, 'exchange_property_flag' => 'Y')));
                    return json_encode($property_ids);
                } else if (is_numeric($user_id) && is_numeric($doc_token_no)) {
                    $property_ids = ClassRegistry::init('property_details_entry')->find('list', array('fields' => array('property_id'), 'conditions' => array('token_no' => $doc_token_no, 'fee_calc_id is NULL', 'val_id !=' => 0)));
                    return json_encode($property_ids);
                } else {
                    return 'return wrong input';
                }
            } else {
                return 'wrong input';
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

//-------------*----------------*--------------*---------------------*-------------*---------------*------------*----------------*---------------------------------------------------------

    function delete_lease_jh($id, $tokenval) {

        try {
            if ($id != NULL) {
                $this->leaseandlicense->id = $id;
                if ($this->leaseandlicense->delete()) {
                    $this->Session->setFlash(__('Record Deleted Successfully'));
                    $this->redirect(array('controller' => 'Citizenentry', 'action' => 'leaseandlicense', $tokenval));
                } else {
                    $this->Session->setFlash(__('Record Not Deleted'));
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_valuation_amt_jh($val_id = NULL) {
        try {
            if (isset($_POST['csrftoken'])) {
                $this->check_csrf_token_withoutset($_POST['csrftoken']);
            }
            $this->autoRender = FALSE;
            $valuation_id = (isset($_POST['val_id'])) ? $_POST['val_id'] : $val_id;
            if ($valuation_id) {
                $this->loadModel('valuation_details');
                $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                $record = $this->valuation_details->find('first', array('fields' => array('rounded_val_amt'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                if (!empty($record)) {
                    return $record['valuation_details']['rounded_val_amt'];
                } else {
                    return '0';
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_valuation_amt_sd_old_jh($val_id = NULL, $property_id = NULL) {
        try {
            if (isset($_POST['csrftoken'])) {
                $this->check_csrf_token_withoutset($_POST['csrftoken']);
            }
            $this->autoRender = FALSE;
            $this->loadModel('valuation_details');
            $this->loadModel('property_details_entry');
            $this->loadModel('party_entry');
            $token = $this->Session->read('Selectedtoken');

            //Sonam code for add valuation amount of same rule_id-------------------------------
            $tokenno = $this->property_details_entry->find('all', array('fields' => array('val_id'), 'conditions' => array('token_no' => $token)));
            $data['val'] = 0;
            $data['cons'] = 0;
            $rule_new = array();
            foreach ($tokenno as $tokenid) {
                $valuation_id = $tokenid['property_details_entry']['val_id'];
                $rule_id = $this->valuation_details->find('first', array('fields' => array('rule_id'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));
                foreach ($rule_id as $rule) {
                    $ruleid = $rule['rule_id'];
                    array_push($rule_new, $ruleid);
                }
            }
            $flag = 0;
            $count = count($rule_new);
            if ($count == 1) {
                $flag = 1;
            } else {
                foreach ($rule_new as $ruleid_new) {
                    if ($ruleid_new != $rule_new[0]) {
                        $flag = 1;
                    }
                }
            }
            //----------------------------------------------------------------------------------------------------------------------------

            if ($flag == 0) {
                $token = $this->Session->read('Selectedtoken');
                $tokenno = $this->property_details_entry->find('all', array('fields' => array('val_id'), 'conditions' => array('token_no' => $token)));

                foreach ($tokenno as $tokenid) {
                    $valuation_id = $tokenid['property_details_entry']['val_id'];

                    $rule_id = $this->valuation_details->find('first', array('fields' => array('rule_id'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                    foreach ($rule_id as $rule) {
                        $ruleid = $rule['rule_id'];
                        array_push($rule_new, $ruleid);
                    }

                    if ($valuation_id) {



                        $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                        $record = $this->valuation_details->find('first', array('fields' => array('rounded_val_amt'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                        if (!empty($record)) {
                            $data['val'] += $record['valuation_details']['rounded_val_amt'];

                            $property = $this->property_details_entry->find("first", array('conditions' => array('val_id' => $valuation_id, 'token_no' => $token)));
                            if (!empty($property)) {
                                $data['cons'] = $property['property_details_entry']['consideration_amount'];
                            }

                            $prop_id = $this->property_details_entry->find('first', array('fields' => array('property_id'), 'conditions' => array('token_no' => $token)));
                            $property_id = $prop_id['property_details_entry']['property_id'];
                        }
                    }
                }
            } else {
                $valuation_id = (isset($_POST['val_id'])) ? $_POST['val_id'] : $val_id;
                $data['val'] = 0;
                $data['cons'] = 0;
                $data['party'] = 0;
                $property_id = $_POST['property_id'];
                if ($valuation_id) {



                    $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                    $record = $this->valuation_details->find('first', array('fields' => array('rounded_val_amt'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                    if (!empty($record)) {
                        $data['val'] = $record['valuation_details']['rounded_val_amt'];

                        $property = $this->property_details_entry->find("first", array('conditions' => array('val_id' => $valuation_id, 'token_no' => $token)));
                        if (!empty($property)) {
                            $data['cons'] = $property['property_details_entry']['consideration_amount'];
                        }


                        $party = $this->party_entry->query("select count(party_id) from ngdrstab_trn_party_entry_new where token_no=$token and property_id=$property_id");
                        if (!empty($party)) {
                            $data['party'] = $party[0][0]['count'];
                        }


                        return json_encode($data);
                        exit;
                    } else {
                        return '0';
                    }
                }
            }
            return json_encode($data);
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_valuation_amt_sd_jh($val_id = NULL, $property_id = NULL) {
        try {
            if (isset($_POST['csrftoken'])) {
                $this->check_csrf_token_withoutset($_POST['csrftoken']);
            }
            $this->autoRender = FALSE;
            $this->loadModel('valuation_details');
            $this->loadModel('property_details_entry');
            $this->loadModel('party_entry');
            $this->loadModel('conf_reg_bool_info');
            $token = $this->Session->read('Selectedtoken');

            //Sonam code for add valuation amount of same rule_id-------------------------------

            $flag = $this->get_prop_same_usage_flag($token);

            if ($flag == 0) {
                $token = $this->Session->read('Selectedtoken');
                $tokenno = $this->property_details_entry->find('all', array('fields' => array('val_id'), 'conditions' => array('token_no' => $token)));
                $data['val'] = 0;
                $data['cons'] = 0;

                foreach ($tokenno as $tokenid) {
                    $valuation_id = $tokenid['property_details_entry']['val_id'];

                    if ($valuation_id) {

                        $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                        $record = $this->valuation_details->find('all', array('fields' => array('final_value'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                        if (!empty($record)) {
                            foreach ($record as $record_result) {
                                $data['val'] += $record_result['valuation_details']['final_value'];
                            }
                            $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 46)));
                            $roundto = '';
                            if (!empty($regconf)) {
                                if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                    if (is_numeric($regconf[0]['conf_reg_bool_info']['info_value']) && $regconf[0]['conf_reg_bool_info']['info_value'] > 0)
                                        $roundto = $regconf[0]['conf_reg_bool_info']['info_value'];
                                }
                            }
                            $data['val'] = $this->round_tonext($data['val'], $roundto);

                            $property = $this->property_details_entry->find("first", array('conditions' => array('val_id' => $valuation_id, 'token_no' => $token)));
                            if (!empty($property)) {
                                $data['cons'] = $property['property_details_entry']['consideration_amount'];
                            }

                            $prop_id = $this->property_details_entry->find('first', array('fields' => array('property_id'), 'conditions' => array('token_no' => $token)));
                            $property_id = $prop_id['property_details_entry']['property_id'];
                        }
                    }
                }
            } else {
                $valuation_id = (isset($_POST['val_id'])) ? $_POST['val_id'] : $val_id;
                $data['val'] = 0;
                $data['cons'] = 0;
                $data['party'] = 0;
                $property_id = $_POST['property_id'];
                if ($valuation_id) {

                    $this->valuation_details->virtualFields['total'] = 'SUM(valuation_details.final_value)';
                    $record = $this->valuation_details->find('all', array('fields' => array('final_value'), 'conditions' => array('val_id' => $valuation_id, 'item_type_id' => 2)));

                    if (!empty($record)) {
                        foreach ($record as $record_result) {
                            $data['val'] += $record_result['valuation_details']['final_value'];
                        }
                        $regconf = $this->conf_reg_bool_info->find("all", array('conditions' => array('reginfo_id' => 46)));
                        $roundto = '';
                        if (!empty($regconf)) {
                            if ($regconf[0]['conf_reg_bool_info']['is_boolean'] == 'Y' && $regconf[0]['conf_reg_bool_info']['conf_bool_value'] == 'Y') {
                                if (is_numeric($regconf[0]['conf_reg_bool_info']['info_value']) && $regconf[0]['conf_reg_bool_info']['info_value'] > 0)
                                    $roundto = $regconf[0]['conf_reg_bool_info']['info_value'];
                            }
                        }
                        $data['val'] = $this->round_tonext($data['val'], $roundto);

                        $property = $this->property_details_entry->find("first", array('conditions' => array('val_id' => $valuation_id, 'token_no' => $token)));
                        if (!empty($property)) {
                            $data['cons'] = $property['property_details_entry']['consideration_amount'];
                        }


                        $party = $this->party_entry->query("select count(party_id) from ngdrstab_trn_party_entry_new where token_no=$token and property_id=$property_id");
                        if (!empty($party)) {
                            $data['party'] = $party[0][0]['count'];
                        }


                        return json_encode($data);
                        exit;
                    } else {
                        return '0';
                    }
                }
            }
            return json_encode($data);
            exit;
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    public function get_name_format_jh() {
        array_map(array($this, 'loadModel'), array('regconfig'));
        $regconfig = $this->regconfig->find('first', array('conditions' => array('reginfo_id' => 29)));
        if (!empty($regconfig)) {
            return $regconfig['regconfig']['conf_bool_value'];
        }
    }

    function get_area_jh($val_id = NULL) {
        try {

            $this->autoRender = FALSE;
            $valuation_id = (isset($_POST['val_id'])) ? $_POST['val_id'] : $val_id;
            if ($valuation_id) {
                $this->loadModel('valuation_details');
                $record = $this->valuation_details->find('first', array('fields' => array('item_id', 'item_value'), 'conditions' => array('val_id' => $valuation_id), 'joins' => array(array('table' => 'ngdrstab_mst_usage_items_list', 'alias' => 'item', 'type' => 'inner', 'foreignKey' => false, 'conditions' => array('valuation_details.item_id=item.usage_param_id', 'item.usage_param_code' => 'AAA')))));


                if (!empty($record)) {
                    return $record['valuation_details']['item_value'];
                } else {
                    return '0';
                }
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

    function get_exmption_rule_jh() {
        try {

            $this->autoRender = FALSE;
            if (isset($_POST['fee_rule_id']) && is_numeric($_POST['fee_rule_id'])) {
                $this->loadModel('article_fee_rule');
                $this->loadModel('exemption_article_rules');
                $article_id = $this->Session->read('article_id');
                $doc_lang = $this->Session->read('doc_lang');
                $exemption_rule = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_' . $doc_lang), 'conditions' => array('fee_rule_id' => $this->exemption_article_rules->find('list', array('fields' => array('fee_rule_id'), 'conditions' => array('article_id' => $article_id))), 'main_rule_id' => $_POST['fee_rule_id']))); //get only Exemption Rules(9998)

                if (empty($exemption_rule)) {
                    $exemption_rule = $this->article_fee_rule->find('list', array('fields' => array('fee_rule_id', 'fee_rule_desc_' . $doc_lang), 'conditions' => array('fee_rule_id' => $this->exemption_article_rules->find('list', array('fields' => array('fee_rule_id'), 'conditions' => array('article_id' => $article_id))), 'main_rule_id' => NULL))); //get only Exemption Rules(9998) 
                }
                echo json_encode($exemption_rule);
            }
        } catch (Exception $ex) {
            $this->Session->setFlash(
                    __('Record Cannot be displayed. Error :' . $ex->getMessage())
            );
            return $this->redirect(array('controller' => 'Error', 'action' => 'exception_occurred'));
        }
    }

}
