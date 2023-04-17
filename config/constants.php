<?php

use Carbon\Carbon;

return [

    'server_timezone' => env('SERVER_TZ', 'UTC'),
    'token_expiry' => env('TOKEN_EXPIRY', (60 * 60 * 24)), // Default 24 hours
    'tinify_key' => env('TINIFY_API_KEY'),
    'google_recaptcha_secret' => env('GOOGLE_RECAPTCHA_SECRET'),

    'allowed_ip_addresses' => [
        'telescope' => env('TELESCOPE_ALLOWED_IP_ADDRESSES'),
    ],

    'custom_validations' => [
        'double' => 'regex:/^\d+(\.\d{1,2})?$/'
    ],

    'paginate' => '10',
    'system_user_id' => 1,
    'system_role_id' => 1,
    'default_sort_field' => 'id',


    'broadcasting' => [
        'operation' => [
            'add' => 1,
            'edit' => 2,
            'delete' => 3,
            'delete_multiple' => 4,
        ],
    ],

    'activity_log' => [

        'response_type' => [
            0 => 'Create/Delete response',
            1 => 'Update response',
            2 => 'Index response',
            3 => 'Import/Export response'
        ],
        'response_type_enum' => ['0', '1', '2', '3'],

    ],

    'calender' => [
        'date' => Carbon::now()->toDateString(),
        'date_format' => Carbon::now()->format('Y-m-d'),
        'time' => Carbon::now()->toTimeString(),
        'date_time' => Carbon::now()->toDateTimeString(),
    ],

    'file' => [
        'name' => Carbon::now('Asia/Kolkata')->format('d_m_Y') . '_' . Carbon::now('Asia/Kolkata')->format('g_i_a'),
    ],

    "otp_type"  => [
        "text"  => "T",
        "voice" => "V",
    ],

    'messages' => [

        'user' => [
            'invalid'       => 'Invalid credentials.',
            'invalid_role'  => 'Your role is Invalid.',
            'inactive'      => 'Your account is not active, please contact administrator.',
        ],
        'login'                      => [
            'success'                       => 'Login is successful.',
            'success_portal'                => 'OTP verified successfully',
            'unverified_account'            => 'Your account is not verified yet.',
            'wrong_credentials'             => 'Invalid combination of email and password.',
            'login_token_failed'            => 'Could not create login token.',
            'unauthorized_access'           => 'You are not able to access this system.',
            'unauthorized_user'             => 'This application is only available to contractor & painter',

            'duplicate_mobile'              => 'Duplicate Mobile Detail with Retailer/Stockist',
            'duplicate_pan'                 => 'Duplicate PAN Detail With Retailer/Stockist',
            'duplicate_bank'                => 'Duplicate Bnak Details With Retailer/Stockist',
            'duplicate_aadhar'              => 'Duplicate Aadhaar Detail With Retailer/Stockist',
            'duplicate_aadhar_ocr'          => 'Duplicate Aadhaar Detail With Influencer',

            'unauthorized_user_ocr'         => 'This application is only available to contractor & painter or aadhaar already taken',
            'mobile_number_exist'           => 'Mobile number is already taken',
            'mobile_number_exist_portal'    => 'Number already exist and you can edit the information.',
        ],
        'logout'                     => [
            'success' => 'Logout is successful.',
        ],
        'login_api' => [
            'app_login_portal'          => 'app-login-portal',
            'app_login'                 => 'app-login',
            'app_login_verify_portal'   => 'app-login-verify-portal',
            'app_login_verify'          => 'app-login-verify',
        ],
        'otp_success'                => 'OTP send successfully.',
        'verified_mobile'            => 'Mobile No. is successfully verified.',
        'verified_mobile_portal'     => 'New number registration initiated.',
        'otp_success_voice'          => 'OTP send via voice successfully.',
        'otp_verify_success'         => 'OTP verified successfully.',
        'otp_verify_failed'          => 'Invalid OTP.',
        'forgotpassword_success' => 'Password reset instructions has been sent to your email. Please check your inbox/spam.',
        'forgotpassword_error' => 'Invalid email.',
        'something_wrong' => 'Something went wrong.',
        'password_changed' => "Password has been changed.",
        'invalid_old_password' => "Invalid old password.",
        'similar_password' => "Please enter a password which is not similar then current password.",
        'not_match_confirm_password' => "New password is not match to confirm password.",
        'delete_multiple_error' => 'Please select records.',
        'file_csv_error' => 'please upload csv file.',
        'error_log_not_available' => 'Error logs are not available.',
        'delete_success' => 'Deleted successfully.',
        'create_success' => 'Created successfully.',
        'update_success' => 'Updated successfully.',
        'change_success' => 'Changed successfully.',
        'import_success' => 'Imported successfully.',
        'import_fail' => 'Import failed.',
        'upload_zip_success' => 'Upload successfully.',
        'admin_role_delete_error' => 'Administrator role can not be delete. ',
        'admin_user_delete_error' => 'Super-admin user can not be delete. ',

        "invalid_mobile_vrf_code" => "Invalid OTP.",
        "unauthorized_access"     => "You do not have permission to this functionality.",
    ],

    "help_desk"  => [
        "length_password" => 8,
        "length_max_id"   => 9,
        "initials_id"     => "H",
    ],

    'validation_codes' => [
        'unauthorized'         => 401,
        'forbidden'            => 403,
        'unprocessable_entity' => 422,
        'unassigned'           => 427,
        'rate_limit'           => 429,
        'ok'                   => 200,
    ],

    'unicode' => [
        'unicode_value' => '2',
    ],

    'export_template_legend' => [
        '{{exportReport_downloadLink}}',
        '{{exportReport_modelName}}',
        '{{exportReport_dateTime}}',
        '{{exportReport_subject}}',
        '{{users_otp}}',
    ],

    'sms_template' => [
        'status'      => ['inactive' => '0', 'active' => '1'],
        'status_enum' => ['0', '1'],
        'template_parametere' => '21',
        'state_code'  => [
            'tamil_nadu'     => 'TN',
            'karnataka'      => 'KA',
            'andhra_pradesh' => 'AP',
            'telangana'      => 'TG',
            'kerala'         => 'KL',
            'hindi'          => 'HINDI',
            'gujarati'          => 'GJ',
            'marathi'          => 'MH',
            'oriya'          => 'OR',
            'bengali'          => 'WB',
            'assamese'          => 'AS'
        ],

        'language_type' => [
            'eng',
            'tam',
            'mal',
            'tel',
            'kan',
            'hin'
        ],

        'welcome_sms_type'  => [
            'hindi'     => '1',
            'english'   => '2',
            'marathi'   => '3',
            'gujarati'  => '4',
            'tamil'     => '5',
            'telugu'    => '6',
            'kannada'   => '7',
            'malayalam' => '8',
            'oriya'     => '9',
            'bengali'   => '10',
            'assamese'  => '11'
        ],
        'type'        => [
            'otp_eng'                  => '1',
            'enrollment_eng'           => '2',
            'welcome_eng'              => '3',
            'first_earning_eng'        => '4',
            'points_credit_eng'        => '5',
            'code_invalid_eng'         => '6',
            'code_already_used_eng'    => '7',
            'fraud_prevention_eng'     => '8',
            'account_activation_eng'   => '9',
            'redemption_eng'           => '10',
            'cash_transfer_eng'        => '11',
            'product_dispatch_eng'     => '12',
            'product_cancellation_eng' => '13',
            'product_delay_update_eng' => '14',
            'product_delivery_eng'     => '15',
            'leader_board_update_eng'  => '16',
            'otp_for_sign_up_eng'      => '17',
            'otp_tam'                  => '18',
            'enrollment_tam'           => '19',
            'welcome_tam'              => '20',
            'first_earning_tam'        => '21',
            'points_credit_tam'        => '22',
            'code_invalid_tam'         => '23',
            'code_already_used_tam'    => '24',
            'fraud_prevention_tam'     => '25',
            'account_activation_tam'   => '26',
            'redemption_tam'           => '27',
            'cash_transfer_tam'        => '28',
            'product_dispatch_tam'     => '29',
            'product_cancellation_tam' => '30',
            'product_delay_update_tam' => '31',
            'product_delivery_tam'     => '32',
            'leader_board_update_tam'  => '33',
            'otp_for_sign_up_tam'      => '34',
            'otp_mal'                  => '35',
            'enrollment_mal'           => '36',
            'welcome_mal'              => '37',
            'first_earning_mal'        => '38',
            'points_credit_mal'        => '39',
            'code_invalid_mal'         => '40',
            'code_already_used_mal'    => '41',
            'fraud_prevention_mal'     => '42',
            'account_activation_mal'   => '43',
            'redemption_mal'           => '44',
            'cash_transfer_mal'        => '45',
            'product_dispatch_mal'     => '46',
            'product_cancellation_mal' => '47',
            'product_delay_update_mal' => '48',
            'product_delivery_mal'     => '49',
            'leader_board_update_mal'  => '50',
            'otp_for_sign_up_mal'      => '51',
            'otp_tel'                  => '52',
            'enrollment_tel'           => '53',
            'welcome_tel'              => '54',
            'first_earning_tel'        => '55',
            'points_credit_tel'        => '56',
            'code_invalid_tel'         => '57',
            'code_already_used_tel'    => '58',
            'fraud_prevention_tel'     => '59',
            'account_activation_tel'   => '60',
            'redemption_tel'           => '61',
            'cash_transfer_tel'        => '62',
            'product_dispatch_tel'     => '63',
            'product_cancellation_tel' => '64',
            'product_delay_update_tel' => '65',
            'product_delivery_tel'     => '66',
            'leader_board_update_tel'  => '67',
            'otp_for_sign_up_tel'      => '68',
            'otp_kan'                  => '69',
            'enrollment_kan'           => '70',
            'welcome_kan'              => '71',
            'first_earning_kan'        => '72',
            'points_credit_kan'        => '73',
            'code_invalid_kan'         => '74',
            'code_already_used_kan'    => '75',
            'fraud_prevention_kan'     => '76',
            'account_activation_kan'   => '77',
            'redemption_kan'           => '78',
            'cash_transfer_kan'        => '79',
            'product_dispatch_kan'     => '80',
            'product_cancellation_kan' => '81',
            'product_delay_update_kan' => '82',
            'product_delivery_kan'     => '83',
            'leader_board_update_kan'  => '84',
            'otp_for_sign_up_kan'      => '85',
            'otp_hin'                  => '86',
            'enrollment_hin'           => '87',
            'welcome_hin'              => '88',
            'first_earning_hin'        => '89',
            'points_credit_hin'        => '90',
            'code_invalid_hin'         => '91',
            'code_already_used_hin'    => '92',
            'fraud_prevention_hin'     => '93',
            'account_activation_hin'   => '94',
            'redemption_hin'           => '95',
            'cash_transfer_hin'        => '96',
            'product_dispatch_hin'     => '97',
            'product_cancellation_hin' => '98',
            'product_delay_update_hin' => '99',
            'product_delivery_hin'     => '100',
            'leader_board_update_hin'  => '101',
            'otp_for_sign_up_hin'      => '102',
            'otp_guj'                  => '103',
            'enrollment_guj'           => '104',
            'welcome_guj'              => '105',
            'first_earning_guj'        => '106',
            'points_credit_guj'        => '107',
            'code_invalid_guj'         => '108',
            'code_already_used_guj'    => '109',
            'fraud_prevention_guj'     => '110',
            'account_activation_guj'   => '111',
            'redemption_guj'           => '112',
            'cash_transfer_guj'        => '113',
            'product_dispatch_guj'     => '114',
            'product_cancellation_guj' => '115',
            'product_delay_update_guj' => '116',
            'product_delivery_guj'     => '117',
            'leader_board_update_guj'  => '118',
            'otp_for_sign_up_guj'      => '119',
            'otp_mar'                  => '120',
            'enrollment_mar'           => '121',
            'welcome_mar'              => '122',
            'first_earning_mar'        => '123',
            'points_credit_mar'        => '124',
            'code_invalid_mar'         => '125',
            'code_already_used_mar'    => '126',
            'fraud_prevention_mar'     => '127',
            'account_activation_mar'   => '128',
            'redemption_mar'           => '129',
            'cash_transfer_mar'        => '130',
            'product_dispatch_mar'     => '131',
            'product_cancellation_mar' => '132',
            'product_delay_update_mar' => '133',
            'product_delivery_mar'     => '134',
            'leader_board_update_mar'  => '135',
            'otp_for_sign_up_mar'      => '136',
            'otp_ori'                  => '137',
            'enrollment_ori'           => '138',
            'welcome_ori'              => '139',
            'first_earning_ori'        => '140',
            'points_credit_ori'        => '141',
            'code_invalid_ori'         => '142',
            'code_already_used_ori'    => '143',
            'fraud_prevention_ori'     => '144',
            'account_activation_ori'   => '145',
            'redemption_ori'           => '146',
            'cash_transfer_ori'        => '147',
            'product_dispatch_ori'     => '148',
            'product_cancellation_ori' => '149',
            'product_delay_update_ori' => '150',
            'product_delivery_ori'     => '151',
            'leader_board_update_ori'  => '152',
            'otp_for_sign_up_ori'      => '153',
            'otp_ben'                  => '154',
            'enrollment_ben'           => '155',
            'welcome_ben'              => '156',
            'first_earning_ben'        => '157',
            'points_credit_ben'        => '158',
            'code_invalid_ben'         => '159',
            'code_already_used_ben'    => '160',
            'fraud_prevention_ben'     => '161',
            'account_activation_ben'   => '162',
            'redemption_ben'           => '163',
            'cash_transfer_ben'        => '164',
            'product_dispatch_ben'     => '165',
            'product_cancellation_ben' => '166',
            'product_delay_update_ben' => '167',
            'product_delivery_ben'     => '168',
            'leader_board_update_ben'  => '169',
            'otp_for_sign_up_ben'      => '170',
            'otp_ass'                  => '171',
            'enrollment_ass'           => '172',
            'welcome_ass'              => '173',
            'first_earning_ass'        => '174',
            'points_credit_ass'        => '175',
            'code_invalid_ass'         => '176',
            'code_already_used_ass'    => '177',
            'fraud_prevention_ass'     => '178',
            'account_activation_ass'   => '179',
            'redemption_ass'           => '180',
            'cash_transfer_ass'        => '181',
            'product_dispatch_ass'     => '182',
            'product_cancellation_ass' => '183',
            'product_delay_update_ass' => '184',
            'product_delivery_ass'     => '185',
            'leader_board_update_ass'  => '186',
            'otp_for_sign_up_ass'      => '187',
            'launch_app'               => '188',
            'attempted_invalid_scan_eng'  => '189',
            'attempted_invalid_scan_tam'  => '190',
            'attempted_invalid_scan_mal'  => '191',
            'attempted_invalid_scan_tel'  => '192',
            'attempted_invalid_scan_kan'  => '193',
            'attempted_invalid_scan_hin'  => '194',
            'attempted_invalid_scan_guj'  => '195',
            'attempted_invalid_scan_mar'  => '196',
            'attempted_invalid_scan_ori'  => '197',
            'attempted_invalid_scan_ben'  => '198',
            'attempted_invalid_scan_ass'  => '199',
        ],

    ],

    'import_csv_log' => [
        'status' => [
            '0' => 'Fail',
            '1' => 'Success',
        ],
        'status_enum' => ['0', '1'],
    ],

    'image' => [
        'dir_path' => '/storage/',
        'default_types' => 'gif|jpg|png|jpeg',
        'default_img' => 'images/default.png',
    ],

    'permission' => [
        'has_permission' => '1',
        'has_not_permission' => '0',
        'role_guard_name' => 'web',
        'user_has_not_permission' => "You don\'t have permission to this functionality . ",
        'user_already_has_permission' => "Given permission already exists . ",
        'user_clinic_mapping_error' => "User clinic is not mapped yet . ",
        'module_error' => "Module not in request . ",
        'invalid_module_error' => "Invalid module . ",
        'validation_error_status_code' => 422,
        'permission_assign_success' => 'Permission assign successfully.',
        'permission_revert_success' => 'Permission reverted successfully.',
        'permission_revert_failure' => 'Permission revert failed.',
        'permission_not_found' => 'Permission not found.',
        'role_not_found' => 'Role not found.',
    ],
    'user' => [

        'status' => [0 => 'Inactive', 1 => 'Active'],
        'status_enum' => ['inactive' => "0", 'active' => "1"],
        'user_status' => [0 => 'Inactive', 1 => 'Active'],
        'user_status_enum' => ['inactive' => "0", 'active' => "1"],
        'user_type' => [
            '0' => 'Admin',
            '1' => 'User',
        ],

        'user_type_code' => [
            'admin' => '0',
            'user' => '1',
        ],
    ],
    'user_status' => [

        'status' => [0 => 'Inactive', 1 => 'Active'],
        'status_enum' => ['inactive' => "0", 'active' => "1"],
        'user_type' => [
            '0' => 'Admin',
            '1' => 'User',
        ],

        'user_type_code' => [
            'admin' => '0',
            'user' => '1',
        ],
    ],
    'product' => [

        'available_status' => [0 => 'Not-available', 1 => 'Available'],
        'available_status_enum' => ['not-available' => "0", 'available' => "1"],

    ],
    'homebanner' => [

        'banner_status' => [0 => 'Inactive', 1 => 'Active'],
        'banner_status_enum' => ['inactive' => "0", 'active' => "1"],

    ],
    'order' => [

        'order_status' => [0 => 'Pending', 1 => 'Inprocess', 2 => 'Cancel', 3 => 'Completed', 4 => 'Return'],
        'order_status_enum' => ['pending' => "0", 'inprocess' => "1", 'cancel' => "2", 'completed' => "3", 'return' => "4"],

    ],
];