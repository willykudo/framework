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
        if (!$user) {
            return $res->setStatus(404)->json(['error' => 'User not found']);
        }
        return $this->jsonResponse($res, ['data' => $user]);
    }

    public function store(Request $req, Response $res){
        $rules = [
            'name' => 'required|min:3|notnull',
            'email' => 'required|email|notnull',
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

    public function update(Request $req, Response $res, int $id) {
        $data = $req->getBody();

        if (empty($data)) {
            return $res->setStatus(400)->json(['error' => 'No data provided']);
        }

        $user = $this->repo->updateUser($id, $data);

        if (!$user) {
            return $res->setStatus(404)->json(['error' => 'User not found or not updated']);
        }

        return $this->jsonResponse($res, ['data' => $user]);
    }

    public function destroy(Request $req, Response $res, int $id){
        $deleted = $this->repo->deleteUser($id);

        if (!$deleted) {
            return $res->setStatus(404)->json(['error' => 'User not found']);
        }

        return $this->jsonResponse($res, ['message'=> 'User deleted successfully']);
    }

   public function search(Request $req, Response $res) {
        $conditions = [];

        $name = $req->input('name');
        if ($name) {
            $conditions['name'] = $name;
        }

        $email = $req->input('email');
        if ($email) {
            $conditions['email'] = $email;
        }

        if (empty($conditions)) {
            return $res->setStatus(400)->json([
                'error' => 'At least one parameter (name or email) is required'
            ]);
        }

        $users = $this->repo->searchUsers($conditions);

        return $this->jsonResponse($res, ['data' => $users]);
    }
}