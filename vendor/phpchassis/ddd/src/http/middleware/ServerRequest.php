<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/11/2019
 * Time: 00:35
 */
namespace phpchassis\http\middleware;

use phpchassis\http\middleware\file\UploadedFile;
use Psr\Http\Message\ {ServerRequestInterface, UploadedFileInterface} ;
use Psr\Log\InvalidArgumentException;
use phpchassis\http\middleware\Constants;

/**
 * Class ServerRequest
 * @package phpchassis-ddd\middleware
 */
class ServerRequest extends Request implements ServerRequestInterface {

    /**
     * @var $serverParams
     */
    protected $serverParams;

    /**
     * @var $cookies
     */
    protected $cookies;

    /**
     * @var $queryParams
     */
    protected $queryParams;

    /**
     * @var $contentType
     */
    protected $contentType;

    /**
     * @var $parsedBody
     */
    protected $parsedBody;

    /**
     * @var object
     */
    protected $data = null;

    /**
     * @var $attributes
     */
    protected $attributes;

    /**
     * @var $method
     */
    protected $method;

    /**
     * @var $uploadedFileInfo
     */
    protected $uploadedFileInfo;

    /**
     * @var array UploadedFile
     */
    protected $uploadedFileObjs;

    /**
     * @var UploadedFile
     */
    protected $uploadedFileObj;

    /**
     * Holds information about the currently executing scripts' filepath
     * @var array
     */
    protected $scriptInfo = null;

    /**
     * In order to load the different properties from an in-bound request, we define initialize(),
     * which is not in PSR-7, but is convenient.
     *
     * @return $this
     */
    public function initialize() {

        $this->getServerParams();
        $this->getCookieParams();
        $this->getQueryParams();
        $this->getUploadedFiles();
        $this->getRequestMethod();
        $this->getContentType();
        $this->getParsedBody();
        $this->getData();
        return $this;
    }

    /**
     * Super-global Getter: Returns server paramaters
     * @return mixed
     */
    public function getServerParams(): array {

        if (!$this->serverParams) {
            $this->serverParams = $_SERVER;
        }
        return $this->serverParams;
    }

    /**
     * Safely, this method returns the value of a given parameter from the $_SERVER SupergGlobals
     * @param string $param
     * @return string
     */
    public function getServerParam(string $param): string {
        return htmlspecialchars(
            $param === "REQUEST_URI_FULL" ? $this->getFullUri() : $this->serverParams[$param]
        );
    }

    /**
     * Returns the Full Uri of the current request
     * @return string
     */
    private function getFullUri(): string {
        $scheme = isset($this->serverParams['HTTPS']) && $this->serverParams['HTTPS'] === 'on' ? "https" : "http";
        return "{$scheme}://{$this->serverParams['HTTP_HOST']}{$this->serverParams['REQUEST_URI']}";
    }

    /**
     * Returns information about the currently executing scripts' filepath
     * @return array
     */
    public function getScriptInfo(): array {
        if (null === $this->scriptInfo) {
            $this->scriptInfo = pathinfo($this->getRequestTarget());
        }
        return $this->scriptInfo;
    }

    /**
     * Super-global Getter: Returns cookies paramaters
     * @return mixed
     */
    public function getCookieParams() {
        if (!$this->cookies) {
            $this->cookies = $_COOKIE;
        }
        return $this->cookies;
    }

    /**
     * Super-global Getter: Returns Query paramaters
     * @return mixed
     */
    public function getQueryParams() {
        if (!$this->queryParams) {
            $this->queryParams = $_GET;
        }
        return $this->queryParams;
    }

    /**
     * Super-global Getter: Returns info about Uploaded File paramaters
     * @return mixed
     */
    public function getUploadedFileInfo() {
        if (!$this->uploadedFileInfo) {
            $this->uploadedFileInfo = $_FILES;
        }
        return $this->uploadedFileInfo;
    }

    /**
     * Returns the request method
     * @return mixed
     */
    public function getRequestMethod() {
        $method = $this->getServerParams()['REQUEST_METHOD'] ?? '';
        $this->method = strtolower($method);
        return $this->method;
    }

    /**
     * Returns the content type
     * @return mixed
     */
    public function getContentType() {
        if (!$this->contentType) {
            $this->contentType = $this->getServerParams()['CONTENT_TYPE'] ?? '';
            $this->contentType = strtolower($this->contentType);
        }
        return $this->contentType;
    }

    /**
     * This method takes $uploadedFileInfo and creates UploadedFile objects as uploaded files are supposed to be
     * represented as independent UploadedFile objects.
     * @return array
     */
    public function getUploadedFiles(): ?array {

        if (!$this->uploadedFileObjs) {
            foreach ($this->getUploadedFileInfo() as $field => $value) {
                $this->uploadedFileObjs[$field] = new UploadedFile($field, $value);
            }
        }

        return $this->uploadedFileObjs;
    }

    /**
     * Alias for method getUploadedFiles
     * @return array
     */
    public function uploadedFiles(): ?array {
        return $this->getUploadedFiles();
    }



