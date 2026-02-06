<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return $this->redirectBasedOnRole(session()->get('role'));
        }
        return view('auth/login');
    }

    public function process()
    {
        $session = session();
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $ses_data = [
                    'id'        => $user['id'],
                    'username'  => $user['username'],
                    'fullname'  => $user['fullname'],
                    'role'      => $user['role'],
                    'logged_in' => TRUE
                ];
                $session->set($ses_data);
                return $this->redirectBasedOnRole($user['role']);
            }
        }

        $session->setFlashdata('error', 'Invalid Username or Password');
        return redirect()->to('/login');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }

    private function redirectBasedOnRole($role)
    {
        if ($role == 'admin') {
            return redirect()->to('/admin/dashboard');
        } elseif ($role == 'production') {
            return redirect()->to('/admin/dashboard'); // Or specific production view
        } else {
            return redirect()->to('/pos');
        }
    }
}
