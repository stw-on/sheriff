<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Don't add this rule in development. 404s are easier to debug without it.
if (app()->environment('production')) {
    Route::get('/{any}', function () {
        $configJson = json_encode([
            'theme_color' => config('sheriff.theme_color'),
            'logo_url' => config('sheriff.logo_url'),
            'custom_translations' => file_exists('/app/strings.yml')
                ? yaml_parse_file('/app/strings.yml')
                : null,
        ]);

        $config = <<<CONFIG
<script>
  window.__sheriff_config = ${configJson};
</script>
CONFIG;

        return str_replace('%CONFIG%', $config, file_get_contents(public_path('ui-index.html')));
    })->where('any', '.*');
}
