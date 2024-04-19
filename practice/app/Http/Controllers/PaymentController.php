<?php

namespace App\Http\Controllers;

use App\Http\Services\FatoorahService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $fatoorahService;
    public function __construct(FatoorahService $fatoorahService)
    {
        $this->fatoorahService=$fatoorahService;
    }
    public function payOrder()
    {
        $data = [
            'InvoiceValue'       => 100,
            'CustomerName'       => 'fname lname',
            'NotificationOption' => 'LNK',
            'DisplayCurrencyIso' => 'SAR',
            'MobileCountryCode'  => '',
            'CustomerMobile'     => '',
            'CustomerEmail'      => 'salmarredab170@gmail.com',
            'CallBackUrl'        => 'http://127.0.0.1:8000/api/call_back',
            'ErrorUrl'           => 'https://www.google.com/', //or 'https://example.com/error.php'
            'Language'           => 'en', //or 'ar'
            'CustomerReference'  => 'orderId',
            'CustomerCivilId'    => 'CivilId',
            'UserDefinedField'   => 'This could be string, number, or array',
        ];
       return $this->fatoorahService->sendPayment($data);
    }
    public function callBack(Request $request)
    {
        $data=[];
        $data['Key']=$request->paymentId;
        $data['KeyType']='paymentId';
        return $this->fatoorahService->getPaymentStatus($data);
    }
}
