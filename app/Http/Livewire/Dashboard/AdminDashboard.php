<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class AdminDashboard extends Component
{

    public function render()
    {
        $blade = 'livewire.dashboard.admin-dashboard';
        $data = [];
        if(Auth::guard('exhibitor')->check()){
            $blade = '';
            $data = $this->getExhibitorData();
        }

        if(Auth::guard('visitor')->check()){
            $blade = '';
            $data = $this->getVisitorData();
        }

        if(Auth::guard('user')->check()){
            $blade = '';
            $data = $this->getUserData();
        }

        if(Auth::guard('web')->check()){
            $blade = '';
            $data = $this->getDefaultUserData();
        }


        return view('livewire.dashboard.admin-dashboard', $data)
            ->layout('layouts.admin');
    }

    public function getDefaultUserData(){
        return [];
    }

    public function getUserData(){
        return [];
    }

    public function getVisitorData(){
        return [];
    }

    public function getExhibitorData(){
        return [];
    }
}
