<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Get all events for the authenticated user
     * Supports filtering by type, status, priority
     */
    public function index(Request $request)
    {
        $query = Event::where('user_id', Auth::id());

        // Apply filters
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            if ($request->status !== 'all') {
                $query->where('status', $request->status);
            }
            // If status=all, no filter applied → shows everything including completed
        } else {
            // Default: exclude completed events so calendar stays clean
            $query->where('status', '!=', 'completed');
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // Date range filter
        if ($request->has('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        $events = $query->orderBy('date')->get();

        // Append display color to each event
        $events->each(function ($event) {
            $event->append('display_color');
        });

        return response()->json($events);
    }

    /**
     * Create a new event
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'nullable|date',
            'type' => 'required|in:exam,study,meeting,birthday,review',
        ]);

        // Auto-derive date from start_datetime if not explicitly provided
        $date = $request->date;
        if (!$date && $request->start_datetime) {
            $date = \Carbon\Carbon::parse($request->start_datetime)->format('Y-m-d');
        }

        $event = Event::create([
            'user_id' => Auth::id(),
            'date' => $date,
            'title' => $request->title,
            'type' => $request->type,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'description' => $request->description,
            'color' => $request->color,
            'reminder' => $request->reminder ?? 'none',
            'is_recurring' => $request->is_recurring ?? false,
            'recurrence_rule' => $request->recurrence_rule,
            'status' => $request->status ?? 'pending',
            'priority' => $request->priority ?? 'medium',
        ]);

        $event->append('display_color');

        return response()->json($event, 201);
    }

    /**
     * Update an existing event
     */
    public function update(Request $request, $id)
    {
        $event = Event::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $event->update($request->only([
            'title',
            'date',
            'type',
            'start_datetime',
            'end_datetime',
            'description',
            'color',
            'reminder',
            'is_recurring',
            'recurrence_rule',
            'status',
            'priority'
        ]));

        $event->append('display_color');

        return response()->json($event);
    }

    /**
     * Update event date (for drag-and-drop)
     */
    public function updateDate(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $event = Event::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $event->update([
            'date' => $request->date,
        ]);

        return response()->json(['success' => true, 'event' => $event]);
    }

    /**
     * Toggle event status (pending -> completed or vice versa)
     */
    public function toggleStatus($id)
    {
        $event = Event::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $newStatus = $event->status === 'completed' ? 'pending' : 'completed';
        $event->update(['status' => $newStatus]);

        return response()->json(['success' => true, 'status' => $newStatus]);
    }

    /**
     * Delete an event
     */
    public function destroy($id)
    {
        Event::where('id', $id)->where('user_id', Auth::id())->delete();
        return response()->json(['success' => true]);
    }
}
