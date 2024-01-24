<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Exhibitor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $currentEvent = getCurrentEvent();
        if (!$currentEvent) {
            return response()->json([
                'success' => "error",
                'message' => 'No current event found.'
            ], 404);
        }

        $currentEventId = $currentEvent->id ?? null;
        $upcomingEvents = Event::where('id', '!=', $currentEventId)
            ->where('start_date', '>=', now()->format('Y-m-d'))
            ->orderBy('start_date', 'asc')->get();

        $data = [
            'currentEvent' => [
                'id' => $currentEvent->id,
                'title' => $currentEvent->title,
                'start_date' => $currentEvent->start_date,
                'end_date' => $currentEvent->end_date,
                'thumbnail' => asset('storage/' . ($currentEvent->_meta['thumbnail'] ?? '')),
                'layout' => asset('storage/' . ($currentEvent->_meta['layout'] ?? ''))
            ],
            'upcomingEvents' => $upcomingEvents->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
                    'thumbnail' => asset('storage/' . ($event->_meta['thumbnail'] ?? '')),
                    'layout' => asset('storage/' . ($event->_meta['layout'] ?? ''))
                ];
            })
        ];

        return response()->json([
            'success' => "success",
            'data' => $data
        ], 200);

    }

    public function store(Request $request)
    {
        $exhibitor_id = auth()->user()->id;
        $exhibitor = Exhibitor::find($exhibitor_id);
        if (!$exhibitor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exhibitor not found...'
            ]);
        }
        if (!$request->event_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event Id is missing..'
            ]);
        }
        $event = Event::find($request->event_id);
        if (!$event) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event not found..'
            ]);
        }
        $isExhibitorExists = $exhibitor->eventExhibitors()->where('event_id', $request->event_id)->first();
        if ($isExhibitorExists) {
            return response()->json([
                'message' => 'Exhibitor already registered',
                'status' => 'error',
            ], 201);
        }
        try {
            $exhibitor->eventExhibitors()->create([
                'event_id' => $request->event_id,
            ]);
            return response()->json([
                'message' => 'Exhibitor registered successfully',
                'status' => 'success',
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'status' => 'fail',
            ], 500);
        }
    }
}


