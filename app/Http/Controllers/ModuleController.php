<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ModuleController extends Controller
{
    // Endpoint to create a new module
    public function createModule(Request $request)
    {
        // Validate data
        $request->validate([
            'width' => 'required|integer',
            'height' => 'required|integer',
            'color' => 'required|string',
            'link' => 'required|url',
        ]);

        // Create the new module and save it
        $module = Module::create([
            'width' => $request->width,
            'height' => $request->height,
            'color' => $request->color,
            'link' => $request->link,
        ]);

        // Return the ID of created module
        return response()->json(['id' => $module->id], 201);
    }

    public function downloadModule($id)
    {
        // Get the module from the database
        $module = Module::findOrFail($id);

        // Create a new ZipArchive object
        $zip = new ZipArchive();
        $zipFilePath = storage_path("app/private/module_{$id}.zip");

        if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
            return response()->json(['message' => 'Could not create ZIP file'], 500);
        }

        // Generate the HTML content 
        $htmlContent = "<html>
                            <head>
                            <!-- Link to the external CSS file -->
                            <link rel='stylesheet' type='text/css' href='module.css'>
                            </head>                    
                            <body>
                                <div id='module' class='module'>
                                Click me
                                </div>
                                <script src='module.js'></script>
                            </body>
                        </html>";
        $zip->addFromString('module.html', $htmlContent);

        // Generate the CSS content 
        $cssContent = "
            #module {
                width: {$module->width}px;
                height: {$module->height}px;
                background-color: {$module->color};
                display: flex;
                justify-content: center;
                align-items: center;
                box-sizing: border-box;
                cursor: pointer;
            }

          
        ";
        $zip->addFromString('module.css', $cssContent);

        // Generate the JS content
        $jsContent = "
            document.getElementById('module').addEventListener('click', function() {
                window.open('{$module->link}', '_blank');
            });
        ";
        $zip->addFromString('module.js', $jsContent);

        // Close the ZIP file
        $zip->close();

        // Return the ZIP file for download
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
