<?php 

namespace WillyFramework\src\Controllers;

use WillyFramework\src\Core\Request;
use WillyFramework\src\Core\Response;
use WillyFramework\src\Repository\UserRepository;
use WillyFramework\src\Core\BaseController;
use WillyFramework\pkg\Validator;

class UserController extends BaseController {
    private UserRepository $repo;

    public function __construct(UserRepository $repo){
        $this->repo = $repo;
    }

    public function index(Request $req, Response $res){
        $users = $this->repo->findAll();
        return $this->jsonResponse($res, ['data' => $users]);
    }

    public function store(Request $req, Response $res){
        $rules = [
            'name' => 'required|min:3|notnull',
            'email' => 'required|email|notnull'
        ];

        $validator = new Validator($req->getBody(), $rules);

        if (!$validator->validate()) {
            return $res->setStatus(400)->json(['errors' => $validator->getErrors()]);
        }

        $user = $this->repo->createUser(
            $req->input('name'),
            $req->input('email')
        );

        return $this->jsonResponse($res, ['data' => $user], 201);
    }
}