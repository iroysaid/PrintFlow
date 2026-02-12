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
        
        // Security Check
        if (session()->get('role') != 'admin') {
            header('Location: /');
            exit;
        }
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
            'role'     => 'required|in_list[admin,cashier,production]'
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

    public function update($id)
    {
        $rules = [
            'username' => "required|is_unique[users.username,id,{$id}]",
            'fullname' => 'required',
            'role'     => 'required|in_list[admin,cashier,production]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validation Failed');
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'fullname' => $this->request->getPost('fullname'),
            'role'     => $this->request->getPost('role'),
        ];

        // Only update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->userModel->update($id, $data);

        return redirect()->back()->with('success', 'User updated successfully');
    }

    public function delete($id)
    {
        // Prevent deleting self (simple check)
        if (session()->get('id') == $id) {
            return redirect()->back()->with('error', 'Cannot delete yourself');
        }

        $this->userModel->delete($id);
        return redirect()->back()->with('success', 'User deleted successfully');
    }
}
