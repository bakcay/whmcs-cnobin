<?php
/**
 * Created by PhpStorm.
 * User: bunyaminakcay
 * Project name inetmar
 * 2.01.2021 22:58
 * Bünyamin AKÇAY <bunyamin@bunyam.in>
 */

if (!defined("WHMCS")) { die("This file cannot be accessed directly"); }

include 'api.cnobin.php';

define('cnobin_modulename','CNOBIN Custom');
define('cnobin_version','1.54.21');
define('cnobin_detailtext','CNOBIN Custom WHMCS Module by <a href="https://bunyam.in">bunyam.in</a>');

function cnobin_MetaData() {

    return array(
        'DisplayName' => cnobin_detailtext,
        'APIVersion'  => cnobin_version,
    );
}

function cnobin_getConfigArray() {
    return array(

        "FriendlyName" => array(
            "Type"  => "System",
            "Value" => cnobin_modulename
        ),
        "Description"  => array(
            "Type"  => "System",
            "Value" => cnobin_detailtext
        ),
        "Username" => array(
            "Type"        => "text",
            "Size"        => "20",
            "Description" => "Enter your username here"
        ),
        "Password" => array(
            "Type"        => "password",
            "Size"        => "20",
            "Description" => "Enter your password here"
        ),
    );
}

function cnobin_RegisterDomain($params) {
    $username    = $params["Username"];
    $password    = $params["Password"];
    $platform    = $params["Platform"];
    $testmode    = $params["TestMode"];
    $debugmode   = $params["DebugMode"];
    $tld         = $params["tld"];
    $sld         = $params["sld"];
    $regperiod   = $params["regperiod"];
    $nameserver1 = $params["ns1"];
    $nameserver2 = $params["ns2"];
    $nameserver3 = $params["ns3"];
    $nameserver4 = $params["ns4"];
    # Registrant Details
    $RegistrantFirstName = $params["firstname"];
    $RegistrantLastName  = $params["lastname"];
    # add by beeyon 2010-07-22 get "dom_org" value from companyname
    $RegistrantCompanyName = $params["companyname"];
    if (strlen($RegistrantCompanyName)<3){
        $RegistrantCompanyName = $RegistrantFirstName . " " . $RegistrantLastName;
    }


    # Admin Details
    $AdminFirstName     = $params["adminfirstname"];
    $AdminLastName      = $params["adminlastname"];
    $AdminAddress1      = $params["adminaddress1"];
    $AdminAddress2      = $params["adminaddress2"];
    $AdminCity          = $params["admincity"];
    $AdminStateProvince = $params["adminstate"];
    $AdminPostalCode    = $params["adminpostcode"];
    $AdminCountry       = $params["admincountry"];
    $AdminEmailAddress  = $params["adminemail"];
    $AdminPhone         = $params["adminfullphonenumber"];

    if(strlen($RegistrantCompanyName)<3){
        $RegistrantCompanyName='FIRMA';
    }
    if(strlen($AdminFirstName)<3){
        $AdminFirstName='MUSTERI';
    }
    if(strlen($AdminLastName)<3){
        $AdminLastName='MUSTERI';
    }
    if(strlen($AdminAddress1)<3){
        $AdminAddress1='ADRES ADRES ADRES';
    }
    if(strlen($AdminCity)<3){
        $AdminCity='ISTANBUL';
    }
    if(strlen($AdminStateProvince)<3){
        $AdminStateProvince='MARMARA';
    }
    if(strlen($AdminPostalCode)<3){
        $AdminPostalCode='34722';
    }
    if(strlen($AdminCountry)<3){
        $AdminCountry='TR';
    }

    $RegistrantCompanyName = cnobin_normalizetr($RegistrantCompanyName);
    $AdminFirstName        = cnobin_normalizetr($AdminFirstName);
    $AdminLastName         = cnobin_normalizetr($AdminLastName);
    $AdminAddress1         = cnobin_normalizetr($AdminAddress1);
    $AdminAddress2         = cnobin_normalizetr($AdminAddress2);
    $AdminCity             = cnobin_normalizetr($AdminCity);
    $AdminStateProvince    = cnobin_normalizetr($AdminStateProvince);
    $AdminPostalCode       = cnobin_normalizetr($AdminPostalCode);
    $AdminCountry          = cnobin_normalizetr($AdminCountry);
    $AdminEmailAddress     = cnobin_normalizetr($AdminEmailAddress);
    $AdminPhone            = cnobin_normalizetr($AdminPhone);



    $_par = [
        'dom_org' => $RegistrantCompanyName,
        'term'    => $regperiod
    ];

    foreach (range(1,6) as $k => $v) {
        $_par["dns_host{$v}"]=$params["ns{$v}"];
    }

    foreach (['dom','admi','tech','bill'] as $k => $v) {
                $_par["{$v}_fn"]  = $AdminFirstName;
                $_par["{$v}_ln"]   = $AdminLastName;
                $_par["{$v}_adr1"] = $AdminAddress1 . ' ' . $AdminAddress2;
                $_par["{$v}_ct"]   = $AdminCity;
                $_par["{$v}_st"]   = $AdminStateProvince;
                $_par["{$v}_co"]   = $AdminCountry;
                $_par["{$v}_ph"]   = $AdminPhone;
                $_par["{$v}_fax"]  = $AdminPhone;
                $_par["{$v}_pc"]   = $AdminPostalCode;
                $_par["{$v}_em"]   = $AdminEmailAddress;
    }


    $cnobin = new cnobin($username, $password);

    $result = $cnobin->registerDomain($sld . "." . $tld, $_par);

    if ($result['resultcode'] == 200) {
        return ['success' => true];
    } else {
        return ['error' => $result['resultstring']];
    }
    

}

