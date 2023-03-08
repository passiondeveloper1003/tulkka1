<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Ticket;
use App\Models\Translation\TicketTranslation;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Validator;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        $canStore = false;
        $user = auth()->user();
        $data = $request->get('ajax')['new'];

        $rules = [
            'title' => 'required|max:64',
            'sub_title' => 'nullable',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'discount' => 'required|integer|between:1,100',
        ];

        if (!empty($data['webinar_id'])) {
            $webinar = Webinar::find($data['webinar_id']);

            if (!empty($webinar) and $webinar->canAccess($user)) {
                $canStore = true;

                $sumTicketsCapacities = $webinar->tickets->sum('capacity');
                $capacity = $webinar->capacity - $sumTicketsCapacities;

                $rules ['webinar_id'] = 'required';
                $rules ['capacity'] = $webinar->isWebinar() ? 'nullable|numeric|min:1|max:' . $capacity : 'nullable';

                if (empty($data['capacity']) and $webinar->isWebinar()) {
                    $data['capacity'] = $capacity;
                }
            }
        } else if (!empty($data['bundle_id'])) {
            $bundle = Bundle::find($data['bundle_id']);

            if (!empty($bundle) and $bundle->canAccess($user)) {
                $canStore = true;
                $rules ['bundle_id'] = 'required';
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($canStore) {
            $ticket = Ticket::create([
                'creator_id' => $user->id,
                'webinar_id' => !empty($data['webinar_id']) ? $data['webinar_id'] : null,
                'bundle_id' => !empty($data['bundle_id']) ? $data['bundle_id'] : null,
                'start_date' => strtotime($data['start_date']),
                'end_date' => strtotime($data['end_date']),
                'discount' => $data['discount'],
                'capacity' => $data['capacity'],
                'created_at' => time()
            ]);

            if (!empty($ticket)) {
                TicketTranslation::updateOrCreate([
                    'ticket_id' => $ticket->id,
                    'locale' => mb_strtolower($data['locale']),
                ], [
                    'title' => $data['title'],
                ]);
            }

            return response()->json([
                'code' => 200,
            ], 200);
        }

        abort(403);
    }

    public function update(Request $request, $id)
    {
        $canStore = false;
        $user = auth()->user();

        $data = $request->get('ajax')[$id];

        $rules = [
            'title' => 'required|max:64',
            'sub_title' => 'nullable',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'discount' => 'required|integer|between:1,100',
        ];

        if (!empty($data['webinar_id'])) {
            $webinar = Webinar::find($data['webinar_id']);

            if (!empty($webinar) and $webinar->canAccess($user)) {
                $canStore = true;

                $sumTicketsCapacities = $webinar->tickets->sum('capacity');
                $capacity = $webinar->capacity - $sumTicketsCapacities;

                $rules ['webinar_id'] = 'required';
                $rules ['capacity'] = $webinar->isWebinar() ? 'nullable|numeric|min:1|max:' . $capacity : 'nullable';

                if (empty($data['capacity']) and $webinar->isWebinar()) {
                    $data['capacity'] = $capacity;
                }
            }
        } else if (!empty($data['bundle_id'])) {
            $bundle = Bundle::find($data['bundle_id']);

            if (!empty($bundle) and $bundle->canAccess($user)) {
                $canStore = true;
                $rules ['bundle_id'] = 'required';
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($canStore) {
            $ticket = Ticket::where('id', $id)
                ->where('creator_id', $user->id)
                ->first();

            if (!empty($ticket)) {
                $ticket->update([
                    'start_date' => strtotime($data['start_date']),
                    'end_date' => strtotime($data['end_date']),
                    'discount' => $data['discount'],
                    'capacity' => $data['capacity'],
                    'updated_at' => time()
                ]);

                TicketTranslation::updateOrCreate([
                    'ticket_id' => $ticket->id,
                    'locale' => mb_strtolower($data['locale']),
                ], [
                    'title' => $data['title'],
                ]);

                return response()->json([
                    'code' => 200,
                ], 200);
            }
        }

        abort(403);
    }

    public function destroy(Request $request, $id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('creator_id', auth()->id())
            ->first();

        if (!empty($ticket)) {
            $ticket->delete();

            return response()->json([
                'code' => 200
            ], 200);
        }

        return response()->json([], 422);
    }
}
