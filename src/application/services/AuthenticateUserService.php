<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/23/2019
 * Time: 03:44
 */
namespace youvids\application\services;

use phpchassis\auth\ {AccessControl, Csrf};
use phpchassis\storage\session\Session;
use phpchassis\data\dto\RequestDataInterface;
use phpchassis\http\middleware\ {HttpStatusCode, Request, Response, TextStream};
use phpchassis\data\service\ {ApplicationService, ApplicationServiceInterface};
use youvids\application\dto\LoginCredential;

/**
 * Class AuthenticateUserService
 *      Authenticates new users...
 *
 *          1.  Create (register) a user
 *          2.  Produce auth tokens: take username/pass, return token
 *          3.  Authenticate:
 *                (a) takes token
 *                (b) return whose username/userid it belongs to
 *                (c) if using RBAC, return which roles this user has {client, staff, admin, system, etc.}.
 *          4.  Get and edit core user fields and profile settings (avatar, name, email, change password)
 *
 * @package YouVids\services
 */
class AuthenticateUserService extends ApplicationService implements ApplicationServiceInterface {

    /**
     * Authenticates a user's login credentials
     * @param RequestDataInterface $requestData
     * @return \Psr\Http\Message\ResponseInterface|Response
     */
    public function execute(RequestDataInterface $requestData) {

        $request = new Request();
        $body = new TextStream(json_encode($requestData->toArray()));      //$body = new TextStream(json_encode($requestData->parsedBody));
        $request = $request->withBody($body);
        $params = json_decode($request->getBody()->getContents());
        $token = $params->token ?? false;

        if (false === ($token && Csrf::matchToken($token))) {

            $code = HttpStatusCode::BAD_REQUEST;
            $body = new TextStream(Csrf::ERROR_AUTH);
            $response = new Response($code, $body);
        }
        else {

            $user = $this->repository->findByUsername($params->username);

            if (null === $user) {
                $code = HttpStatusCode::NOT_FOUND;
                $body = new TextStream(HttpStatusCode::toText($code));
                $response = new Response($code, $body);
            }
            else {
                // @TODO Move this to a factory so it can communicate with the Config Loader and dynamically load the set adapter
                $authAdapter = new \phpchassis\auth\adapters\DbAuthAdapter(
                    new LoginCredential($user->username(), $user->password())
                );

                $response = $authAdapter->authenticate($request);
            }
        }

        //unset($user->password());

        if ($response->statusCodeIsWithinSuccessRange()) {

            Session::instance()->set(
                AccessControl::SESSION_AUTH_KEY,
                json_decode($response->getBody()->getContents())
            );
        }
        else {
            Session::instance()->set(AccessControl::SESSION_AUTH_KEY, null);
        }

        return $response;
    }
}