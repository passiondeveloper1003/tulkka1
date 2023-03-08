<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Translation\WebinarExtraDescriptionTranslation;
use App\Models\Webinar;
use App\Models\WebinarExtraDescription;
use Illuminate\Http\Request;

class WebinarExtraDescriptionController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('admin_webinars_edit');

        $this->validate($request, [
            'type' => 'required|in:' . implode(',', WebinarExtraDescription::$types),
            'value' => 'required',
            'webinar_id' => 'required',
        ]);

        $data = $request->all();

        if (empty($data['locale'])) {
            $data['locale'] = getDefaultLocale();
        }

        if (!empty($data['webinar_id'])) {
            $webinar = Webinar::findOrFail($data['webinar_id']);

            $webinarExtraDescription = WebinarExtraDescription::create([
                'creator_id' => $webinar->creator_id,
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
        }

        return response()->json([
            'code' => 200,
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_webinars_edit');

        $webinarExtraDescription = WebinarExtraDescription::find($id);

        if (!empty($webinarExtraDescription)) {
            $locale = $request->get('locale', app()->getLocale());
            if (empty($locale)) {
                $locale = app()->getLocale();
            }
            storeContentLocale($locale, $webinarExtraDescription->getTable(), $webinarExtraDescription->id);

            $webinarExtraDescription->value = $webinarExtraDescription->getValueAttribute();
            $webinarExtraDescription->locale = mb_strtoupper($locale);

            return response()->json([
                'webinarExtraDescription' => $webinarExtraDescription
            ], 200);
        }

        return response()->json([], 422);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_webinars_edit');

        $this->validate($request, [
            'type' => 'required|in:' . implode(',', WebinarExtraDescription::$types),
            'value' => 'required',
            'webinar_id' => 'required',
        ]);

        $data = $request->all();

        if (empty($data['locale'])) {
            $data['locale'] = getDefaultLocale();
        }

        $webinarExtraDescription = WebinarExtraDescription::find($id);

        if ($webinarExtraDescription) {

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

    public function destroy(Request $request, $id)
    {
        $this->authorize('admin_webinars_edit');

        WebinarExtraDescription::find($id)->delete();

        return redirect()->back();
    }
}
