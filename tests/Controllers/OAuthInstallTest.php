<?php

use function Pest\Laravel\get;

it('redirects to rd station dialog page', function () {
    $url = urlencode('http://localhost/rd-station/oauth/callback');

    get('rd-station/oauth/install')
        ->assertRedirect(sprintf('https://api.rd.services/auth/dialog?client_id=TEST_CLIENT_ID&redirect_uri=%s', $url));
});
