<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

use App\Http\Requests\AddUpdateHtmlRequest;
use App\Repository\HtmlRepositoryInterface;

class HtmlController extends Controller
{
    private $htmlRepository;
  
    public function __construct(HtmlRepositoryInterface $htmlRepository)
    {
        $this->htmlRepository = $htmlRepository;
    } 

    public function all() 
    {
        return response()->json(['allHtml' => $this->htmlRepository->get()]);       
    }

    public function add(AddUpdateHtmlRequest $request)
    {
        $this->htmlRepository->create($request->only(['title', 'description', 'snippet']));
        
        return response()->json(['success'=>'Html snippet added successfully.']);   
    }

    public function delete($id)  
    {
        $this->htmlRepository->destroy($id);

        return response()->json(['success'=>'Html snippet deleted successfully.']);
    }   
    
    public function update($id, AddUpdateHtmlRequest $request)
    {
        $this->htmlRepository->update($id, $request->only(['title', 'description', 'snippet']));

        return response()->json(['success'=>'Html snippet updated successfully.']);   
    }    
}
