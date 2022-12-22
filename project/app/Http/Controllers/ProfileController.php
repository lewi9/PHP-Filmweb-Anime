<?php

namespace App\Http\Controllers;

use App\Helpers\HasEnsure;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Anime;
use App\Models\AnimeUsers;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use HasEnsure;

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $this->ensureIsNotNullUser($request->user());

        $data = $this->ensureIsArray($request->validated());

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $this->ensureIsNotNullUser($request->user());

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function show(string $username): View
    {
        $user = User::where('username', $username)->first();
        return view('profile.show', [
            'user' => $user,
        ]);
    }
    public function favorites(string $username): View
    {
        $user = User::where('username', $username)->first();
        $user_favorites = AnimeUsers::where('user_id', $user->id)->where('favorite', true)->get();
        $anime_list = [];
        foreach ($user_favorites as $favorite) {
            $anime_id = $favorite->anime_id;
            $anime_list[] = Anime::where('id', $anime_id)->first();
        }
        return view('profile.animes', ['anime_list' => $anime_list, 'user' => $user]);
    }
    public function ratings(): View
    {
        return view('profile.animes');
    }
    public function to_watch(string $username): View
    {
        $user = User::where('username', $username)->first();
        $user_favorites = AnimeUsers::where('user_id', $user->id)->where('would_like_to_watch', true)->get();
        $anime_list = [];
        foreach ($user_favorites as $favorite) {
            $anime_id = $favorite->anime_id;
            $anime_list[] = Anime::where('id', $anime_id)->first();
        }
        return view('profile.animes', ['anime_list' => $anime_list, 'user' => $user]);
    }
}