function cnobin_TransferDomain($params) {
    $username       = $params["Username"];
    $password       = $params["Password"];
    $platform       = $params["Platform"];
    $testmode       = $params["TestMode"];
    $debugmode      = $params["DebugMode"];
    $tld            = $params["tld"];
    $sld            = $params["sld"];
    $regperiod      = $params["regperiod"];
    $transfersecret = $params["transfersecret"];
    $nameserver1    = $params["ns1"];
    $nameserver2    = $params["ns2"];


    $cnobin = new cnobin($username, $password);

    $result = $cnobin->transferDomain($sld . "." . $tld, $transfersecret);

    if ($result['resultcode'] == 200) {
        return ['success' => true];
    } else {
        return ['error' => $result['resultstring']];
    }
}

function cnobin_RenewDomain($params) {
    $username  = $params["Username"];
    $password  = $params["Password"];
    $platform  = $params["Platform"];
    $testmode  = $params["TestMode"];
    $debugmode = $params["DebugMode"];
    $tld       = $params["tld"];
    $sld       = $params["sld"];
    $regperiod = $params["regperiod"];

    $cnobin = new cnobin($username, $password);

    $result = $cnobin->renewDomain($sld . "." . $tld, $regperiod);

    if ($result['resultcode'] == 200) {
        return ['success' => true];
    } else {
        return ['error' => $result['resultstring']];
    }
}

function cnobin_GetNameservers($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld      = $params["tld"];
    $sld      = $params["sld"];

    $cnobin = new cnobin($username, $password);

    $result = $cnobin->getNameservers($sld . "." . $tld);

    if ($result['resultcode'] == 200) {

        $arr = [];

        foreach ($result['response'] as $k => $v) {
            $arr['ns' . ($k + 1)] = $v;
        }
        return $arr;


    } else {
        return ['error' => $result['resultstring']];
    }

}

