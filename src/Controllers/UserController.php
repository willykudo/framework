<?php 

namespace WillyFramework\src\Controllers;

use WillyFramework\src\Core\Request;
use WillyFramework\src\Core\Response;
use WillyFramework\src\Repository\UserRepository;
use WillyFramework\src\Core\BaseController;
use WillyFramework\src\Resources\UserResource;
use WillyFramework\pkg\Validator;

class UserController extends BaseController {
    private UserRepository $repo;

    public function __construct(UserRepository $repo){
        $this->repo = $repo;
    }

    public function index(Request $req, Response $res){
        $users = $this->repo->findAll();
        $resource = new UserResource($users);
        return $this->jsonResponse($res, ['data' => $resource->toArray()]);
    }

    public function show(Request $req, Response $res, int $id){
        $user = $this->repo->findUser($id);
        $resource = new UserResource($user);
        return $this->jsonResponse($res, ['data' => $resource->toArray()]);
    }

    public function store(Request $req, Response $res){
        $validator = new Validator($req->getBody(), [
            'name' => 'required|min:3|notnull|max:255',
            'email' => 'required|email|notnull',
            'password' => 'required|min:6|notnull|max:255',
            'role' => 'required|notnull',
            'status' => 'required|notnull',
        ]);

        if (!$validator->validate()) {
            return $res->setStatus(400)->json(['errors' => $validator->getErrors()]);
        }

        $user = $this->repo->createUser(
            $req->input('name'),
            $req->input('email'),
            $req->input('password'),
            $req->input('role'),
            $req->input('status')
        );

        $resource = new UserResource($user);
        return $this->jsonResponse($res, ['data' => $resource->toArray()], 201);
    }

    public function update(Request $req, Response $res, int $id) {
        $validator = new Validator($req->getBody(), [
            'name' => 'min:3|max:255',
            'password' => 'min:6|max:255',
        ]);

        if (!$validator->validate()) {
            return $res->setStatus(400)->json(['errors' => $validator->getErrors()]);
        }

        
        $user = $this->repo->updateUser($id, $req->getBody());
        $resource = new UserResource($user);
        return $this->jsonResponse($res, ['data' => $resource->toArray()]);
    }

    public function destroy(Request $req, Response $res, int $id){
        $this->repo->deleteUser($id);
        return $this->jsonResponse($res, ['message'=> 'User deleted successfully']);
    }

   public function search(Request $req, Response $res) {
        $users = $this->repo->searchUsers($req->getQueryParams());
        $resource = new UserResource($users);
        return $this->jsonResponse($res, ['data' => $resource->toArray()]);
    }
}