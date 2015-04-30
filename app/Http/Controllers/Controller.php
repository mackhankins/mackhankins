<?php namespace MH\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Meta;

abstract class Controller extends BaseController {

    use DispatchesCommands, ValidatesRequests;

    public function __construct()
    {
        # Default title
        Meta::title('Mack Hankins');

        # Default robots
        Meta::meta('robots', 'index,follow');
    }

}
