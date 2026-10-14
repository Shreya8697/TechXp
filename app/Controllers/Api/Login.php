<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AttendeeModel;

class Login extends BaseController
{
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (! is_array($data)) {
            $data = $this->request->getPost();
        }

        $rules = [
            'identifier' => 'required|max_length[255]',
            'password'   => 'required|max_length[72]',
        ];

        if (! $this->validateData($data, $rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $identifier = trim($data['identifier']);
        $model = new AttendeeModel();

        $attendee = $model
            ->groupStart()
                ->where('email', strtolower($identifier))
                ->orWhere('mobile', $identifier)
            ->groupEnd()
            ->first();

        if (
            ! $attendee ||
            empty($attendee['password_hash']) ||
            ! password_verify($data['password'], $attendee['password_hash'])
        ) {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => 'error',
                'message' => 'Invalid email/mobile or password.',
            ]);
        }

        $session = session();
        $session->regenerate(true);
        $session->set([
            'attendee_id' => $attendee['id'],
            'attendee_name' => $attendee['name'],
            'attendee_email' => $attendee['email'],
            'isLoggedIn' => true,
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Login successful.',
            'redirect' => site_url('event'),
        ]);
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'));
    }
}
