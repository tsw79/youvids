<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/29/2019
 * Time: 01:43
 * 
 ***************************************
 **  NOTE:  THIS IS A SAMPLE REST API **
 ***************************************
 */
namespace phpchassis\http\webservices\rest\api;

use phpchassis\http\webservices\rest\ {
    Request as RestRequest,
    Response as RestResponse,
    BaseApi as BaseRestApi
};

/**
 * Class CustomerApi
 *
 * @package phpchassis\http\rest\api
 */
class CustomerApi extends BaseRestApi {

    const ERROR = 'ERROR';
    const ERROR_NOT_FOUND = 'ERROR: Not Found';
    const SUCCESS_UPDATE = 'SUCCESS: update succeeded';
    const SUCCESS_DELETE = 'SUCCESS: delete succeeded';
    const ID_FIELD = 'id'; // field name of primary key
    const TOKEN_FIELD = 'token'; // field used for authentication
    const LIMIT_FIELD = 'limit';
    const OFFSET_FIELD = 'offset';
    const DEFAULT_LIMIT = 20;
    const DEFAULT_OFFSET = 0;

    /**
     * @var CustomerService
     */
    protected $service;

    /**
     * CustomerApi constructor.
     * @param $registeredKeys
     * @param $dbparams
     * @param null $tokenField
     */
    public function __construct($registeredKeys, $dbparams, $tokenField = null) {
        parent::__construct($registeredKeys, $tokenField);
        $this->service = new CustomerService(new Connection($dbparams));                // @db initialisation
    }

    /**
     * Retrieve information about a given customer
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function get(RestRequest $request, RestResponse $response) {

        $result = array();
        $id = $request->dataByKey(self::ID_FIELD) ?? 0;

        if ($id > 0) {
            $result = $this->service->fetchById($id)->entityToArray();                  // @db query
        }
        else {
            $limit = $request->dataByKey(self::LIMIT_FIELD) ?? self::DEFAULT_LIMIT;
            $offset = $request->getDataByKey(self::OFFSET_FIELD) ?? self::DEFAULT_OFFSET;
            $result = [];
            $fetch = $this->service->fetchAll($limit, $offset);                         // @db query

            foreach ($fetch as $row) {
                $result[] = $row;
            }
        }

        if ($result) {
            $response->data($result);
            $response->status(RestRequest::STATUS_200);
        } else {
            $response->data([self::ERROR_NOT_FOUND]);
            $response->status(RestRequest::STATUS_500);
        }
    }

    /**
     * Inserts customer data
     * @param Request $request
     * @param Response $response
     * @return mixed|void
     */
    public function put(RestRequest $request, RestResponse $response) {

        $cust = Customer::arrayToEntity(
            $request->data(),
            new Customer()
        );
        if ($newCust = $this->service->save($cust)) {                                   // @db query
            $response->data([
                'success' => self::SUCCESS_UPDATE,
                'id'      => $newCust->getId()
            ]);
            $response->status(RestRequest::STATUS_200);
        }
        else {
            $response->data([self::ERROR]);
            $response->status(RestRequest::STATUS_500);
        }
    }

    /**
     * Updates an existing customer's entry
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function post(RestRequest $request, RestResponse $response) {

        $id = $request->dataByKey(self::ID_FIELD) ?? 0;
        $reqData = $request->data();
        $custData = $this->service->fetchById($id)->entityToArray();                    // @db query
        $updateData = array_merge($custData, $reqData);
        $updateCust = Customer::arrayToEntity($updateData,new Customer());

        if ($this->service->save($updateCust)) {                                        // @db query
            $response->data([
                'success' => self::SUCCESS_UPDATE,
                'id'      => $updateCust->getId()
            ]);
            $response->status(RestRequest::STATUS_200);
        }
        else {
            $response->data([self::ERROR]);
            $response->status(RestRequest::STATUS_500);
        }
    }

    /**
     * Removes a given customer's entry
     * @param Request $request
     * @param Response $response
     * @return mixed|void
     */
    public function delete(RestRequest $request, RestResponse $response) {

        $id = $request->dataByKey(self::ID_FIELD) ?? 0;
        $cust = $this->service->fetchById($id);                                         // @db query

        if ($cust && $this->service->remove($cust)) {                                   // @db query
            $response->data([
                'success' => self::SUCCESS_DELETE,
                'id'      => $id
            ]);
            $response->status(RestRequest::STATUS_200);
        }
        else {
            $response->data([self::ERROR_NOT_FOUND]);
            $response->status(RestRequest::STATUS_500);
        }
    }

    /**
     * Authenticates a REST request
     * @param Request $request
     * @return bool|mixed
     */
    public function authenticate(RestRequest $request): bool {

        $authToken = $request->dataByKey(self::TOKEN_FIELD) ?? false;

        if (in_array($authToken, $this->registeredKeys, true)) {
            return true;
        }
        else {
            return false;
        }
    }
}