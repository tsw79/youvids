<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/23/2019
 * Time: 17:47
 */
namespace youvids\application\controllers;

use phpchassis\http\FormRequest;

/**
 * Class VideoRequest
 * @package youvids\application\controllers
 */
class VideoRequest extends FormRequest {

    /**
     * List of fields that need to be validated per Controller's action
     * @override
     * @var array
     */
//    protected $formFields = [
//        "signIn" => ["username", "password"]
//    ];

    /**
     * Returns true if the user is authorized to make this request.
     * @return bool
     */
    public function authorization(): bool {
        // return $this->user()->can('create', Admin::class);
        return true;
    }

    /**
     * Returns a list of assignment declarations for form data filtering and validation.
     * @return array|null
     */
    public function rules(): ?array {

        return [
            /*
             * @TODO Need to validate the uploaded file is an authentic and acceptable video type
             */
            "videoFile"   => [
                [ 'key' => 'required' ]
            ],
            "title"       => [
                [ 'key' => 'length', 'params' => ['max' => 50] ],
                [ 'key' => 'required' ]
            ],
            "description" => [
                [ 'key' => 'length', 'params' => ['max' => 100] ],
            ],
            "privacy"     => [
                [ 'key' => 'integer' ],
                [ 'key' => 'required' ]
            ],
            "category"    => [
                [ 'key' => 'integer' ],
                [ 'key' => 'required' ]
            ]
        ];
    }

    /**
     * Returns a list of data filters
     * @return array|null
     */
    public function filters(): ?array {

        return [
            '*' => [
                [ 'key' => 'trim', "except" => ["privacy", "category"] ],
                [ 'key' => 'strip_tags' ]
            ]
        ];
    }
}