<?php

Route::group(['middleware' => 'web', 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\DisableNewConvoEmailPerMailbox\Http\Controllers'], function()
{
    Route::get('/', 'DisableNewConvoEmailPerMailboxController@index');
});
