<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'users' => $this->userModel->findAll()
        ];
        return view('admin/users', $data);
    }

    public function create()
    {
        $rules = [
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[5]',
            'fullname' => 'required',
            'role'     => 'required|in_list[admin,cashier]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validation Failed');
        }

        $this->userModel->insert([
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'fullname' => $this->request->getPost('fullname'),
            'role'     => $this->request->getPost('role'),
        ]);

        return redirect()->back()->with('success', 'User created successfully');
    }
}
