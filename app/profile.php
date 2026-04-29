<?php

use PhpSPA\Component;
use PhpSPA\Http\Request;

return new Component(function(Request $request) {
   $name = $request('name', 'Dave Conco');
   $email = $request('email', 'me@dconco.tech');
   $gender = $request('gender', 'Male');

   $userData = json_encode(compact('name', 'email', 'gender'));

   return <<<HTML
      <h2>Profile Page PHP Render</h2>
      <script type="application/json" id="profile-script">{$userData}</script>
   HTML;
})->route('/profile');
