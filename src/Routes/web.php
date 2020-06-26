<?php 
Route::group([
    'prefix' => 'role',
    'middleware' => ['web','auth'], 
    ], 
    function()
    {
        
        Route::get('/list',
                    ['as' => 'role.list', 
                        'uses' => 'Vitoutry\PermissionUI\Controllers\RoleController@roleList'
                    ]
                )->middleware('can:view-role');
        

        Route::get('/',
                    ['as' => 'role.index', 
                        'uses' => 'Vitoutry\PermissionUI\Controllers\RoleController@index'
                    ]
                )->middleware('can:view-role');

        Route::get('/create',
                    ['as' => 'role.create', 
                        'uses' => 'Vitoutry\PermissionUI\Controllers\RoleController@create'
                    ]
                )->middleware('can:create-role');

        Route::post('/',
                    ['as' => 'role.store', 
                        'uses' => 'Vitoutry\PermissionUI\Controllers\RoleController@store'
                    ]
                )->middleware('can:create-role');

        Route::get('/{id}/edit',
                    ['as' => 'role.edit', 
                        'uses' => 'Vitoutry\PermissionUI\Controllers\RoleController@edit'
                    ]
                )->middleware('can:edit-role');

        Route::put('/{id}',
                    ['as' => 'role.update', 
                        'uses' => 'Vitoutry\PermissionUI\Controllers\RoleController@update'
                    ]
                )->middleware('can:edit-role');

        Route::post('/destroy',
                    ['as' => 'role.destroy',
                        'uses' => 'Vitoutry\PermissionUI\Controllers\RoleController@postDelete'
                    ]
                )->middleware('can:delete-role');

        // Route::resource('role', 'Vitoutry\PermissionUI\Controllers\RoleController');
        
    }
);

