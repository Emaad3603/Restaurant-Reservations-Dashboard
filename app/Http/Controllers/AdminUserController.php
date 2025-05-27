<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\AdminPrivilege;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = AdminUser::with('privilege')->where('admin', '!=', 1)->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255|unique:admin_users,user_name',
            'email' => 'nullable|email|max:255|unique:admin_users,email',
            'phone' => 'nullable|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'admin' => 'nullable|boolean',
            // Privileges
            'hotels_tab' => 'required|boolean',
            'currencies_tab' => 'required|boolean',
            'meal_types_tab' => 'required|boolean',
            'restaurants_tab' => 'required|boolean',
            'restaurant_times_tab' => 'required|boolean',
            'menu_links_tab' => 'required|boolean',
            'reservations_tab' => 'required|boolean',
            'reports_tab' => 'required|boolean',
        ]);

        $user = AdminUser::create([
            'user_name' => $validated['user_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'display_name' => $validated['display_name'],
            'company_id' => auth()->user()->company_id,
            'admin' => $validated['admin'] ?? 0,
            'password' => Hash::make($validated['password']),
        ]);

        AdminPrivilege::create([
            'admin_users_id' => $user->admin_users_id,
            'hotels_tab' => $validated['hotels_tab'],
            'currencies_tab' => $validated['currencies_tab'],
            'meal_types_tab' => $validated['meal_types_tab'],
            'restaurants_tab' => $validated['restaurants_tab'],
            'restaurant_times_tab' => $validated['restaurant_times_tab'],
            'menu_links_tab' => $validated['menu_links_tab'],
            'reservations_tab' => $validated['reservations_tab'],
            'reports_tab' => $validated['reports_tab'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created!');
    }

    public function edit(AdminUser $user)
    {
        $user->load('privilege');
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, AdminUser $user)
    {
        $validated = $request->validate([
            'user_name' => [
                'required', 'string', 'max:255',
                Rule::unique('admin_users', 'user_name')->ignore($user->admin_users_id, 'admin_users_id')
            ],
            'email' => [
                'nullable', 'email', 'max:255',
                Rule::unique('admin_users', 'email')->ignore($user->admin_users_id, 'admin_users_id')
            ],
            'phone' => 'nullable|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'admin' => 'nullable|boolean',
            // Privileges
            'hotels_tab' => 'required|boolean',
            'currencies_tab' => 'required|boolean',
            'meal_types_tab' => 'required|boolean',
            'restaurants_tab' => 'required|boolean',
            'restaurant_times_tab' => 'required|boolean',
            'menu_links_tab' => 'required|boolean',
            'reservations_tab' => 'required|boolean',
            'reports_tab' => 'required|boolean',
        ]);

        $user->update([
            'user_name' => $validated['user_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'display_name' => $validated['display_name'],
            'admin' => $validated['admin'] ?? 0,
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
        ]);

        if ($user->privilege) {
            $user->privilege->update([
                'hotels_tab' => $validated['hotels_tab'],
                'currencies_tab' => $validated['currencies_tab'],
                'meal_types_tab' => $validated['meal_types_tab'],
                'restaurants_tab' => $validated['restaurants_tab'],
                'restaurant_times_tab' => $validated['restaurant_times_tab'],
                'menu_links_tab' => $validated['menu_links_tab'],
                'reservations_tab' => $validated['reservations_tab'],
                'reports_tab' => $validated['reports_tab'],
            ]);
        } else {
            AdminPrivilege::create([
                'admin_users_id' => $user->admin_users_id,
                'hotels_tab' => $validated['hotels_tab'],
                'currencies_tab' => $validated['currencies_tab'],
                'meal_types_tab' => $validated['meal_types_tab'],
                'restaurants_tab' => $validated['restaurants_tab'],
                'restaurant_times_tab' => $validated['restaurant_times_tab'],
                'menu_links_tab' => $validated['menu_links_tab'],
                'reservations_tab' => $validated['reservations_tab'],
                'reports_tab' => $validated['reports_tab'],
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated!');
    }

    public function destroy(AdminUser $user)
    {
        $user->privilege()->delete();
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted!');
    }
} 