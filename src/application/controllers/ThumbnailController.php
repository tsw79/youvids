<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 6/11/2019
 * Time: 05:06
 */
namespace youvids\application\controllers;

use phpchassis\auth\AuthUser;
use phpchassis\http\controllers\BaseController;
use youvids\domain\repositories\ThumbnailRepo;

/**
 * Class ThumbnailController
 * @package youvids\application\controllers
 */
class ThumbnailController extends BaseController {

    /**
     * List of dependencies to be registered by the Dependency Injection Controller
     * @var array
     */
    protected $dependencies = [ThumbnailRepo::class];

    /**
     * Returns a list of dependencies to be registered by the Dependency Injection Container
     * @return array|null
     */
    protected function dependencies(): ?array {

        return [
            ThumbnailRepo::class
        ];
    }

    /**
     * Edits the selected thumbnail
     * @ajax
     * @return array
     */
    public function editSelected(): array {

        if (!AuthUser::isLoggedIn() || !$this->request->isPostMethod()) {
            return "Request has been denied!";
        }

        if (!$this->params->videoId || !$this->params->thumbnailId) {
            return "One or more parameters are not passed into the updateThumbnail.php file";
        }

        $thumbnailRepo = DIContainer::instance()->get(ThumbnailRepo::class);

        //@TODO Need to put this in a transaction -----------------------
        if ($thumbnailRepo->unsetSelected($this->params->videoId)) {
            return ["error" => "Failed to unset video."];
            // Rollback
        }

        if ($thumbnailRepo->setSelected($this->params->thumbnailId)) {
            return ["error" => "Failed to set selected thumbnail."];
            // Rollback
        }
        // ---------------------------------------------------------------

        return ["success" => "The selected thumbnail was updated successfully."];
    }
}