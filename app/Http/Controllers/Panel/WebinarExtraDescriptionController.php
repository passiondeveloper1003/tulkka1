<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Translation\WebinarExtraDescriptionTranslation;
use App\Models\Webinar;
use App\Models\WebinarExtraDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebinarExtraDescriptionController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->get('ajax')['new'];

        $validator = Validator::make($data, [
            'type' => 'required|in:' . implode(',', WebinarExtraDescription::$types),
            'value' => 'required',
            'webinar_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!empty($data['webinar_id'])) {
            $webinar = Webinar::find($data['webinar_id']);

            if (!empty($webinar) and $webinar->canAccess($user)) {
                $webinarExtraDescription = WebinarExtraDescription::create([
                    'creator_id' => $user->id,
                    'webinar_id' => $data['webinar_id'],
                    'type' => $data['type'],
                    'created_at' => time()
                ]);

                if (!empty($webinarExtraDescription)) {
                    WebinarExtraDescriptionTranslation::updateOrCreate([
                        'webinar_extra_description_id' => $webinarExtraDescription->id,
                        'locale' => mb_strtolower($data['locale']),
                    ], [
                        'value' => $data['value'],
                    ]);
                }

                return response()->json([
                    'code' => 200,
                ], 200);
            }
        }

        abort(403);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $data = $request->get('ajax')[$id];

        $validator = Validator::make($data, [
            'type' => 'required|in:' . implode(',', WebinarExtraDescription::$types),
            'value' => 'required',
            'webinar_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!empty($data['webinar_id'])) {
            $webinar = Webinar::find($data['webinar_id']);

            if (!empty($webinar) and $webinar->canAccess($user)) {
                $webinarExtraDescription = WebinarExtraDescription::where('id', $id)
                    ->where('creator_id', $user->id)
                    ->first();

                if (!empty($webinarExtraDescription)) {

                    WebinarExtraDescriptionTranslation::updateOrCreate([
                        'webinar_extra_description_id' => $webinarExtraDescription->id,
                        'locale' => mb_strtolower($data['locale']),
                    ], [
                        'value' => $data['value'],
                    ]);

                    return response()->json([
                        'code' => 200,
                    ], 200);
                }
            }
        }

        abort(403);
    }

    public function destroy(Request $request, $id)
    {
        $webinarExtraDescription = WebinarExtraDescription::where('id', $id)
            ->where('creator_id', auth()->id())
            ->first();

        if (!empty($webinarExtraDescription)) {
            $webinarExtraDescription->delete();
        }

        return response()->json([
            'code' => 200
        ], 200);
    }
}
