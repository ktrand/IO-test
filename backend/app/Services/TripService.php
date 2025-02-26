<?php

namespace App\Services;

use App\Enums\TripStatuses;
use App\Enums\UserRoles;
use App\Models\Trip;
use App\Repositories\TripRepository;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class TripService
{
    public function __construct(private readonly TripRepository $tripRepository) {}

    public function create(array $data, User $user): Trip
    {
        $data['passenger_id'] = $user->id;
        $data['status'] = TripStatuses::PENDING->value;

        return $this->tripRepository->create($data);
    }

    public function getUserTrips(User $user, int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        return $this->tripRepository->getByUser($user, $perPage, $filters);
    }

    /**
     * @throws ValidationException
     */
    public function update(User $user, Trip $trip, array $data): Trip
    {
        if (isset($data['status']) && $user->role === UserRoles::DRIVER) {
            $this->handleDriverStatusUpdate($user, $trip, $data);
        }

        return $this->tripRepository->update($trip, $data);
    }

    public function delete(Trip $trip): void
    {
        $this->tripRepository->delete($trip);
    }

    /**
     * @throws ValidationException
     */
    private function handleDriverStatusUpdate(User $user, Trip $trip, array &$data): void
    {
        $currentStatus = $trip->status->value;
        $newStatus = $data['status'] ?? null;

        if (!$newStatus || !$this->canChangeStatus($currentStatus, $newStatus)) {
            throw ValidationException::withMessages([
                'status' => "Нельзя сменить статус с {$currentStatus} на {$newStatus}."
            ]);        }

        if ($newStatus === TripStatuses::CANCELED->value) {
            $data['driver_id'] = null;
            $data['status'] = TripStatuses::PENDING->value;
        } elseif ($newStatus === TripStatuses::ACCEPTED->value) {
            $data['driver_id'] = $user->id;
        }
    }

    private function canChangeStatus(string $currentStatus, string $newStatus): bool
    {
        $allowedTransitions = [
            TripStatuses::PENDING->value     => [TripStatuses::ACCEPTED->value, TripStatuses::CANCELED->value],
            TripStatuses::ACCEPTED->value    => [TripStatuses::IN_PROGRESS->value, TripStatuses::CANCELED->value],
            TripStatuses::IN_PROGRESS->value => [TripStatuses::COMPLETED->value, TripStatuses::CANCELED->value],
            TripStatuses::COMPLETED->value   => [],
            TripStatuses::CANCELED->value    => [],
        ];

        return in_array($newStatus, $allowedTransitions[$currentStatus] ?? [], true);
    }

    public function getById(int $id): Trip
    {
        return $this->tripRepository->find($id);
    }
}
