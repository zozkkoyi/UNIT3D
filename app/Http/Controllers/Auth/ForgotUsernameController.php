<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\UsernameReminder;

class ForgotUsernameController extends Controller
{
    /**
     * Forgot Username Form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showForgotUsernameForm()
    {
        return view('auth.username');
    }

    /**
     * Send Username Reminder.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function sendUsernameReminder(Request $request)
    {
        $email = $request->get('email');

        $v = validator($request->all(), [
            'email' => 'required',
        ]);

        if ($v->fails()) {
            return redirect()->route('username.request')
                ->withErrors($v->errors());
        } else {
            $user = User::where('email', '=', $email)->first();

            if (empty($user)) {
                return redirect()->route('username.request')
                    ->withErrors('We could not find this email in our system!');
            }

            //send username reminder notification
            $user->notify(new UsernameReminder());

            return redirect()->route('login')
                ->withSuccess('Your username has been sent to your email address!');
        }
    }
}