    /**
     * Returns a single instance of UploadedFile
     * @return UploadedFileInterface
     */
    public function getUploadedFile(): UploadedFileInterface {

        if (!$this->uploadedFileObj) {

            $fileInfo = $this->getUploadedFileInfo();
            $field = key($fileInfo);
            $this->uploadedFileObj = new UploadedFile($field, $fileInfo[$field]);
        }
        return $this->uploadedFileObj;
    }

    /**
     * Alias for method getUploadedFile
     * @alias
     * @return UploadedFileInterface
     */
    public function uploadedFile(): UploadedFileInterface {
        return $this->getUploadedFile();
    }

    /**
     * Returns a list files by a given name
     * @return array
     */
    public function files(string $filename): array {
        return $this->uploadedFileObjs[$filename];
    }

    /**
     * Returns a file by a given name
     * @return UploadedFileInterface
     */
    public function file(string $filename): UploadedFileInterface {
        return $this->uploadedFileObjs[$filename];
    }

    public function hasFile(string $filename): bool {
        return isset($this->uploadedFileObjs[$filename]);
    }

// --------------------------------------------------------------------------------------
// with methods are provided to add or overwrite properties and return the new instance:
// --------------------------------------------------------------------------------------

    /**
     * BUILDER: Adds an array of cookies info to this object and returns it
     *
     * @param array $cookies
     * @return $this
     */
    public function withCookieParams(array $cookies) {
        array_merge($this->getCookieParams(), $cookies);
        return $this;
    }

    /**
     * BUILDR: Adds an array of query info to this object and returns it
     *
     * @param array $query
     * @return $this
     */
    public function withQueryParams(array $query) {
        array_merge($this->getQueryParams(), $query);
        return $this;
    }

    /**
     * BUILDR: withUploadedFiles
     * @param array $uploadedFiles
     * @throws InvalidArgumentException
     * @return null
     */
    public function withUploadedFiles(array $uploadedFiles) {

        if (!count($uploadedFiles)) {
            throw new InvalidArgumentException(Constants::ERROR_NO_UPLOADED_FILES);
        }
        foreach ($uploadedFiles as $fileObj) {
            if (!$fileObj instanceof UploadedFileInterface) {
                throw new InvalidArgumentException(Constants::ERROR_INVALID_UPLOADED);
            }
        }
        $this->uploadedFileObjs = $uploadedFiles;
    }

    /**
     * getParsedBody() method makes the body available in a parsed manner.
     *
     * @return mixed
     */
    public function getParsedBody() {

        if (!$this->parsedBody) {

            if (($this->getContentType() == Constants::CONTENT_TYPE_FORM_ENCODED || $this->getContentType() == Constants::CONTENT_TYPE_MULTI_FORM)
                && $this->getRequestMethod() == Constants::METHOD_POST) {

                $this->parsedBody = $_POST;
            }
            elseif ($this->getContentType() == Constants::CONTENT_TYPE_JSON || $this->getContentType() == Constants::CONTENT_TYPE_HAL_JSON) {

                ini_set("allow_url_fopen", true);
                $this->parsedBody = json_decode(file_get_contents('php://input'));
            }
            elseif (!empty($_REQUEST)) {
                $this->parsedBody = $_REQUEST;
            }
            else {
                ini_set("allow_url_fopen", true);
                $this->parsedBody = file_get_contents('php://input');
            }
        }
        return $this->parsedBody;
    }

    /**
     * Returns an SPL object of $parsedBody
     * @return object
     */
    public function getData(): ?object {
        if (null === $this->data) {

            if (is_array($this->parsedBody)) {
                $this->data = (object) $this->parsedBody;
            }
        }
        return $this->data;
    }

    /**
     * BUILDER: withParsedBody
     *
     * @param $data
     * @return $this
     */
    public function withParsedBody($data) {
        $this->parsedBody = $data;
        return $this;
    }

// --------------------------------------------------------------------------------------
// Allowing for attributes that are not precisely defined in PSR-7.
// --------------------------------------------------------------------------------------

    /**
     * Returns an array of attributes
     * @return mixed
     */
    public function getAttributes() {
        return $this->attributes;
    }

    /**
     * Returns a particular attribute from the list of attributes
     * @param $name
     * @param null $default
     * @return null
     */
    public function getAttribute($name, $default = NULL) {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * BUILDER: Sets an element in the attributes array and returns the instance
     * @param $name
     * @param $value
     * @return $this
     */
    public function withAttribute($name, $value) {
        $this->attributes[$name] = $value;
        return $this;
    }

    /**
     * Removes an attribute from the attributes array
     * @param $name
     * @return $this
     */
    public function withoutAttribute($name) {
        if (isset($this->attributes[$name])) {
            unset($this->attributes[$name]);
        }
        return $this;
    }

    /**
     * Returns the value of the given property as set in $uploadedFileObjs, otherwise from $parsedBody
     * @param string $propertyName
     * @return mixed
     */
    public function __get(string $propertyName) {

        return ($this->hasFile($propertyName))
            ? $this->uploadedFileObjs[$propertyName]
            : $this->parsedBody[$propertyName];
    }
}