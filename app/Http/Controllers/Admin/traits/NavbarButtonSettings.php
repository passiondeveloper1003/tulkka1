<?php

namespace App\Http\Controllers\Admin\traits;

use App\Models\NavbarButton;
use App\Models\Role;
use App\Models\Translation\NavbarButtonTranslation;
use Illuminate\Http\Request;

trait NavbarButtonSettings
{
    protected $settingName = 'navbar_button';

    public function navbarButtonSettings(Request $request)
    {
        removeContentLocale();

        $this->authorize('admin_settings_personalization');

        $navbarButtons = NavbarButton::query()
            ->with([
                'role'
            ])->get();

        $defaultLocal = getDefaultLocale();
        $locale = $request->get('locale', mb_strtolower($defaultLocal));

        $data = [
            'pageTitle' => trans('admin/main.settings'),
            'navbarButtons' => $navbarButtons,
            'name' => $this->settingName,
            'selectedLocale' => $locale,
            'roles' => Role::all()
        ];

        return view('admin.settings.personalization', $data);
    }

    public function storeNavbarButtonSettings(Request $request)
    {
        $this->authorize('admin_settings_personalization');

        $itemId = $request->get('item_id');

        $this->validate($request, [
            'role_id' => 'required|unique:navbar_buttons' . (!empty($itemId) ? (',role_id,' . $itemId) : ''),
            'title' => 'required',
            'url' => 'required',
        ]);

        $data = $request->all();

        $navbarButton = NavbarButton::where('role_id', $data['role_id'])->first();

        if (empty($navbarButton)) {
            $navbarButton = NavbarButton::create([
                'role_id' => $data['role_id']
            ]);
        }

        NavbarButtonTranslation::updateOrCreate([
            'navbar_button_id' => $navbarButton->id,
            'locale' => mb_strtolower($data['locale']),
        ], [
            'title' => $data['title'],
            'url' => $data['url'],
        ]);

        return redirect('/admin/settings/personalization/navbar_button');
    }

    public function navbarButtonSettingsEdit(Request $request, $id)
    {
        $this->authorize('admin_settings_personalization');

        $navbarButton = NavbarButton::findOrFail($id);

        $defaultLocal = getDefaultLocale();
        $locale = $request->get('locale', mb_strtolower($defaultLocal));
        storeContentLocale($locale, $navbarButton->getTable(), $navbarButton->id);

        $data = [
            'pageTitle' => trans('admin/main.settings'),
            'navbarButton' => $navbarButton,
            'roles' => Role::all(),
            'name' => $this->settingName,
            'selectedLocale' => $locale,
        ];

        return view('admin.settings.personalization', $data);
    }

    public function navbarButtonSettingsDelete($id)
    {
        $this->authorize('admin_settings_personalization');

        $navbarButton = NavbarButton::findOrFail($id);

        $navbarButton->delete();

        return redirect('/admin/settings/personalization/navbar_button');
    }
}
