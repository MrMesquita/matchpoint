<?php

namespace App\Services;

use App\Enums\CourtTimetableStatus;
use App\Enums\ReservationStatus;
use App\Exceptions\ReservationCanceledException;
use App\Exceptions\ReservationNotFoundException;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    private User $authUser;
    private CustomerService $customerService;
    private Reservation $reservationModel;
    private CourtService $courtService;
    private CourtTimetableService $courtTimetableService;

    public function __construct(
        CustomerService       $customerService,
        Reservation           $reservationModel,
        CourtService          $courtService,
        CourtTimetableService $courtTimetableService
    )
    {
        $this->courtTimetableService = $courtTimetableService;
        $this->courtService = $courtService;
        $this->reservationModel = $reservationModel;
        $this->customerService = $customerService;
        $this->authUser = Auth::user();
    }

    public function getAllReservations()
    {
        return $this->reservationModel->getReservationsByAcess($this->authUser->type, $this->authUser->id);
    }

    public function getReservationById($reservation): Reservation
    {
        $reservation = $this->reservationModel->getReservationById($reservation, $this->authUser);
        if (is_null($reservation)) {
            throw new ModelNotFoundException(Reservation::class);
        }

        return $reservation;
    }

    public function save(Request $request)
    {
        $validated = $this->validateReservationData($request);
        $courtTimetable = $this->courtTimetableService->getCourtTimetableById($validated['court_timetable_id']);

        $this->checkConflictingReservationConfirmed(
            $validated['court_id'],
            $courtTimetable
        );

        return Reservation::create($validated);
    }

    public function confirmReservation(string $reservationId)
    {
        $reservation = $this->validateConfirmReservation($reservationId);

        $reservation->update(['status' => ReservationStatus::CONFIRMED]);
        $reservation->courtTimetable->update(['status' => CourtTimetableStatus::BUSY]);
        $this->cancelPendingReservationsForSchedule($reservation->court->id, $reservation->courtTimetable->id);

        return $reservation;
    }

    /**
     * @throws ReservationNotFoundException
     */
    public function cancelReservation(string $reservationId): Reservation
    {
        $reservation = $this->getReservationById($reservationId);

        $reservation->update(['status' => ReservationStatus::CANCELED]);
        $reservation->courtTimetable->update(['status' => CourtTimetableStatus::AVAILABLE]);

        return $reservation;
    }

    public function checkConflictingReservationConfirmed($courtId, $courtTimetable)
    {
        $conflictingReservation = $this->reservationModel->existsConflictingReservation($courtId, $courtTimetable);

        if ($conflictingReservation) {
            throw ValidationException::withMessages([
                'start_time' => 'The time entered conflicts with an already scheduled time.',
            ]);
        }
    }

    private function validateReservationData(Request $request): array
    {
        $validated = $request->validate([
            'customer_id' => 'required',
            'court_id' => 'required',
            'court_timetable_id' => 'required'
        ]);

        $this->validateCustomerIdSent($validated['customer_id']);
        $this->courtService->getCourtById($validated['court_id']);
        $this->courtTimetableService->getCourtTimetableById($validated['court_timetable_id']);

        return $validated;
    }

    private function validateCustomerIdSent(string $id)
    {
        switch ($this->authUser->type) {
            case 'system':
                $this->customerService->getCustomerById($id);
                break;
            case 'customer':
                $this->authorizeCustomerAccess($id);
                break;
            default:
                throw new UnauthorizedException("Unauthorized access");
        }
    }

    private function authorizeCustomerAccess(string $id)
    {
        $customer = $this->customerService->getCustomerById($id);
        if ($customer->id !== $this->authUser->id) {
            throw ValidationException::withMessages([
                'customer_id' => ["you can't send an customer_id other than your own"],
            ]);
        }
    }

    private function validateConfirmReservation(string $reservationId): Reservation
    {
        $reservation = $this->getReservationById($reservationId);
        $reservationTimetable = $reservation->courtTimetable;

        if ($reservation->status == ReservationStatus::CANCELED) throw new ReservationCanceledException();
        if ($reservation->status == ReservationStatus::PENDING) {
            $this->checkConflictingReservationConfirmed(
                $reservation->court->id,
                $reservationTimetable
            );
        }

        return $reservation;
    }

    private function cancelPendingReservationsForSchedule($courtId, $courtTimetableId): void
    {
        $pendingReservations = $this->reservationModel->getPendingReservationsForSchedule($courtId, $courtTimetableId);

        foreach ($pendingReservations as $reservation) {
            $reservation->update(['status' => ReservationStatus::CANCELED]);
        }
    }
}
