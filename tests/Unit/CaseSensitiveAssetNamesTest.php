<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Statamic\Facades\Asset;
use Statamic\Facades\AssetContainer;

it('meta data retrieval works case-insensitive', function () {
    $containerName = 'container';
    $container = AssetContainer::make($containerName);
    $container->save();

    $asset = AssetContainer::find($containerName)->makeAsset(
        'path/to/file.txt'
    );
    $asset
        ->disk()
        ->filesystem()
        ->put(
            $asset->path(),
            'some content',
        );
    $asset->data([
        'alt' => 'Lorem ipsum dolor sit amet.',
    ]);
    $asset->save();

    $uppercaseAsset = AssetContainer::find($containerName)->makeAsset(
        'path/to/FILE.txt'
    );

    expect($asset->exists())->toBeTrue();
    
    if($uppercaseAsset->exists())
    {
        expect(Storage::get($uppercaseAsset->path()))->toEqual(Storage::get($asset->path()))
            ->and($uppercaseAsset->data())->toEqual($asset->data());
    }
});
