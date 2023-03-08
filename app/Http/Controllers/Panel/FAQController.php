<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Faq;
use App\Models\Translation\FaqTranslation;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Validator;

class FAQController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->get('ajax')['new'];
        $canStore = false;

        $validator = Validator::make($data, [
            'title' => 'required|max:255',
            'answer' => 'required',
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
                $canStore = true;
            }
        } elseif (!empty($data['bundle_id'])) {
            $bundle = Bundle::find($data['bundle_id']);

            if (!empty($bundle) and $bundle->canAccess($user)) {
                $canStore = true;
            }
        }


        if ($canStore) {
            $faq = Faq::create([
                'creator_id' => $user->id,
                'webinar_id' => !empty($data['webinar_id']) ? $data['webinar_id'] : null,
                'bundle_id' => !empty($data['bundle_id']) ? $data['bundle_id'] : null,
                'created_at' => time()
            ]);

            if (!empty($faq)) {
                FaqTranslation::updateOrCreate([
                    'faq_id' => $faq->id,
                    'locale' => mb_strtolower($data['locale']),
                ], [
                    'title' => $data['title'],
                    'answer' => $data['answer'],
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
        $user = auth()->user();
        $data = $request->get('ajax')[$id];

        $canStore = false;

        $validator = Validator::make($data, [
            'title' => 'required|max:255',
            'answer' => 'required',
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
                $canStore = true;
            }
        } elseif (!empty($data['bundle_id'])) {
            $bundle = Bundle::find($data['bundle_id']);

            if (!empty($bundle) and $bundle->canAccess($user)) {
                $canStore = true;
            }
        }

        if ($canStore) {

            $faq = Faq::where('id', $id)
                ->where('creator_id', $user->id)
                ->first();

            if (!empty($faq)) {
                $faq->update([
                    'updated_at' => time()
                ]);

                FaqTranslation::updateOrCreate([
                    'faq_id' => $faq->id,
                    'locale' => mb_strtolower($data['locale']),
                ], [
                    'title' => $data['title'],
                    'answer' => $data['answer'],
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
        $faq = Faq::where('id', $id)
            ->where('creator_id', auth()->id())
            ->first();

        if (!empty($faq)) {
            $faq->delete();
        }

        return response()->json([
            'code' => 200
        ], 200);
    }
}
