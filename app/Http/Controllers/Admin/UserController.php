<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Override;

class UserController extends BaseController
{
    protected $model = User::class;
    protected $page = 'users';
    public function index(Request $request)
    {
        $query = User::withCount(['books as reviews_count' => function ($q) {
            $q->whereNotNull('user_books.review')->where('user_books.review', '!=', '');
        }]);
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if ($request->role === 'admin') {
            $query->where('is_admin', true);
        } elseif ($request->role === 'user') {
            $query->where('is_admin', false);
        }
        $items = $query->latest()->paginate(10)->withQueryString();
        return view('admin.users.index', ['items' => $items] + $this->extraData());
    }
    public function toggleActive($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == Auth::id()) {
            return back()->with('error', 'Kendi durumunuzu değiştiremezsiniz!');
        }
        if ($user->is_super_admin) {
            return back()->with('error', 'Süper adminin durumu değiştirilemez!');
        }
        if ($user->is_admin && !auth()->user()->is_super_admin) {
            return back()->with('error', 'Adminleri sadece süper admin pasif yapabilir!');
        }
        $user->is_active = !$user->is_active;
        $user->save();
        return back()->with('success', $user->is_active ? 'Kullanıcı aktif edildi.' : 'Kullanıcı pasif edildi.');
    }
    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == Auth::id()) {
            return back()->with('error', 'Kendi rolünüzü değiştiremezsiniz!');
        }
        if ($user->is_super_admin) {
            return back()->with('error', 'Süper adminin rolü değiştirilemez!');
        }
        if (!auth()->user()->is_super_admin) {
            return back()->with('error', 'Rol değişikliğini sadece süper admin yapabilir!');
        }
        $user->is_admin = !$user->is_admin;
        $user->save();
        return back()->with('success', $user->is_admin ? 'Kullanıcı rolü Admin olarak değiştirildi.' : 'Kullanıcı rolü User olarak değiştirildi.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->is_super_admin) {
            return back()->with('error', 'Süper admin silinemez!');
        }
        if ($user->id == Auth::id()) {
            return back()->with('error', 'Kendinizi silemezsiniz!');
        }
        if ($user->is_admin && !auth()->user()->is_super_admin) {
            return back()->with('error', 'Adminleri sadece süper admin silebilir!');
        }
        $user->delete();
        return back()->with('success', 'Kullanıcı silindi');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
