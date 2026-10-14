<?php
namespace App\Controllers;

use Config\Database;

class Home extends BaseController
{
    public function index()
    {
        try {
            $db = Database::connect();
            $db->initialize();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Database connected successfully!',
                'tables' => $db->listTables(),
            ]);
        } catch (\Throwable $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
        }
    }
}