function cnobin_SaveNameservers($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld      = $params["tld"];
    $sld      = $params["sld"];


    $nameservers = [
        $params["ns1"],
        $params["ns2"],
        $params["ns3"],
        $params["ns4"],
        $params["ns5"],
        $params["ns6"]
    ];


    $cnobin = new cnobin($username, $password);

    $result = $cnobin->setNameservers($sld . "." . $tld, $nameservers);

    if ($result['resultcode'] == 200) {

        return ['success' => 'success'];


    } else {
        return ['error' => $result['resultstring']];
    }
}

function cnobin_GetContactDetails($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld      = $params["tld"];
    $sld      = $params["sld"];


    $cnobin = new cnobin($username, $password);

    $result = $cnobin->getContactDetails($sld . "." . $tld);

    if ($result['resultcode'] == 200) {

        $arr_results = $result['response'];

        $values["Registrant"]["First Name"]     = $arr_results['dom_fn'];
        $values["Registrant"]["Last Name"]      = $arr_results['dom_ln'];
        $values["Registrant"]["Organization"]   = $arr_results['dom_org'];
        $values["Registrant"]["Address1"]       = $arr_results['dom_adr1'];
        $values["Registrant"]["Address2"]       = $arr_results['dom_adr2'];
        $values["Registrant"]["City"]           = $arr_results['dom_ct'];
        $values["Registrant"]["State Province"] = $arr_results['dom_st'];
        $values["Registrant"]["Country"]        = $arr_results['dom_co'];
        $values["Registrant"]["Phone"]          = $arr_results['dom_ph'];
        $values["Registrant"]["Email Address"]  = $arr_results['dom_em'];
        $values["Registrant"]["Postcode"]       = $arr_results['dom_pc'];
        $values["Admin"]["First Name"]          = $arr_results['admi_fn'];
        $values["Admin"]["Last Name"]           = $arr_results['admi_ln'];
        $values["Admin"]["Address1"]            = $arr_results['admi_adr1'];
        $values["Admin"]["Address2"]            = $arr_results['admi_adr2'];
        $values["Admin"]["City"]                = $arr_results['admi_ct'];
        $values["Admin"]["State Province"]      = $arr_results['admi_st'];
        $values["Admin"]["Country"]             = $arr_results['admi_co'];
        $values["Admin"]["Phone"]               = $arr_results['admi_ph'];
        $values["Admin"]["Email Address"]       = $arr_results['admi_em'];
        $values["Admin"]["Postcode"]            = $arr_results['admi_pc'];
        $values["Tech"]["First Name"]           = $arr_results['tech_fn'];
        $values["Tech"]["Last Name"]            = $arr_results['tech_ln'];
        $values["Tech"]["Address1"]             = $arr_results['tech_adr1'];
        $values["Tech"]["Address2"]             = $arr_results['tech_adr2'];
        $values["Tech"]["City"]                 = $arr_results['tech_ct'];
        $values["Tech"]["State Province"]       = $arr_results['tech_st'];
        $values["Tech"]["Country"]              = $arr_results['tech_co'];
        $values["Tech"]["Phone"]                = $arr_results['tech_ph'];
        $values["Tech"]["Email Address"]        = $arr_results['tech_em'];
        $values["Tech"]["Postcode"]             = $arr_results['tech_pc'];
        $values["Bill"]["First Name"]           = $arr_results['bill_fn'];
        $values["Bill"]["Last Name"]            = $arr_results['bill_ln'];
        $values["Bill"]["Address1"]             = $arr_results['bill_adr1'];
        $values["Bill"]["Address2"]             = $arr_results['bill_adr2'];
        $values["Bill"]["City"]                 = $arr_results['bill_ct'];
        $values["Bill"]["State Province"]       = $arr_results['bill_st'];
        $values["Bill"]["Country"]              = $arr_results['bill_co'];
        $values["Bill"]["Phone"]                = $arr_results['bill_ph'];
        $values["Bill"]["Email Address"]        = $arr_results['bill_em'];
        $values["Bill"]["Postcode"]             = $arr_results['bill_pc'];
        return $values;


    } else {
        return ['error' => $result['resultstring']];
    }

}

