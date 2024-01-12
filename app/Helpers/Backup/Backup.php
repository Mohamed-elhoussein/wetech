<?php

namespace App\Helpers\Backup;

class Backup
{





  public static function db_backup()
  {

    // get database info
    $host =  env('DB_HOST');
    $database = env('DB_DATABASE');
    $user = env('DB_USERNAME');
    $password = env('DB_PASSWORD');
    $driver  = 'mysql';

    // connect to database and setup the backup class
    $db = new DBBackup(compact('driver', 'host', 'user', 'password', 'database'));

    // make the backup
    $backup = $db->backup();

    // if every thing is fine
    if (!$backup['error']) {

      $now = now()->toDateTimeString();

      $file = strtolower(config('app.name')) . '_' . str_replace(' ', '_', $now) . '.sql';

      $directory = storage_path('app/backups/');

      if (!\File::isDirectory($directory)) {
        \File::makeDirectory($directory, 0777, true, true);
      }

      $database = $directory . $file;

      if (file_exists($database)) {
        unlink($database);
      }



      // create the database file
      $fp = fopen($database, 'a+');
      fwrite($fp, $backup['msg']);
      fclose($fp);


      // send the database to backup server
      //self::sendBackup($database);
      return 'file created';
      return $database;
    }

    return false;
  }


  public static function sendBackup($file)
  {
    $target_url = "https://backups.almabra.com/";
    $file_name_with_full_path = $file;
    if (function_exists('curl_file_create')) {
      $cFile = curl_file_create($file_name_with_full_path);
    } else {
      $cFile = '@' . realpath($file_name_with_full_path);
    }
    $post = array('database' => $cFile);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $result = curl_exec($ch);
    curl_close($ch);
    return true;
  }
}
