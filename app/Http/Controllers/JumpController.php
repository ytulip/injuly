<?php

namespace App\Http\Controllers;

use App\Log\Facades\Logger;
use App\Model\CashStream;
use App\Model\Deliver;
use App\Model\InvitedCodes;
use App\Model\Message;
use App\Model\Order;
use App\Model\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class JumpController extends Controller
{
    public function getIndex()
    {
        echo 'jump';
    }
}