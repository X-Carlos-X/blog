<?php
/**
 * Created by PhpStorm.
 * User: linux
 * Date: 06/03/19
 * Time: 23:03
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class UserRestController extends Controller {

    public function getRequest() {
        return new JsonResponse([
            'hola' => 'hola es una prueba'
        ]);
    }

    public function postRequest(Request $request) {
        return new JsonResponse($request->getContent());
    }

}