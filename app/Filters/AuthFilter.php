<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');
        $validToken = env('API_TOKEN');

        if ($authHeader !== 'Bearer ' . $validToken) {
            return Services::response()->setStatusCode(401)->setJSON([
                'status' => 401,
                'message' => 'Unauthorized. Invalid or missing token.'
            ]);
        }


        // if ($authHeader !== 'Bearer HEALTHY-FOOD-2025') {
        //     return Services::response()
        //         ->setStatusCode(401)
        //         ->setJSON([
        //             'status' => 401,
        //             'message' => 'Unauthorized. Invalid or missing token.'
        //         ]);
        // }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak perlu isi apa-apa
    }
}
