<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
 
use App\Models\Package;
use App\Models\Customer;
use App\Models\Payments;
 
use App\Models\Usertokens;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\PackageFeature;
use App\Services\HelperService;
use App\Models\UserPackageLimit;
use App\Services\ResponseService;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;
use Exception;
 


class WebhookController extends Controller
{
    public function paystack()
    {
        return ResponseService::errorResponse('Paystack not supported');
    }
    public function razorpay(Request $request)
    {
        return ResponseService::errorResponse('Razorpay not supported');
    }
    public function paypal(Request $request)
    {
        return ResponseService::errorResponse('Paypal not supported');
    }
    public function stripe(Request $request)
    {
        return ResponseService::errorResponse('Stripe not supported');
    }
    public function flutterwave(Request $request){
        return ResponseService::errorResponse('Flutterwave not supported');
    }

    public function paystackSuccessCallback(){
        return ResponseService::errorResponse('Paystack not supported');
    }



     /**
     * Success Business Login
     * @param $payment_transaction_id
     * @param $user_id
     * @param $package_id
     * @return array
     */
    private function assignPackage($paymentTransactionId,$transactionId) {
        try {
            $paymentTransactionData = PaymentTransaction::where('id', $paymentTransactionId)->first();
            if ($paymentTransactionData == null) {
                Log::error("Payment Transaction id not found");
                ResponseService::errorResponse("Payment Transaction id not found");
            }

            if ($paymentTransactionData->payment_status == "succeed") {
                Log::info("Transaction Already Succeed");
                ResponseService::errorResponse("Transaction Already Succeed");
            }

            DB::beginTransaction();
            $paymentTransactionData->update(['transaction_id' => $transactionId,'payment_status' => "success"]);

            $packageId = $paymentTransactionData->package_id;
            $userId = $paymentTransactionData->user_id;


            $package = Package::findOrFail($packageId);

            if (!empty($package)) {
                // Assign Package to user
                $userPackage = UserPackage::create([
                    'package_id'  => $packageId,
                    'user_id'     => $userId,
                    'start_date'  => Carbon::now(),
                    'end_date'    => $package->package_type == "unlimited" ? null : Carbon::now()->addHours($package->duration),
                ]);
                DB::commit();

                DB::beginTransaction();
                // Assign limited count feature to user with limits
                $packageFeatures = PackageFeature::where(['package_id' => $packageId, 'limit_type' => 'limited'])->get();
                if(collect($packageFeatures)->isNotEmpty()){
                    $userPackageLimitData = array();
                    foreach ($packageFeatures as $key => $feature) {
                        $userPackageLimitData[] = array(
                            'user_package_id' => $userPackage->id,
                            'package_feature_id' => $feature->id,
                            'total_limit' => $feature->limit,
                            'used_limit' => 0,
                            'created_at' => now(),
                            'updated_at' => now()
                        );
                    }

                    if(!empty($userPackageLimitData)){
                        UserPackageLimit::insert($userPackageLimitData);
                    }
                }
            }

            $userFcmTokensDB = Usertokens::where('customer_id', $userId)->pluck('fcm_id');
            if(collect($userFcmTokensDB)->isNotEmpty()){
                $title = "Package Purchased";
                $body = 'Amount :- ' . $paymentTransactionData->amount;

                $registrationIDs = array_filter($userFcmTokensDB->toArray());

                $fcmMsg = array(
                    'title' => $title,
                    'message' => $body,
                    "image" => null,
                    'type' => 'default',
                    'body' => $body,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'sound' => 'default',

                );
                send_push_notification($registrationIDs, $fcmMsg);

                Notifications::create([
                    'title' => $title,
                    'message' => $body,
                    'image' => '',
                    'type' => '2',
                    'send_type' => '0',
                    'customers_id' => $userId,
                ]);
            }
            DB::commit();
            ResponseService::successResponse("Transaction Verified Successfully");

        } catch (Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage() . "WebhookController -> assignPackage");
            ResponseService::errorResponse();
        }
    }


    /**
     * Failed Business Logic
     * @param $paymentTransactionId
     * @return array
     */
    private function failedTransaction($paymentTransactionId) {
        try {
            $paymentTransactionData = PaymentTransaction::find($paymentTransactionId);
            if (!$paymentTransactionData) {
                Log::error("Payment Transaction id not found");
                return ResponseService::errorResponse("Payment Transaction id not found");
            }

            if ($paymentTransactionData->payment_status == "failed") {
                Log::info("Transaction Already Failed");
                return ResponseService::errorResponse("Transaction Already Failed");
            }

            DB::beginTransaction();
            $paymentTransactionData->update(['payment_status' => "failed"]);

            $userId = $paymentTransactionData->user_id;
            $title = "Package Payment Failed";
            $body = 'Amount :- ' . $paymentTransactionData->amount;

            $userFcmTokensDB = Usertokens::where('customer_id', $userId)->pluck('fcm_id');
            if(collect($userFcmTokensDB)->isNotEmpty()){
                $registrationIDs = array_filter($userFcmTokensDB->toArray());

                $fcmMsg = array(
                    'title' => $title,
                    'message' => $body,
                    "image" => null,
                    'type' => 'default',
                    'body' => $body,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'sound' => 'default',

                );
                send_push_notification($registrationIDs, $fcmMsg);
            }
            Notifications::create([
                'title' => $title,
                'message' => $body,
                'image' => '',
                'type' => '2',
                'send_type' => '0',
                'customers_id' => $userId,
            ]);

            DB::commit();
            ResponseService::successResponse("Transaction Failed Successfully");
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage() . "WebhookController -> failedTransaction");
            ResponseService::errorResponse();
        }
    }
}
