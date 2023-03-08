<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Web\CartManagerController;
use App\Models\Cart;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Share
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (auth()->check()) {
            $user = auth()->user();
            view()->share('authUser', $user);

            if (!$user->isAdmin()) {

                $unReadNotifications = $user->getUnReadNotifications();

                view()->share('unReadNotifications', $unReadNotifications);
            }
        }

        $cartManagerController = new CartManagerController();
        $carts = $cartManagerController->getCarts();
        $totalCartsPrice = Cart::getCartsTotalPrice($carts);

        view()->share('userCarts', $carts);
        view()->share('totalCartsPrice', $totalCartsPrice);

        $generalSettings = getGeneralSettings();
        view()->share('generalSettings', $generalSettings);
        $currency = currencySign();
        view()->share('currency', $currency);


        // locale config
        if (!Session::has('locale')) {
            Session::put('locale', mb_strtolower(getDefaultLocale()));
        }
        App::setLocale(session('locale'));

        view()->share('categories', \App\Models\Category::getCategories());
        view()->share('navbarPages', getNavbarLinks());

        return $next($request);
    }
}
