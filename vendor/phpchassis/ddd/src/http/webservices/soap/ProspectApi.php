<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/29/2019
 * Time: 15:16
 * 
 **************************************
 ** NOTE:  THIS IS A SAMPLE SOAP API **
 **************************************
 */
namespace phpchassis\http\webservices\soap;

/**
 * Class ProspectApi
 * @package phpchassis\http\webservices\soap
 */
class ProspectApi {

    /**
     * @var
     */
    protected $registerKeys;

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * ProspectApi constructor.
     * @param \PDO $pdo
     * @param $registeredKeys
     */
    public function __construct(\PDO $pdo, $registeredKeys) {

        $this->pdo = $pdo;
        $this->registeredKeys = $registeredKeys;
    }

    // @TODO Implement these methods
    public function put(array $request, array $response) {}
    public function post(array $request, array $response) {}
    public function delete(array $request, array $response) {}

    /**
     * @param array $request
     * @param array $response
     * @return array|bool|mixed
     */
    public function get(array $request, array $response) {

        if (!$this->authenticate($request)) {
            return false;
        }

        $result = array();
        $id = $request[self::ID_FIELD] ?? 0;
        $email = $request[self::EMAIL_FIELD] ?? 0;

        if ($id > 0) {
            $result = $this->fetchById($id);                                                    // @db initialisation
            $response[self::ID_FIELD] = $id;
        }
        elseif ($email) {
            $result = $this->fetchByEmail($email);                                              // @db initialisation
            $response[self::ID_FIELD] = $result[self::ID_FIELD] ?? 0;
        }
        else {
            $limit = $request[self::LIMIT_FIELD] ?? self::DEFAULT_LIMIT;
            $offset = $request[self::OFFSET_FIELD] ?? self::DEFAULT_OFFSET;
            $result = [];

            foreach ($this->fetchAll($limit, $offset) as $row) {                                // @db initialisation
                $result[] = $row;
            }
        }

        $response = $this->processResponse(
            $result, $response, self::SUCCESS, self::ERROR
        );

        return $response;
    }

    /**
     * Processes the response
     * @param $result
     * @param $response
     * @param $success_code
     * @param $error_code
     * @return mixed
     */
    protected function processResponse($result, $response, $success_code, $error_code) {

        if ($result) {
            $response['data'] = $result;
            $response['code'] = $success_code;
            $response['status'] = self::STATUS_200;
        }
        else {
            $response['data'] = false;
            $response['code'] = self::ERROR_NOT_FOUND;
            $response['status'] = self::STATUS_500;
        }

        return $response;
    }
}