<?php 

namespace WillyFramework\src\Controllers;

use WillyFramework\src\Core\Request;
use WillyFramework\src\Core\Response;
use WillyFramework\src\Repository\UserRepository;

class UserController {
    private UserRepository $repo;

    public function __construct(){
        $this->repo = new UserRepository();
    }

    public function index(Request $req, Response $res){
        $users = $this->repo->findAll();
        return $res->json(['data' => $users]);
    }

    public function store(Request $req, Response $res){
        $name = $req->input('name');
        $email = $req->input(('email'));

        if(!$name || !$email){
            return $res->setStatus(400)->json(['error' => 'Name & email required']);
        }

        $user = $this->repo->create($name, $email);
        return $res->setStatus(201)->json(['data' => $user]);
    }
}