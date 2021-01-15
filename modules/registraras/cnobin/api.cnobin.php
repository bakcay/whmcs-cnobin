<?php
/**
 * Created by PhpStorm.
 * User: bunyaminakcay
 * Project name inetmar
 * 2.01.2021 22:58
 * Bünyamin AKÇAY <bunyamin@bunyam.in>
 */
class cnobin{

    private $username='';
    private $password='';
    private $method='';
    private $parameters=[];


    public function __construct($username,$password) {

        $this->username =$username;
        $this->password = $password;

    }


    public function registerDomain($domain,$regdata):array{

       $params = [
            'domainname' => trim($domain),
            'dom_org'    => $regdata['dom_org'],
            'dom_ln'     => $regdata['dom_ln'],
            'dom_fn'     => $regdata['dom_fn'],
            'dom_co'     => $regdata['dom_co'],
            'dom_st'     => $regdata['dom_st'],
            'dom_ct'     => $regdata['dom_ct'],
            'dom_adr1'   => $regdata['dom_adr1'],
            'dom_adr2'   => $regdata['dom_adr2'],
            'dom_pc'     => $regdata['dom_pc'],
            'dom_ph'     => $regdata['dom_ph'],
            'dom_fax'    => $regdata['dom_ph'],
            'dom_em'     => $regdata['dom_em'],
            'admi_ln'    => $regdata['admi_ln'],
            'admi_fn'    => $regdata['admi_fn'],
            'admi_co'    => $regdata['admi_co'],
            'admi_st'    => $regdata['admi_st'],
            'admi_ct'    => $regdata['admi_ct'],
            'admi_adr1'  => $regdata['admi_adr1'],
            'admi_adr2'  => $regdata['admi_adr2'],
            'admi_pc'    => $regdata['admi_pc'],
            'admi_ph'    => $regdata['admi_ph'],
            'admi_fax'   => $regdata['admi_ph'],
            'admi_em'    => $regdata['admi_em'],
            'tech_ln'    => $regdata['tech_ln'],
            'tech_fn'    => $regdata['tech_fn'],
            'tech_co'    => $regdata['tech_co'],
            'tech_st'    => $regdata['tech_st'],
            'tech_ct'    => $regdata['tech_ct'],
            'tech_adr1'  => $regdata['tech_adr1'],
            'tech_adr2'  => $regdata['tech_adr2'],
            'tech_pc'    => $regdata['tech_pc'],
            'tech_ph'    => $regdata['tech_ph'],
            'tech_fax'   => $regdata['tech_ph'],
            'tech_em'    => $regdata['tech_em'],
            'bill_ln'    => $regdata['bill_ln'],
            'bill_fn'    => $regdata['bill_fn'],
            'bill_co'    => $regdata['bill_co'],
            'bill_st'    => $regdata['bill_st'],
            'bill_ct'    => $regdata['bill_ct'],
            'bill_adr1'  => $regdata['bill_adr1'],
            'bill_adr2'  => $regdata['bill_adr2'],
            'bill_pc'    => $regdata['bill_pc'],
            'bill_ph'    => $regdata['bill_ph'],
            'bill_fax'   => $regdata['bill_ph'],
            'bill_em'    => $regdata['bill_em'],
        ];

        foreach (range(1, 6) as $k => $v) {
            if (isset($regdata["dns_host{$v}"])) {
                $params["dns_host{$v}"] = $regdata["dns_host{$v}"];
            }
        }

        if($regdata['term']>10 || $regdata['term']<1){
            $params['term']=1;
        }else{
            $params['term']=$regdata['term'];
        }



        $this->setMethod('adddomain');

        $this->setParameters($params);

        return $this->call();


    }

    public function transferDomain($domain,$authcode,$ip=null):array{

        $this->setMethod('transferdomain');

        if($ip==null){
            $ip=$_SERVER['REMOTE_ADDR'];
        }

        $this->setParameters(['domainname'=>trim($domain),'domainpwd'=>$authcode,'ip'=>$ip]);

        return $this->call();
    }

    public function renewDomain($domain,$term):array{

        $this->setMethod('renewdomain');

        $this->setParameters(['domain'=>trim($domain),'term'=>$term]);

        return $this->call();
    }

    public function getDomainInfo($domain):array{

        $this->setMethod('getdomaininfo');

        $this->setParameters(['domainname'=>trim($domain)]);

        return $this->call();
    }

    public function getNameservers($domain): array {
        $this->setMethod('getdomaindns');

        $this->setParameters(['domainname'=>trim($domain)]);

        return $this->call();
    }

    public function setNameservers($domain, $nameservers): array {

        $this->setMethod('moddomaindns');

        $_params = ['domainname' => trim($domain)];

        $i = 1;
        foreach ($nameservers as $k => $v) {
            if (strlen(trim($v)) > 0) {
                $_params['dns_host' . $i] = trim($v);
            }
            $i++;
        }

        $this->setParameters($_params);

        return $this->call();

    }

    public function getContactDetails($domain): array {

        $this->setMethod('getcontactdetails');

        $this->setParameters(['domainname' => trim($domain)]);

        return $this->call();

    }

