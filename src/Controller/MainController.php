<?php
/**
 * Created by PhpStorm.
 * User: kchaudemanche2018
 * Date: 26/11/2018
 * Time: 11:36
 */

namespace App\Controller;


use http\Env\Request;

class MainController
{
    /**
     * @Route("/", name="home")
     */
    public function home(Request $request){
        return $this->render("main/home.html.twig");
    }
}