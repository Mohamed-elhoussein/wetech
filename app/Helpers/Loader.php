<?php

namespace App\Helpers;

use App\Models\Cities;
use App\Models\Countries;
use App\Models\Street;
use App\Models\User;

class Loader
{
    public static function loadCities()
    {
        session(['cities' => Cities::select('id', 'name')->get()->toArray()]);
    }

    public static function loadUsers()
    {
        session(['users' => User::user()->select('id', 'username')->get()->toArray()]);
    }

    public static function loadStreets()
    {
        session(['streets' => Street::select('id', 'name')->get()->toArray()]);
    }

    public static function loadCountries()
    {
        session(['countries' => Countries::select('id', 'name')->get()->toArray()]);
    }

    public static function getCities()
    {
        if (!session('cities')) {
            self::loadCities();
        }

        return session('cities');
    }

    public static function getCountries()
    {
        if (!session('countries')) {
            self::loadCountries();
        }

        return session('countries');
    }

    public static function getCityId(?string $name = null)
    {
        if (!$name) {
            return null;
        }

        if (!session('cities')) {
            self::loadCities();
        }

        $cities = session('cities');

        foreach ($cities as $city) {
            if ($city['name'] === trim(strtolower($name))) {
                return $city['id'];
            }
        }

        return null;
    }

    public static function getUserId(?string $username = null)
    {
        if (!$username) {
            return null;
        }

        if (!session('users')) {
            self::loadUsers();
        }

        $users = session('users');

        foreach ($users as $user) {
            if ($user['username'] === trim(strtolower($username))) {
                return $user['id'];
            }
        }

        return null;
    }

    public static function getStreetId(?string $name = null)
    {
        if (!$name) {
            return null;
        }

        if (!session('streets')) {
            self::loadStreets();
        }

        $streets = session('streets');

        foreach ($streets as $street) {
            if ($street['name'] === trim(strtolower($name))) {
                return $street['id'];
            }
        }

        return null;
    }

    public static function getCountryId(?string $name = null)
    {
        if (!$name) {
            return null;
        }

        if (!session('countries')) {
            self::loadCountries();
        }

        $countries = session('countries');

        foreach ($countries as $country) {
            if ($country['name'] === trim(strtolower($name))) {
                return $country['id'];
            }
        }

        return null;
    }
}
