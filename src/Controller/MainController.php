<?php
<<<<<<< HEAD

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
=======
/**
 * Created by PhpStorm.
 * User: kchaudemanche2018
 * Date: 26/11/2018
 * Time: 11:36
 */

namespace App\Controller;


use http\Env\Request;

class MainController
>>>>>>> origin/master
{
    /**
     * @Route("/", name="home")
     */
<<<<<<< HEAD
    public function home()
    {
        return $this->render('main/home.html.twig');
    }
}
=======
    public function home(Request $request){
        return $this->render("main/home.html.twig");
    }
}
>>>>>>> origin/master
