<?php

namespace App\Http\Controllers;

use App\InstructorCourse;
use App\Payment;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::where('api_token', '=', $request->bearerToken())->first();
        $role = $user->role;

        switch ($role) {
            case 'admin':
                return Payment::all();
            default:
                return $user->payments;
                break;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request `
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payment = Payment::latest('created_at')->first();
        if (!$payment) {
            $payment = new Payment(['paymentid' => Str::uuid()] + $request->all());
            //Confirm if mobile number is registered on mobile money network.
            $curl = curl_init();
            $MOMO_API_CLIENT_ID = env("MOMO_API_CLIENT_ID");
            $MOMO_API_CLIENT_PASSWORD = env("MOMO_API_CLIENT_PASSWORD");
            $payment_token = hash('sha512', $payment->paymentid . $MOMO_API_CLIENT_ID . $MOMO_API_CLIENT_PASSWORD);
            curl_setopt_array($curl, array(
                CURLOPT_URL => env("MOMO_API_BASE_URL"),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\"header\": {\"clientid\": \"{$MOMO_API_CLIENT_ID}\",\"countrycode\": \"{$payment->countrycode}\",\"requestid\": \"{$payment->paymentid}\",\"token\": \"{$payment_token}\"},\"msisdn\": \"{$payment->msisdn}\",\"network\": \"{$payment->network}\"}",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));

            $registration_status_response = curl_exec($curl);

            curl_close($curl);
            //end check
            $decoded_registration_status_response = json_decode($registration_status_response);
//            if (!($decoded_registration_status_response->header->message == 'SUCCESS' && $decoded_registration_status_response->header->status == '000' && $decoded_registration_status_response->customerstatus == "TRUE")) {
            if (false) {
                return response()->json(array(
                    'not_registered' => true
                ));
            }
            $stored_payment = $this->storePayment($request);
            return response()->json(array(
                'stored_payment' => $stored_payment
            ));
        }

        $pending_payment = Payment::where('status', '=', 'ACCEPTED')
            ->orWhere('status', '=', 'PENDING')
            ->latest('created_at')->first();
        if ($pending_payment) {
            $time_created = Carbon::createFromFormat('Y-m-d H:i:s', $pending_payment->created_at);
            $now = Carbon::now();
            $wait_time = 0 - $now->diffInRealMinutes($time_created);

            if ($wait_time <= 0) {
                //Check status
                $curl = curl_init();
                $MOMO_API_CLIENT_ID = env("MOMO_API_CLIENT_ID");
                $MOMO_API_CLIENT_PASSWORD = env("MOMO_API_CLIENT_PASSWORD");
                $pending_payment_token = hash('sha512', $pending_payment->paymentid . $MOMO_API_CLIENT_ID . $MOMO_API_CLIENT_PASSWORD);
                curl_setopt_array($curl, array(
                    CURLOPT_URL => env("MOMO_API_BASE_URL"),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{\"header\": {\"clientid\": \"{$MOMO_API_CLIENT_ID}\",\"countrycode\": \"{$pending_payment->countrycode}\",\"requestid\": \"{$pending_payment->paymentid}\",\"token\": \"{$pending_payment_token}\"},\"requesttype\": \"DEBIT\",\"paymentref\": \"{$pending_payment->paymentref}\"}",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json"
                    ),
                ));

                $status_response = curl_exec($curl);

                Log::info('status_response', [
                    'status_response' => $status_response
                ]);

                curl_close($curl);
                //end status check
                if ($status_response == "{}") {
                    return response()->json(array(
                        'wait_time' => $wait_time,
                    ));
                }
                $decoded_response = json_decode($status_response);

                if ($decoded_response->header->message == 'SUCCESS' && $decoded_response->header->status == '000') {
                    $pending_payment->update(
                        [
                            'status' => $decoded_response->transactionstatus,
//                            'externalreferenceno' => $decoded_response->transactionstatusreason
                        ]
                    );
                    $pending_payment = Payment::find($pending_payment->paymentid);
                }


                if ($pending_payment->transactionstatus == 'ACCEPTED' || $pending_payment->transactionstatus == 'PENDING') {
                    return response()->json(array(
                        'wait_time' => $wait_time,
                    ));
                }
                $current_payment = $this->storePayment($request);
                return response()->json(array(
                    'current_payment' => $current_payment,
                    'prev_payment' => $pending_payment
                ));
            }
            return response()->json(array(
                'wait_time' => $wait_time,
            ));
        }
        $stored_payment = $this->storePayment($request);
        return response()->json(array(
            'stored_payment' => $stored_payment
        ));
    }

    public function pay(Request $request)
    {
        $paymentid = request('paymentid');
        $payment = Payment::where('paymentid', $paymentid)->first();

        $MOMO_API_CLIENT_ID = env("MOMO_API_CLIENT_ID");
        $header['clientid'] = $MOMO_API_CLIENT_ID;
        $header['countrycode'] = $payment->countrycode;
        $header['requestid'] = $paymentid;
        $MOMO_API_CLIENT_PASSWORD = env("MOMO_API_CLIENT_PASSWORD");
        $header['token'] = hash('sha512', $paymentid . $MOMO_API_CLIENT_ID . $MOMO_API_CLIENT_PASSWORD);

        $body['header'] = $header;
        $body['msisdn'] = $payment->msisdn;
        $body['network'] = $payment->network;
        $body['description'] = $payment->description;
        $body['amount'] = (double)$payment->amount;
        $body['currency'] = $payment->currency;

        $curl = curl_init();

        $MOMO_API_BASE_URL = env("MOMO_API_BASE_URL");
        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$MOMO_API_BASE_URL}debitcustomer",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"header\": {\"clientid\": \"{$MOMO_API_CLIENT_ID}\",\"countrycode\": \"{$header['countrycode']}\",\"requestid\": \"{$header['requestid']}\",\"token\": \"{$header['token']}\"},\"msisdn\": \"{$body['msisdn']}\",\"network\": \"{$body['network']}\",\"description\": \"{$body['description']}\",\"amount\": {$body['amount']},\"currency\": \"{$body['currency']}\"}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        Log::info('debit_response', [
            'debit_response' => $response
        ]);

        curl_close($curl);
        $decoded_response = json_decode($response);
        $payment->update(
            [
                'message' => $decoded_response->header->message,
                'status' => $decoded_response->header->status,
                'externalreferenceno' => $decoded_response->externalreferenceno,
                'paymentref' => $decoded_response->paymentref,
            ]
        );
    }

    public function getRoom(Request $request)
    {
        $client = new Client();
        $CALL_API_BASE_URL = env("CALL_API_BASE_URL");
        $response = $client->createRequest(
            "GET",
            $CALL_API_BASE_URL . 'api/v1/room');
        $contents = $client->send($response)->getBody()->getContents();


        $latest_payment = Payment::latest('created_at')->first();

        $expired = $latest_payment ? $latest_payment->expired : true;

        $eligible = !$expired;

        $phonenumber = User::where('api_token', '=', $request->bearerToken())->first()->phonenumber;
        $client_whitelist = new Client();
        $eligibility = $eligible == 1 ? "true" : "false";
        $response = $client_whitelist->createRequest(
            "GET",
            "{$CALL_API_BASE_URL}api/v1/student/{$phonenumber}/{$eligibility}"
        );
        $whitelist_response = $client_whitelist->send($response)->getBody();
        /*Log::info('whitelist_response', [
            'whitelist_response' => $whitelist_response->getContents()
        ]);*/

        return response()->json(array(
            'eligible' => $eligible,
            'contents' => $contents
        ));
    }

    public function checkSubscription(Request $request)
    {
        $latest_payment = Payment::latest('created_at')->first();

        $expired = $latest_payment ? $latest_payment->expired : true;

        return response()->json(array(
            'eligible' => !$expired
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        return $payment;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        $bodyContent = $request->getContent();

        $payment->update(
            [
                'message' => $bodyContent->header->message,
                'status' => $bodyContent->header->status,
                'externalreferenceno' => $bodyContent->externalreferenceno,
                'paymentref' => $bodyContent->paymentref,
            ]
        );

        $payment->update($request->all());

        $updated_payment = Payment::where('paymentid', $payment->paymentid)->first();


        return response()->json($updated_payment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }

    public function sendOtp()
    {
        $phonenumber = request('phonenumber');

        $user = User::where('phonenumber', $phonenumber)->first();

        $already_registered = false;
        if (!$user) {
            $already_registered = false;
            $userid = Str::uuid();
            $user = User::forceCreate([
                'userid' => $userid,
                'phonenumber' => $phonenumber,
                'role' => request('role'),
                'api_token' => Str::uuid()
            ]);
            if ($user->role == 'student') {
                Student::forceCreate([
                    'userid' => $userid
                ]);
            } else if ($user->role == 'instructor') {
                Instructor::forceCreate([
                    'userid' => $userid
                ]);
            }
        } else {
            $already_registered = true;
        }
        $hash = request('hash');
        $client = new Client();
        $otp = mt_rand(1000, 9999);
        $content = "<#> Your OTP is: $otp $hash";
        $content = urlencode($content);

        $user->update([
            'otp' => $otp
        ]);

        $CLICKATELL_API_KEY = env("CLICKATELL_API_KEY");
        $response = $client->createRequest(
            "GET",
            "https://platform.clickatell.com/messages/http/send?apiKey={$CLICKATELL_API_KEY}&to=$phonenumber&content=$content"
        );
        $contents = $client->send($response)->getBody()->getContents();
//        return $contents;
        return response()->json(array(
            'contents' => $contents,
            'userid' => $user->userid,
            'api_token' => $user->api_token,
            'already_registered' => $already_registered,
        ));
    }

    public function callback(Request $request)
    {
        Log::info('callback_request_params', [
            'callback_request_params' => $request
        ]);

        $payment = Payment::where('paymentref', request('paymentref'))
            ->where('externalreferenceno', '=', request('externalrefno'))
            ->first();

        if ($payment) {
            $payment->update(
                [
                    'paymentref' => request('paymentref'),
                    'externalreferenceno' => request('externalrefno'),
                    'status' => request('status')
                ]
            );

            if (request('status') == 'SUCCESS' || request('status') == 'SUCCESSFUL') {
                $payer = User::find($payment->payerid);
                $payer->update(
                    [
                        'timeremaining' => $payer->timeremaining + $payment->duration
                    ]
                );
            }
        }

        /*Log::info('payment_not_found', [
            'payment_not_found' => 'payment not found'
        ]);*/

        return response()->noContent();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function storePayment(Request $request)
    {
        $paymentid = Str::uuid();
        Payment::forceCreate(
            [
                'paymentid' => $paymentid,
                'status' => "PENDING",
                'msisdn' => request('msisdn'),
                'countrycode' => request('countrycode'),
                'network' => request('network'),
                'currency' => request('currency'),
                'amount' => request('amount'),
                'description' => request('description'),
                'duration' => request('duration'),
                'payerid' => request('payerid')
            ]);
        $current_payment = Payment::where('paymentid', $paymentid)->first();
        return $current_payment;
    }
}
