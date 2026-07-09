<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->isAdmin() && !$request->user()->she_admin()) {
            $html = <<<HTML
                <html>
                    <head>
                        <script type="text/javascript">
                            alert("Admin ONLY");
                            window.location.href = "/dashboard";
                        </script>
                    </head>
                </html>
                HTML;

            return response($html, 403);
        }

        return $next($request);
    }
}
