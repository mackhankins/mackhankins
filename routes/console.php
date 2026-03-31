<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:generate-sitemap')->weekly();
