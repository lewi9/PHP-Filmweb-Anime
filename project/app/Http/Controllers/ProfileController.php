<?php

namespace App\Http\Controllers;

use App\Helpers\HasEnsure;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Anime;
use App\Models\AnimeUsers;
use App\Models\User;
use App\Models\UsersFriends;
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
        $user = $this->ensureIsNotNullUser($request->user());

        return view('profile.edit', [
            'user' => $user,
            'username' => $user->username,
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

        return Redirect::route('profile.edit', ['username', $user->username])->with('status', 'profile-updated');
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
        if (!$user) {
            abort(404);
        }
        $user_favorites = AnimeUsers::where('user_id', $user->id)->where('favorite', true)->get();
        $anime_list = [];
        foreach ($user_favorites as $favorite) {
            $anime_id = $favorite->anime_id;
            $anime_list[] = Anime::where('id', $anime_id)->first();
        }
        return view('profile.animes', ['anime_list' => $anime_list, 'user' => $user, 'type' => 'favorites']);
    }
    public function ratings(string $username): View
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            abort(404);
        }
        $user_favorites = AnimeUsers::where('user_id', $user->id)->where('rating', '!=', '0')->get();
        $anime_list = [];
        foreach ($user_favorites as $favorite) {
            $anime_id = $favorite->anime_id;
            $anime_list[] = array(Anime::where('id', $anime_id)->first(), $favorite->rating);
        }
        return view('profile.animes', ['anime_list' => $anime_list, 'user' => $user, 'type' => 'ratings']);
    }
    public function to_watch(string $username): View
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            abort(404);
        }
        $user_favorites = AnimeUsers::where('user_id', $user->id)->where('would_like_to_watch', true)->get();
        $anime_list = [];
        foreach ($user_favorites as $favorite) {
            $anime_id = $favorite->anime_id;
            $anime_list[] = Anime::where('id', $anime_id)->first();
        }
        return view('profile.animes', ['anime_list' => $anime_list, 'user' => $user, 'type' => 'to watch']);
    }
    public function friends(string $username): View
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            abort(404);
        }
        $user_friends = UsersFriends::where('user1_id', $user->id)->orWhere('user2_id', $user->id)->get();
        $friends_list = [];
        foreach ($user_friends as $friends) {
            $friend_id = $friends->user1_id == $user->id ? $friends->user2_id : $friends->user1_id;
            $friend = User::where('id', $friend_id)->first();
            $friends_list[] = $friend;
        }
        return view('profile.friends', ['friends_list' => $friends_list, 'user' => $user]);
    }
    public function store_image(Request $request, string $username): RedirectResponse
    {
        $user = $this->ensureIsNotNullUser($request->user());
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->image;

        $image_name = $username . '.' . $file->extension();
        $file->move(public_path('images'), $image_name);
        $user->profile_pic = $image_name;
        $user->save();
        return back()->with('success', 'Image uploaded successfully!')
            ->with('image', $image_name);
    }
    public function add_to_friends(Request $request, string $username): RedirectResponse
    {
        $inviting_user = $this->ensureIsNotNullUser($request->user()); //dodawacz
        $invited_user = User::where('username', $username)->first(); //dodawany
        if (!$invited_user) {
            abort(404);
        }
        $are_already_friends = UsersFriends::where('user1_id', $inviting_user->id)->where('user2_id', $invited_user->id)->get();
        $are_already_friends1 = UsersFriends::where('user1_id', $invited_user->id)->where('user2_id', $inviting_user->id)->get();
        if (count($are_already_friends) or count($are_already_friends1)) {
            return back()->with('success', 'You already are friends!');
        }
        UsersFriends::create(['user1_id' => $inviting_user->id, 'user2_id' => $invited_user->id]);
        return back()->with('success', 'You are now friends!');
    }
}
