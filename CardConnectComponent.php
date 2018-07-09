<?php
namespace App\Controller\Component;

require_once(ROOT . DS . 'vendor' . DS . "card_connect" . DS . "CardConnectRestClient.php");

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use CardConnectRestClient;

class CardConnectComponent extends Component {
    private $url;
    private $user;
    private $password;
    private $merchantId;

    public function __construct() {
        $this->url = Configure::read('CardConnect.ApiUrl');
        $this->user = Configure::read('CardConnect.Username');
        $this->password = Configure::read('CardConnect.Password');
        $this->merchantId  = Configure::read('CardConnect.MerchantId');
    }

    public function paymentRequest($cardDetails = array(), $customerDetails = array()) {
        $client = new CardConnectRestClient($this->url, $this->user, $this->password);
        $request = array(
            'merchid'   => $this->merchantId,
            'accttyppe' => $cardDetails['accttyppe'],   //  "VISA",
            'account'   => $cardDetails['account'],     //  "4444333322221111",
            'expiry'    => $cardDetails['expiry'],      //  "0918",
            'cvv2'      => $cardDetails['cvv2'],        //  "123",
            'amount'    => $cardDetails['amount'],      //  "100",
            'currency'  => $cardDetails['currency'],    //  "USD",
            'orderid'   => $customerDetails['orderid'],
            'name'      => $customerDetails['name'], //"Test User",
            'address'   => $customerDetails['address'], //"123 Test St",
            'city'      => $customerDetails['city'], //"Testville",
            'region'    => $customerDetails['region'], //"Test State",
            'country'   => $customerDetails['country'], //"US",
            'tokenize'  => "Y",
            'profile'   => "Y",
        );
        $response = $client->authorizeTransaction($request);

        return $response;
    }

    /*
     * Api Response
     * Array
        (
            [amount] => 1.00
            [resptext] => Approval
            [acctid] => 1
            [commcard] =>  C
            [cvvresp] => M
            [avsresp] => Z
            [respcode] => 00
            [defaultacct] => Y
            [merchid] => 496160873888
            [token] => 9441149619831111
            [authcode] => PPS000
            [respproc] => FNOR
            [profileid] => 13899013577002433264
            [retref] => 190985208907
            [respstat] => A
            [account] => 44XXXXXXXXXX1111
        )
     */

}
