<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Registrations extends BaseController
{
    public function index()

    {
        if (
            session()->get('isLoggedIn') !== true ||
            !session()->get('attendee_id')
        ) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Please log in first.'
                ]);
        }

        $db = \Config\Database::connect();

        $attendeeId = session()->get('attendee_id');
        $isAdmin = session()->get('role') === 'admin';

        $builder = $db->table('event_registrations er')
            ->select('er.id, er.attendee_id, er.event_id, er.created_at');

        // Attendees can see only their own registrations.
        // Admins can see all registrations.
        if (!$isAdmin) {
            $builder->where('er.attendee_id', $attendeeId);
        }

        $registrations = $builder
            ->orderBy('er.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'status' => 'success',
                'count' => count($registrations),
                'data' => $registrations
            ]);
    }
    public function show($id)
    {
        if (
            session()->get('isLoggedIn') !== true ||
            !session()->get('attendee_id')
        ) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Please log in first.'
                ]);
        }

        if (!ctype_digit((string) $id) || (int) $id < 1) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid registration ID.'
                ]);
        }

        $db = \Config\Database::connect();

        $builder = $db->table('event_registrations er')
            ->select('er.id, er.attendee_id, er.event_id, er.created_at')
            ->where('er.id', (int) $id);

        // Attendees can view only their own registrations.
        if (session()->get('role') !== 'admin') {
            $builder->where(
                'er.attendee_id',
                session()->get('attendee_id')
            );
        }

        $registration = $builder->get()->getRowArray();

        if (!$registration) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Registration not found.'
                ]);
        }

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'status' => 'success',
                'data' => $registration
            ]);
    }
}
