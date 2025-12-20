<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use App\Models\Categories;

use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Str;

class CategoriesController extends MainAdminController
{
	public function __construct()
    {
		 $this->middleware('auth');
		  
		parent::__construct(); 	
		  
    }
    public function categories()    { 
        
              
        $categories = Categories::orderBy('category_name')->get();
        
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }
 
         
        return view('admin.pages.categories',compact('categories'));
    }
    
    public function addeditCategory()    { 
         
        if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }

        

        return view('admin.pages.addeditCategory');
    }
    
    public function addnew(Request $request)
    { 
        
        $data =  \Request::except(array('_token')) ;
        
        $rule=array(
                'category_name' => 'required'
                 );
        
         $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                return redirect()->back()->withErrors($validator->messages());
        } 
        $inputs = $request->all();
        
        if(!empty($inputs['id'])){
           
            $cat = Categories::findOrFail($inputs['id']);

        }else{

            $cat = new Categories;

        }
        
        $category_image = $request->file('category_image');
         
        
        if($category_image){

            \File::delete(public_path() .'/upload/category/'.$cat->category_image);
 
            $tmpFilePath = public_path('upload/category/');

            $hardPath =  Str::slug($inputs['category_name'], '-').'-'.md5(time());

            $img = Image::make($category_image);

            $img->fit(300, 200)->save($tmpFilePath.$hardPath.'-b.jpg');
 
            $cat->category_image = $hardPath.'-b.jpg';
        }

        
        if($inputs['category_slug']=="")
        {
            $category_slug = Str::slug($inputs['category_name'], "-");
        }
        else
        {
            $category_slug =Str::slug($inputs['category_slug'], "-"); 
        }
        
        $cat->category_icon = $inputs['category_icon'];
        $cat->category_name = $inputs['category_name']; 
        $cat->category_slug = $category_slug;
        $cat->description = $inputs['description'] ?? null;
        $cat->color = $inputs['color'] ?? null;
        $cat->is_featured = isset($inputs['is_featured']) ? 1 : 0;
        $cat->status = isset($inputs['status']) ? 1 : 0;
        $cat->sort_order = $inputs['sort_order'] ?? 0;
        $cat->deals_count = $inputs['deals_count'] ?? ($cat->deals_count ?? 0);
         
        $cat->save();
        
        if(!empty($inputs['id'])){

            \Session::flash('flash_message', trans('words.successfully_updated'));

            return \Redirect::back();
        }else{

            \Session::flash('flash_message', trans('words.successfully_added'));

            return \Redirect::back();

        }            
        
         
    }     
   
    
    public function editCategory($cat_id)    
    {     
    
    	  if(Auth::User()->usertype!="Admin"){

            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        }
        	     
          $cat = Categories::findOrFail($cat_id);
          
           

          return view('admin.pages.addeditCategory',compact('cat'));
        
    }	 
    
    public function delete($cat_id)
    {
    	if(Auth::User()->usertype=="Admin" or Auth::User()->usertype=="Owner")
        {
        	
        $cat = Categories::findOrFail($cat_id);
        $cat->delete();

        \Session::flash('flash_message', trans('words.deleted'));

        return redirect()->back();
        }
        else
        {
            \Session::flash('flash_message', trans('words.access_denied'));

            return redirect('admin/dashboard');
            
        
        }
    }

      
}
