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

Route::get('test', function () {
    $a = new stdClass();
    return ;
});

Route::group(['prefix' => 'db-test'], function () {
    Route::group(['prefix' => 'nativity'], function () {
        Route::post('select', function () {
            // DB 是一个 facade(门面) , 用于便捷的访问数据库操作对象
            // select 方法可以执行一个查询语句，并返回结果对象
            // select 方法，返回对象的集合

//            $tables = DB::select("show tables");
            $result = DB::select("select * from users");

            Log::debug('tables', [$result]);

            return $result;
        });
        Route::post('insert', function () {

            // insert操作会返回操作是否成功
            // 只允许执行一条语句

            $created_at = date('Y-m-d H:i:s');
            $email = time() . '@qq.com';

            $insert_ret = DB::insert("
insert 
into `users` (`name`, `email`, `password`, `created_at`) 
value ('zhan', '$email', '123456', '$created_at')
");
            return $insert_ret?'插入成功':'插入失败';

        });
        Route::post('update', function () {
            // update 操作会返回受影响的行数 (被修改了数据的行数)
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
            $affect = DB::delete("delete from `users` where name = :name", ['name' => 'new-name']);
            return $affect;
        });
        Route::post('statement', function () {
            // 用于执行没有返回值的语句
            DB::statement("drop table `password_resets`");
        });
        // 事务操作
        Route::post('auto-transaction', function () {
            // 自动事务
            // 当sql语句操作成功时，自动提交
            // 失败时，自动回滚
            DB::transaction(function () {
                DB::update("update `users` set `name` = :name", ['name' => 'new-name']);
                throw new Exception('手动抛出一个异常');
                DB::update("update `users` set `name` = :name", ['name' => 'name']);
            });
        });
        Route::post('transaction', function () {
            // 手动操作事务
            // 包含 beginTransaction, commit rollBack
            DB::beginTransaction();
            try {
                DB::update("update `users` set `name` = :name", ['name' => 'new-name']);
//                throw new Exception('手动抛出一个异常');
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
    // 构造器
    Route::group(['prefix' => 'structure'], function () {
        Route::post('get', function () {
            // 获取所有符合条件的值
            return DB::table('users')->get();
        });
        Route::post('first', function () {
            // 获取单条数据, 返回一个对象
            $ret =  DB::table('users')->first();
            return response()->json($ret);
        });
        Route::post('value', function () {
            // 只返回单个值
            return DB::table('users')->value('email');
        });
        Route::post('pluck', function () {
            // 查询所有的执行的列的数据
            return DB::table('users')->pluck('email');
        });
        Route::post('polymeric', function () {
            // count (统计符合条件数据条数)
            // max min sum
            return DB::table('users')->count();
        });
        Route::post('select', function () {
            // select
            // raw 使用原生表达式
            // select * from users
            // select name, email from users
            // 使用别名时，直接写作 name as real_name
            // select count(id) from users;
            // 通过 raw方法来设定原生sql

//            return DB::table('users')
//                ->select(['name as real_name', 'email'])
//                ->get();
            return DB::table('users')
                ->select(DB::raw('count(id) as count'))
                ->get();

        });
        Route::group(['prefix' => 'where'], function () {
            Route::post('where', function () {
                // where 的使用
                // where($column, $op, $value)
                // where($column, $value)
                // where([])
//                return DB::table('users')
//                    ->where('name', '=', 'zhan')
//                    ->get();

//                return DB::table('users')
//                    ->where('name', 'zhan')
//                    ->get();

                return DB::table('users')
                    ->where([['name', '=', 'zhan'], ['id', '=', '4']])
                    ->get();

            });
            Route::post('orWhere', function () {
                return DB::table('users')
                    ->where('name', 'zhan')
                    ->orWhere('id', 6)
                    ->get();
            });
            Route::post('whereBetween', function () {});
            Route::post('whereIn', function () {
                // whereIn
                // whereNotIn
            });
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
            // 分组的示例
            // skip tack
        });
    });

});

