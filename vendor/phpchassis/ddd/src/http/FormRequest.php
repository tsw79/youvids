<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 7/4/2019
 * Time: 19:43
 */
namespace phpchassis\http;

use phpchassis\auth\AccessControl;
use phpchassis\auth\AuthUser;
use phpchassis\configs\base\ConfigLoader;
use phpchassis\data\DIContainer;
use phpchassis\data\dto\AuthorizeCredential;
use phpchassis\filter\ {Callbacks, Filter, Validator};
use phpchassis\http\middleware\HttpStatusCode;
use phpchassis\http\middleware\Request;
use phpchassis\http\middleware\Response;
use phpchassis\http\middleware\ServerRequest;
use phpchassis\http\middleware\TextStream;
use phpchassis\lib\traits\PhpCommons;
use phpchassis\http\FlashMessage;

/**
 * Class FormRequest
 * @package phpchassis\http
 */
abstract class FormRequest extends ServerRequest {

    use PhpCommons;

    /**
     * @var array
     */
    private $results = [];

    /**
     * List of fields that need to be validated per Controller's action
     * @var array
     */
    protected $formFields = [];

    /**
     * Returns true if the user is authorized to make this request.
     * @return bool
     */
    abstract public function authorization(): bool;

    /**
     * Returns a list of data (validation) rules.
     * @return array|null
     */
    abstract public function rules(): ?array;

    /**
     * Returns a list of data filters.
     * @return array|null
     */
    abstract public function filters(): ?array;

    /**
     * Returns true if the user is authorized to make this request. Otherwise, false.
     * @param $srcController    Name of the controller the request is made to
     * @return bool
     */
    public function authorize(AuthorizeCredential $credential, string $srcController): bool {

        if (!AuthUser::isLoggedIn()) {
            return false;
        }
        $outbound = new Request(
            $this->getUri()->getUriString(),
            $this->getMethod(),
            new TextStream(json_encode(AuthUser::sessionData()))
        );
        $outbound->withBody(new TextStream(json_encode($credential)));
        $outbound->getUri()->withQuery( $this->getServerParam('QUERY_STRING') );
        $outbound->getUri()->withPagename($this->getScriptInfo()["filename"]);

        $accessControlConfig = ConfigLoader::accessControl();
        $accessControl = new AccessControl($accessControlConfig["assignments"]);
        $response = $accessControl->authorize($outbound, $srcController);
        return (true === (HttpStatusCode::OK === $response->getStatusCode()));
    }

    /**
     * Returns true if the form has submitted valid data
     */
    public function isValid(): bool {

        $onlyTheseFields = null;
        $callbacks = Callbacks::get();
        $currentScript = $this->getScriptInfo()["filename"];

        if (!empty($this->formFields) && $this->array_key_isset($currentScript, $this->formFields)) {
            // Get the fields and flip it, i.e. keys become values and values become keys
            $onlyTheseFields = array_flip($this->formFields[$currentScript]);
        }
        // Run data validation
        $isSuccess = $this->validate($callbacks["validators"], $onlyTheseFields);

        if ($isSuccess) {
            $this->filterize($callbacks["filters"], $onlyTheseFields);
        }
        else {
            FlashMessage::instance()->error("Unable to process data.");
        }
        return $isSuccess;
    }

    /**
     * Runs validation callbacks against set data rules. Returns true if is successfull, otherwise, false.
     * @param array $callbackValidators
     * @param $onlyTheseFields
     * @return bool
     */
    private function validate(array $callbackValidators, $onlyTheseFields): bool {

        $validator = new Validator(
            $callbackValidators,
            $this->getRules($onlyTheseFields)
        );
        $this->unsetSubmit();

        // Check for any uploaded files
        $uploadedFiles = $this->getUploadedFiles();

        // TODO We need to get the filename that was entered and validate it ??!!!

        // if any files were uploaded, merge them with the data of $parsedBody
        $data = (null !== $uploadedFiles)
            ? array_merge($uploadedFiles, $this->parsedBody)
            : $this->parsedBody;

        // Run validation checks
        $isSuccessful = $validator->process($data);
        $this->results = $validator->results();

        return $isSuccessful;
    }

    /**
     * Runs filter callbacks against set data filters. The process of filtering data can encompass any or all of the
     * following:
     *      - Removing unwanted characters (that is, removing <script> tags)
     *      - Performing transformations on the data (that is, converting a quote to &quot;)
     *      - Encrypting or decrypting the data
     */
    private function filterize(array $callbackFilters, $onlyTheseFields): void {

        // Run filters
        $filter = new Filter(
            $callbackFilters,
            $this->getFilters($onlyTheseFields)
        );
        $filter->process($this->parsedBody);

        // TODO Need to try and use ONLY the $data value!
        // Get the filtered data and overwrite the values of $parsedBody and $data
        $this->parsedBody = $filter->getItemsAsArray();
        $this->data = (object) $this->parsedBody;
    }

    /**
     * Returns a list of (validation) rules by cross-checking the set abstract assignments with the set for the current script. 
     * If validate fields for current script is set, we extract ONLY those fields!
     * 
     * @return array
     */
    private function getRules($onlyTheseFields): array {

        $rules = $this->rules();
        if (null === $onlyTheseFields) {
            return $rules;
        }

        // Did not return!
        // TODO Well, it seems like we have to extract only the fields needed for the current script's validation

        $onlyTheseRules = array_intersect_key($rules, $onlyTheseFields);

        if ($this->array_key_isset('*', $rules)) {
            $onlyTheseRules['*'] = $rules['*'];
        }

        return $onlyTheseRules;
    }

    /**
     * Returns a list of assignments by checking the set abstract assignments and ones for the current script. 
     * If validate fields for current script is set, we extract ONLY those fields!
     * 
     * @return array
     */
    private function getFilters($onlyTheseFields): array {

        $filters = $this->filters();

        if (null === $onlyTheseFields) {
            return $filters;
        }

        // Did not return!
        // TODO Well, it seems like we have to extract only the fields needed for the current script's filters

        $onlyTheseFilters = array_intersect_key($filters, $onlyTheseFields);

        if ($this->array_key_isset('*', $filters)) {
            $onlyTheseFilters['*'] = $filters['*'];
        }

        return $onlyTheseFilters;
    }

    /**
     * Unsets the Submit button from the request body
     */
    private function unsetSubmit(): void {

        // Remove the submit button from the request
        if ($this->array_key_isset("submit", $this->parsedBody)) {
            unset($this->parsedBody["submit"]);
            unset($this->data->submit);
        }
    }

    /**
     * Getter/Setter for (validated) results
     * @param array $results
     * @return array
     */
    public function results(array $results = null) {

        if(null === $results) {
            return $this->results;
        }
        else {
            $this->results = $results;
        }
    }
}