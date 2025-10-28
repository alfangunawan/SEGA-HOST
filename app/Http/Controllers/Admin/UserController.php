<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $role = $request->query('role');

        $users = User::query()
            ->when($role, fn($query, $role) => $query->where('role', $role))
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'role'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateUser($request);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'balance' => $validated['balance'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('status', __('Pengguna :name berhasil ditambahkan.', ['name' => $user->name]));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $this->validateUser($request, $user->id, isUpdate: true);

        if ($this->wouldRemoveLastAdmin($user, $validated['role'])) {
            return redirect()->route('admin.users.index')
                ->with('error', __('Minimal harus ada satu admin aktif. Peran pengguna ini tidak dapat diubah.'));
        }

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'balance' => $validated['balance'],
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);

        return redirect()->route('admin.users.index')
            ->with('status', __('Pengguna :name berhasil diperbarui.', ['name' => $user->name]));
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return redirect()->route('admin.users.index')
                ->with('error', __('Anda tidak dapat menghapus akun yang sedang digunakan.'));
        }

        if ($this->wouldRemoveLastAdmin($user, null)) {
            return redirect()->route('admin.users.index')
                ->with('error', __('Minimal harus ada satu admin aktif. Pengguna ini tidak dapat dihapus.'));
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', __('Pengguna berhasil dihapus.'));
    }

    /**
     * Validate incoming request for storing/updating user.
     */
    protected function validateUser(Request $request, ?int $userId = null, bool $isUpdate = false): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'balance' => ['required', 'numeric', 'min:0'],
        ]);
    }

    /**
     * Ensure at least one admin remains in the system.
     */
    protected function wouldRemoveLastAdmin(User $user, ?string $newRole): bool
    {
        $adminCount = User::where('role', 'admin')->count();

        $isDowngradingAdmin = $user->role === 'admin' && $newRole !== 'admin';
        $isDeletingAdmin = $newRole === null && $user->role === 'admin';

        return $adminCount <= 1 && ($isDowngradingAdmin || $isDeletingAdmin);
    }
}
