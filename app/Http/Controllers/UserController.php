<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\UpdateUser;
use App\Product;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Services\TwofaService;
use Google2FA;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use Illuminate\Support\Facades\Mail;
use Mpociot\Teamwork\TeamInvite;

class UserController extends Controller
{
	/**
	 * Display the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request)
	{
		$user = $request->user();
		$user->load('countries');

		$key = $request->session()->get('2fa');
		if (is_null($key)) {
			$key = $user['2fa'] ?? TwofaService::getSecretKey();

			$request->session()->put('2fa', $key);
		}
		$inlineUrl = TwofaService::getInlineUrl($key, $user->email);

		$productLocations = Product::isPublic()
			->WhereNotNull('locations')
			->Where('locations', '!=', 'all')
			->select('locations')
			->get()
			->implode('locations', ',');

        $teamInvite = TeamInvite::where('email', $user->email)
            ->first();

		return view('templates.user.show', [
			'user' => $user,
			'userLocations' => $user->countries->pluck('code')->toArray(),
			'locations' => array_unique(explode(',', $productLocations)),
			'key' => $key,
			'inlineUrl' => $inlineUrl,
            'teamInvite' => $teamInvite,
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(UserRequest $request)
	{
		$validated = $request->validated();
		$user = $request->user();
		$emails = [$user->email];
		$hasNewEmail = $request->email !== $user->email;

		if ($hasNewEmail) {
			$emails[] = $validated['email'];
			$validated['email_verified_at'] = null;
		}

		if(!is_null($validated['password'])) {
			$validated['password'] = bcrypt($validated['password']);
		} else {
			unset($validated['password']);
		}

		$user->update($validated);

		if ($request->has('locations')) {
			$user->countries()->sync($validated['locations']);
		}

		if ($hasNewEmail) {
			$user->sendEmailVerificationNotification();
		}

		Mail::to($emails)->send(new UpdateUser($user));

		return redirect()->back()->with('alert', 'Success:Your profile has been updated.');
	}

	public function updateProfilePicture(Request $request)
	{
		$request->validate([
			'profile' => 'required|mimes:jpeg,jpg,png|max:5120',
		]);
		$disk = \Storage::disk('public');
		$user = $request->user();
		$currentImageName = basename($user->profile_picture);
		$isDefaultImage = strpos($currentImageName, 'profile-') !== false;
		$imageName = $isDefaultImage || strlen($currentImageName) > 20 ? base64_encode(date('iYHs') . rand(1, 24)) . '.png' : $currentImageName;
		$imageName = 'profile/' . $imageName;
		$currentImageName = 'profile/' . $currentImageName;

		if (!$isDefaultImage && $disk->exists($currentImageName)) {
			$disk->delete($currentImageName);
		}

		$image = Image::make($request->file('profile'))
			->fit(452, 452, function ($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			});

		$success = $disk->put($imageName, (string) $image->encode('png', 95));
		$request->user()->update(['profile_picture' => '/storage/' . $imageName]);

		return response()->json([
			'success' => $success,
		]);
	}

	public function enable2fa(Request $request)
	{
		$request->user()->update([
			'2fa' => $request->key
		]);

		return response()->json(['success' => true, 'message' => 'Key has been added.'], 200);
	}

	public function disable2fa(Request $request)
	{
		$request->user()->update(['2fa' => null, 'recovery_codes' => null]);

		return redirect()->back()->with('alert', "Success:2FA has been disabled");
	}

	public function verify2fa(Request $request)
	{
		$message = "Success:2FA has been verified";
		$user = $request->user();

		if ($request->has('one_time_key')) {
			$verify = Google2FA::verifyGoogle2FA($request->one_time_key, $request->one_time_password);

			if (!$verify) {
				return back()->with('alert', "Error:Your one time password didn't match;Please try again.");
			}

			if (is_null($user['2fa'])) {
				$user->update([
					'2fa' => $request->one_time_key
				]);
			}

			Google2FA::login();

			$message = "Success:2FA has been enabled";
		} else {
			$authenticator = app(Authenticator::class)->bootStateless($request);
			$oneTimePassword = $request->get('one_time_password', '');

			if (!$authenticator->isAuthenticated()) {
				$recoveryCodes = $user->recovery_codes ?? [];

				if (!in_array($oneTimePassword, $recoveryCodes, true)) {
					return back()->with('alert', "Error:Your one time password didn't match;Please try again.");
				}

				$recoveryCodes = array_values(array_filter($recoveryCodes, fn ($code) => $code !== $oneTimePassword));

				$user->update([
					'recovery_codes' => $recoveryCodes ?: null
				]);

				Google2FA::login();
			} else {
				Google2FA::login();
			}
		}

		if (strpos(url()->previous(), '2fa/verify') !== false) {
			return redirect()->route('app.index')->with('alert', $message);
		}

		return back()->with('alert', $message);
	}
}
