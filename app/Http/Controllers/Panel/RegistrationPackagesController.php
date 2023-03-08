<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentChannel;
use App\Models\Product;
use App\Models\RegistrationPackage;
use App\Models\Webinar;
use Illuminate\Http\Request;

class RegistrationPackagesController extends Controller
{
    private function checkAccess($user = null)
    {
        if (empty($user)) {
            $user = auth()->user();
        }

        if (!($user->isOrganization() or $user->isTeacher()) or !getRegistrationPackagesGeneralSettings('status')) {
            abort(404);
        }
    }

    public function index()
    {
        $user = auth()->user();

        $this->checkAccess($user);

        $role = 'instructors';

        if ($user->isOrganization()) {
            $role = 'organizations';
        }

        $packages = RegistrationPackage::where('role', $role)
            ->where('status', 'active')
            ->get();

        $userPackage = new UserPackage($user);
        $activePackage = $userPackage->getPackage();

        $data = [
            'pageTitle' => trans('update.registration_packages'),
            'packages' => $packages,
            'activePackage' => $activePackage,
            'accountStatistics' => $this->handleAccountStatistics($user),
        ];

        return view('web.default.panel.financial.registration_packages', $data);
    }

    private function handleAccountStatistics($user)
    {
        $myInstructorsCount = 0;
        $myStudentsCount = 0;
        if ($user->isOrganization()) {
            $myInstructorsCount = $user->getOrganizationTeachers()->count();
            $myStudentsCount = $user->getOrganizationStudents()->count();
        }

        $myCoursesCount = Webinar::where('creator_id', $user->id)->count();
        $myMeetingCount = !empty($user->meeting) ? $user->meeting->meetingTimes()->count() : 0;
        $myProductCount = Product::where('creator_id', $user->id)->count();

        return [
            'myInstructorsCount' => $myInstructorsCount,
            'myStudentsCount' => $myStudentsCount,
            'myCoursesCount' => $myCoursesCount,
            'myMeetingCount' => $myMeetingCount,
            'myProductCount' => $myProductCount,
        ];
    }

    public function pay(Request $request)
    {
        $user = auth()->user();

        $paymentChannels = PaymentChannel::where('status', 'active')->get();

        $becomeInstructorId = $request->get('become_instructor_id');
        $package = RegistrationPackage::where('id', $request->input('id'))->first();

        if (empty($package)) {
            $toastData = [
                'msg' => trans('update.registration_package_not_valid'),
                'status' => 'error'
            ];
            return back()->with(['toast' => $toastData]);
        }

        $financialSettings = getFinancialSettings();
        $tax = $financialSettings['tax'] ?? 0;
        $amount = $package->price;
        $taxPrice = $tax ? $amount * $tax / 100 : 0;

        $order = Order::create([
            "user_id" => $user->id,
            "status" => Order::$pending,
            'tax' => $taxPrice,
            'commission' => 0,
            "amount" => $amount,
            "total_amount" => $amount + $taxPrice,
            "created_at" => time(),
        ]);

        OrderItem::updateOrCreate([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'registration_package_id' => $package->id,
        ], [
            'become_instructor_id' => $becomeInstructorId ?? null,
            'amount' => $order->amount,
            'total_amount' => $amount + $taxPrice,
            'tax' => $tax,
            'tax_price' => $taxPrice,
            'commission' => 0,
            'commission_price' => 0,
            'created_at' => time(),
        ]);

        $razorpay = false;
        foreach ($paymentChannels as $paymentChannel) {
            if ($paymentChannel->class_name == 'Razorpay') {
                $razorpay = true;
            }
        }

        $data = [
            'pageTitle' => trans('public.checkout_page_title'),
            'paymentChannels' => $paymentChannels,
            'total' => $order->total_amount,
            'order' => $order,
            'count' => 1,
            'userCharge' => $user->getAccountingCharge(),
            'razorpay' => $razorpay
        ];

        return view(getTemplate() . '.cart.payment', $data);
    }
}
