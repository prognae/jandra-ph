<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request) {
        $validated = $request->validate([
            'page' => ['integer', 'min:1'],
            'limit' => ['integer', 'min:10', 'max:100'],
            'order' => ['string', 'in:asc,desc'],
            'sort' => ['string', 'in:id,name'],
            'search' => ['string', 'max:255']
        ]);

        $limit = $validated['limit'] ?? 10;

        $order = $validated['order'] ?? 'desc';

        $sort = $validated['sort'] ?? 'id';

        $events = Event::query();

        if(isset($validated['search'])) {
            $events->where('event_title', 'like', '%'. $validated['search'] .'%');
        }

        if($events->doesntExist()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No events found.'
            ]);
        }

        $data = $events->orderBy($sort, $order)->paginate($limit);

        return response()->json([
            'status' => 'success',
            'message' => 'Events retrieved successfully',
            'data' => $data
        ]);
    }

    public function show($id) {
        $event = Event::where('id', $id);

        if($event->doesntExist()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No event found.'
            ]);
        }

        $data = $event->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Event retrieved.',
            'data' => $data
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'event_title' => ['required', 'string', 'max:255'],
            'event_description' => ['required', 'string', 'max:500'],
            'event_date' => ['required', 'date'],
            'event_photo' => ['required', 'url'],
        ]);

        $event = new Event();

        $event->event_title = $validated['event_title'];

        $event->event_description = $validated['event_description'];

        $event->event_date = $validated['event_date'];

        $event->event_photo = $validated['event_photo'];

        $event->thumbnail_event_photo = 'thumbnail_' . $validated['event_photo'];

        $event->save();

        if(!$event->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error on event creation.'
            ]);
        } else if($event->id) {
            return response()->json([
                'status' => 'success',
                'message' => 'Event created successfully.'
            ]);
        }
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'event_title' => ['required', 'string', 'max:255'],
            'event_description' => ['required', 'string', 'max:500'],
            'event_date' => ['required', 'date'],
            'event_photo' => ['required', 'url'],
        ]);

        $event = Event::find($id);

        if(!isset($event)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No event found.'
            ]);
        }

        $event->event_title = $validated['event_title'];

        $event->event_description = $validated['event_description'];

        $event->event_date = $validated['event_date'];

        $event->event_photo = $validated['event_photo'];

        $event->thumbnail_event_photo = 'thumbnail_' . $validated['event_photo'];

        if(!$event->isDirty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No changes has been made.'
            ]);
        } else if($event->isDirty()) {
            $event->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Event updated successfully.'
            ]);
        }
    }

    public function delete($id) {
        $event = Event::find($id);

        if(!isset($event)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No event found.'
            ]);
        }

        $delete = $event->delete();

        if(!$delete) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database error.'
            ]);
        } else if($delete) {
            return response()->json([
                'status' => 'success',
                'message' => 'Event has been deleted.'
            ]);
        }
    }

    public function restore($id) {
        $event = Event::where('id', $id)->onlyTrashed()->first();

        if(!$event) {
            return response()->json([
                'status' => 'error',
                'message' => 'No event found.'
            ]);
        }

        $restore = $event->restore();

        if(!$restore) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database error.'
            ]);
        } else if($restore) {
            return response()->json([
                'status' => 'success',
                'message' => 'Event has been restored.'
            ]);
        }
    }
}