function cnobin_SaveContactDetails($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld      = $params["tld"];
    $sld      = $params["sld"];


    $contact['dom_org']   = $params["contactdetails"]["Registrant"]["Organization"];
    $contact['dom_ln']    = $params["contactdetails"]["Registrant"]["Last Name"];
    $contact['dom_fn']    = $params["contactdetails"]["Registrant"]["First Name"];
    $contact['dom_co']    = $params["contactdetails"]["Registrant"]["Country"];
    $contact['dom_st']    = $params["contactdetails"]["Registrant"]["State Province"];
    $contact['dom_ct']    = $params["contactdetails"]["Registrant"]["City"];
    $contact['dom_adr1']  = $params["contactdetails"]["Registrant"]["Address1"];
    $contact['dom_adr2']  = $params["contactdetails"]["Registrant"]["Address2"];
    $contact['dom_pc']    = $params["contactdetails"]["Registrant"]["Postcode"];
    $contact['dom_ph']    = $params["contactdetails"]["Registrant"]["Phone"];
    $contact['dom_fax']   = '';
    $contact['dom_em']    = $params["contactdetails"]["Registrant"]["Email Address"];
    $contact['admi_ln']   = $params["contactdetails"]["Admin"]["Last Name"];
    $contact['admi_fn']   = $params["contactdetails"]["Admin"]["First Name"];
    $contact['admi_co']   = $params["contactdetails"]["Admin"]["Country"];
    $contact['admi_st']   = $params["contactdetails"]["Admin"]["State Province"];
    $contact['admi_ct']   = $params["contactdetails"]["Admin"]["City"];
    $contact['admi_adr1'] = $params["contactdetails"]["Admin"]["Address1"];
    $contact['admi_adr2'] = $params["contactdetails"]["Admin"]["Address2"];
    $contact['admi_pc']   = $params["contactdetails"]["Admin"]["Postcode"];
    $contact['admi_ph']   = $params["contactdetails"]["Admin"]["Phone"];
    $contact['admi_fax']  = '';
    $contact['admi_em']   = $params["contactdetails"]["Admin"]["Email Address"];
    $contact['tech_ln']   = $params["contactdetails"]["Tech"]["Last Name"];
    $contact['tech_fn']   = $params["contactdetails"]["Tech"]["First Name"];
    $contact['tech_co']   = $params["contactdetails"]["Tech"]["Country"];
    $contact['tech_st']   = $params["contactdetails"]["Tech"]["State Province"];
    $contact['tech_ct']   = $params["contactdetails"]["Tech"]["City"];
    $contact['tech_adr1'] = $params["contactdetails"]["Tech"]["Address1"];
    $contact['tech_adr2'] = $params["contactdetails"]["Tech"]["Address2"];
    $contact['tech_pc']   = $params["contactdetails"]["Tech"]["Postcode"];
    $contact['tech_ph']   = $params["contactdetails"]["Tech"]["Phone"];
    $contact['tech_fax']  = '';
    $contact['tech_em']   = $params["contactdetails"]["Tech"]["Email Address"];
    $contact['bill_ln']   = $params["contactdetails"]["Bill"]["Last Name"];
    $contact['bill_fn']   = $params["contactdetails"]["Bill"]["First Name"];
    $contact['bill_co']   = $params["contactdetails"]["Bill"]["Country"];
    $contact['bill_st']   = $params["contactdetails"]["Bill"]["State Province"];
    $contact['bill_ct']   = $params["contactdetails"]["Bill"]["City"];
    $contact['bill_adr1'] = $params["contactdetails"]["Bill"]["Address1"];
    $contact['bill_adr2'] = $params["contactdetails"]["Bill"]["Address2"];
    $contact['bill_pc']   = $params["contactdetails"]["Bill"]["Postcode"];
    $contact['bill_ph']   = $params["contactdetails"]["Bill"]["Phone"];
    $contact['bill_fax']  = '';
    $contact['bill_em']   = $params["contactdetails"]["Bill"]["Email Address"];


    $cnobin = new cnobin($username, $password);

    $result = $cnobin->saveContactDetails($sld . "." . $tld, $contact);

    if ($result['resultcode'] == 200) {
        return ['success' => true];
    } else {
        return ['error' => $result['resultstring']];
    }


}

