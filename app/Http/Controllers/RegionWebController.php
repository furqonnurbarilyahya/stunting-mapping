<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionWebController extends Controller
{
    public function create()
    {
        return view('pages.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        if ($request->hasFile('csv_file')) {
            $path = $request->file('csv_file')->getRealPath();
            
            $file = fopen($path, 'r');
            // Assuming first row is header
            $header = fgetcsv($file);
            
            // Clean up headers (trim strings etc.)
            if ($header) {
                $header = array_map('trim', $header);
            }

            $count = 0;
            while (($row = fgetcsv($file)) !== false) {
                if (count($header) !== count($row)) {
                    continue; // Skip malformed rows
                }
                
                $rowData = array_combine($header, $row);
                
                // Handle naming convention differences in CSV
                $identifier = $rowData['kab/kota'] ?? ($rowData['wilayah'] ?? null);
                
                if (!$identifier) {
                    continue; // Skip if no identifier
                }

                // Standardize to database column 'kab/kota'
                $rowData['kab/kota'] = $identifier;
                unset($rowData['wilayah']); // Remove wilayah if exists so it doesn't break mass assignment

                // Update or Create based on 'kab/kota' matching
                Region::updateOrCreate(
                    ['kab/kota' => $identifier],
                    $rowData
                );
                
                $count++;
            }
            
            fclose($file);

            return back()->with('success', "Proses import sukses! {$count} baris data berhasil disinkronkan ke pangkalan data.");
        }

        return back()->with('error', 'Terjadi kesalahan sistem saat mencoba membaca ekstensi file dokumen tersebut.');
    }
}
