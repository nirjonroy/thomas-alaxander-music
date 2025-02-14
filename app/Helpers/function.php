<?php
use App\Models\Category;
use App\Models\PopularCategory;
use App\Models\FeaturedCategory;
use App\Models\Setting;
use App\Models\Brand;

function custom_sanitize($content)
{
    $replace = array('<p>', '</p>');
    $response = str_replace($replace, '', $content);
    return $response;
}

function getOrderStatus($type=""){

    if($type != "")
      {
       return [''=> 'All', '0'=>'Pending','1'=>'Process','5'=>'On Hold','3'=>'Complete','4'=>'Cancelled','6' => 'Return'];
      }

      return ['0'=>'Pending','1'=>'Process','5'=>'On Hold','3'=>'Complete','4'=>'Cancelled','6' => 'Return'];

  }

function categories()
{
    $categories = Category::with('activeSubCategories')->where('status', 1)->orderBy('serial', 'ASC')->get();

    return $categories;
}

function featuredCategories()
{
    $feateuredCategories = FeaturedCategory::with('category')->orderBy('serial', 'DESC')->get();

    return $feateuredCategories;
}

function popularCategories()
{
    $popularCategories = PopularCategory::with('category')->orderBy('serial', 'ASC')->get();

    return $popularCategories;
}

function siteInfo()
{
    $setting = Setting::first();

    return $setting;
}

function calculateDiscountPercent($product)
{
    if(!empty($product->offer_price) && $product->price > $product->offer_price)
    {
        return (int) ( ( ($product->price - $product->offer_price) / $product->price) * 100 );
    }

    return 0;
}

function cartItems()
{
    $cart = session()->get('cart', []);
    // dd($cart);

    return $cart;
}

function getCartProductArray(){
    $carts = session()->get('cart', []);
    $product_id=[];
    foreach($carts as $key=>$cart){
        $product_id[]=$key;

    }

    return $product_id;
}


function totalCartItems()
{
    $cart = cartItems();

    return count($cart);
}

function cartTotalAmount()
{
    $cart = cartItems();
    $total = 0;
    $total_qty = 0;
    foreach($cart as $key => $item)
    {
        $total += ($item['price'] * $item['quantity']);
        $total_qty += $item['quantity'];
    }

    return ['total_qty' => $total_qty, 'total'=> $total];
}

function getProductInfo($product){


	$price=($product->offer_price  > 0) ? $product->offer_price : $product->price;
// 	$discount_amount=$product->dicount_amount;

// 	$old_price=($product->offer_price > 0) ? $product->sell_price : $product->regular_price;
	$old_price=$product->price;

	return ['price'=>$price,'old_price'=>$old_price];
}

function brands()
{
    $brands = Brand::with('products')->where('status', 1)->get();

    return $brands;
}

function getImage($folder=null,$value=null){

	$url = asset('images/no_found.png');
	$path = public_path($folder.'/'.$value);
	if (!empty($folder) && (!empty($value))) {
		if(file_exists($path)){
			$url = asset($folder.'/'.$value);
		}
	}
	return $url;
}


function deleteImage($folder=null, $file=null){

    if (!empty($folder) && !empty($file)) {
        $path = public_path($folder.'/'.$file);
        $isExists = file_exists($path);
        if ($isExists) {
            unlink($path);
        }
    }
    return true;
}

function BanglaText($index)
{
  $bangla_text = array(
    "order"                 => "Order",
    "cart"                  => "Add To Cart",
    "free"                  => "Free Shipping",
    "offer"                 => "Mega Offer",
    "cart_add"              => "Add to cart",
    "cust_info"             =>"Customer Information",
    "instruction"           =>"If you want to confirm your order then, fillup the form and press submit",
    "name"                  => "Your Name",
    "placeholder_name"      => "Write down your name",
    "mobile"                => "Your Phone number",
    "placeholder_mobile"    => "your phone number",
    "address"               => "Full Address",
    "placeholder_address"   => "",
    "delivery_zone"         => "Delivery area",
    "confirm_order"         => "Confirm Order",
    "order_information"     => "Order Information",
    "land_instruction"      => "To proced order, fillup the form carefully with valid data",
    "login_account"         => "If you already have an account, login",
    "coupon_apply"          => "Do you have cupon"
    );
  return $bangla_text[$index];
}
