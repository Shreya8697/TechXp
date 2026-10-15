<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class Chat extends BaseController
{
    public function create()
    {
        if (
            session()->get('isLoggedIn') !== true ||
            !session()->get('attendee_id')
        ) {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => 'error',
                'message' => 'Please log in first.'
            ]);
        }

        $data = $this->request->getJSON(true);

        $message = trim($data['message'] ?? '');

        if ($message === '' || strlen($message) > 500) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Message is required and must be under 500 characters.'
            ]);
        }

        $normalized = strtolower($message);

        if (
            strpos($normalized, 'hello') !== false ||
            strpos($normalized, 'hi') !== false
        ) {
            $reply = 'Hello! Welcome to the TechXP event. How can I help?';
        } elseif (strpos($normalized, 'register') !== false) {
            $reply = 'You can register for the event using the registration button.';
        } elseif (strpos($normalized, 'event') !== false) {
            $reply = 'Please check the event information and content on this page.';
        } elseif (strpos($normalized, 'thank') !== false) {
            $reply = 'You are welcome!';
        } else {
            $reply = 'Thanks for your message! This is a demo assistant.';
        }

        return $this->response->setStatusCode(200)->setJSON([
            'status' => 'success',
            'reply' => $reply
        ]);
    }
}
