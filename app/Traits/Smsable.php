<?php

namespace App\Traits;

// use App\Models\CtmInfluncr;
use App\Models\User;
use App\Models\SmsCommunicationHistory;
use App\Models\SmsTemplate;
// use App\Models\LymInfluncr;
// use App\Models\PrmEmployee;
use App\Notifications\SmsNotification;
use App\Notifications\SmsNotificationWithoutQueue;
use Illuminate\Support\Facades\Http;

trait Smsable
{
    /**
     * @param $lytTmpInflr
     * @param $extraLegendValues
     */
    public static function sendOtpForSignUp($lytTmpInflr, $extraLegendValues = null)
    {

        $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.otp_for_sign_up_eng'));
        if (!empty($smsTemplate)) {
            $modelArray['lytTmpInflr'] = $lytTmpInflr;
            $message = User::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            // $lytTmpInflr->notify(new SmsNotification($message, true));
            $lytTmpInflr->notify(new SmsNotificationWithoutQueue($message, true));
        }
    }

    /**
     * @param $User
     * @param $extraLegendValues
     */
    public static function sendOtp($arrayModels, $extraLegendValues = null)
    {
        $otp_english_template_type = 1; // SMS TYPE  = 1 for English SMS  - mtalkz
        $smsTemplate = Smsable::getSMSTemplate($otp_english_template_type); // Get ENGLISH SMS Templates from the database.

        if (!empty($smsTemplate)) {
            $modelArray['users'] = $arrayModels[0]; // Store users table data
            $message = User::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues); //  Create dynamic SMS using  with database contact_number and otp field.
            $arrayModels[0]->notify(new SmsNotificationWithoutQueue($message, true)); //  Send SMS Notify to contact_number field.
        }
    }

    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendWelcomeSms($lytLoginUsr, $extraLegendValues = null)
    {
        //get the state code $lymInfluncr->lymState
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);
        $lngMstId = $lymInfluncr->lngMstId;

        if ($lngMstId == config('constants.sms_template.welcome_sms_type.hindi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.welcome_hin'));
        } elseif ($lngMstId == config('constants.sms_template.welcome_sms_type.english')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.welcome_eng'));
        } elseif ($lngMstId == config('constants.sms_template.welcome_sms_type.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.welcome_mar'));
        } elseif ($lngMstId == config('constants.sms_template.welcome_sms_type.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.welcome_guj'));
        } elseif ($lngMstId == config('constants.sms_template.welcome_sms_type.tamil')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.welcome_tam'));
        } elseif ($lngMstId == config('constants.sms_template.welcome_sms_type.telugu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.welcome_tel'));
        } elseif ($lngMstId == config('constants.sms_template.welcome_sms_type.kannada')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.welcome_kan'));
        } elseif ($lngMstId == config('constants.sms_template.welcome_sms_type.malayalam')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.welcome_mal'));
        } elseif ($lngMstId == config('constants.sms_template.welcome_sms_type.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.welcome_ori'));
        } elseif ($lngMstId == config('constants.sms_template.welcome_sms_type.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.welcome_ben'));
        }

        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = CtmInfluncr::noLock()->find($lytLoginUsr->inflCode);
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendFirstEarningSms($arrayModels, $extraLegendValues = null)
    {
        $lytLoginUsr = $arrayModels[1];

        //get the state code $lymInfluncr->lymState
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.first_earning_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.first_earning_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.first_earning_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.first_earning_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.first_earning_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.first_earning_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.first_earning_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.first_earning_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.first_earning_hin'));
        }


        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = $arrayModels[0];
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendPointsCreditSms($arrayModels, $extraLegendValues = null)
    {
        $lytLoginUsr = $arrayModels[1];

        //get the state code $lymInfluncr->lymState
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.points_credit_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.points_credit_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.points_credit_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.points_credit_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.points_credit_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.points_credit_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.points_credit_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.points_credit_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.points_credit_hin'));
        }


        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = $arrayModels[0];
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendCodeInvalidSms($arrayModels, $extraLegendValues = null)
    {
        $lytLoginUsr = $arrayModels[1];

        //get the state code $lymInfluncr->lymState
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_invalid_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_invalid_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_invalid_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_invalid_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_invalid_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_invalid_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_invalid_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_invalid_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_invalid_hin'));
        }


        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = $arrayModels[0];
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendCodeAlreadyUsedSms($arrayModels, $extraLegendValues = null)
    {
        $lytLoginUsr = $arrayModels[1];

        //get the state code $lymInfluncr->lymState
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_already_used_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_already_used_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_already_used_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_already_used_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_already_used_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_already_used_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_already_used_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_already_used_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.code_already_used_hin'));
        }


        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = $arrayModels[0];
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendFraudPreventionSms($arrayModels, $extraLegendValues = null)
    {
        $lytLoginUsr = $arrayModels[1];
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.fraud_prevention_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.fraud_prevention_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.fraud_prevention_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.fraud_prevention_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.fraud_prevention_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.fraud_prevention_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.fraud_prevention_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.fraud_prevention_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.fraud_prevention_hin'));
        }


        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = $arrayModels[0];
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendAccountActivationSms($arrayModels, $extraLegendValues = null)
    {
        $lytLoginUsr = $arrayModels[1];
        //get the state code $lymInfluncr->lymState
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.account_activation_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.account_activation_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.account_activation_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.account_activation_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.account_activation_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.account_activation_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.account_activation_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.account_activation_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.account_activation_hin'));
        }

        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = $arrayModels[0];
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }


    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendSetYourTargetSms($lytLoginUsr, $extraLegendValues = null)
    {
        $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.set_your_target'));
        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = CtmInfluncr::noLock()->find($lytLoginUsr->inflCode);
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendCashTransferSms($arrayModels, $extraLegendValues = null)
    {
        $lytLoginUsr = $arrayModels[1];
        //get the state code $lymInfluncr->lymState
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.cash_transfer_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.cash_transfer_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.cash_transfer_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.cash_transfer_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.cash_transfer_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.cash_transfer_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.cash_transfer_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.cash_transfer_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.cash_transfer_hin'));
        }


        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = $arrayModels[0];
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendProductDispatchSms($lytLoginUsr, $extraLegendValues = null)
    {
        //Check user existing
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_dispatch_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_dispatch_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_dispatch_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_dispatch_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_dispatch_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_dispatch_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_dispatch_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_dispatch_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_dispatch_hin'));
        }

        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = CtmInfluncr::noLock()->find($lytLoginUsr->inflCode);
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }
    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendProductCancellationSms($lytLoginUsr, $extraLegendValues = null)
    {
        //Check user existing
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_cancellation_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_cancellation_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_cancellation_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_cancellation_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_cancellation_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_cancellation_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_cancellation_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_cancellation_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_cancellation_hin'));
        }

        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = CtmInfluncr::noLock()->find($lytLoginUsr->inflCode);
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendProductDeliverySms($lytLoginUsr, $extraLegendValues = null)
    {

        //Check user existing
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delivery_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delivery_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delivery_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delivery_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delivery_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delivery_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delivery_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delivery_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delivery_hin'));
        }

        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = CtmInfluncr::noLock()->find($lytLoginUsr->inflCode);
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendProductInProccessSms($lytLoginUsr, $extraLegendValues = null)
    {
        //Check user existing
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.redemption_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.redemption_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.redemption_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.redemption_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.redemption_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.redemption_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.redemption_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.redemption_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.redemption_hin'));
        }

        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = CtmInfluncr::noLock()->find($lytLoginUsr->inflCode);
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    public static function sendLeaderboardAnnoucementUpdateSms($lytLoginUsr, $extraLegendValues = null)
    {
        //Check user existing
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.leader_board_update_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.leader_board_update_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.leader_board_update_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.leader_board_update_mal'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.leader_board_update_hin'));
        }

        if (!empty($smsTemplate)) {
            $modelArray['ctmInfluncr'] = CtmInfluncr::noLock()->find($lytLoginUsr->inflCode);
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, $modelArray, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    /**
     * @param $lytLoginUsr
     * @param $extraLegendValues
     */
    public static function sendProductPendingConfirmationSms($lytLoginUsr, $extraLegendValues = null)
    {

        //Check user existing
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delay_update_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delay_update_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delay_update_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delay_update_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delay_update_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delay_update_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delay_update_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delay_update_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.product_delay_update_hin'));
        }

        if (!empty($smsTemplate)) {
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, null, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    public static function sendEnrollmentSms($lytLoginUsr, $extraLegendValues = null)
    {
        //Check user existing
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_hin'));
        }

        if (!empty($smsTemplate)) {
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, null, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    public static function sendEnrollmentApprovedSms($lytLoginUsr, $extraLegendValues = null)
    {
        //Check user existing
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_hin'));
        }

        if (!empty($smsTemplate)) {
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, null, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    public static function sendEnrollmentRejectedSms($lytLoginUsr, $extraLegendValues = null)
    {
        //Check user existing
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.enrollment_hin'));
        }

        if (!empty($smsTemplate)) {
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, null, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    /**
     * @param $lytTmpInflr
     * @param $extraLegendValues
     */
    public static function sendNonRegisterUserSms($lytNonInflCodeHsty, $extraLegendValues = null)
    {

        $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.launch_app'));

        if (!empty($smsTemplate)) {
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, null, $extraLegendValues);
            $lytNonInflCodeHsty->notify(new SmsNotificationWithoutQueue($message, true));
        }
    }

    public static function sendAttemptedInvalidScanSms($lytLoginUsr, $extraLegendValues = null)
    {
        //Check user existing
        $lymInfluncr = LymInfluncr::noLock()->find($lytLoginUsr->inflCode);

        if ($lymInfluncr->lymState == config('constants.sms_template.state_code.tamil_nadu')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.attempted_invalid_scan_tam'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.karnataka')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.attempted_invalid_scan_kan'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.andhra_pradesh') || $lymInfluncr->lymState == config('constants.sms_template.state_code.telangana')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.attempted_invalid_scan_tel'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.kerala')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.attempted_invalid_scan_mal'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.gujarati')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.attempted_invalid_scan_guj'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.marathi')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.attempted_invalid_scan_mar'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.oriya')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.attempted_invalid_scan_ori'));
        } elseif ($lymInfluncr->lymState == config('constants.sms_template.state_code.bengali')) {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.attempted_invalid_scan_ben'));
        } else {
            $smsTemplate = Smsable::getSMSTemplate(config('constants.sms_template.type.attempted_invalid_scan_hin'));
        }

        if (!empty($smsTemplate)) {
            $message = LytLoginUsr::getDynamicContent($smsTemplate->message, null, $extraLegendValues);
            $lytLoginUsr->notify(new SmsNotification($message));
        }
    }

    /**
     * Prepare & Send SMS
     *
     * @param $number
     * @param $message
     * @param $path
     */
    public static function sendSms($number, $message, $path)
    {
        Smsable::sendSMSAndSaveHistory($number, $message, $path, config('bw.mtalkz.api_key'));
    }

    /**
     * Prepare & Send SMS
     *
     * @param $number
     * @param $message
     * @param $path
     */
    public static function sendOtpSms($number, $message, $path)
    {
        Smsable::sendSMSAndSaveHistory($number, $message, $path, config('bw.mtalkz.api_key_for_otp'));
    }

    /**
     * Prepare & Send mail, also store the email history
     *
     * @param $number
     * @param $message
     * @param $path
     * @param $apiKey
     */
    public static function sendSMSAndSaveHistory($number, $message, $path, $apiKey)
    {
        $param = [
            "apikey"   => $apiKey,
            "senderid" => config('bw.mtalkz.sender_id'),
            "format"   => "json",
            "number"   => $number,
            "message"  => $message
        ];

        Http::post(config('bw.mtalkz.base_url') . $path, $param);

        SmsCommunicationHistory::storeHistory($number, $message);
    }

    /**
     * @param $type
     * @return array
     */
    public static function getSmsTemplate($type)
    {
        return SmsTemplate::where('type', $type)
            // ->withValidSmsTemplateFields()
            // ->withActiveStatus()
            ->first();
    }
}
