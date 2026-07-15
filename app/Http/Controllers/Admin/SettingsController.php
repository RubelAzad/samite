<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Support\ImageCompressor;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        $company = auth()->user()->company;

        return view('admin.settings.edit', compact('company'));
    }

    public function update(Request $request)
    {
        $company = auth()->user()->company;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
            // Not 'image': Laravel's image rule only allows jpg/jpeg/png/gif/bmp/webp and rejects
            // .ico outright, but a favicon is conventionally .ico — mimes alone still validates the type.
            'icon' => 'nullable|mimes:jpg,jpeg,png,webp,ico|max:8192',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = ImageCompressor::store($request->file('logo'), 'companies', 100, preserveTransparency: true);
        }

        if ($request->hasFile('icon')) {
            $validated['icon'] = ImageCompressor::store($request->file('icon'), 'companies', 100, preserveTransparency: true);
        }

        $company->update($validated);

        AuditLog::record('update', 'company', $company->id, null, $validated, 'Updated company settings');

        return back()->with('success', 'Settings updated successfully.');
    }
}
