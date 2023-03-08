<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Translation\FileTranslation;
use App\Models\Webinar;
use App\Models\WebinarChapterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Validator;

class FileController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('admin_webinars_edit');

        $data = $request->get('ajax')['new'];

        if (empty($data['storage'])) {
            $data['storage'] = 'upload';
        }

        if (!empty($data['file_path']) and is_array($data['file_path'])) {
            $data['file_path'] = $data['file_path'][0];
        }

        $sourceRequiredFileType = ['external_link', 's3', 'google_drive', 'upload'];
        $sourceRequiredFileVolume = ['external_link', 's3', 'google_drive'];
        $sourceDefaultFileTypeAndVolume = ['youtube', 'vimeo', 'iframe'];

        if (in_array($data['storage'], $sourceDefaultFileTypeAndVolume)) {
            $data['file_type'] = 'video';
            $data['volume'] = 0;
        }

        $rules = [
            'webinar_id' => 'required',
            'chapter_id' => 'required',
            'title' => 'required|max:255',
            'accessibility' => 'required|' . Rule::in(File::$accessibility),
            'file_path' => 'required',
            'file_type' => Rule::requiredIf(in_array($data['storage'], $sourceRequiredFileType)),
            'volume' => Rule::requiredIf(in_array($data['storage'], $sourceRequiredFileVolume)),
            'description' => 'nullable',
        ];

        if ($data['storage'] == 'upload_archive') {
            $rules['interactive_type'] = 'required';
            $rules['interactive_file_name'] = Rule::requiredIf($data['interactive_type'] == 'custom');
        }

        if ($data['storage'] == 's3') {
            $rules ['file_path'] = 'nullable';
            $rules ['s3_file'] = 'required';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data['downloadable'] = !empty($data['downloadable']);
        if (in_array($data['storage'], ['youtube', 'vimeo', 'iframe', 'google_drive', 'upload_archive'])) {
            $data['downloadable'] = false;
        } elseif (in_array($data['storage'], ['external_link', 's3']) and $data['file_type'] != 'video') {
            $data['downloadable'] = true;
        }

        if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
            $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
            $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
        } else {
            $data['check_previous_parts'] = false;
            $data['access_after_day'] = null;
        }

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar)) {
            $user = $webinar->creator;

            $volumeMatches = [''];
            $fileInfos = null;

            if ($data['storage'] == 'upload_archive') {
                $fileInfos = $this->fileInfo($data['file_path']);

                if (empty($fileInfos) or $fileInfos['extension'] != 'zip') {
                    return response([
                        'code' => 422,
                        'errors' => [
                            'file_path' => [trans('validation.mimes', ['attribute' => 'file', 'values' => 'zip'])]
                        ],
                    ], 422);
                }

                $fileInfos['extension'] = 'archive';
                $data['interactive_file_path'] = $this->handleUnZipFile($data, $user->id);

            } elseif ($data['storage'] == 'upload') {
                $uploadFile = $this->fileInfo($data['file_path']);
                $data['volume'] = $uploadFile['size'];
            } elseif ($data['storage'] == 's3') {
                $result = $this->uploadFileToS3($data['s3_file'], $user->id);

                if (!$result['status']) {
                    return $result['path'];
                }

                $data['file_path'] = $result['path'];
                $fileInfos['extension'] = $data['file_type'];

                preg_match('!\d+!', $data['volume'], $volumeMatches);
                $fileInfos['size'] = $volumeMatches[0] * 1048576;
            } else {
                preg_match('!\d+!', $data['volume'], $volumeMatches);
            }

            $file = File::create([
                'creator_id' => $user->id,
                'webinar_id' => $data['webinar_id'],
                'chapter_id' => $data['chapter_id'],
                'file' => $data['file_path'],
                'volume' => formatSizeUnits(!empty($fileInfos) ? $fileInfos['size'] : $data['volume']),
                'file_type' => !empty($fileInfos) ? $fileInfos['extension'] : $data['file_type'],
                'accessibility' => $data['accessibility'],
                'storage' => $data['storage'],
                'interactive_type' => $data['interactive_type'] ?? null,
                'interactive_file_name' => $data['interactive_file_name'] ?? null,
                'interactive_file_path' => $data['interactive_file_path'] ?? null,
                'downloadable' => $data['downloadable'],
                'online_viewer' => (!empty($data['online_viewer']) and $data['online_viewer'] == 'on'),
                'check_previous_parts' => $data['check_previous_parts'],
                'access_after_day' => $data['access_after_day'],
                'status' => (!empty($data['status']) and $data['status'] == 'on') ? File::$Active : File::$Inactive,
                'created_at' => time()
            ]);

            if (!empty($file)) {
                FileTranslation::updateOrCreate([
                    'file_id' => $file->id,
                    'locale' => mb_strtolower($data['locale']),
                ], [
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);

                if (!empty($file->chapter_id)) {
                    WebinarChapterItem::makeItem($file->creator_id, $file->chapter_id, $file->id, WebinarChapterItem::$chapterFile);
                }
            }

            return response()->json([
                'code' => 200,
            ], 200);
        }

        return response()->json([], 422);
    }


    public function edit(Request $request, $id)
    {
        $this->authorize('admin_webinars_edit');

        $file = File::where('id', $id)->first();

        if (!empty($file)) {
            $locale = $request->get('locale', app()->getLocale());
            if (empty($locale)) {
                $locale = app()->getLocale();
            }
            storeContentLocale($locale, $file->getTable(), $file->id);

            $file->title = $file->getTitleAttribute();
            $file->description = $file->getDescriptionAttribute();
            $file->file_path = $file->file;
            $file->locale = mb_strtoupper($locale);

            return response()->json([
                'file' => $file
            ], 200);
        }

        return response()->json([], 422);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('admin_webinars_edit');

        $data = $request->get('ajax')[$id];

        $sourceRequiredFileType = ['external_link', 's3', 'google_drive', 'upload'];
        $sourceRequiredFileVolume = ['external_link', 's3', 'google_drive'];

        if (empty($data['storage'])) {
            $data['storage'] = 'upload';
        }

        if (!empty($data['file_path']) and is_array($data['file_path'])) {
            $data['file_path'] = $data['file_path'][0];
        }

        $rules = [
            'webinar_id' => 'required',
            'chapter_id' => 'required',
            'title' => 'required|max:255',
            'accessibility' => 'required|' . Rule::in(File::$accessibility),
            'file_path' => 'required',
            'file_type' => Rule::requiredIf(in_array($data['storage'], $sourceRequiredFileType)),
            'volume' => Rule::requiredIf(in_array($data['storage'], $sourceRequiredFileVolume)),
            'description' => 'nullable',
        ];

        if ($data['storage'] == 'upload_archive') {
            $rules['interactive_type'] = 'required';
            $rules['interactive_file_name'] = Rule::requiredIf($data['interactive_type'] == 'custom');
        }

        if ($data['storage'] == 's3') {
            $rules ['file_path'] = 'nullable';
            $rules ['s3_file'] = 'nullable';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data['downloadable'] = !empty($data['downloadable']);
        if (in_array($data['storage'], ['youtube', 'vimeo', 'iframe', 'google_drive', 'upload_archive'])) {
            $data['downloadable'] = false;
        } elseif (in_array($data['storage'], ['external_link', 's3']) and $data['file_type'] != 'video') {
            $data['downloadable'] = true;
        }

        if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
            $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
            $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
        } else {
            $data['check_previous_parts'] = false;
            $data['access_after_day'] = null;
        }

        $volumeMatches = ['0'];
        $fileInfos = null;

        $file = File::where('id', $id)->first();

        if ($data['storage'] == 'upload_archive') {
            $fileInfos = $this->fileInfo($data['file_path']);

            if (empty($fileInfos) or $fileInfos['extension'] != 'zip') {
                return response([
                    'code' => 422,
                    'errors' => [
                        'file_path' => [trans('validation.mimes', ['attribute' => 'file', 'values' => 'zip'])]
                    ],
                ], 422);
            }

            $fileInfos['extension'] = 'archive';
            $data['interactive_file_path'] = $this->handleUnZipFile($data, $file->creator_id);

        } elseif ($data['storage'] == 'upload') {
            $uploadFile = $this->fileInfo($data['file_path']);
            $data['volume'] = $uploadFile['size'];
        } elseif ($data['storage'] == 's3') {
            if (!empty($data['s3_file'])) {
                $result = $this->uploadFileToS3($data['s3_file'], $file->creator_id);

                if (!$result['status']) {
                    return $result['path'];
                }

                $data['file_path'] = $result['path'];
            }

            $fileInfos['extension'] = $data['file_type'];

            preg_match('!\d+!', $data['volume'], $volumeMatches);
            $fileInfos['size'] = $volumeMatches[0] * 1048576;
        } else {
            preg_match('!\d+!', $data['volume'], $volumeMatches);
        }

        if (!empty($file)) {
            $file->update([
                'file' => $data['file_path'],
                'volume' => formatSizeUnits(!empty($fileInfos) ? $fileInfos['size'] : $data['volume']),
                'file_type' => !empty($fileInfos) ? $fileInfos['extension'] : $data['file_type'],
                'accessibility' => $data['accessibility'],
                'storage' => $data['storage'],
                'interactive_type' => $data['interactive_type'] ?? null,
                'interactive_file_name' => $data['interactive_file_name'] ?? null,
                'interactive_file_path' => $data['interactive_file_path'] ?? null,
                'downloadable' => $data['downloadable'],
                'online_viewer' => (!empty($data['online_viewer']) and $data['online_viewer'] == 'on'),
                'check_previous_parts' => $data['check_previous_parts'],
                'access_after_day' => $data['access_after_day'],
                'status' => (!empty($data['status']) and $data['status'] == 'on') ? File::$Active : File::$Inactive,
                'updated_at' => time()
            ]);

            FileTranslation::updateOrCreate([
                'file_id' => $file->id,
                'locale' => mb_strtolower($data['locale']),
            ], [
                'title' => $data['title'],
                'description' => $data['description'],
            ]);

            WebinarChapterItem::where('user_id', $file->creator_id)
                ->where('item_id', $file->id)
                ->where('type', WebinarChapterItem::$chapterFile)
                ->delete();

            if (!empty($file->chapter_id)) {
                WebinarChapterItem::makeItem($file->creator_id, $file->chapter_id, $file->id, WebinarChapterItem::$chapterFile);
            }

            removeContentLocale();

            return response()->json([
                'code' => 200,
            ], 200);
        }

        removeContentLocale();

        return response()->json([], 422);
    }

    private function handleUnZipFile($data, $user_id)
    {
        $path = $data['file_path'];
        $interactiveType = $data['interactive_type'] ?? null;
        $interactiveFileName = $data['interactive_file_name'] ?? null;

        $storage = Storage::disk('public');

        $fileInfo = $this->fileInfo($path);

        $extractPath = $user_id . '/' . $fileInfo['name'];
        $storageExtractPath = $storage->url($extractPath);

        if (!$storage->exists($extractPath)) {
            $storage->makeDirectory($extractPath);

            $filePath = public_path($path);

            $zip = new \ZipArchive();
            $res = $zip->open($filePath);

            if ($res) {
                $zip->extractTo(public_path($storageExtractPath));

                $zip->close();
            }
        }

        $fileName = 'index.html';

        if ($interactiveType == 'i_spring') {
            $fileName = 'story.html';
        } elseif ($interactiveType == 'custom') {
            $fileName = $interactiveFileName;
        }

        return $storageExtractPath . '/' . $fileName;
    }

    private function uploadFileToS3($file, $user_id)
    {
        $path = 'store/' . $user_id;

        $result = [
            'path' => null,
            'status' => true
        ];

        try {
            $fileName = time() . $file->getClientOriginalName();

            $storage = Storage::disk('minio');

            if (!$storage->exists($path)) {
                $storage->makeDirectory($path);
            }

            $path = $storage->put($path, $file, $fileName);
            $result['path'] = $storage->url($path);
        } catch (\Exception $ex) {

            $result = [
                'path' => response([
                    'code' => 500,
                    'message' => $ex->getMessage(),
                    'traces' => $ex->getTrace(),
                ], 500),
                'status' => false
            ];
        }

        return $result;
    }

    public function fileInfo($path)
    {
        $file = array();

        $file_path = public_path($path);

        $filePath = pathinfo($file_path);

        $file['name'] = $filePath['filename'];
        $file['extension'] = $filePath['extension'];
        $file['size'] = filesize($file_path);

        return $file;
    }

    public function destroy(Request $request, $id)
    {
        $this->authorize('admin_webinars_edit');

        $file = File::where('id', $id)
            ->first();

        if (!empty($file)) {
            WebinarChapterItem::where('user_id', $file->creator_id)
                ->where('item_id', $file->id)
                ->where('type', WebinarChapterItem::$chapterFile)
                ->delete();

            $file->delete();
        }

        return response()->json([
            'code' => 200
        ], 200);
    }
}
