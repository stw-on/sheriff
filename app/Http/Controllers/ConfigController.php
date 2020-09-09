<?php


namespace App\Http\Controllers;


class ConfigController extends Controller
{
    public function isHostAllowed()
    {
        $data = $this->validateWith([
            'host' => 'string|max:128',
        ]);

        if ($data['host'] === config('sheriff.host')) {
            return response()->json(['allowed' => true]);
        }

        foreach (explode(',', config('sheriff.external_hosts')) as $item) {
            $item = trim($item);
            if ($item === $data['host']) {
                return response()->json(['allowed' => true]);
            }
        }

        return response()->json(['allowed' => false]);
    }
}
