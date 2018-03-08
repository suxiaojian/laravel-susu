<?php

namespace App\Http\Controllers;

use App\User;
use Colinwait\LaravelPockets\Pocket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Overtrue\Socialite\SocialiteManager;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    protected $config = [
        'github' => [
            'client_id'     => 'b3675d9706b5c5835ef8',
            'client_secret' => 'bc34b7e356241fbbe214a2bfa4ddf274ae4128b5',
            'redirect'      => 'http://localhost:8000/github/login',
        ],
    ];//数组写在 config下的 services 下 用env()的形式写-----用的话直接用 config('services')

    public function login()
    {
        $socialite = new SocialiteManager($this->config);

        return $socialite->driver('github')->redirect();
    }

    public function github()
    {
        $socialite  = new SocialiteManager($this->config);
        $githubuser = $socialite->driver('github')->user();

        $user = User::create([
            'name'     => $githubuser->getNickname(),
            'email'    => $githubuser->getEmail(),
            'password' => bcrypt(str_random(16)),
        ]);
        Auth::login($user);
        dd(Auth::user());
    }

    public function aa()
    {
        $running_count = $waiting_count = 0;
        $result        = [];
        $res = Pocket::getTranscodeStatusLists();
        if (isset($res['return']) && $res['return'] == 'success') {
            if (isset($res['running'])) {
                $running_count      = count($res['running']);
                $running            = collect($res['running']);
                $transcode_lefttime = $running->max('transcode_lefttime');
                $transcode_percent  = $running->pluck('transcode_percent')->avg();
            }
            if (isset($res['waiting'])) {
                $waiting_count = count($res['waiting']);
            }
        }
        $result['all_count']     = intval($running_count) + intval($waiting_count);
        $result['running_count'] = intval($running_count);
        if ($result['all_count'] || $result['running_count']) {
            $result['transcode_percent']  = isset($transcode_percent) ? intval($transcode_percent) : 0;
            $result['transcode_lefttime'] = isset($transcode_lefttime) ? intval($transcode_lefttime / 60000) : 0;
        }
        return $result;
    }
}
