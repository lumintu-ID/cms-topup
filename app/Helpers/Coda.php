<?php

namespace App\Helpers;

use App\Models\Price;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class Coda
{

    public static function UpdateStatus($request)
    {
        DB::beginTransaction();
        try {
            Log::info('success transaction Coda', ['DATA' => $request->all()]);

            $TxnID = $request->TxnId;
            $ApiKey = '5a8ca8f31f19a23c41edd14b29a74fd2';
            $OrderID = $request->OrderId;
            $ResultCode = $request->ResultCode;
            $checkSumString = $TxnID . $ApiKey . $OrderID . $ResultCode;
            $resultChecksum = bin2hex(md5($checkSumString, true));


            $checkSum = bin2hex(md5($request->Checksum, true));

            if (!hash_equals($resultChecksum, $checkSum)) {
                Log::info('Coda Checksum Match', ['DATA' => hash_equals($resultChecksum, $checkSum)]);
                return \response()->json([
                    'code' => 403,
                    'status' => 'CHECKSUM_NOT_MATCH',
                    'error' => 'CHECKSUM_NOT_MATCH',
                ], 403);
            }

            DB::commit();

            return "ResultCode = 0";
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Error Notify TopUp Transaction Coda', ['DATA' => Carbon::now()->format('Y-m-d H:i:s') . ' | ERR ' . ' | Error Notify TopUp Transaction']);

            return \response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'status' => 'BAD_REQUEST',
                'error' => 'BAD REQUEST',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public static function Check($request)
    {
    }
}
