<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 8/5/2019
 * Time: 20:42
 */
namespace phpchassis\middleware;

use phpchassis\http\middleware\Response;
use phpchassis\http\middleware\TextStream;
use Psr\Http\Message\RequestInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Class ResponseCacheMiddleware
 *  Caches data in the form of a ResponseInterface
 *
 * @package phpchassis\middleware
 */
class CacheResponseMiddleware {

    /**
     * @var CacheInterface
     */
    private $adapter;

    /**
     * Core constructor.
     * @param CacheInterface $adapter
     */
    public function __construct(CacheInterface $adapter) {
        $this->adapter = $adapter;
    }

    /**
     * @wrapper
     * @param RequestInterface $request
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function has(RequestInterface $request): bool {
        $key = $request->getUri()->getQueryParams()['key'] ?? '';
        return $this->adapter->has($key);
    }

    /**
     * The get() method retrieves information from the cache by pulling the key and group parameters from the request object, and
     * then call the same method from the adapter. 
     *  - If no results are obtained, set a 204 code, which indicates the request was a success, but no content was produced. 
     *  - Otherwise, set a 200 (success) code, and iterate through the results. Everything is then put into a response object, 
     *    which is returned.
     *
     * @wrapper
     * @param RequestInterface $request
     * @return Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function get(RequestInterface $request) {

        $text = array();
        $key = $request->getUri()->getQueryParams()['key'] ?? '';
        $group = $request->getUri()->getQueryParams()['group'] ?? Constants::DEFAULT_GROUP;
        $results = $this->adapter->get($key, $group);

        if (!$results) {
            $code = 204;
        }
        else {
            $code = 200;
            foreach ($results as $line) {
                $text[] = $line;
            }
        }

        if (!$text || count($text) == 0) {
            $code = 204;
        }
        $body = new TextStream(json_encode($text));

        return (new Response())
            ->withStatus($code)
            ->withBody($body);
    }

    /**
     * @param RequestInterface $request
     * @return Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function set(RequestInterface $request) {

        $text = array();
        $key = $request->getUri()->getQueryParams()['key'] ?? '';
        $group = $request->getUri()->getQueryParams()['group'] ?? Constants::DEFAULT_GROUP;
        $data = $request->getBody()->getContents();
        $results = $this->adapter->set($key, $data, $group);

        if (!$results) {
            $code = 204;
        }
        else {
            $code = 200;
            $text[] = $results;
        }
        $body = new TextStream(json_encode($text));

        return (new Response())
            ->withStatus($code)
            ->withBody($body);
    }

    /**
     * @param RequestInterface $request
     * @return Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function delete(RequestInterface $request) {

        $text = array();
        $key = $request->getUri()->getQueryParams()['key'] ?? '';
        $results = $this->adapter->delete($key);

        if (!$results) {
            $code = 204;
        }
        else {
            $code = 200;
            $text[] = $results;
        }
        $body = new TextStream(json_encode($text));

        return (new Response())
            ->withStatus($code)
            ->withBody($body);
    }

    /**
     * @param RequestInterface $request
     * @return Response
     */
    public function deleteByGroup(RequestInterface $request) {

        $text = array();
        $group = $request->getUri()->getQueryParams()['group'] ?? Constants::DEFAULT_GROUP;
        $results = $this->adapter->deleteByGroup($group);

        if (!$results) {
            $code = 204;
        }
        else {
            $code = 200;
            $text[] = $results;
        }
        $body = new TextStream(json_encode($text));

        return (new Response())
            ->withStatus($code)
            ->withBody($body);
    }
}