<?php

use PhpSPA\Component;
use PhpSPA\Http\Session;
use function Component\useState;
use function Component\useEffect;

return new Component(function() {
   $savedCounter = Session::get('counter');
   $state = useState('counter', $savedCounter ?? 0);

   // --- Save to session on state change ---
   useEffect(function ($newState) {
      Session::set('counter', $newState());
   }, [$state]);

   return "<h1>This is from PHP App! {$state}</h1>";
});
