<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AttendeeModel;

class Profile extends BaseController
{
    public function index()
    {
        // Check whether the user is logged in
        if (session()->get('isLoggedIn') !== true) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Please log in to view your profile.'
                ]);
        }

        $attendeeId = session()->get('attendee_id');

        if (!$attendeeId) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid login session.'
                ]);
        }

        $attendeeModel = new AttendeeModel();

        $attendee = $attendeeModel
            ->select(
                'id, name, email, mobile, company, designation, city, country, session_preference'
            )
            ->find($attendeeId);

        if (!$attendee) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Attendee profile not found.'
                ]);
        }

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'status' => 'success',
                'data' => $attendee
            ]);
    }
}
