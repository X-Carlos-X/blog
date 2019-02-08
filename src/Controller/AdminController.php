<?php
/**
 * Created by PhpStorm.
 * User: linux
 * Date: 07/02/19
 * Time: 16:15
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 *
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }
}
?>