    public function saveContactDetails($domain, $contact): array {


        $params = [
            'domainname' => trim($domain),
            'dom_org'    => $contact['dom_org'],
            'dom_ln'     => $contact['dom_ln'],
            'dom_fn'     => $contact['dom_fn'],
            'dom_co'     => $contact['dom_co'],
            'dom_st'     => $contact['dom_st'],
            'dom_ct'     => $contact['dom_ct'],
            'dom_adr1'   => $contact['dom_adr1'],
            'dom_adr2'   => $contact['dom_adr2'],
            'dom_pc'     => $contact['dom_pc'],
            'dom_ph'     => $contact['dom_ph'],
            'dom_fax'    => $contact['dom_ph'],
            'dom_em'     => $contact['dom_em'],
            'admi_ln'    => $contact['admi_ln'],
            'admi_fn'    => $contact['admi_fn'],
            'admi_co'    => $contact['admi_co'],
            'admi_st'    => $contact['admi_st'],
            'admi_ct'    => $contact['admi_ct'],
            'admi_adr1'  => $contact['admi_adr1'],
            'admi_adr2'  => $contact['admi_adr2'],
            'admi_pc'    => $contact['admi_pc'],
            'admi_ph'    => $contact['admi_ph'],
            'admi_fax'   => $contact['admi_ph'],
            'admi_em'    => $contact['admi_em'],
            'tech_ln'    => $contact['tech_ln'],
            'tech_fn'    => $contact['tech_fn'],
            'tech_co'    => $contact['tech_co'],
            'tech_st'    => $contact['tech_st'],
            'tech_ct'    => $contact['tech_ct'],
            'tech_adr1'  => $contact['tech_adr1'],
            'tech_adr2'  => $contact['tech_adr2'],
            'tech_pc'    => $contact['tech_pc'],
            'tech_ph'    => $contact['tech_ph'],
            'tech_fax'   => $contact['tech_ph'],
            'tech_em'    => $contact['tech_em'],
            'bill_ln'    => $contact['bill_ln'],
            'bill_fn'    => $contact['bill_fn'],
            'bill_co'    => $contact['bill_co'],
            'bill_st'    => $contact['bill_st'],
            'bill_ct'    => $contact['bill_ct'],
            'bill_adr1'  => $contact['bill_adr1'],
            'bill_adr2'  => $contact['bill_adr2'],
            'bill_pc'    => $contact['bill_pc'],
            'bill_ph'    => $contact['bill_ph'],
            'bill_fax'   => $contact['bill_ph'],
            'bill_em'    => $contact['bill_em'],
        ];

        $this->setMethod('savecontactdetails');

        $this->setParameters($params);

        return $this->call();


    }

    public function getRegistrarLock($domain): array {
        $this->setMethod('getdomainlock');

        $this->setParameters(['domainname' => trim($domain)]);

        return $this->call();
    }

    public function toggleRegistrarLock($domain, $lock): array {

        if ($lock == true) {
            $this->setMethod('lockdomain');
        } else {
            $this->setMethod('unlockdomain');
        }
        $this->setParameters(['domainname' => trim($domain)]);

        return $this->call();

    }

    public function getDNSRecords($domain): array {
        $this->setMethod('getdnsrecord');

        $this->setParameters(['domainname' => trim($domain)]);

        return $this->call();
    }

    public function toggleWhoisProtection($domain, $lock): array {

        if ($lock == true) {
            $this->setMethod('startwhoisprotect');
        } else {
            $this->setMethod('stopwhoisprotect');
        }
        $this->setParameters(['domainname' => trim($domain)]);

        return $this->call();

    }

    public function getEppCode($domain): array {
        $this->setMethod('geteppcode');

        $this->setParameters(['domainname' => trim($domain)]);

        return $this->call();
    }

    public function createNameserver($domain,$host,$ip): array {
        $this->setMethod('createnameserver');

        $this->setParameters(['hostname' => trim($host),'ip'=>$ip]);

        return $this->call();
    }

    public function saveNameserverIp($domain,$host,$oldip,$newip):array{
        $this->setMethod('modnameserver');

        $this->setParameters(['hostname' => trim($host),'oldip'=>$oldip,'newip'=>$newip]);

        return $this->call();
    }

    public function deleteNameserver($domain,$host): array {
        $this->setMethod('delnameserver');

        $this->setParameters(['hostname' => trim($host)]);

        return $this->call();
    }



    private function call(): array {

        $query = [
            'module'=>$this->method,
            'username'=>$this->username,
            'password'=>md5($this->password),
        ];

        $_postval = http_build_query($this->parameters);

        $curl = curl_init();


        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://www.cnobin.com/webrrpdomain?'.http_build_query($query),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $_postval,
          CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ));

        $response = curl_exec($curl);
        $headers  = curl_getinfo($curl);
        curl_close($curl);




        $responses =explode(PHP_EOL,$response);

        $result           = $responses[0];
        $output           = [];
        //$output['result'] = $result;
        $output['resultcode'] = substr($result,0,3)*1;
        $output['resultstring'] = trim(substr($result,3));

        foreach ($responses as $k => $v) {
            if ($k > 0) {
                $v = trim($v);
                if(strlen($v)>0){
                  if (strpos($v, ':') !== false) {
                    $_line             = explode(':', trim($v));
                    $output['response'][$_line[0]] = $_line[1];
                } else {
                    $output['response'][] = $v;
                }
                }

            }
        }

        logModuleCall('cnobin', $this->method, $this->parameters, ['response'=>$output,'headers'=>$headers]);



        return $output;
    }

    public function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    public function setMethod($method) {
        $this->method = $method;
    }

}