function cnobin_GetRegistrarLock($params) {
    $username = $params["Username"];
    $password = $params["Password"];

    $tld = $params["tld"];
    $sld = $params["sld"];


    $cnobin = new cnobin($username, $password);

    $result = $cnobin->getRegistrarLock($sld . "." . $tld);

    if ($result['resultcode'] == 200) {
        return $result['response']['lock'] == 'true' ? 'locked' : 'unlocked';
    } else {
        return ['error' => $result['resultstring']];
    }

}

function cnobin_SaveRegistrarLock($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld      = $params["tld"];
    $sld      = $params["sld"];


    $cnobin = new cnobin($username, $password);

    $result = $cnobin->toggleRegistrarLock($sld . "." . $tld, $params["lockenabled"] == "locked");

    if ($result['resultcode'] == 200) {
        return ['success' => 'success'];
    } else {
        return ['error' => $result['resultstring']];
    }


}

/*
function cnobin_GetDNS($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld      = $params["tld"];
    $sld      = $params["sld"];



        $cnobin = new cnobin($username,$password);

        $result = $cnobin->getDNSRecords($sld . "." . $tld);

        if($result['resultcode']==200){

            $dnsrecords = [];

            foreach ($result['response'] as $k => $v) {

                $rec = explode('|',trim($v));

                $dnsrecords[] = array(
                        "hostname" => $rec[1],
                        "type"     => $rec[0],
                        "address"  => $rec[2],
                        "priority" => $rec[3],
                    );
            }
            if(count($dnsrecords)==0){
                return null;
            }else{
                return $dnsrecords;
            }

        }else{
            return ['error' => $result['resultstring']];
        }


    return ['error' => 'notsupported'];
}

function cnobin_SaveDNS($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld      = $params["tld"];
    $sld      = $params["sld"];
    $dnsData  = $params["dnsrecords"];




    $cnobin = new cnobin($username,$password);

    $result_dns = $cnobin->getDNSRecords($sld . "." . $tld);

    $dns_list=[];

    if($result_dns['resultcode']!=200) {
        return array('error' => $result_dns['resultstring']);
    }else{
        foreach ($result_dns['response'] as $k => $v) {
            $rec = explode('|',trim($v));
            $dns_list[strtolower($rec[0].'_'.$rec[1])]= ['host'    => $v[1], 'type'    => $v[0], 'value'   => $v[2], 'mxlevel' => intval($v[3])];
        }
    }

    $dnsRecords = array();
    $cur_dns_list = [];
    foreach ($dnsData as $k => $v) {
        if ($v['address']) {

            $_tmp = strtolower($v['type'].'_'.$v['hostname']);
            $cur_dns_list[$_tmp]= ['host'    => $v['hostname'], 'type'    => $v['type'], 'value'   => $v['address'], 'mxlevel' => intval($v['priority'])];

            if(!in_array($_tmp,array_keys($dns_list))){
                //sil
            }elseif(isset($cur_dns_list[$_tmp]) && isset($dns_list[$_tmp]) && $cur_dns_list[$_tmp]['value']!=$dns_list[$_tmp]['value']){
                //Düzenle
            }

            $dnsRecords[] = [
                'host'    => $v['hostname'],
                'type'    => $v['type'],
                'value'   => $v['address'],
                'mxlevel' => intval($v['priority']),
            ];

        }
    }

    foreach ($dns_list as $k => $v) {

        if(!in_array($k,array_keys($cur_dns_list))){

            //ekle


        }

    }

    return ['error' => 'notsupported'];
}
*/

