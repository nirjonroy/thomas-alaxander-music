<?php

namespace App\Http\Controllers\WEB\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Brand;
use App\Models\AboutUs;
use App\Models\ChildCategory;
use App\Models\FlashSaleProduct;
use App\Models\FooterLink;
use App\Models\Footer;
use App\Models\CustomPage;
use App\Models\Blog;
use App\Models\Event;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    

public function index()
{
    $now = Carbon::now();

    $sliders = Slider::where('status', 1)
        ->orderBy('serial', 'asc')
        ->get();

    $products = Product::with(['category', 'subCategory', 'childCategory'])
        ->where('type', 'single')
        ->latest()
        ->take(24)
        ->get();

    $top_picks = Product::with(['category', 'subCategory', 'childCategory'])
        ->where([
            ['today_special', 1],
            ['type', 'single'],
        ])
        ->latest()
        ->take(24)
        ->get();

    $tranding_songs = Product::with(['category', 'subCategory', 'childCategory'])
        ->where([
            ['tranding_songs', 1],
            ['type', 'single'],
        ])
        ->latest()
        ->take(24)
        ->get();

    $physical_product = Product::with(['category', 'subCategory', 'childCategory'])
        ->where('type', 'variable')
        ->latest()
        ->take(24)
        ->get();

    $events = Event::whereMonth('date', $now->month)
        ->whereYear('date', $now->year)
        ->orderBy('date', 'asc')
        ->get();

    return view('frontend.home.index', compact(
        'products',
        'tranding_songs',
        'top_picks',
        'physical_product',
        'events',
        'sliders'
    ));
}


    public function about(){
        $about = DB::table('about_us')->first();
        // dd($about);
        return view('frontend.home.about', compact('about'));
    }

    public function subCategoriesByCategory(Request $request)
    {
        if($request->type == 'subcategory')
        {
            $id = Category::whereSlug($request->slug)->first()->id;
            $categories = SubCategory::where(['category_id' => $id])->get();
            if($categories->count() <= 0)
            {
                return redirect()->route('front.shop', ['slug'=> $request->slug ] );
            }

            return view('frontend.category.sub-category', compact('categories'));
        }
        else if($request->type == 'childcategory')
        {
            $id = SubCategory::whereSlug($request->slug)->first()->id;
            $categories = ChildCategory::where(['sub_category_id' => $id])->get();
            if($categories->count() <= 0)
            {
                return redirect()->route('front.shop', ['slug'=> $request->slug ] );
            }

            return view('frontend.category.child-category', compact('categories'));
        }

    }

public function shop(Request $request, $slug = null)
{
    $data = null;

    if (!empty($slug)) {
        $data = Category::with('products')->whereSlug($slug)->first();

        if (!$data) {
            $data = SubCategory::with('products')->whereSlug($slug)->first();
        }

        if (!$data) {
            $data = ChildCategory::with('products')->whereSlug($slug)->first();
        }
    }

    if ($data instanceof Category || $data instanceof SubCategory || $data instanceof ChildCategory) {
        $products = $data->products;
    } else {
        $products = Product::with(['category', 'subCategory', 'childCategory'])->take(30)->get();
    }

    // Apply price range filter
    $minPrice = $products->min('price');
    $maxPrice = $products->max('price');

    $minPriceFilter = $request->input('min_price', $minPrice);
    $maxPriceFilter = $request->input('max_price', $maxPrice);

    $filteredProducts = $products->whereBetween('price', [$minPriceFilter, $maxPriceFilter]);

    // Apply availability filter
    $inStock = $request->input('in_stock');
    $outOfStock = $request->input('out_of_stock');

    if ($request->input('in_stock')) {
        $filteredProducts = $filteredProducts->where('qty', '>', 0);
    }

    if ($request->input('out_of_stock')) {
        $filteredProducts = $filteredProducts->where('sold_qty', '==', 'qty');
    }
    // dd($data);
    return view('frontend.shop.index', compact('filteredProducts', 'minPrice', 'maxPrice', 'data'));
}









    public function mostSellingProducts()
    {
        $products = Product::with(['category', 'subCategory', 'childCategory'])
                            ->leftJoin('order_products as op','products.id','=','op.product_id')
                            ->selectRaw('products.*, COALESCE(sum(op.qty),0) total')
                            ->groupBy('products.id')
                            ->orderBy('total','desc')
                            ->take(50)
                            ->get();

        return view('frontend.shop.most-selling', compact('products'));
    }

     public function flashSellProducts(Request $request)
    {
        $data = null;


    $products = Product::with(['category', 'subCategory', 'childCategory'])->take(30)->get();
       //dd($flashProd);
    // Apply price range filter

   $minPrice = $products->min('price');
    $maxPrice = $products->max('price');

    $minPriceFilter = $request->input('min_price', $minPrice);
    $maxPriceFilter = $request->input('max_price', $maxPrice);

    $filteredProducts = $products->whereBetween('price', [$minPriceFilter, $maxPriceFilter]);

    // Apply availability filter
    $inStock = $request->input('in_stock');
    $outOfStock = $request->input('out_of_stock');

    if ($request->input('in_stock')) {
        $filteredProducts = $filteredProducts->where('qty', '>', 0);
    }

    if ($request->input('out_of_stock')) {
        $filteredProducts = $filteredProducts->where('sold_qty', '==', 'qty');
    }

        $flashSell = FlashSaleProduct::with('product')->where('status', 1)->latest()->get();

        return view('frontend.shop.flash-sell', compact('flashSell', 'filteredProducts', 'minPrice', 'maxPrice'));
    }
    public function customPages($slug){
        $customPage=CustomPage::where('slug', $slug)->first();

        // dd($customPage);
        return view('frontend.pages', compact('customPage'));
    }

    public function all_category(){

        $categories = Category::where('status', 1)->with('products')->get();
        return view('frontend.category.category', compact('categories'));
    }

    public function contact_us(){
    	// $contact = contactPage::first();
      	return view('frontend.pages.contact');
      	//dd($contact);
    }

  	public function blog(){
    	$blogs = Blog::latest()->get();
      	// dd($blog);
      	return view('frontend.pages.blog', compact('blogs'));
      	//dd($contact);
    }
    
    public function event(){
    	$events = Event::latest()->get();
      	// dd($blog);
      	return view('frontend.pages.events', compact('events'));
      	//dd($contact);
    }

  	public function blog_details($slug){
    	$blog = Blog::where('slug', $slug)->first();
      	//dd($blog);
      	return view('frontend.pages.blog_details', compact('blog'));
    }

}
