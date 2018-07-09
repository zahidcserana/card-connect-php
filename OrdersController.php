<?php

namespace App\Controller;

use App\Controller\AppController;
use Exception;

class OrdersController extends AppController
{
   private function onlinePayment($data)
    {
        $this->loadComponent('CardConnect');
        if (!empty($data['order_id'])) {
            $customerInfo = $this->Orders->get($data['order_id']);
            $data['amount'] = $data['payment_amount'];
            if (
                (isset($data['accttyppe']) && !empty($data['accttyppe'])) &&
                (isset($data['account']) && !empty($data['account'])) &&
                (isset($data['expiry']) && !empty($data['expiry'])) &&
                (isset($data['cvv2']) && !empty($data['cvv2'])) &&
                (isset($data['amount']) && !empty($data['amount'])) &&
                (isset($data['currency']) && !empty($data['currency']))
            ) {
                $cardData = array(
                    'accttyppe'     => $data['accttyppe'],
                    'account'       => $data['account'],
                    'expiry'        => $data['expiry'],
                    'cvv2'          => $data['cvv2'],
                    'amount'        => $data['amount'],
                    'currency'      => $data['currency']
                );
                $customerData = array(
                    'name'          => $customerInfo['first_name'].' '.$customerInfo['last_name'],
                    'address'       => $customerInfo['address_line1'],
                    'city'          => $customerInfo['city'],
                    'region'        => $customerInfo['state_id'],
                    'country'       => $customerInfo['country_id'],
                    'orderid'       => $customerInfo['uuid'],
                );
                $response = $this->CardConnect->paymentRequest($cardData,$customerData);
                return $response;
            }
        }
        return false;
    }

}
