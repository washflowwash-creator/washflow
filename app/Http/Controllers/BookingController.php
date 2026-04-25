<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    private const SERVICE_TYPES = [
        'Wash • Dry • Fold (Minimum 8 kg)',
        'Wash Only (Minimum 8 kg)',
        'Dry Only (Minimum 8 kg)',
        'Heavy Items (Min. 5 kg)',
        'Comforter (1 pc per load)',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::query()
            ->with('order')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bookings.create', ['serviceTypes' => self::SERVICE_TYPES]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type' => ['required', Rule::in(self::SERVICE_TYPES)],
            'scheduled_at' => ['required', 'date', 'after_or_equal:now'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        Booking::create([
            'user_id' => $request->user()->id,
            'service_type' => $validated['service_type'],
            'scheduled_at' => $validated['scheduled_at'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking submitted.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);

        $booking->load('order.payment');

        return view('bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);

        return view('bookings.edit', [
            'booking' => $booking,
            'serviceTypes' => self::SERVICE_TYPES,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if($booking->status !== 'pending', 422, 'Only pending bookings can be edited.');

        $validated = $request->validate([
            'service_type' => ['required', Rule::in(self::SERVICE_TYPES)],
            'scheduled_at' => ['required', 'date', 'after_or_equal:now'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $booking->update($validated);

        return redirect()->route('bookings.index')->with('success', 'Booking updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);
        abort_if($booking->status !== 'pending', 422, 'Only pending bookings can be cancelled.');

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking cancelled.');
    }
}
