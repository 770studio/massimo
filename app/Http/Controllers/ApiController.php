<?php

namespace App\Http\Controllers;

use App\Models\Process;
use App\Models\Task;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ApiController extends Controller
{
    public function createTask(Request $request)
    {
        $request->validate([
            'process_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $process = Process::query()->find($request->process_id);
        $user = User::query()->find($request->user_id);
        $auth_user = Auth::user();

        Validator::make($request->all(), [
            'process_id' => [
                function (string $attribute, mixed $value, Closure $fail) use ($auth_user, $process) {

                    if ($process->user_id !== $auth_user->getKey()) {
                        $fail("User A does not match.");
                    }
                },
            ],
            'user_id' => [
                function (string $attribute, mixed $value, Closure $fail) use ($user, $auth_user, $process) {
                    if (!$user) {
                        $fail("User B does not exist.");
                    }
                },
            ],
            'data' => 'sometimes|array',

        ])->validate();


        Task::query()->create([
            'process_id' => $process->getKey(),
            'assigned_to' => $user->getKey(),

        ]);


        return response()->json([
            'status' => 'ok'
        ]);

    }


}
