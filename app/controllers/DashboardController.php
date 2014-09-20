<?php

class DashboardController extends Controller
{
    /**
     * Show dashboard
     *
     * @return string
     */
    public function indexAction()
    {
        return View::make('dashboard');
    }
}