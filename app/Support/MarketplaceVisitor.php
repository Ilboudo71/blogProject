<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MarketplaceVisitor
{
    public static function key(Request $request): string
    {
        if (! $request->session()->has('marketplace_visitor_key')) {
            $request->session()->put('marketplace_visitor_key', Str::uuid()->toString());
        }

        return hash('sha256', (string) $request->session()->get('marketplace_visitor_key'));
    }
}
