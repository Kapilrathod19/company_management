<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.site_setting', compact('setting'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'site_name' => 'required',
            ]);

            $imageName = $request->old_site_logo;

            if ($request->hasFile('site_logo')) {

                if (!empty($request->old_site_logo)) {
                    $oldImagePath = public_path('site_logo/' . $request->old_site_logo);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $file = $request->file('site_logo');
                $imageName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('site_logo'), $imageName);
            }

            $data = array(
                'site_name' => $request->site_name,
                'site_logo' => $imageName,
            );
            if (!empty($request->id)) {
                Setting::where('id', $request->id)->update($data);
                return redirect()->route('admin.site_setting')->with('success', 'Setting update successfully');
            } else {
                Setting::create($data);
                return redirect()->route('admin.site_setting')->with('success', 'Setting created successfully');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.site_setting')->with('error', 'something went wrong');
        }
    }
}
