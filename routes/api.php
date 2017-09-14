<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'db-test'], function () {
    Route::group(['prefix' => 'nativity'], function () {
        Route::post('select', function () {
            // DB 是一个 facade , 用于便捷的访问数据库操作对象
            // select 方法可以执行一个查询语句，并返回结果对象
            $tables = DB::select("show tables");
            Log::info('tables', [$tables]);
            return $tables;
        });
        Route::post('insert', function () {
            // insert操作会返回操作是否成功
            $created_at = date('Y-m-d');
            $email = time() . '@qq.com';
            DB::insert("
insert 
into `users` (`name`, `email`, `password`, `created_at`) 
value ('zhan', '$email', '123456', '$created_at')
");
        });
        Route::post('update', function () {
            // update 操作会返回受影响的行数
            // 可以进行参数绑定操作
            // 未被修改的行不算做受影响的行数
            $affect1 = DB::update("update `users` set `password` = ?", ['654321']);
            $affect2 = DB::update("update `users` set `name` = :name", ['name' => 'new-name']);
            return [
                'affect1' => $affect1,
                'affect2' => $affect2
            ];
        });
        Route::post('delete', function () {
            // delete 操作返回被删除的行数
            $affect = DB::delete("delete from `users` where name=:name", ['name' => 'new-name']);
            return $affect;
        });
        Route::post('statement', function () {
            // 用于执行没有返回值的语句
            DB::statement("drop table `password_resets`");
        });
        Route::post('auto-transaction', function () {
            // 自动事务
            // 当sql语句操作成功时，自动提交
            // 失败时，自动回滚
            DB::transaction(function () {
                DB::update("update `users` set `name` = :name", ['name' => 'new-name']);
                throw new Exception('手动抛出一个异常');
            });
        });
        Route::post('transaction', function () {
            // 手动操作事务
            // 包含 beginTransaction, commit rollBack
            DB::beginTransaction();
            try {
                DB::update("update `users` set `name` = :name", ['name' => 'new-name']);
                throw new Exception('手动抛出一个异常');
                DB::commit();
                return '事务提交了，数据被提交到数据库中了';
            } catch (Exception $exception) {
                DB::rollBack();
                return '事务回滚了，数据操作被取消';
            }
        });
        Route::post('bind', function () {
            // 参数绑定

            // 命名绑定
            $affected1 = DB::update("update `users` set `name` = :name, `password` = :password", ['name' => 'new-name', 'password' => '111111']);

            // 位置绑定
            $affected2 = DB::update("update `users` set `name` = ?, `password` = ?", ['old-name', '222222']);
            return [
                '1' => $affected1,
                '2' => $affected2
            ];
        });

    });
    Route::group(['prefix' => 'structure'], function () {
        Route::post('get', function () {});
        Route::post('first', function () {});
        Route::post('value', function () {});
        Route::post('pluck', function () {});
        Route::post('polymeric', function () {
            // count
            // max min sum
        });
        Route::post('select', function () {
            // select
            // raw 使用原生表达式
        });
        Route::group(['prefix' => 'where'], function () {
            Route::post('where', function () {});
            Route::post('orWhere', function () {});
            Route::post('whereBetween', function () {});
            Route::post('whereIn', function () {});
            Route::post('whereDate', function () {});
            Route::post('whereColumn', function () {});
        });
        Route::group(['prefix' => 'most'], function () {
            Route::post('orderBy', function () {});
            Route::post('inRandomOrder', function () {});
            Route::post('groupBy', function () {});
            Route::post('skip', function () {});
            Route::post('tack', function () {});
        });
        Route::post('insert', function () {
            // insert
            // insetGetId
        });
        Route::post('update', function () {
            // update
            // increment
            // decrement
        });
        Route::post('delete', function () {
            // delete
            // truncate 删除表所有数据
        });
        Route::post('paging', function () {
            // skip tack
        });
    });

});