function cnobin_IDProtectToggle($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    // domain parameters
    $sld = $params['sld'];
    $tld = $params['tld'];
    // id protection parameter
    $protectEnable = ( bool )$params['protectenable'];

    $cnobin = new cnobin($username, $password);

    $result = $cnobin->toggleRegistrarLock($sld . "." . $tld, $protectEnable);

    if ($result['resultcode'] == 200) {
        return ['success' => 'success'];
    } else {
        return ['error' => $result['resultstring']];
    }

}

function cnobin_GetEPPCode($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $tld      = $params["tld"];
    $sld      = $params["sld"];

    $cnobin = new cnobin($username, $password);

    $result = $cnobin->getEppCode($sld . "." . $tld);

    if ($result['resultcode'] == 200) {
        return ['eppcode' => $result['response']['eppcode']];
    } else {
        return ['error' => $result['resultstring']];
    }


}

function cnobin_RegisterNameserver($params) {
    $username   = $params["Username"];
    $password   = $params["Password"];
    $tld        = $params["tld"];
    $sld        = $params["sld"];
    $nameserver = $params["nameserver"];
    $ipaddress  = $params["ipaddress"];


    $cnobin = new cnobin($username, $password);


    $result = $cnobin->createNameserver($sld . "." . $tld, $nameserver, $ipaddress);

    if ($result['resultcode'] == 200) {
        return ['success' => 'success'];
    } else {
        return ['error' => $result['resultstring']];
    }

}

function cnobin_ModifyNameserver($params) {
    $username         = $params["Username"];
    $password         = $params["Password"];
    $tld              = $params["tld"];
    $sld              = $params["sld"];
    $nameserver       = $params["nameserver"];
    $currentipaddress = $params["currentipaddress"];
    $newipaddress     = $params["newipaddress"];


    $cnobin = new cnobin($username, $password);

    $result = $cnobin->saveNameserverIp($sld . "." . $tld, $nameserver, $currentipaddress, $newipaddress);

    if ($result['resultcode'] == 200) {
        return ['success' => 'success'];
    } else {
        return ['error' => $result['resultstring']];
    }

}

function cnobin_DeleteNameserver($params) {
    $username   = $params["Username"];
    $password   = $params["Password"];
    $platform   = $params["Platform"];
    $testmode   = $params["TestMode"];
    $debugmode  = $params["DebugMode"];
    $tld        = $params["tld"];
    $sld        = $params["sld"];
    $nameserver = $params["nameserver"];


    $cnobin = new cnobin($username, $password);

    $result = $cnobin->deleteNameserver($sld . "." . $tld, $nameserver);

    if ($result['resultcode'] == 200) {
        return ['success' => 'success'];
    } else {
        return ['error' => $result['resultstring']];
    }

}

function cnobin_Sync($params) {
    $username = $params["Username"];
    $password = $params["Password"];
    $sld      = $params["sld"];
    $tld      = $params["tld"];


    $cnobin = new cnobin($username, $password);

    $result = $cnobin->getDomainInfo($sld . "." . $tld);

    if ($result['resultcode'] == 200) {

        $_date = strtotime($result['response']['expiredate']);


        $resp = [
            'expirydate' => date('Y-m-d', $_date),
            //'active'          => ( bool )$result['active'],
            //'expired'         => ( bool )$result['expired'],
            //'transferredAway' => ( bool )$result['transferredaway'],
        ];

        if (time() > $_date) {
            $resp['active'] = true;
        } else {
            $resp['expired'] = true;
        }

        return $resp;

    } else {
        return ['error' => $result['resultstring']];
    }

}

function cnobin_normalizetr($text) {
$text = trim($text);
$search = array('Ç','ç','Ğ','ğ','ı','İ','Ö','ö','Ş','ş','Ü','ü');
$replace = array('c','c','g','g','i','i','o','o','s','s','u','u');
return preg_replace("/[^A-Za-z0-9 ]/", ' ', str_replace($search,$replace,$text));
}
