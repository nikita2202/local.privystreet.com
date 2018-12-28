<?php

require_once APPPATH . "composer/vendor/autoload.php";

use Aws\Sns\SnsClient;
use Aws\Sns\SnsClient\AwsClient;

class SNSPush {

    const GCM_ARN = GCM_ARN;
    const APNS_ARN = APNS_ARN;
    const REGION = REGION;

    private $topicARN;
    private $sns;
    private $protocol;

    public function __construct($topicARN = "", $protocol = "", $for_sms = False) {
        $this->topicARN = $topicARN;
        $this->protocol = $protocol;
        if ($for_sms) {
            $this->sns = SnsClient::factory(array(
                'credentials' => array(
                            'key' => SNS_ACCESS_KEY,
                            'secret' => SNS_SECRET_KEY,
                        ),
                        'region' => self::REGION,
                        'version' => 'latest'
            ));
        } else {
            $this->sns = SnsClient::factory(array(
                        'credentials' => array(
                            'key' => SNS_ACCESS_KEY,
                            'secret' => SNS_SECRET_KEY,
                        ),
                        'region' => self::REGION,
                        'version' => 'latest'
            ));
        }
    }

    /*
     * Add device Arn for androin & IOS both applications
     */

    public function addDeviceEndPoint($deviceToken, $deviceType, $userData = "") {

        if ($deviceType == "1") {
            $platformApplicationArn = self::GCM_ARN;
        } elseif ($deviceType == "2") {
            $platformApplicationArn = self::APNS_ARN;
        } else {
            return false;
        }
        try {
            $result = array();
            $result = $this->sns->createPlatformEndpoint(array(
                'PlatformApplicationArn' => $platformApplicationArn,
                'CustomUserData' => $userData,
                'Token' => $deviceToken,
            ));

            return [
                "success" => true,
                "message" => "OK",
                "result" => $result
            ];
        } catch (\Exception $error) {
            
            $str = '---------------Error for Adding the ARN------- ';
            $str.= $error;
            $str .= '----------------------\n\n\n';

           // file_put_contents(getcwd() . "/application/sns_log/error.txt", $str);
            
            return [
                "success" => false,
                "message" => "Error",
                "error" => $result
            ];
        }
    }

    /*
     * Delete device ARN
     */

