<?php

namespace App\Http\Controllers;

use App\Helpers\GetForUsers;
use App\Helpers\GetOrFail;
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
    use GetOrFail;
    use GetForUsers;

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

        return Redirect::route('profile.edit', $user->username)->with('status', 'profile-updated');
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
        $user = $this->getOrFailUser($username);
        return view('profile.show', [
            'user' => $user,
        ]);
    }

    public function favorites(string $username): View
    {
        $user = $this->getOrFailUser($username);
        $anime_list = $this->getListAnimes($user->id, 'favorite');
        return view('profile.animes', ['anime_list' => $anime_list, 'user' => $user, 'type' => 'favorites']);
    }

    public function ratings(string $username): View
    {
        $user = $this->getOrFailUser($username);
        $anime_list = $this->getForAnimes($user->id, 'ratings');
        return view('profile.animes', ['anime_list' => $anime_list, 'user' => $user, 'type' => 'ratings']);
    }

    public function to_watch(string $username): View
    {
        $user = $this->getOrFailUser($username);
        $anime_list = $this->getListAnimes($user->id, 'would_like_to_watch');
        return view('profile.animes', ['anime_list' => $anime_list, 'user' => $user, 'type' => 'to watch']);
    }

    public function friends(string $username): View
    {
        $user = $this->getOrFailUser($username);
        $user_friends = UsersFriends::where('user1_id', $user->id)->where('is_pending', false)->orWhere('user2_id', $user->id)->where('is_pending', false)->get();
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
        $invited_user = $this->getOrFailUser($username);
        $are_already_friends = UsersFriends::where('user1_id', $inviting_user->id)->where('user2_id', $invited_user->id)->where('is_pending', false)->get();
        $are_already_friends1 = UsersFriends::where('user1_id', $invited_user->id)->where('user2_id', $inviting_user->id)->where('is_pending', false)->get();
        if (count($are_already_friends) or count($are_already_friends1)) {
            return back();
        }
        UsersFriends::create(['user1_id' => $inviting_user->id, 'user2_id' => $invited_user->id, 'is_pending' => true, 'who_invited' => $inviting_user->id]);
        return back();
    }

    public function watched_episodes(string $username): View
    {
        $user = $this->getOrFailUser($username);
        $anime_list = $this->getForAnimes($user->id, 'watched_episodes');
        return view('profile.animes', ['anime_list' => $anime_list, 'user' => $user, 'type' => 'episodes']);
    }

    public function get_invitations(string $username): View
    {
        $user = $this->getOrFailUser($username);
        $user_invitations = UsersFriends::where('is_pending', true)
            ->where('who_invited', '!=', $user->id)
            ->where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)->where('is_pending', true)->get();
        $invitations_list = [];
        foreach ($user_invitations as $invitation) {
            $inviting_user_id = $invitation->user1_id == $user->id ? $invitation->user2_id : $invitation->user1_id;
            $inviting_user = User::where('id', $inviting_user_id)->first();
            $invitations_list[] = $inviting_user;
        }
        return view('profile.invitations', ['invitations_list' => $invitations_list, 'user' => $user]);
    }

    public function accept_invitation(string $username, string $inviting_user_username): RedirectResponse
    {
        $accepting_user =  $this->getOrFailUser($username);
        $accepted_user = $this->getOrFailUser($inviting_user_username);
        $invitation = UsersFriends::where('user1_id', $accepting_user->id)->where('user2_id', $accepted_user->id)->first();
        if (!$invitation) {
            $invitation = UsersFriends::where('user1_id', $accepted_user->id)->where('user2_id', $accepting_user->id)->first();
        }
        $invitation->is_pending = false;
        $invitation->save();
        return back()->with('success', 'You\'ve accepted an invitation!');
    }

    public function delete_invitation(string $username, string $inviting_user_username): RedirectResponse
    {
        $deleting_user =  $this->getOrFailUser($username);
        $deleted_user = $this->getOrFailUser($inviting_user_username);
        $invitation = UsersFriends::where('user1_id', $deleting_user->id)->where('user2_id', $deleted_user->id)->first();
        if (!$invitation) {
            $invitation = UsersFriends::where('user1_id', $deleted_user->id)->where('user2_id', $deleting_user->id)->first();
        }
        $invitation->delete();
        return back()->with('success', 'You\'ve deleted an invitation!');
    }

    public function delete_friendship(Request $request, string $username): RedirectResponse
    {
        $deleting_user =  $this->ensureIsNotNullUser($request->user());
        $deleted_user = $this->getOrFailUser($username);
        $friendship = UsersFriends::where('user1_id', $deleting_user->id)->where('user2_id', $deleted_user->id)->first();
        if (!$friendship) {
            $friendship = UsersFriends::where('user2_id', $deleting_user->id)->where('user1_id', $deleted_user->id)->first();
        }
        $friendship->delete();
        return back()->with('success', 'You\'ve deleted a friend!');
    }
}
