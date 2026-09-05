<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditRecorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAccess($request);
        return view('cms.users.index', ['users' => User::with('roles')->latest()->paginate(25)]);
    }

    public function create(Request $request): View
    {
        $this->authorizeAccess($request);
        return view('cms.users.form', ['user' => new User, 'roles' => Role::orderBy('name')->get()]);
    }

    public function store(Request $request, AuditRecorder $audit): RedirectResponse
    {
        $this->authorizeAccess($request);
        $data = $this->validated($request);
        $user = User::create([...$data, 'is_active' => true, 'must_change_password' => true, 'email_verified_at' => now()]);
        $user->syncRoles([$data['role']]);
        $audit->record('user.created', $request->user(), $user, null, $user->only(['name', 'email', 'is_active']));
        return redirect()->route('cms.users.index')->with('status', 'Staff account created. Share the temporary password through an approved secure channel.');
    }

    public function edit(Request $request, User $user): View
    {
        $this->authorizeAccess($request);
        return view('cms.users.form', ['user' => $user, 'roles' => Role::orderBy('name')->get()]);
    }

    public function update(Request $request, User $user, AuditRecorder $audit): RedirectResponse
    {
        $this->authorizeAccess($request);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'role' => ['required', Rule::exists('roles', 'name')],
        ]);
        $this->protectLastSuperAdministrator($user, $data['role']);
        $before = [...$user->only(['name', 'email', 'is_active']), 'roles' => $user->getRoleNames()->all()];
        $user->update(['name' => $data['name'], 'email' => $data['email']]);
        $user->syncRoles([$data['role']]);
        $audit->record('user.updated', $request->user(), $user, $before, [...$user->only(['name', 'email', 'is_active']), 'roles' => $user->getRoleNames()->all()]);
        return back()->with('status', 'Staff account updated.');
    }

    public function disable(Request $request, User $user, AuditRecorder $audit): RedirectResponse
    {
        $this->authorizeAccess($request);
        abort_if($request->user()->is($user), 422, 'You cannot disable your own account.');
        $this->protectLastSuperAdministrator($user, null);
        $user->forceFill(['is_active' => false, 'disabled_at' => now()])->save();
        $audit->record('user.disabled', $request->user(), $user, ['is_active' => true], ['is_active' => false]);
        return back()->with('status', 'Staff account disabled.');
    }

    public function enable(Request $request, User $user, AuditRecorder $audit): RedirectResponse
    {
        $this->authorizeAccess($request);
        $user->forceFill(['is_active' => true, 'disabled_at' => null])->save();
        $audit->record('user.enabled', $request->user(), $user, ['is_active' => false], ['is_active' => true]);
        return back()->with('status', 'Staff account enabled.');
    }

    public function resetSecurity(Request $request, User $user, AuditRecorder $audit): RedirectResponse
    {
        $this->authorizeAccess($request);
        abort_if($request->user()->is($user), 422, 'A second administrator must perform account recovery.');
        abort_unless($user->is_active, 422, 'Enable the account before starting recovery.');
        DB::transaction(function () use ($user, $request, $audit): void {
            $before = ['two_factor_confirmed_at' => $user->two_factor_confirmed_at, 'must_change_password' => $user->must_change_password];
            $user->forceFill([
                'two_factor_secret' => null, 'two_factor_recovery_codes' => null, 'two_factor_confirmed_at' => null,
                'must_change_password' => true, 'remember_token' => Str::random(60),
            ])->save();
            DB::table('sessions')->where('user_id', $user->id)->delete();
            Password::broker()->deleteToken($user);
            $audit->record('user.security_recovery.initiated', $request->user(), $user, $before, ['two_factor_confirmed_at' => null, 'must_change_password' => true]);
        });
        return back()->with('status', 'Security recovery started. Existing sessions and reset links were revoked. The staff member must change their password and enroll MFA again.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12', 'confirmed'], 'role' => ['required', Rule::exists('roles', 'name')],
        ]);
    }

    private function authorizeAccess(Request $request): void { abort_unless($request->user()->can('users.manage'), 403); }

    private function protectLastSuperAdministrator(User $user, ?string $newRole): void
    {
        if ($user->hasRole('Super Administrator') && $newRole !== 'Super Administrator'
            && User::role('Super Administrator')->where('is_active', true)->count() <= 1) {
            abort(422, 'The final active Super Administrator cannot be removed or disabled.');
        }
    }
}
