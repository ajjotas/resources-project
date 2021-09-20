<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\AddPdfRequest;
use App\Repository\PdfRepositoryInterface;

class PdfController extends Controller
{
    private $pdfRepository;
  
    public function __construct(PdfRepositoryInterface $pdfRepository)
    {
        $this->pdfRepository = $pdfRepository;
    } 

    public function all() 
    {     
        return response()->json(['allPdf' => $this->pdfRepository->get()]);
    }

    public function add(AddPdfRequest $request)
    {
        $file = $request->file('file');

        if($file) {
            $fileName = time().'_'.$file->getClientOriginalName();

            $filePath = Storage::putFileAs('/public/uploads', $file, $fileName);

            $this->pdfRepository->create([
                'description' => $request->description,
                'path' => '/'.$filePath
            ]);

            return response()->json(['success'=>'File uploaded successfully.']);
        }    
    }

    public function download($id)  
    {
        $file = $this->pdfRepository->find($id);        
        $filePath = storage_path('app') . $file->path;    
        return response()->download($filePath);
    }

    public function delete($id)  
    {
        $file = $this->pdfRepository->find($id);     
        Storage::delete($file->path);
        $this->pdfRepository->destroy($id);      

        return response()->json(['success'=>'File deleted successfully.']);
    }    
}
