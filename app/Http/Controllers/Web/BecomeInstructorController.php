<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\BecomeInstructor;
use App\Models\Category;
use App\Models\RegistrationPackage;
use App\Models\Role;
use App\Models\UserOccupation;
use Illuminate\Http\Request;

class BecomeInstructorController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isUser()) {
            $categories = Category::where('parent_id', null)
                ->with('subCategories')
                ->get();

            $occupations = $user->occupations->pluck('category_id')->toArray();

            $lastRequest = BecomeInstructor::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            $isOrganizationRole = (!empty($lastRequest) and $lastRequest->role == Role::$organization);
            $isInstructorRole = (empty($lastRequest) or $lastRequest->role == Role::$teacher);

            $data = [
                'pageTitle' => trans('site.become_instructor'),
                'user' => $user,
                'lastRequest' => $lastRequest,
                'categories' => $categories,
                'occupations' => $occupations,
                'isOrganizationRole' => $isOrganizationRole,
                'isInstructorRole' => $isInstructorRole,
            ];

            return view('web.default.user.become_instructor.index', $data);
        }

        abort(404);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->isUser()) {
            $lastRequest = BecomeInstructor::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'accept'])
                ->first();

            if (empty($lastRequest)) {
                $this->validate($request, [
                    'role' => 'required',
                    'occupations' => 'required',
                    'certificate' => 'nullable|string',
                    'account_type' => 'required',
                    'iban' => 'required',
                    'account_id' => 'required',
                    'identity_scan' => 'required',
                    'description' => 'nullable|string',
                ]);

                $data = $request->all();

                BecomeInstructor::create([
                    'user_id' => $user->id,
                    'role' => $data['role'],
                    'certificate' => $data['certificate'],
                    'description' => $data['description'],
                    'created_at' => time()
                ]);

                $user->update([
                    'account_type' => $data['account_type'],
                    'iban' => $data['iban'],
                    'account_id' => $data['account_id'],
                    'identity_scan' => $data['identity_scan'],
                    'certificate' => $data['certificate'],
                ]);

                if (!empty($data['occupations'])) {
                    UserOccupation::where('user_id', $user->id)->delete();

                    foreach ($data['occupations'] as $category_id) {
                        UserOccupation::create([
                            'user_id' => $user->id,
                            'category_id' => $category_id
                        ]);
                    }
                }
            }

            if ((!empty(getRegistrationPackagesGeneralSettings('show_packages_during_registration')) and getRegistrationPackagesGeneralSettings('show_packages_during_registration'))) {
                return redirect(route('becomeInstructorPackages'));
            }

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('site.become_instructor_success_request'),
                'status' => 'success'
            ];
            return back()->with(['toast' => $toastData]);
        }

        abort(404);
    }

    public function packages()
    {
        $user = auth()->user();

        $role = 'instructors';

        if (!empty($user) and $user->isUser()) {
            $becomeInstructor = BecomeInstructor::where('user_id', $user->id)->first();

            if (!empty($becomeInstructor) and $becomeInstructor->role == Role::$organization) {
                $role = 'organizations';
            }

            $packages = RegistrationPackage::where('role', $role)
                ->where('status', 'active')
                ->get();

            $userPackage = new UserPackage();
            $defaultPackage = $userPackage->getDefaultPackage($role);

            $data = [
                'pageTitle' => trans('update.registration_packages'),
                'packages' => $packages,
                'defaultPackage' => $defaultPackage,
                'becomeInstructor' => $becomeInstructor ?? null,
                'selectedRole' => $role
            ];

            return view('web.default.user.become_instructor.packages', $data);
        }

        abort(404);
    }
}
