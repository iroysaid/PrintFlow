<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $uri = service('uri');
        
        // Admin restriction
        // Admin restriction (Allow Admin and Production)
        if ($uri->getSegment(1) == 'admin' && !in_array($session->get('role'), ['admin', 'production'])) {
            return redirect()->to('/pos')->with('error', 'Access Denied');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}
