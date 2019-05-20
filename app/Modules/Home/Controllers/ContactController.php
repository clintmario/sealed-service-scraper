<?php

namespace App\Modules\Home\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Modules\Home\Models\ContactModel;

class ContactController extends Controller
{
    protected $fields = [
        'name',
        'email',
        'subject',
        'message',
    ];

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255|email',
            'subject' => 'required|max:255',
            'message' => 'required|max:2047',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'messages' => $validator->messages()], 200);
        }

        $contact = [];
        foreach ($this->fields as $field) {
            $contact[$field] = $request->input($field);
        }

        $contactModel = new ContactModel();
        $contactModel->insertContact($contact);

        Mail::send("Fortuna::email-contact", ['contact' => $contact], function($message) use ($contact) {
            $message->subject("Contact Message on Bogex from {$contact['email']}")
                ->to('info@bogex.com')
                ->replyTo($contact['email']);
        });

        return response()->json(['status' => 1, 'messages' => 'Message recorded.'], 200);
    }

    public function hello(Request $request)
    {
        return response()->json(['status' => 1, 'messages' => 'Message recorded.'], 200);
    }

    public function getBill(Request $request)
    {
        $username = 'utilitybills@sealed.com';
        $password = 'u6(DKpJ%nA-,>q.E';
        $loginUrl = 'https://www.coned.com/sitecore/api/ssc/ConEd-Cms-Services-Controllers-Okta/User/0/Login';

        //init curl
        $ch = curl_init();

        //$this->setOptions();
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://coned.okta.com/api/v1/sessions/me',
            CURLOPT_CUSTOMREQUEST => 'OPTIONS',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            //CURLOPT_NOBODY => true,
            CURLOPT_COOKIEJAR => '/tmp/cookie.txt',
            CURLOPT_COOKIEFILE => '/tmp/cookie.txt'
        ));
        $r = curl_exec($ch);

        //echo PHP_EOL.'Response Headers:'.PHP_EOL;
        //print_r($r);
        //curl_close($ch);

        //Set the URL to work with
        curl_setopt($ch, CURLOPT_URL, $loginUrl);

        // ENABLE HTTP POST
        curl_setopt($ch, CURLOPT_POST, 1);

        //Set the post parameters
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'LoginEmail='.$username.'&LoginPassword='.$password);

        //Handle cookies for the login
        curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookie.txt');

        //Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
        //not to print out the results of its query.
        //Instead, it will return the results as a string return value
        //from curl_exec() instead of the usual true/false.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        ///curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        //execute the request (the login)
        $store = curl_exec($ch);

        print_r($store);

        //$this->verifyFactor();

        //the login is now done and you can continue to get the
        //protected content.

        /*curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://coned.okta.com/api/v1/sessions/me',
            CURLOPT_CUSTOMREQUEST => 'OPTIONS',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            //CURLOPT_NOBODY => true,
            CURLOPT_COOKIEJAR => '/tmp/cookie.txt',
            CURLOPT_COOKIEFILE => '/tmp/cookie.txt'
        ));
        $r = curl_exec($ch);*/

        //set the URL to the protected file
        //curl_setopt($ch, CURLOPT_URL, 'http://www.example.com/protected/download.zip');
        //curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookie.txt');
        //curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookie.txt');
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch, CURLOPT_POST, 0);
        //curl_setopt($ch, CURLOPT_URL, 'https://dcxsab.blob.core.windows.net/cecony-bill/6669aed7-a322-4037-938a-b3d9a313aa6c.pdf?sv=2017-04-17&sr=b&sig=GZCIHo%2F17wgCMvZGMyLMcdoEKeO3s5QM67eHVbYnPn4%3D&se=2019-05-19T13%3A13%3A54Z&sp=rw');
        //curl_setopt($ch, CURLOPT_URL, 'https://dcxsab.blob.core.windows.net/cecony-bill/6669aed7-a322-4037-938a-b3d9a313aa6c.pdf');
        //curl_setopt($ch, CURLOPT_URL, 'https://www.coned.com/sitecore/api/ssc/ConEd-Cms-Services-Controllers-Dcx/Account/0/BillInsertImage?ScId=dad7ec5d-cc42-474d-b25b-6e2ae1b0e6ea&Maid=005100001083938&DocumentId=djcxMjYtODAwNS04MDA2LTgwMTEtU0hCMS04NTA1NkZBQUEtMzgwNjQ3LTQxMzA0LTIwNzcyODItMjE5MjM4LTg1LTc5LTgxOTUxLTE3MS0wLV4BMDYwMQFDT04BODI4ATU4ODk0MjUyOTAwMDAwNQExODAxNwExODAxOAEkMC4wMAFNUiBBTEdJRSBHUkVHT1JZICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgAVMyMQEgICAgICAgICAgICAgICABRUwgATE3OTg4ATEBRQEgICAgAQIBMQECATAxASFNNjAwMzE5X1ImUl9SRVNfRU5HICAgICAgICAgICAhICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgISAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICEgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAhASEgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAhICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgISAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICEgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAhAQIBMAECATABAgEyOTA4MTBfQ0VJRTIxX1BVTEwBAgFOUgE2ATE1NTY5MjEzMTIBIAFCSUxMICAgICAgICAgICAgICAgIAExAS0kODcyLjI0ICAgICAgIAE4MiBTS1kgTUVBRE9XIFBMQUMgUEQgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgAVdISVRFIFBMQUlOUyBOWSAgMTA2MDctMTIyNCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICABICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAEgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgASAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICABMTA2MDcxMjI0ODI3ATA=&BillDate=2019-05-01&Type=image');
        //curl_setopt($ch,CURLOPT_URL,'https://www.coned.com/en/accounts-billing/dashboard?tab1=billingandusage-1&account=005100001047003');

        //curl_setopt($ch,CURLOPT_HEADER, true);
        /*curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Origin: coned.com',
            //'Access-Control-Allow-Origin: *',
            //'Access-Control-Allow-Headers: *',
            ///'Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE',
            'Access-Control-Request-Method: *',
            //'Access-Control-Request-Headers: *',
        ));*/
        //header('Access-Control-Allow-Origin: *');

        /*header('Access-Control-Allow-Origin: *');
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            $headers=getallheaders();
            @$ACRH=$headers["Access-Control-Request-Headers"];
            header("Access-Control-Allow-Headers: $ACRH");
        }

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");*/
        //header("Access-Control-Allow-Origin: *");


        //execute the request
        //$content = curl_exec($ch);

        //echo $content;
        //$this->fetchBill();
        //$this->fetchDashboard();

        curl_close($ch);

        //save the data to disk
        //file_put_contents('~/download.zip', $content);
    }

    public function setOptions()
    {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => 'https://coned.okta.com/api/v1/sessions/me',
            CURLOPT_CUSTOMREQUEST => 'OPTIONS',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            //CURLOPT_NOBODY => true,
            CURLOPT_COOKIEJAR => '/tmp/cookie.txt',
            CURLOPT_COOKIEFILE => '/tmp/cookie.txt'
        ));
        $r = curl_exec($ch);
    }

    public function verifyFactor()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.coned.com/sitecore/api/ssc/ConEd-Cms-Services-Controllers-Okta/User/0/VerifyFactor');
        // ENABLE HTTP POST
        curl_setopt($ch, CURLOPT_POST, 1);

        //Set the post parameters
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'MFACode=New%20York'.'&ReturnUrl=""&OpenIdRelayState=""');

        //Handle cookies for the login
        curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookie.txt');

        //Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
        //not to print out the results of its query.
        //Instead, it will return the results as a string return value
        //from curl_exec() instead of the usual true/false.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        ///curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        //execute the request (the login)
        $store = curl_exec($ch);
        print_r($store);
    }

    public function fetchDashboard()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookie.txt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch, CURLOPT_POST, 0);
        //curl_setopt($ch, CURLOPT_URL, 'https://www.coned.com/sitecore/api/ssc/ConEd-Cms-Services-Controllers-Dcx/Account/0/BillInsertImage?ScId=dad7ec5d-cc42-474d-b25b-6e2ae1b0e6ea&Maid=005100001083938&DocumentId=djcxMjYtODAwNS04MDA2LTgwMTEtU0hCMS04NTA1NkZBQUEtMzgwNjQ3LTQxMzA0LTIwNzcyODItMjE5MjM4LTg1LTc5LTgxOTUxLTE3MS0wLV4BMDYwMQFDT04BODI4ATU4ODk0MjUyOTAwMDAwNQExODAxNwExODAxOAEkMC4wMAFNUiBBTEdJRSBHUkVHT1JZICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgAVMyMQEgICAgICAgICAgICAgICABRUwgATE3OTg4ATEBRQEgICAgAQIBMQECATAxASFNNjAwMzE5X1ImUl9SRVNfRU5HICAgICAgICAgICAhICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgISAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICEgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAhASEgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAhICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgISAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICEgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAhAQIBMAECATABAgEyOTA4MTBfQ0VJRTIxX1BVTEwBAgFOUgE2ATE1NTY5MjEzMTIBIAFCSUxMICAgICAgICAgICAgICAgIAExAS0kODcyLjI0ICAgICAgIAE4MiBTS1kgTUVBRE9XIFBMQUMgUEQgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgAVdISVRFIFBMQUlOUyBOWSAgMTA2MDctMTIyNCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICABICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAEgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgASAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICABMTA2MDcxMjI0ODI3ATA=&BillDate=2019-05-01&Type=image');
        curl_setopt($ch,CURLOPT_URL,'https://www.coned.com/en/accounts-billing/dashboard?tab1=billingandusage-1&account=005100001047003');
        $content = curl_exec($ch);

        echo $content;

        curl_close($ch);

    }

    public function fetchBill()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://dcxsab.blob.core.windows.net/cecony-bill/6669aed7-a322-4037-938a-b3d9a313aa6c.pdf?sv=2017-04-17&sr=b&sig=GZCIHo%2F17wgCMvZGMyLMcdoEKeO3s5QM67eHVbYnPn4%3D&se=2019-05-19T13%3A13%3A54Z&sp=rw');
        $content = curl_exec($ch);

        echo $content;

        curl_close($ch);
    }

    public function processBill(Request $request)
    {
        set_time_limit(0);

        $remoteFile = urldecode($request->input('remote_file'));

        $fileIdentifier = uniqid();
        $pdfFileName = $fileIdentifier . '.pdf';
        $txtFileName = $fileIdentifier . '.txt';

        //$url = 'https://dcxsab.blob.core.windows.net/cecony-bill/88abf6a8-6388-41e6-940f-f1e9dc98e474.pdf?sv=2017-04-17&sr=b&sig=TUQAih1XPJpEO0kAGnmaXjn4gjB0SQv%2FdpYJDEXEkJY%3D&se=2019-05-21T01%3A57%3A03Z&sp=rw';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $remoteFile);
        $fileData = curl_exec($ch);
        curl_close($ch);

        file_put_contents("/tmp/" . $pdfFileName, $fileData);
        system("/usr/bin/pdftotext -layout /tmp/$pdfFileName /tmp/$txtFileName");

        $fn = fopen("/tmp/$txtFileName","r");

        $returnData = [];
        $billType = 'ConEdison';
        $returnData['bill_type'] = $billType;

        $lineNumber = 0;
        $billingPeriodLineNumber = 0;

        while(!feof($fn))  {
            $lineNumber++;
            $line = fgets($fn);
            if (preg_match('/Billing period:/', $line)) {
                $fromDate = trim(preg_replace('/Billing period: (.*?) to .*/', "$1", $line));
                $toDate = trim(preg_replace('/Billing period: .*? to (.*?[0-9][0-9][0-9][0-9]).*/', "$1", $line));
                $billingFromDate = date("n/d/Y", strtotime($fromDate));
                $billingToDate = date("n/d/Y", strtotime($toDate));

                $returnData['bill_from_date'] = $billingFromDate;
                $returnData['bill_to_date'] = $billingToDate;
                $billingPeriodLineNumber = $lineNumber;
            }

            if (preg_match('/Esco electricity supply charges/', $line)) {
                $escoCharge = trim(preg_replace('/.*?Esco electricity.*?(\$.*?\.[0-9][0-9]).*/', "$1", $line));

                $returnData['esco_electricity_charge'] = $escoCharge;
            }

            if (preg_match('/lectricity charges/', $line) && $lineNumber - $billingPeriodLineNumber <= 5) {
                $electricityCharge = trim(preg_replace('/.*?lectricity charges.*?(\$.*?\.[0-9][0-9]).*/', "$1", $line));

                $returnData['electricity_charge'] = $electricityCharge;
            }

            if (preg_match('/Esco gas supply charges/', $line) && $lineNumber - $billingPeriodLineNumber <= 6) {
                $gasCharge = trim(preg_replace('/.*?Esco gas.*?(\$.*?\.[0-9][0-9]).*/', "$1", $line));

                $returnData['esco_gas_charge'] = $gasCharge;
            }

            if (preg_match('/Gas charges/', $line) && $lineNumber - $billingPeriodLineNumber <= 8) {
                $gasCharge = trim(preg_replace('/.*?Gas charges.*?(\$.*?\.[0-9][0-9]).*/', "$1", $line));

                $returnData['gas_charge'] = $gasCharge;
            }

            if (preg_match('/Total amount due/', $line) && $lineNumber - $billingPeriodLineNumber <= 10) {
                $totalCharge = trim(preg_replace('/.*?Total amount due.*?(\$.*?\.[0-9][0-9]).*/', "$1", $line));

                $returnData['total_charge'] = $totalCharge;
            }

            if (preg_match('/Your electricity use.*Wh/', $line)) {
                $electricityUsage = trim(preg_replace('/.*?Your electricity use.*?([\d,]+).*/', "$1", $line));

                $returnData['electricity_usage'] = $electricityUsage;
            }

            if (preg_match('/Your gas use.*therms/', $line)) {
                $gasUsage = trim(preg_replace('/.*?Your gas use.*?([\d]+).*/', "$1", $line));

                $returnData['gas_usage'] = $gasUsage;
            }
        }

        fclose($fn);

        return response()->json($returnData);
    }
}
