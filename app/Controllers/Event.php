<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class Event extends BaseController
{
    public function index()
    {
        $session = session();

        if (! $session->get('isLoggedIn') || ! $session->get('attendee_id')) {
            return redirect()->to(site_url('login'));
        }

        return view('event', [
            'name' => $session->get('attendee_name'),
            'email' => $session->get('attendee_email'),
        ]);
    }
}
