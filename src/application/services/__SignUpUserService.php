<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/1/2019
 * Time: 04:11
 */
namespace youvids\application\services;

use phpchassis\data\dto\RequestDataInterface;
use phpchassis\data\service\ApplicationService;
use phpchassis\data\service\ApplicationServiceInterface;

/**
 * Class SignUpUserService
 * @package youvids\domain\services
 */
class SignUpUserService extends ApplicationService implements ApplicationServiceInterface {

    /**
     * Registers a new user
     */
    public function execute(RequestDataInterface $requestData) { }
}

/*
class SignUpUserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function execute(SignUpUserRequest $request)
    {
        $user = $this->userRepository->userOfEmail($request->email);
        if ($user) {
            throw new UserAlreadyExistsException();
        }
        $user = new User(
            $this->userRepository->nextIdentity(),
            $request->email,
            $request->password
        );
        $this->userRepository->add($user);
        return new SignUpUserResponse($user);
    }
}
*/