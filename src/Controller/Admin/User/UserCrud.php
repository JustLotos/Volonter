<?php

declare(strict_types=1);

namespace App\Controller\Admin\User;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Routing\Annotation\Route;

/** @Route(value="admin/user") */
class UserCrud extends CRUDController
{
    /** @Route("/create/", name="admin_app_domain_user_create", methods={"POST"}) */
    public function create()
    {
    }

    /** @Route("/list/", name="admin_app_domain_user_list", methods={"GET"}, options={"forSonata"}) */
    public function list()
    {
    }
}
