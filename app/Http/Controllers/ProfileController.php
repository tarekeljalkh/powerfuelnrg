<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilePasswordUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Traits\FileUploadTrait;
use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use FileUploadTrait;

    function index() : View {
        return view('profile.index');
    }

    function updateProfile(ProfileUpdateRequest $request) : RedirectResponse {

        $user = Auth::user();

        $imagePath = $this->uploadImage($request, 'image');

        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = isset($imagePath) ? $imagePath : $user->image;
        $user->save();

        toastr('Updated Successfully!', 'success');

        return redirect()->back();
    }

    function updatePassword(ProfilePasswordUpdateRequest $request) : RedirectResponse {

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();
        toastr()->success('Password Updated Successfully');

        return redirect()->back();
    }
}
