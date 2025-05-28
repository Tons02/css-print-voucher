<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

class PrintVoucherController extends Controller
{
    public function PrintVoucher(Request $request)
    {
        $ip = $request->ip();
        $voucher_code = $request->voucher_code;
        $fullname = $request->fullname;
        $birthday = $request->birthday;
        $gender = $request->gender;
        $contact_number = $request->contact_number;

        try {
            // Use raw COM2 with proper Windows syntax
            $localIP = "10.10.12.12";

            if ($localIP != $ip) {
                return response()->json(['error' => "Unable to print: different local IP address."], 500);
            }

            $connector = new FilePrintConnector("\\\\.\\COM2");

            $printer = new Printer($connector);
            $printer->text($voucher_code . "\n");
            $printer->text($fullname . "\n");
            $printer->text($birthday . "\n");
            $printer->text($gender . "\n");
            $printer->text($contact_number . "\n");
            $printer->cut();
            $printer->close();

            return response()->json(['message' => 'Printed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
