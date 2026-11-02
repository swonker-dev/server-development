<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('statistics:send')->everyMinute()->withoutOverlapping(5);
