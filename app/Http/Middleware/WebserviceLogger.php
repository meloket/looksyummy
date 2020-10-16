<?php

namespace App\Http\Middleware;

use Closure;

class WebserviceLogger
{
    /**
     * This will tell where to extract data from
     */
    private static $loggerType = "content";

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $loggerType='json')
    {
        static::$loggerType = $loggerType;

        return $next($request);
    }

    public function terminate($request, $response)
    {
        if ( env('LOG_WEBSERVICE', true) ) {

            

            $filename = 'webservice_' . date('d-m-y') . '.log';
            
            if ( static::$loggerType == 'json') {
                $output   = $response;

            } else if ( static::$loggerType == 'content' ) {
                $output = $response->getContent();
            } else {
                return;
            }
            

            $inputExcept = [
                 //'password',
                 //'new_password',
                 //'confirm_password',
                 //'credit_card',
            ];

            $input = [];
            foreach ($request->request->all() as $key => $value) {
                $input[$key] = $value;
                if (in_array($key, $inputExcept)) {
                    $input[$key] = '********';
                }
            }

            //dd($input);


            $dataToLog  = '[' . \Carbon\Carbon::now()->toDateTimeString() . "] log.DEBUG: ";
            $dataToLog .= 'Time: '   . gmdate("F j, Y, g:i a") . "\n";
            $dataToLog .= 'URL: '    . $request->fullUrl() . "\n";
            $dataToLog .= 'Method: ' . $request->method() . "\n";
            $dataToLog .= 'Input: '  . print_r((array) $input, true) . "\n";
            $dataToLog .= 'Output: ' . $output . "\n";

            // Finally write log
            \File::append( storage_path('logs' . DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n" . str_repeat("=", 20) . "\n\n");
        }
    }
}