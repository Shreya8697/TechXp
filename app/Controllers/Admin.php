<?php

namespace App\Controllers;

use App\Models\AttendeeModel;

class Admin extends BaseController
{
    private function checkAdmin()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'admin') {
            return $this->response
                ->setStatusCode(403)
                ->setBody('403 Forbidden: Admin access required');
        }

        return null;
    }

    public function index()
    {
        if ($response = $this->checkAdmin()) {
            return $response;
        }

        $model = new AttendeeModel();

        $search = trim((string) $this->request->getGet('search'));
        $country = trim((string) $this->request->getGet('country'));

        $builder = $model->builder();

        $builder->select(
            'id, name, email, mobile, company, designation, city, country, created_at'
        );

        if ($search !== '') {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('email', $search)
                ->orLike('company', $search)
                ->groupEnd();
        }

        if ($country !== '') {
            $builder->where('country', $country);
        }

        $page = max(1, (int) $this->request->getGet('page'));
        $perPage = 10;

        $total = (clone $builder)->countAllResults();

        $attendees = $builder
            ->orderBy('id', 'DESC')
            ->get($perPage, ($page - 1) * $perPage)
            ->getResultArray();

        $db = \Config\Database::connect();

        $data = [
            'totalAttendees' => $model->countAll(),
            'totalEvents' => $db->table('events')->countAllResults(),
            'totalRegistrations' => $db->table('event_registrations')->countAllResults(),
            'attendees' => $attendees,
            'search' => $search,
            'country' => $country,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'countries' => $model->select('country')
                ->distinct()
                ->where('country !=', '')
                ->orderBy('country', 'ASC')
                ->findColumn('country') ?? [],
        ];

        return view('admin/dashboard', $data);
    }

    public function attendee($id)
    {
        if ($response = $this->checkAdmin()) {
            return $response;
        }

        $attendee = (new AttendeeModel())->find($id);

        if (! $attendee) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Attendee not found'
            );
        }

        unset($attendee['password_hash']);

        return view('admin/attendee', ['attendee' => $attendee]);
    }
}
