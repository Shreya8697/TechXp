<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AttendeeModel;
use CodeIgniter\HTTP\ResponseInterface;

class Attendees extends BaseController
{
    private function isAdmin(): bool
    {
        return session()->get('isLoggedIn') === true
            && session()->get('role') === 'admin';
    }

    public function index()
    {
        if (! $this->isAdmin()) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Admin access required']);
        }

        $model = new AttendeeModel();

        $attendees = $model->select(
            'id, name, email, mobile, company, designation, city, country, session_preference, consent, created_at'
        )->findAll();

        return $this->response->setJSON([
            'success' => true,
            'data' => $attendees,
        ]);
    }

    public function show($id)
    {
        if (! $this->isAdmin()) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Admin access required']);
        }

        $attendee = (new AttendeeModel())
            ->select(
                'id, name, email, mobile, company, designation, city, country, session_preference, consent, created_at'
            )
            ->find($id);

        if (! $attendee) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => 'Attendee not found']);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $attendee,
        ]);
    }

    public function update($id)
    {
        if (! $this->isAdmin()) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Admin access required']);
        }

        $attendee = (new AttendeeModel())->find($id);

        if (! $attendee) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => 'Attendee not found']);
        }

        $data = $this->request->getJSON(true);

        if (! is_array($data) || $data === []) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'Provide JSON data to update']);
        }

        $allowedFields = [
            'name', 'mobile', 'company', 'designation',
            'city', 'country', 'session_preference', 'consent'
        ];

        $updateData = array_intersect_key(
            $data,
            array_flip($allowedFields)
        );

        if (isset($updateData['name']) && trim($updateData['name']) === '') {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['message' => 'Name cannot be empty']);
        }

        if (isset($updateData['consent'])) {
            if (! in_array($updateData['consent'], [0, 1, '0', '1', true, false], true)) {
                return $this->response
                    ->setStatusCode(422)
                    ->setJSON(['message' => 'Consent must be 0 or 1']);
            }

            $updateData['consent'] = (int) (bool) $updateData['consent'];
        }

        if ($updateData === []) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'No allowed fields supplied']);
        }

        $model = new AttendeeModel();

        if (! $model->update($id, $updateData)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'message' => 'Update failed',
                    'errors' => $model->errors(),
                ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Attendee updated successfully',
        ]);
    }

    public function delete($id)
    {
        if (! $this->isAdmin()) {
            return $this->response
                ->setStatusCode(403)
                ->setJSON(['message' => 'Admin access required']);
        }

        $model = new AttendeeModel();
        $attendee = $model->find($id);

        if (! $attendee) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['message' => 'Attendee not found']);
        }

        if (! $model->delete($id)) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['message' => 'Could not delete attendee']);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Attendee deleted successfully',
        ]);
    }
}
