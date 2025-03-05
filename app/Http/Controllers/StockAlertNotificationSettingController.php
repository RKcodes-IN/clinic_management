<?php

namespace App\Http\Controllers;

use App\Models\StockAlertNotificationSetting;
use Illuminate\Http\Request;

class StockAlertNotificationSettingController extends Controller
{
    // Display a listing of all settings
    public function index()
    {
        $settings = StockAlertNotificationSetting::all();
        return view('stock_alert_settings.index', compact('settings'));
    }

    // Show the form for creating a new setting
    public function create()
    {
        return view('stock_alert_settings.create');
    }

    // Store a newly created setting in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'frequency' => 'required|in:daily,weekly,monthly',
            'time_of_day' => 'required|date_format:H:i',
            'day_of_week' => [
                'nullable',
                'integer',
                'between:1,7',
                'required_if:frequency,weekly',
                'prohibited_if:frequency,daily,monthly',
            ],
            'day_of_month' => [
                'nullable',
                'integer',
                'between:1,31',
                'required_if:frequency,monthly',
                'prohibited_if:frequency,daily,weekly',
            ],
        ]);

        $settings = new StockAlertNotificationSetting();
        $settings->frequency = $validated['frequency'];
        $settings->time_of_day = $validated['time_of_day'];

        if ($validated['frequency'] === 'weekly') {
            $settings->day_of_week = $validated['day_of_week'];
            $settings->day_of_month = null;
        } elseif ($validated['frequency'] === 'monthly') {
            $settings->day_of_month = $validated['day_of_month'];
            $settings->day_of_week = null;
        } else { // daily
            $settings->day_of_week = null;
            $settings->day_of_month = null;
        }

        $settings->save();

        return redirect()->route('stock_alert_settings.index')->with('success', 'Settings created successfully.');
    }

    // Display a specific setting
    public function show(StockAlertNotificationSetting $stockAlertSetting)
    {
        return view('stock_alert_settings.show', compact('stockAlertSetting'));
    }

    // Show the form for editing a setting
    public function edit(StockAlertNotificationSetting $stockAlertSetting)
    {
        return view('stock_alert_settings.edit', compact('stockAlertSetting'));
    }

    // Update a specific setting in the database
    public function update(Request $request, StockAlertNotificationSetting $stockAlertSetting)
    {
        $validated = $request->validate([
            'frequency' => 'required|in:daily,weekly,monthly',
            'time_of_day' => 'required|date_format:H:i',
            'day_of_week' => [
                'nullable',
                'integer',
                'between:1,7',
                'required_if:frequency,weekly',
                'prohibited_if:frequency,daily,monthly',
            ],
            'day_of_month' => [
                'nullable',
                'integer',
                'between:1,31',
                'required_if:frequency,monthly',
                'prohibited_if:frequency,daily,weekly',
            ],
        ]);

        $stockAlertSetting->frequency = $validated['frequency'];
        $stockAlertSetting->time_of_day = $validated['time_of_day'];

        if ($validated['frequency'] === 'weekly') {
            $stockAlertSetting->day_of_week = $validated['day_of_week'];
            $stockAlertSetting->day_of_month = null;
        } elseif ($validated['frequency'] === 'monthly') {
            $stockAlertSetting->day_of_month = $validated['day_of_month'];
            $stockAlertSetting->day_of_week = null;
        } else { // daily
            $stockAlertSetting->day_of_week = null;
            $stockAlertSetting->day_of_month = null;
        }

        $stockAlertSetting->save();

        return redirect()->route('stock_alert_settings.index')->with('success', 'Settings updated successfully.');
    }

    // Delete a specific setting
    public function destroy(StockAlertNotificationSetting $stockAlertSetting)
    {
        $stockAlertSetting->delete();
        return redirect()->route('stock_alert_settings.index')->with('success', 'Settings deleted successfully.');
    }
}
