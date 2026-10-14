<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AttendeeModel;

class Register extends BaseController
{
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (! is_array($data)) {
            $data = $this->request->getPost();
        }

        $rules = [
            'name'               => 'required|min_length[2]|max_length[100]',
            'email'              => 'required|valid_email|max_length[255]',
            'mobile'             => 'required|regex_match[/^[0-9]{10}$/]',
            'company'            => 'required|max_length[150]',
            'designation'        => 'required|max_length[100]',
            'city'               => 'required|max_length[100]',
            'country'             => 'required|max_length[100]',
            'session_preference' => 'required|max_length[100]',
            'consent'            => 'required|in_list[0,1]',
            'password' => 'required|min_length[8]|max_length[72]',
        ];

        if (! $this->validateData($data, $rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $model = new AttendeeModel();

        $email = strtolower(trim($data['email']));
        $mobile = trim($data['mobile']);

        if ($model->where('email', $email)->first()) {
            return $this->response->setStatusCode(409)->setJSON([
                'status' => 'error',
                'message' => 'This email is already registered.',
            ]);
        }

        if ($model->where('mobile', $mobile)->first()) {
            return $this->response->setStatusCode(409)->setJSON([
                'status' => 'error',
                'message' => 'This mobile number is already registered.',
            ]);
        }

        $id = $model->insert([
            'name' => trim($data['name']),
            'email' => $email,
            'mobile' => $mobile,
            'company' => trim($data['company']),
            'designation' => trim($data['designation']),
            'city' => trim($data['city']),
            'country' => trim($data['country']),
            'session_preference' => trim($data['session_preference']),
            'consent' => (int) $data['consent'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);

        if ($id === false) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Registration could not be completed.',
                'errors' => $model->errors(),
            ]);
        }

        return $this->response->setStatusCode(201)->setJSON([
            'status' => 'success',
            'message' => 'Registration successful!',
            'attendee_id' => $id,
        ]);
    }
}
