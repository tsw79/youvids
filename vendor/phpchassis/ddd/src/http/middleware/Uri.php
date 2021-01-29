<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/11/2019
 * Time: 11:11
 *
 *
 * Uri Parts and Components explained: (Ref: https://en.wikipedia.org/wiki/Uniform_Resource_Identifier)
 * -----------------------------------------------------------------------------------------------------
 *
 *
 *               userinfo      host      port
 *              ┌────┴────┐┌────────┴────────┐ ┌┴┐
 *      https://john.doe@www.example.com:123/forum/questions/?tag=networking&order=newest#top
 *     └──┬──┘  └───────────────┬─────────────────┘└─────────┬────────┘ └───────────────┬────────────────┘ └┬┘
 *     scheme          authority                  path                  query           fragment
 *
 *
 *      ldap://[2001:db8::7]/c=GB?objectClass?one
 *      └┬─┘   └───────┬──────┘└──┬─┘ └────────┬────────┘
 *      scheme  authority   path      query
 *
 *
 *      mailto:John.Doe@example.com
 *     └───┬──┘ └─────────┬─────────────┘
 *      scheme       path
 *
 *
 *      news:comp.infosystems.www.servers.unix
 *     └─┬─┘ └──────────────────┬────────────────────┘
 *      scheme            path
 *
 *
 *      tel:+1-816-555-1212
 *      └┬┘ └──────┬──────┘
 *      scheme    path
 *
 *
 *      telnet://192.0.2.16:80/
 *      └─┬──┘   └─────┬─────┘│
 *      scheme     authority  path
 *
 *
 *      urn:oasis:names:specification:docbook:dtd:xml:4.1.2
 *      └┬┘ └──────────────────────┬──────────────────────┘
 *      scheme                    path
 */
namespace phpchassis\http\middleware;

use Psr\Http\Message\UriInterface;

/**
 * Class Uri
 *  This class represents value objects (Uri) used by other PSR-7 classes.
 * @package phpchassis-ddd\middleware
 */
class Uri implements UriInterface {

    /**
     * @var $uriString
     */
    protected $uriString;

    /**
     * @var array $uriParts
     */
    protected $uriParts = array();

    /**
     * @var array $scriptInfo
     */
    protected $scriptInfo = array();

    /**
     * @var array
     */
    protected $queryParams;

    /**
     * Uri constructor.
     * @param $uriString
     */
    public function __construct($uriString) {
        $this->uriParts = parse_url($uriString);
        if (!$this->uriParts) {
            throw new \InvalidArgumentException(Constants::ERROR_INVALID_URI);
        }
        $this->uriString = $uriString;
    }

    /**
     * The scheme represents a PHP wrapper (that is, HTTP, FTP, and so on).
     *
     * @return string
     */
    public function getScheme() {
        return strtolower($this->uriParts['scheme']) ?? '';
    }

    /**
     * The authority represents the username (if present), the host, and optionally the port number.
     * @return string
     */
    public function getAuthority() {

        $val = '';

        if (!empty($this->getUserInfo())) {
            $val .= $this->getUserInfo() . '@';
        }
        $val .= $this->uriParts['host'] ?? '';

        if (!empty($this->uriParts['port'])) {
            $val .= ':' . $this->uriParts['port'];
        }
        return $val;
    }

    /**
     * User info represents the username (if present) and optionally the password. 
     *
     * @return mixed|string
     */
    public function getUserInfo() {

        if (empty($this->uriParts['user'])) {
            return '';
        }
        $val = $this->uriParts['user'];

        if (!empty($this->uriParts['pass'])) {
            $val .= ':' . $this->uriParts['pass'];
        }
        return $val;
    }

    /**
     * Host is the DNS address included in the URI.
     * @return string
     */
    public function getHost() {
        if (empty($this->uriParts['host'])) {
            return '';
        }
        return strtolower($this->uriParts['host']);
    }

    /**
     * Port is the HTTP port, if present. 
     *  Note: If a port is listed in our STANDARD_PORTS constant, the return value is NULL,
     *  according to the requirements of PSR-7.
     *
     * @return int|null
     */
    public function getPort() {

        if (empty($this->uriParts['port'])) {
            return NULL;
        }
        else {
            if ($this->getScheme()) {
                if ($this->uriParts['port'] == Constants::STANDARD_PORTS[$this->getScheme()]) {
                    return null;
                }
            }
            return (int) $this->uriParts['port'];
        }
    }

    /**
     * Path is the part of the URI that follows the DNS address. According to PSR-7, this must be encoded.
     *
     * @return string
     */
    public function getPath() {
        if (empty($this->uriParts['path'])) {
            return '';
        }
        return implode('/', array_map("rawurlencode", explode('/', $this->uriParts['path'])));
    }

    /**
     * Returns the name of the page (script)
     * @return string
     */
    public function getPagename(): string {
        return strtolower($this->uriParts['pagename']) ?? '';
    }

    /**
     * This method retrieves the query string (that is, from $_GET).
     *
     * @param bool $reset
     * @return array
     */
    public function getQueryParams($reset = false) {

        if ($this->queryParams && !$reset) {
            return $this->queryParams;
        }
        $this->queryParams = [];

        if (!empty($this->uriParts['query'])) {
            foreach (explode('&', $this->uriParts['query']) as $keyPair) {
                list($param, $value) = explode('=', $keyPair);
                $this->queryParams[$param] = $value;
            }
        }
        return $this->queryParams;
    }

    /**
     * getQuery
     */
    public function getQuery() {

        if (!$this->getQueryParams()) {
            return '';
        }
        $output = '';

        foreach ($this->getQueryParams() as $key => $value) {
            $output .= rawurlencode($key) . '=' . rawurlencode($value) . '&';
        }
        return substr($output, 0, -1);
    }

    /**
     * This method returns the fragment (that is, a # in the URI), and any part following it.
     * @return string
     */
    public function getFragment() {

        if (empty($this->urlParts['fragment'])) {
            return '';
        }
        return rawurlencode($this->urlParts['fragment']);
    }

    /**
     * When the object is used in a string context, this method returns a proper URI, assembled from $uriParts.
     * @return string
     */
    public function __toString() {

        $uri = ($this->getScheme()) ? $this->getScheme() . '://' : '';

        // If the authority URI part is present, we add it. authority includes the user
        // information, host, and port. Otherwise, we just append host and port
        if ($this->getAuthority()) {
            $uri .= $this->getAuthority();
        }
        else {
            $uri .= ($this->getHost()) ? $this->getHost() : '';
            $uri .= ($this->getPort()) ? ':' . $this->getPort() : '';
        }
        $path = $this->getPath();

        // Before adding path, we first check whether the first character is '/'. If not, we add it.
        if ($path) {
            if ($path[0] != '/') {
                $uri .= '/' . $path;
            }
            else {
                $uri .= $path;
            }
        }

        // ...We then add query and fragment, if present!
        $uri .= ($this->getQuery()) ? '?' . $this->getQuery() : '';
        $uri .= ($this->getFragment()) ? '#' . $this->getFragment() : '';
        return $uri;
    }

    /**
     * @alias __toString()
     * @return string
     */
    public function getUriString() {
        return $this->__toString();
    }

    /* ------------------------------------------------------------------------------------------------
    Here, we define a series of withXXX() methods, which match the getXXX() methods described above.
    These methods are designed to add, replace, or remove properties associated with the request class
    (scheme, authority, user info, and so on). In addition, these methods return the current instance 
    that allows us to use these methods in a series of successive calls.
    ---------------------------------------------------------------------------------------------------*/

    /**
     * @param string $scheme
     * @return $this
     */
    public function withScheme($scheme) {

        if (empty($scheme) && $this->getScheme()) {
            unset($this->uriParts['scheme']);
        }
        else {
            if(array_key_isset(strtolower($scheme), Constants::STANDARD_PORTS)) {
                $this->uriParts['scheme'] = $scheme;
            }
            else {
                throw new \InvalidArgumentException(Constants::ERROR_BAD . __METHOD__);
            }
        }
        return $this;
    }

    /**
     * withUserInfo
     */
    public function withUserInfo($user, $password = null) {

        if (empty($user) && $this->getUserInfo()) {
            unset($this->uriParts['user']);
        }
        else {
            $this->urlParts['user'] = $user;
            if ($password) {
                $this->urlParts['pass'] = $password;
            }
        }
        return $this;
    }

    /**
     * withQuery
     */
    public function withQuery($query) {

        if (empty($query) && $this->getQuery()) {
            unset($this->uriParts['query']);
        }
        else {
            $this->uriParts['query'] = $query;
        }
        // reset query params array
        $this->getQueryParams(true);
        return $this;
    }

    /**
     * Return an instance with the specified host.
     *  This method MUST retain the state of the current instance, and return an instance that contains the specified host.
     *  Note: An empty host value is equivalent to removing the host.
     *
     * @param string $host The hostname to use with the new instance.
     * @return static A new instance with the specified host.
     * @throws \InvalidArgumentException for invalid hostnames.
     */
    public function withHost($host)
    {
        // TODO: Implement withHost() method.
    }

    /**
     * Return an instance with the specified port.
     *
     * This method MUST retain the state of the current instance, and return an instance that contains the specified port.
     * Implementations MUST raise an exception for ports outside the established TCP and UDP port ranges.
     *  Note: A null value provided for the port is equivalent to removing the port information.
     *
     * @param null|int $port The port to use with the new instance; a null value removes the port information.
     * @return static A new instance with the specified port.
     * @throws \InvalidArgumentException for invalid ports.
     */
    public function withPort($port)
    {
        // TODO: Implement withPort() method.
    }

    /**
     * Return an instance with the specified path.
     *
     * This method MUST retain the state of the current instance, and return
     * an instance that contains the specified path.
     *
     * The path can either be empty or absolute (starting with a slash) or
     * rootless (not starting with a slash). Implementations MUST support all
     * three syntaxes.
     *
     * If the path is intended to be domain-relative rather than path relative then
     * it must begin with a slash ("/"). Paths not starting with a slash ("/")
     * are assumed to be relative to some dto path known to the application or
     * consumer.
     *
     * Users can provide both encoded and decoded path characters.
     * Implementations ensure the correct encoding as outlined in getPath().
     *
     * @param string $path The path to use with the new instance.
     * @return static A new instance with the specified path.
     * @throws \InvalidArgumentException for invalid paths.
     */
    public function withPath($path)
    {
        // TODO: Implement withPath() method.
    }

    /**
     * Return an instance with the specified URI fragment.
     *  This method MUST retain the state of the current instance, and return an instance that contains the specified URI fragment.
     *  Users can provide both encoded and decoded fragment characters. Implementations ensure the correct encoding as outlined in getFragment().
     *  Note: An empty fragment value is equivalent to removing the fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     * @return static A new instance with the specified fragment.
     */
    public function withFragment($fragment)
    {
        // TODO: Implement withFragment() method.
    }

    /**
     * withPagename
     */
    public function withPagename(string $page) {
        $this->uriParts['pagename'] = $page;
    }
}