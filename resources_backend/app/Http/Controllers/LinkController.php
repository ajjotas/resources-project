<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

use App\Http\Requests\AddUpdateLinkRequest;
use App\Repository\LinkRepositoryInterface;

class LinkController extends Controller
{
    private $linkRepository;
  
    public function __construct(LinkRepositoryInterface $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    } 

    public function all() 
    {
        return response()->json(['allLink' => $this->linkRepository->get()]);          
    }

    public function add(AddUpdateLinkRequest $request)
    {
        $this->linkRepository->create($request->only(['description', 'link', 'newTab']));
        
        return response()->json(['success'=>'Link added successfully.']);   
    }

    public function delete($id)  
    {
        $this->linkRepository->destroy($id);        

        return response()->json(['success'=>'Link deleted successfully.']);
    }   
    
    public function update($id, AddUpdateLinkRequest $request)
    {
        $this->linkRepository->update($id, $request->only(['description', 'link', 'newTab']));

        return response()->json(['success'=>'Link updated successfully.']);   
    }    
}
