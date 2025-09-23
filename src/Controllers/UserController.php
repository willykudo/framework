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

    public function show(Request $req, Response $res, int $id){
        $user = $this->repo->findUser($id);
        return $this->jsonResponse($res, ['data' => $user]);
    }

    public function store(Request $req, Response $res){
        $validator = new Validator($req->getBody(), [
            'name' => 'required|min:3|notnull',
            'email' => 'required|email|notnull',
        ]);

        if (!$validator->validate()) {
            return $res->setStatus(400)->json(['errors' => $validator->getErrors()]);
        }

        $user = $this->repo->createUser(
            $req->input('name'),
            $req->input('email')
        );

        return $this->jsonResponse($res, ['data' => $user], 201);
    }

    public function update(Request $req, Response $res, int $id) {
        $data = $req->getBody();
        $user = $this->repo->updateUser($id, $data);
        return $this->jsonResponse($res, ['data' => $user]);
    }

    public function destroy(Request $req, Response $res, int $id){
        $deleted = $this->repo->deleteUser($id);
        return $this->jsonResponse($res, ['message'=> 'User deleted successfully']);
    }

   public function search(Request $req, Response $res) {
        $users = $this->repo->searchUsers($req->getBody());
        return $this->jsonResponse($res, ['data' => $users]);
    }
}