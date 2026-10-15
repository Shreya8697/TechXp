<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class EventRegistration extends BaseController
{
    public function create()
    {
        $session = session();

        if (
            $session->get('isLoggedIn') !== true ||
            !$session->get('attendee_id')
        ) {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => 'error',
                'message' => 'Please log in first.'
            ]);
        }

        $attendeeId = (int) $session->get('attendee_id');
        $eventId = 1;

        $db = \Config\Database::connect();

        $event = $db->table('events')
            ->where('id', $eventId)
            ->get()
            ->getRowArray();

        if (!$event) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Event not found.'
            ]);
        }

        $existing = $db->table('event_registrations')
            ->where('attendee_id', $attendeeId)
            ->where('event_id', $eventId)
            ->get()
            ->getRowArray();

        if ($existing) {
            return $this->response->setStatusCode(200)->setJSON([
                'status' => 'success',
                'message' => 'You are already registered for this event.',
                'data' => $existing
            ]);
        }

        $db->table('event_registrations')->insert([
            'attendee_id' => $attendeeId,
            'event_id' => $eventId
        ]);

        $registrationId = $db->insertID();

        return $this->response->setStatusCode(201)->setJSON([
            'status' => 'success',
            'message' => 'Successfully registered for the event.',
            'data' => [
                'id' => $registrationId,
                'attendee_id' => $attendeeId,
                'event_id' => $eventId
            ]
        ]);
    }
}
