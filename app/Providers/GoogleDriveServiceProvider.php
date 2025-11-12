<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;
use Google\Client;
use Google\Service\Drive;

class GoogleDriveServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Storage::extend('google', function ($app, $config) {
            $options = [];
            
            $client = new Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);
            
            $service = new Drive($client);
            
            // Set options if folder is specified
            if (!empty($config['folder'])) {
                $options['folderId'] = $config['folder'];
            }
            
            $adapter = new GoogleDriveAdapter($service, 'root', $options);
            
            return new Filesystem($adapter, ['case_sensitive' => false]);
        });
    }
}
