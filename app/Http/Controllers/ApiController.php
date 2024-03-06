<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Process;
use App\Models\Task;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\UnauthorizedException;


class ApiController extends Controller
{
    public function createTask(Request $request)
    {
        $company = $request->user();
        if (!$company instanceof Company) {
            throw new UnauthorizedException('not a company');
        }
        $request->validate([
            'process_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $process = Process::query()->find($request->process_id);
        $user = User::query()->find($request->user_id);

        Validator::make($request->all(), [
            'process_id' => [
                function (string $attribute, mixed $value, Closure $fail) use ($company, $process) {

                    if ($process->company_id !== $company->getKey()) {
                        $fail("company does not match.");
                    }
                },
            ],
            'user_id' => [
                function (string $attribute, mixed $value, Closure $fail) use ($user, $process) {
                    if (!$user) {
                        $fail("assigned user does not exist.");
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
