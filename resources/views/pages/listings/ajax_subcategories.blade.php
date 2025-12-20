<option value="">{{trans('words.select_sub_category')}}</option>
@foreach($subcategories as $i => $subcategory)    
                                 
        <option value="{{$subcategory->id}}">{{$subcategory->sub_category_name}}</option> 
                                                   
@endforeach