    public function deleteDeviceEndPoint($deviceArn) {
        try {
            $this->sns->deleteEndpoint([
                "EndpointArn" => $deviceArn
            ]);
            return [
                "success" => true,
                "message" => "OK"
            ];
        } catch (\Exception $error) {
            $str = '---------------Error for deleting the ARN------- ';
            $str.= $error;
            $str .= '----------------------\n\n\n';

            file_put_contents(getcwd() . "/application/sns_log/error.txt", $str);

            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /*
     * Send OTP to one user
     */

    public function send_msg($params) {
        $result = array();
        try {
            $result = $this->sns->publish([
                'Message' => $params['message'],
                'PhoneNumber' => $params['phone'],
                'Subject' => $params['subject']
            ]);
            return [
                "success" => true,
                "message" => "OK"
            ];
        } catch (\Exception $error) {

            $str = '---------------Error for Sending SMS------- ';
            $str.= $error;
            $str .= '----------------------\n\n\n';

            file_put_contents(getcwd() . "/application/sns_log/error.txt", $str);
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /*
     * Send OTP to multiple user
     */

    public function send_bulk_msg($params) {

        $result = array();
        foreach ($params['phone'] as $val) {
            $result[] = $this->sns->publishAsync([
                'Message' => $params['message'] . base64_encode($val),
                'PhoneNumber' => $val,
                'Subject' => $params['subject']
            ]);
        }
        $allPromise = \GuzzleHttp\Promise\all($result);
        $data_promise = $allPromise->wait();
        try {
            return [
                "success" => true,
                "message" => "OK"
            ];
        } catch (\Exception $error) {
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /*
     * Send push to one user
     */

    public function asyncPublish($arns, $post_data) {

        $CI = & get_instance();
        $CI->load->model('Common_model');
        try {
            $promises = array();
            $insert_data = array();
            
            if (isset($post_data['sender']) && !empty($post_data['sender'])) {
                $insert_data['title'] = $post_data['title'];
                $insert_data['sender_id'] = $post_data['sender'];
                $insert_data['message'] = isset($post_data['message']) ? $post_data['message'] : "";
                $insert_data['receive_id'] = $arns['id'];
                $insert_data['device_type'] = $arns['device_type'];
                $insert_data['type'] = $post_data['type'];
                $insert_data['type_id'] = $post_data['type_id'];
                $insert_data['category'] = isset($post_data["category"]) ? $post_data["category"] : "";
                $insert_data['created_date'] = date('Y-m-d H:i:s');

                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
            }
            
            $notificationCount = $this->getBadgeCount($arns['id']);
            $message = [
                "default" => $post_data["title"],
                "APNS" => json_encode([
                    "aps" => [
                        "alert" => array(
                            "title" => "",
                            "body" => $post_data["message"],
                            "subtitle" => ""
                        ),
                        "sound" => "default",
                        "mutable-content" => 1,
                        "category" => "budfieIcon",
                        "badge" => (int) $notificationCount,
                        "data" => array(
                            "attachment_url" => $post_data["rest_image"],
                            "message" => $post_data["message"],
                            "ext_link" => $post_data["ext_link"],
                            "url_title" => $post_data["url_title"],
                            "content_type" => "image",
                            "type" => $post_data["type"],
                            "type_id" => $post_data["type_id"],
                            "category" => isset($post_data["category"]) ? $post_data["category"] : ""
                        )
                    ]
                ]),
                "GCM" => json_encode([
                    "data" => ["body" => array(
                            "title" => $post_data["title"],
                            "message" => $post_data["message"],
                            "type" => $post_data["type"],
                            "type_id" => $post_data["type_id"],
                            "image" => $post_data["rest_image"],
                            "ext_link" => $post_data["ext_link"],
                            "url_title" => $post_data["url_title"],
                            "category" => isset($post_data["category"]) ? $post_data["category"] : ""
                        )]
                ])
            ];
            $promises = $this->sns->publishAsync([
                'Message' => json_encode($message),
                'MessageStructure' => 'json',
                'TargetArn' => $arns['device_arn']
            ]);
//            if (isset($post_data['sender']) && !empty($post_data['sender'])) {
//                $insert_data['title'] = $post_data['title'];
//                $insert_data['sender_id'] = $post_data['sender'];
//                $insert_data['message'] = isset($post_data['message']) ? $post_data['message'] : "";
//                $insert_data['receive_id'] = $arns['id'];
//                $insert_data['device_type'] = $arns['device_type'];
//                $insert_data['type'] = $post_data['type'];
//                $insert_data['type_id'] = $post_data['type_id'];
//                $insert_data['category'] = isset($post_data["category"]) ? $post_data["category"] : "";
//                $insert_data['created_date'] = date('Y-m-d H:i:s');
//
//                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
//            }
            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {

            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /*
     * Send push to multiple user
     */

    public function asyncPublish2($arns, $post_data) {
        $CI = & get_instance();
        $CI->load->model('Common_model');
        try {
            $promises = array();
            $insert_data = array();
            $i = 0;
            foreach ($arns as $row => $val) {
                
                $insert_data['title'] = $post_data['title'];
                $insert_data['sender_id'] = $post_data['sender'];
                $insert_data['message'] = isset($post_data['message']) ? $post_data['message'] : "";
                $insert_data['receive_id'] = $val['id'];
                $insert_data['device_type'] = $val['device_type'];
                $insert_data['type'] = $post_data['type'];
                $insert_data['type_id'] = $post_data['type_id'];
                $insert_data['category'] = isset($post_data["category"]) ? $post_data["category"] : "";
                $insert_data['created_date'] = date('Y-m-d H:i:s');
                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
                
                $notificationCount = $this->getBadgeCount($val['id']);
                $message = [
                    "default" => $post_data["title"],
                    "APNS" => json_encode([
                        "aps" => [
                            "alert" => array(
                                "title" => "",
                                "body" => $post_data["message"],
                                "subtitle" => ""
                            ),
                            "sound" => "default",
                            "mutable-content" => 1,
                            "category" => "budfieIcon",
                            "badge" => (int) $notificationCount,
                            "data" => array(
                                "attachment_url" => $post_data["rest_image"],
                                "message" => $post_data["message"],
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "content_type" => "image",
                                "type" => $post_data["type"],
                                "type_id" => $post_data["type_id"],
                                "category" => isset($post_data["category"]) ? $post_data["category"] : ""
                            )
                        ]
                    ]),
                    "GCM" => json_encode([
                        "data" => ["body" => array(
                                "title" => $post_data["title"],
                                "message" => $post_data["message"],
                                "type" => $post_data["type"],
                                "type_id" => $post_data["type_id"],
                                "image" => $post_data["rest_image"],
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "category" => isset($post_data["category"]) ? $post_data["category"] : ""
                            )]
                    ])
                ];
                $promises[] = $this->sns->publishAsync([
                    'Message' => json_encode($message),
                    'MessageStructure' => 'json',
                    'TargetArn' => $val['device_arn']
                ]);
//                $insert_data[$i]['title'] = $post_data['title'];
//                $insert_data[$i]['sender_id'] = $post_data['sender'];
//                $insert_data[$i]['message'] = isset($post_data['message']) ? $post_data['message'] : "";
//                $insert_data[$i]['receive_id'] = $val['id'];
//                $insert_data[$i]['device_type'] = $val['device_type'];
//                $insert_data[$i]['type'] = $post_data['type'];
//                $insert_data[$i]['type_id'] = $post_data['type_id'];
//                $insert_data[$i]['category'] = isset($post_data["category"]) ? $post_data["category"] : "";
//                $insert_data[$i]['created_date'] = date('Y-m-d H:i:s');
//                $i++;
            }
         //   $CI->Common_model->insert_batch('budfie_notifications', array(), $insert_data);
            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {
            // file_put_contents(getcwd() . "/application/sns_log/error.txt", $error);
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /*
     * Send push to one user
     */

    public function singleChatPush($arns, $post_data) {

        $CI = & get_instance();
        $CI->load->model('Common_model');
        try {
            $promises = array();
            $insert_data = array();
            if (isset($post_data['sender']) && !empty($post_data['sender'])) {
                $insert_data['title'] = $post_data['title'];
                $insert_data['sender_id'] = $post_data['sender'];
                $insert_data['message'] = isset($post_data['message']) ? $post_data['message'] : "";
                $insert_data['receive_id'] = $arns['id'];
                $insert_data['device_type'] = $arns['device_type'];
                $insert_data['type'] = $post_data['type'];
                $insert_data['type_id'] = $post_data['type_id'];
                $insert_data['category'] = isset($post_data["category"]) ? $post_data["category"] : "";
                $insert_data['created_date'] = date('Y-m-d H:i:s');

                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
            }
            $notificationCount = $this->getBadgeCount($arns['id']);
            $message = [
                "default" => $post_data["title"],
                "APNS" => json_encode([
                    "aps" => [
                        "alert" => array(
                            "title" => $post_data["title"],
                            "body" => $post_data["message"],
                            "subtitle" => ""
                        ),
                        "sound" => "default",
                        "mutable-content" => 1,
                        "category" => "budfieIcon",
                        "badge" => (int) $notificationCount,
                        "data" => array(
                            "attachment_url" => $post_data["rest_image"],
                            "message" => $post_data["message"],
                            "ext_link" => $post_data["ext_link"],
                            "url_title" => $post_data["url_title"],
                            "content_type" => "image",
                            "type" => $post_data["type"],
                            "type_id" => $post_data["type_id"],
                            "category" => isset($post_data["category"]) ? $post_data["category"] : ""
                        )
                    ]
                ]),
                "GCM" => json_encode([
                    "data" => ["body" => array(
                            "title" => $post_data["title"],
                            "message" => $post_data["message"],
                            "type" => $post_data["type"],
                            "type_id" => $post_data["type_id"],
                            "image" => $post_data["rest_image"],
                            "ext_link" => $post_data["ext_link"],
                            "url_title" => $post_data["url_title"],
                            "category" => isset($post_data["category"]) ? $post_data["category"] : ""
                        )]
                ])
            ];
            $promises = $this->sns->publishAsync([
                'Message' => json_encode($message),
                'MessageStructure' => 'json',
                'TargetArn' => $arns['device_arn']
            ]);
//            if (isset($post_data['sender']) && !empty($post_data['sender'])) {
//                $insert_data['title'] = $post_data['title'];
//                $insert_data['sender_id'] = $post_data['sender'];
//                $insert_data['message'] = isset($post_data['message']) ? $post_data['message'] : "";
//                $insert_data['receive_id'] = $arns['id'];
//                $insert_data['device_type'] = $arns['device_type'];
//                $insert_data['type'] = $post_data['type'];
//                $insert_data['type_id'] = $post_data['type_id'];
//                $insert_data['category'] = isset($post_data["category"]) ? $post_data["category"] : "";
//                $insert_data['created_date'] = date('Y-m-d H:i:s');
//
//                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
//            }
            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {

            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /*
     * Send push to multiple user
     */

    public function groupChatPush($arns, $post_data) {
        $CI = & get_instance();
        $CI->load->model('Common_model');
        try {
            $promises = array();
            $insert_data = array();
            $i = 0;
            foreach ($arns as $row => $val) {
                $insert_data['title'] = $post_data['title'];
                $insert_data['sender_id'] = $post_data['sender'];
                $insert_data['message'] = isset($post_data['message']) ? $post_data['message'] : "";
                $insert_data['receive_id'] = $val['id'];
                $insert_data['device_type'] = $val['device_type'];
                $insert_data['type'] = $post_data['type'];
                $insert_data['type_id'] = $post_data['type_id'];
                $insert_data['category'] = isset($post_data["category"]) ? $post_data["category"] : "";
                $insert_data['created_date'] = date('Y-m-d H:i:s');
                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
                
                $notificationCount = $this->getBadgeCount($val['id']);
                $message = [
                    "default" => $post_data["title"],
                    "APNS" => json_encode([
                        "aps" => [
                            "alert" => array(
                                "title" => $post_data["title"],
                                "body" => $post_data["message"],
                                "subtitle" => ""
                            ),
                            "sound" => "default",
                            "mutable-content" => 1,
                            "category" => "budfieIcon",
                            "badge" => (int) $notificationCount,
                            "data" => array(
                                "attachment_url" => $post_data["rest_image"],
                                "message" => $post_data["message"],
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "content_type" => "image",
                                "type" => $post_data["type"],
                                "type_id" => $post_data["type_id"],
                                "category" => isset($post_data["category"]) ? $post_data["category"] : ""
                            )
                        ]
                    ]),
                    "GCM" => json_encode([
                        "data" => ["body" => array(
                                "title" => $post_data["title"],
                                "message" => $post_data["message"],
                                "type" => $post_data["type"],
                                "type_id" => $post_data["type_id"],
                                "image" => $post_data["rest_image"],
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "category" => isset($post_data["category"]) ? $post_data["category"] : ""
                            )]
                    ])
                ];
                $promises[] = $this->sns->publishAsync([
                    'Message' => json_encode($message),
                    'MessageStructure' => 'json',
                    'TargetArn' => $val['device_arn']
                ]);
//                $insert_data[$i]['title'] = $post_data['title'];
//                $insert_data[$i]['sender_id'] = $post_data['sender'];
//                $insert_data[$i]['message'] = isset($post_data['message']) ? $post_data['message'] : "";
//                $insert_data[$i]['receive_id'] = $val['id'];
//                $insert_data[$i]['device_type'] = $val['device_type'];
//                $insert_data[$i]['type'] = $post_data['type'];
//                $insert_data[$i]['type_id'] = $post_data['type_id'];
//                $insert_data[$i]['category'] = isset($post_data["category"]) ? $post_data["category"] : "";
//                $insert_data[$i]['created_date'] = date('Y-m-d H:i:s');
//                $i++;
            }
//            $CI->Common_model->insert_batch('budfie_notifications', array(), $insert_data);
            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {
            // file_put_contents(getcwd() . "/application/sns_log/error.txt", $error);
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /*
     * Send push to multiple user
     */

    public function eventNotification($eventArr, $post_data) {
        $CI = & get_instance();
        $CI->load->model('Common_model');
        try {
            $promises = array();
            $insert_data = array();
            $i = 0;
            foreach ($eventArr as $row => $val) {
                
                $insert_data['title'] = isset($val['title']) ? $val['title'] : "";
                $insert_data['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
                $insert_data['message'] = isset($post_data['message']) ? $post_data['message'] : "";
                $insert_data['receive_id'] = isset($val['id']) ? $val['id'] : "";
                $insert_data['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
                $insert_data['type'] = isset($post_data['type']) ? $post_data['type'] : "";
                $insert_data['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
                $insert_data['category'] = isset($val["type"]) ? $val["type"] : "";
                $insert_data['created_date'] = date('Y-m-d H:i:s');
                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
                
                $notificationCount = $this->getBadgeCount($val['id']);
                $message = [
                    "default" => isset($val["title"]) ? $val["title"] : "",
                    "APNS" => json_encode([
                        "aps" => [
                            "alert" => array(
                                "title" => "",
                                "body" => $post_data["message"],
                                "subtitle" => ""
                            ),
                            "sound" => "default",
                            "mutable-content" => 1,
                            "category" => "budfieIcon",
                            "badge" => (int) $notificationCount,
                            "data" => array(
                                "attachment_url" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "content_type" => "image",
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )
                        ]
                    ]),
                    "GCM" => json_encode([
                        "data" => ["body" => array(
                                "title" => isset($val["title"]) ? $val["title"] : "",
                                "message" => $post_data["message"],
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "image" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )]
                    ])
                ];
                $promises[] = $this->sns->publishAsync([
                    'Message' => json_encode($message),
                    'MessageStructure' => 'json',
                    'TargetArn' => $val['device_arn']
                ]);
//                $insert_data[$i]['title'] = isset($val['title']) ? $val['title'] : "";
//                $insert_data[$i]['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
//                $insert_data[$i]['message'] = isset($post_data['message']) ? $post_data['message'] : "";
//                $insert_data[$i]['receive_id'] = isset($val['id']) ? $val['id'] : "";
//                $insert_data[$i]['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
//                $insert_data[$i]['type'] = isset($post_data['type']) ? $post_data['type'] : "";
//                $insert_data[$i]['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
//                $insert_data[$i]['category'] = isset($val["type"]) ? $val["type"] : "";
//                $insert_data[$i]['created_date'] = date('Y-m-d H:i:s');
//                $i++;
            }
//            $CI->Common_model->insert_batch('budfie_notifications', array(), $insert_data);
            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {
            //   file_put_contents(getcwd() . "/application/sns_log/error.txt", $error);
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /*
     * Send push to multiple user
     */

    public function reminderNotification($reminderArr, $post_data) {

        $CI = & get_instance();
        $CI->load->model('Common_model');
        try {
            $promises = array();
            $insert_data = array();
            $i = 0;
            foreach ($reminderArr as $row => $val) {
                $insert_data['title'] = isset($val['title']) ? $val['title'] : "";
                $insert_data['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
                $insert_data['message'] = "Reminder Alert: " . $val["title"] . ' set by ' . $val['owner'];
                $insert_data['receive_id'] = isset($val['id']) ? $val['id'] : "";
                $insert_data['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
                $insert_data['type'] = isset($post_data['type']) ? $post_data['type'] : "";
                $insert_data['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
                $insert_data['category'] = isset($val["type"]) ? $val["type"] : "";
                $insert_data['created_date'] = date('Y-m-d H:i:s');
                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
                
                $notificationCount = $this->getBadgeCount($val['id']);
                $message = [
                    "default" => isset($val["title"]) ? $val["title"] : "",
                    "APNS" => json_encode([
                        "aps" => [
                            "alert" => array(
                                "title" => "",
                                "body" => "Reminder Alert: " . $val["title"] . ' set by ' . $val['owner'],
                                "subtitle" => ""
                            ),
                            "sound" => "default",
                            "mutable-content" => 1,
                            "category" => "budfieIcon",
                            "badge" => (int) $notificationCount,
                            "data" => array(
                                "attachment_url" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "content_type" => "image",
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )
                        ]
                    ]),
                    "GCM" => json_encode([
                        "data" => ["body" => array(
                                "title" => isset($val["title"]) ? $val["title"] : "",
                                "message" => "Reminder Alert: " . $val["title"] . ' set by ' . $val['owner'],
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "image" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )]
                    ])
                ];
                $promises[] = $this->sns->publishAsync([
                    'Message' => json_encode($message),
                    'MessageStructure' => 'json',
                    'TargetArn' => $val['device_arn']
                ]);
//                $insert_data[$i]['title'] = isset($val['title']) ? $val['title'] : "";
//                $insert_data[$i]['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
//                $insert_data[$i]['message'] = "Reminder Alert: " . $val["title"] . ' set by ' . $val['owner'];
//                $insert_data[$i]['receive_id'] = isset($val['id']) ? $val['id'] : "";
//                $insert_data[$i]['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
//                $insert_data[$i]['type'] = isset($post_data['type']) ? $post_data['type'] : "";
//                $insert_data[$i]['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
//                $insert_data[$i]['category'] = isset($val["type"]) ? $val["type"] : "";
//                $insert_data[$i]['created_date'] = date('Y-m-d H:i:s');
//                $i++;
            }
//            $CI->Common_model->insert_batch('budfie_notifications', array(), $insert_data);
            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {
            //   file_put_contents(getcwd() . "/application/sns_log/error.txt", $error);
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /*
     * Send push to multiple user
     */

    public function personalEventNotification($eventArr, $post_data) {
        $CI = & get_instance();
        $CI->load->model('Common_model');
        try {
            $promises = array();
            $insert_data = array();
            $i = 0;
            foreach ($eventArr as $row => $val) {
                $insert_data['title'] = isset($val['title']) ? $val['title'] : "";
                $insert_data['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
                $insert_data['message'] = "You have an event " . ucfirst($val["title"]) . " at " . date("H:i A", strtotime($val["event_time"])) . " today";
                $insert_data['receive_id'] = isset($val['id']) ? $val['id'] : "";
                $insert_data['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
                $insert_data['type'] = isset($post_data['type']) ? $post_data['type'] : "";
                $insert_data['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
                $insert_data['category'] = isset($val["type"]) ? $val["type"] : "";
                $insert_data['created_date'] = date('Y-m-d H:i:s');
                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
                
                $notificationCount = $this->getBadgeCount($val['id']);
                $message = [
                    "default" => isset($val["title"]) ? $val["title"] : "",
                    "APNS" => json_encode([
                        "aps" => [
                            "alert" => array(
                                "title" => "",
                                "body" => "You have an event " . ucfirst($val["title"]) . " at " . date("H:i A", strtotime($val["event_time"])) . " today",
                                "subtitle" => ""
                            ),
                            "sound" => "default",
                            "mutable-content" => 1,
                            "category" => "budfieIcon",
                            "badge" => (int) $notificationCount,
                            "data" => array(
                                "attachment_url" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "content_type" => "image",
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )
                        ]
                    ]),
                    "GCM" => json_encode([
                        "data" => ["body" => array(
                                "title" => isset($val["title"]) ? $val["title"] : "",
                                "message" => "You have an event " . ucfirst($val["title"]) . " at " . date("H:i A", strtotime($val["event_time"])) . " today",
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "image" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )]
                    ])
                ];
                $promises[] = $this->sns->publishAsync([
                    'Message' => json_encode($message),
                    'MessageStructure' => 'json',
                    'TargetArn' => $val['device_arn']
                ]);
//                $insert_data[$i]['title'] = isset($val['title']) ? $val['title'] : "";
//                $insert_data[$i]['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
//                $insert_data[$i]['message'] = "You have an event " . ucfirst($val["title"]) . " at " . date("H:i A", strtotime($val["event_time"])) . " today";
//                $insert_data[$i]['receive_id'] = isset($val['id']) ? $val['id'] : "";
//                $insert_data[$i]['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
//                $insert_data[$i]['type'] = isset($post_data['type']) ? $post_data['type'] : "";
//                $insert_data[$i]['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
//                $insert_data[$i]['category'] = isset($val["type"]) ? $val["type"] : "";
//                $insert_data[$i]['created_date'] = date('Y-m-d H:i:s');
//                $i++;
            }
//            $CI->Common_model->insert_batch('budfie_notifications', array(), $insert_data);
            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {
            //   file_put_contents(getcwd() . "/application/sns_log/error.txt", $error);
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /*
     * Send push to multiple user
     */

    public function sportNotification($eventArr, $post_data) {
        $CI = & get_instance();
        $CI->load->model('Common_model');
        try {
            $promises = array();
            $insert_data = array();
            $i = 0;
            foreach ($eventArr as $row => $val) {
                $insert_data['title'] = isset($val['title']) ? $val['title'] : "";
                $insert_data['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
                $insert_data['message'] = "Let Nothing come between you and your match. " . $val["title"] . " starting at " . date("H:i A", strtotime($val["match_time"])) . " today";
                $insert_data['receive_id'] = isset($val['id']) ? $val['id'] : "";
                $insert_data['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
                $insert_data['type'] = isset($post_data['type']) ? $post_data['type'] : "";
                $insert_data['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
                $insert_data['category'] = isset($val["type"]) ? $val["type"] : "";
                $insert_data['created_date'] = date('Y-m-d H:i:s');
                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
                
                $notificationCount = $this->getBadgeCount($val['id']);
                $message = [
                    "default" => isset($val["title"]) ? $val["title"] : "",
                    "APNS" => json_encode([
                        "aps" => [
                            "alert" => array(
                                "title" => "",
                                "body" => "Let Nothing come between you and your match. " . $val["title"] . " starting at " . date("H:i A", strtotime($val["match_time"])) . " today",
                                "subtitle" => ""
                            ),
                            "sound" => "default",
                            "mutable-content" => 1,
                            "category" => "budfieIcon",
                            "badge" => (int) $notificationCount,
                            "data" => array(
                                "attachment_url" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "content_type" => "image",
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )
                        ]
                    ]),
                    "GCM" => json_encode([
                        "data" => ["body" => array(
                                "title" => isset($val["title"]) ? $val["title"] : "",
                                "message" => "Let Nothing come between you and your match. " . $val["title"] . " starting at " . date("H:i A", strtotime($val["match_time"])) . " today",
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "image" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )]
                    ])
                ];
                $promises[] = $this->sns->publishAsync([
                    'Message' => json_encode($message),
                    'MessageStructure' => 'json',
                    'TargetArn' => $val['device_arn']
                ]);
//                $insert_data[$i]['title'] = isset($val['title']) ? $val['title'] : "";
//                $insert_data[$i]['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
//                $insert_data[$i]['message'] = "Let Nothing come between you and your match. " . $val["title"] . " starting at " . date("H:i A", strtotime($val["match_time"])) . " today";
//                $insert_data[$i]['receive_id'] = isset($val['id']) ? $val['id'] : "";
//                $insert_data[$i]['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
//                $insert_data[$i]['type'] = isset($post_data['type']) ? $post_data['type'] : "";
//                $insert_data[$i]['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
//                $insert_data[$i]['category'] = isset($val["type"]) ? $val["type"] : "";
//                $insert_data[$i]['created_date'] = date('Y-m-d H:i:s');
//                $i++;
            }
//            $CI->Common_model->insert_batch('budfie_notifications', array(), $insert_data);
            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {
            //   file_put_contents(getcwd() . "/application/sns_log/error.txt", $error);
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /*
     * Send push to multiple user
     */

    public function sportEndNotification($eventArr, $post_data) {
        $CI = & get_instance();
        $CI->load->model('Common_model');
        try {
            $promises = array();
            $insert_data = array();
            $i = 0;
            foreach ($eventArr as $row => $val) {
                $insert_data['title'] = isset($val['title']) ? $val['title'] : "";
                $insert_data['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
                $insert_data['message'] = "What a match it was. " . $val["title"];
                $insert_data['receive_id'] = isset($val['id']) ? $val['id'] : "";
                $insert_data['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
                $insert_data['type'] = isset($post_data['type']) ? $post_data['type'] : "";
                $insert_data['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
                $insert_data['category'] = isset($val["type"]) ? $val["type"] : "";
                $insert_data['created_date'] = date('Y-m-d H:i:s');
                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
                
                $notificationCount = $this->getBadgeCount($val['id']);
                $message = [
                    "default" => isset($val["title"]) ? $val["title"] : "",
                    "APNS" => json_encode([
                        "aps" => [
                            "alert" => array(
                                "title" => "",
                                "body" => "What a match it was. " . $val["title"],
                                "subtitle" => ""
                            ),
                            "sound" => "default",
                            "mutable-content" => 1,
                            "category" => "budfieIcon",
                            "badge" => (int) $notificationCount,
                            "data" => array(
                                "attachment_url" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "content_type" => "image",
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )
                        ]
                    ]),
                    "GCM" => json_encode([
                        "data" => ["body" => array(
                                "title" => isset($val["title"]) ? $val["title"] : "",
                                "message" => "What a match it was. " . $val["title"],
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "image" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )]
                    ])
                ];
                $promises[] = $this->sns->publishAsync([
                    'Message' => json_encode($message),
                    'MessageStructure' => 'json',
                    'TargetArn' => $val['device_arn']
                ]);
//                $insert_data[$i]['title'] = isset($val['title']) ? $val['title'] : "";
//                $insert_data[$i]['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
//                $insert_data[$i]['message'] = "What a match it was. " . $val["title"];
//                $insert_data[$i]['receive_id'] = isset($val['id']) ? $val['id'] : "";
//                $insert_data[$i]['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
//                $insert_data[$i]['type'] = isset($post_data['type']) ? $post_data['type'] : "";
//                $insert_data[$i]['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
//                $insert_data[$i]['category'] = isset($val["type"]) ? $val["type"] : "";
//                $insert_data[$i]['created_date'] = date('Y-m-d H:i:s');
//                $i++;
            }
//            $CI->Common_model->insert_batch('budfie_notifications', array(), $insert_data);
            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {
            //   file_put_contents(getcwd() . "/application/sns_log/error.txt", $error);
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /*
     * Send push to multiple user
     */

    public function birthdayNotification($user_data, $post_data) {
        $CI = & get_instance();
        $CI->load->model('Common_model');
        try {
            $promises = array();
            $insert_data = array();
           // $i = 0;
            foreach ($user_data as $row => $val) {
                
                $insert_data['title'] = isset($post_data['title']) ? $post_data['title'] : "";
                $insert_data['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
                $insert_data['message'] = $val['first_name'] . " has their birthday today. Please click to share a special wish with them";
                $insert_data['receive_id'] = isset($val['id']) ? $val['id'] : "";
                $insert_data['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
                $insert_data['type'] = isset($post_data['type']) ? $post_data['type'] : "";
                $insert_data['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
                $insert_data['category'] = isset($val["type"]) ? $val["type"] : "";
                $insert_data['created_date'] = date('Y-m-d H:i:s');
                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
                
                $notificationCount = $this->getBadgeCount($val['id']);
                $message = [
                    "default" => isset($post_data["title"]) ? $post_data["title"] : "",
                    "APNS" => json_encode([
                        "aps" => [
                            "alert" => array(
                                "title" => "",
                                "body" => $val['first_name'] . " has their birthday today. Please click to share a special wish with them",
                                "subtitle" => ""
                            ),
                            "sound" => "default",
                            "mutable-content" => 1,
                            "category" => "budfieIcon",
                            "badge" => (int) $notificationCount,
                            "data" => array(
                                "attachment_url" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "content_type" => "image",
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )
                        ]
                    ]),
                    "GCM" => json_encode([
                        "data" => ["body" => array(
                                "title" => isset($post_data["title"]) ? $post_data["title"] : "",
                                "message" => $val['first_name'] . " has their birthday today. Please click to share a special wish with them",
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "image" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )]
                    ])
                ];
                $promises[] = $this->sns->publishAsync([
                    'Message' => json_encode($message),
                    'MessageStructure' => 'json',
                    'TargetArn' => $val['device_arn']
                ]);
//                $insert_data[$i]['title'] = isset($post_data['title']) ? $post_data['title'] : "";
//                $insert_data[$i]['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
//                $insert_data[$i]['message'] = $val['first_name'] . " has their birthday today. Please click to share a special wish with them";
//                $insert_data[$i]['receive_id'] = isset($val['id']) ? $val['id'] : "";
//                $insert_data[$i]['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
//                $insert_data[$i]['type'] = isset($post_data['type']) ? $post_data['type'] : "";
//                $insert_data[$i]['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
//                $insert_data[$i]['category'] = isset($val["type"]) ? $val["type"] : "";
//                $insert_data[$i]['created_date'] = date('Y-m-d H:i:s');
//                $i++;
            }
//            $CI->Common_model->insert_batch('budfie_notifications', array(), $insert_data);
            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {
            //   file_put_contents(getcwd() . "/application/sns_log/error.txt", $error);
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    /*
     * Send push to multiple user
     */

    public function userBirthday($user_data, $post_data) {
        $CI = & get_instance();
        $CI->load->model('Common_model');
        try {
            $promises = array();
            $insert_data = array();
            //$i = 0;
            foreach ($user_data as $row => $val) {
                $insert_data['title'] = isset($post_data['title']) ? $post_data['title'] : "";
                $insert_data['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
                $insert_data['message'] = "Happy Birthday " . $val['first_name'] . " Budfie Wishes you a rocking Year Ahead.";
                $insert_data['receive_id'] = isset($val['id']) ? $val['id'] : "";
                $insert_data['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
                $insert_data['type'] = isset($post_data['type']) ? $post_data['type'] : "";
                $insert_data['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
                $insert_data['category'] = isset($val["type"]) ? $val["type"] : "";
                $insert_data['created_date'] = date('Y-m-d H:i:s');
                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
                
                $notificationCount = $this->getBadgeCount($val['id']);
                $message = [
                    "default" => isset($post_data["title"]) ? $post_data["title"] : "",
                    "APNS" => json_encode([
                        "aps" => [
                            "alert" => array(
                                "title" => "",
                                "body" => "Happy Birthday " . $val['first_name'] . " Budfie Wishes you a rocking Year Ahead.",
                                "subtitle" => ""
                            ),
                            "sound" => "default",
                            "mutable-content" => 1,
                            "category" => "budfieIcon",
                            "badge" => (int) $notificationCount,
                            "data" => array(
                                "attachment_url" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "content_type" => "image",
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )
                        ]
                    ]),
                    "GCM" => json_encode([
                        "data" => ["body" => array(
                                "title" => isset($post_data["title"]) ? $post_data["title"] : "",
                                "message" => "Happy Birthday " . $val['first_name'] . " Budfie Wishes you a rocking Year Ahead.",
                                "type" => $post_data["type"],
                                "type_id" => isset($val["type_id"]) ? $val["type_id"] : "",
                                "image" => isset($val["rest_image"]) ? $val["rest_image"] : "",
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "category" => isset($val["type"]) ? $val["type"] : ""
                            )]
                    ])
                ];
                $promises[] = $this->sns->publishAsync([
                    'Message' => json_encode($message),
                    'MessageStructure' => 'json',
                    'TargetArn' => $val['device_arn']
                ]);
//                $insert_data[$i]['title'] = isset($post_data['title']) ? $post_data['title'] : "";
//                $insert_data[$i]['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
//                $insert_data[$i]['message'] = "Happy Birthday " . $val['first_name'] . " Budfie Wishes you a rocking Year Ahead.";
//                $insert_data[$i]['receive_id'] = isset($val['id']) ? $val['id'] : "";
//                $insert_data[$i]['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
//                $insert_data[$i]['type'] = isset($post_data['type']) ? $post_data['type'] : "";
//                $insert_data[$i]['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
//                $insert_data[$i]['category'] = isset($val["type"]) ? $val["type"] : "";
//                $insert_data[$i]['created_date'] = date('Y-m-d H:i:s');
//                $i++;
            }
//            $CI->Common_model->insert_batch('budfie_notifications', array(), $insert_data);
            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {
            //   file_put_contents(getcwd() . "/application/sns_log/error.txt", $error);
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

    private function getBadgeCount($id) {
        $CI = & get_instance();
        $CI->load->model('Common_model');

        $user_tab_count = $CI->Common_model->fetch_data('budfie_users', array('push_tab_count'), array('where' => array('id' => $id)), true);

        $notiCount = $CI->Common_model->fetch_data('budfie_notifications', array('count(*) as count'), array('where' => array('receive_id' => $id, 'read_status' => 0, 'status' => 0), 'where_not_in' => array('type' => 8)), true);
        $count = $notiCount['count'] - $user_tab_count['push_tab_count'];

        if ($count > 0)
            return $count;
        else
            return 0;
    }
    
    
    public function adminPush($user_data, $post_data) {
       
        try {
            $CI = & get_instance();
            $CI->load->model('Common_model');

            $promises = array();
            $insert_data = array();
            //$i = 0;
            foreach ($user_data as $row => $val) 
            {
                # save notification to the database
                $insert_data['title'] = isset($post_data['title']) ? $post_data['title'] : "";
                $insert_data['sender_id'] = isset($post_data['sender']) ? $post_data['sender'] : "";
                $insert_data['message'] = $post_data["message"];
                $insert_data['receive_id'] = isset($val['id']) ? $val['id'] : "";
                $insert_data['device_type'] = isset($val['device_type']) ? $val['device_type'] : "";
                $insert_data['type'] = isset($post_data['type']) ? $post_data['type'] : "";
                $insert_data['type_id'] = isset($val["type_id"]) ? $val["type_id"] : "";
                $insert_data['category'] = isset($val["type"]) ? $val["type"] : "";
                $insert_data['created_date'] = date('Y-m-d H:i:s');
                $id = $CI->Common_model->insert_single('budfie_notifications', $insert_data);
                
                $notificationCount = $this->getBadgeCount($val['id']);
               
                $message = [
                    "default" => $post_data["title"],
                    "APNS" => json_encode([
                        "aps" => [
                            "alert" => array(
                                "title" => $post_data["title"],
                                "body" => $post_data["message"],
                                "subtitle" => ""
                            ),
                            "sound" => "default",
                            "mutable-content" => 1,
                            "category" => "budfieIcon",
                            "badge" => (int) $notificationCount,
                            "data" => array(
                                "attachment_url" => $post_data["rest_image"],
                                "message" => $post_data["message"],
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "content_type" => "image",
                                "type" => $post_data["type"],
                                "type_id" => $post_data["type_id"],
                                "category" => isset($post_data["category"]) ? $post_data["category"] : ""
                            )
                        ]
                    ]),
                    "GCM" => json_encode([
                        "data" => ["body" => array(
                                "title" => $post_data["title"],
                                "message" => $post_data["message"],
                                "type" => $post_data["type"],
                                "type_id" => $post_data["type_id"],
                                "image" => $post_data["rest_image"],
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "category" => isset($post_data["category"]) ? $post_data["category"] : ""
                            )]
                    ])
                ];

                $promises[] = $this->sns->publishAsync([
                    'Message' => json_encode($message),
                    'MessageStructure' => 'json',
                    'TargetArn' => $val['device_arn']
                ]);

            }

            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {
            file_put_contents(getcwd() . "/application/sns_log/error.txt", $error);
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }


        /*
     * Send push to multiple user
     */

    public function asyncPublishWithout_Db_Insert($val, $post_data) {
       
        
        try {
            $promises = array();
            $insert_data = array();
            $i = 0;

                $message = [
                    "default" => $post_data["title"],
                    "APNS" => json_encode([
                        "aps" => [
                            "alert" => array(
                                "title" => "",
                                "body" => $post_data["message"],
                                "subtitle" => ""
                            ),
                            "sound" => "default",
                            "mutable-content" => 1,
                            "category" => "budfieIcon",
                            "badge" => (int) 0,
                            "data" => array(
                                "attachment_url" => $post_data["rest_image"],
                                "message" => $post_data["message"],
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "content_type" => "image",
                                "type" => $post_data["type"],
                                "type_id" => $post_data["type_id"],
                                "category" => isset($post_data["category"]) ? $post_data["category"] : ""
                            )
                        ]
                    ]),
                    "GCM" => json_encode([
                        "data" => ["body" => array(
                                "title" => $post_data["title"],
                                "message" => $post_data["message"],
                                "type" => $post_data["type"],
                                "type_id" => $post_data["type_id"],
                                "image" => $post_data["rest_image"],
                                "ext_link" => $post_data["ext_link"],
                                "url_title" => $post_data["url_title"],
                                "category" => isset($post_data["category"]) ? $post_data["category"] : ""
                            )]
                    ])
                ];
                $promises[] = $this->sns->publishAsync([
                    'Message' => json_encode($message),
                    'MessageStructure' => 'json',
                    'TargetArn' => $val['device_arn']
                ]);

            $allPromise = \GuzzleHttp\Promise\all($promises);
            $data_promise = $allPromise->wait();
            return [
                "success" => true,
                "message" => "OK",
                "result" => $data_promise
            ];
        } catch (\Exception $error) {
            // file_put_contents(getcwd() . "/application/sns_log/error.txt", $error);
            return [
                "success" => false,
                "message" => "error"
            ];
        }
    }

}
