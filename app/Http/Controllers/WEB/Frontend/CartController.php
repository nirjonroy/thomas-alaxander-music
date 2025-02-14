<?php

namespace App\Http\Controllers\WEB\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariantItem;
use App\Models\Shipping;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        // dd($cart);
        return view('frontend.cart.index', compact('cart'));
    }

    public function store(Request $request)
    {
        
        $item = Product::findOrFail($request->productId);
        $productType = $item->product_type;
    
        $cart = session()->get('cart', []);
        $productId = $item->id;
        $variationId = $request->product_variation['id'] ?? null;
        $variationName = $request->product_variation['name'] ?? null;
        $totalPrice = $request->finalPrice;
        $quantity = $request->quantity ?? 1;
        $flavour = $request->flavour;
        $freeSides = $request->selectedFreeSides ?? []; // Free sides array
        $topping = $request->topping;
        $dip = $request->dip;
        $proteinName = $request->protein['name'] ?? null;
        $proteinPrice = $request->protein['price'] ?? 0;
        $sides = $request->selectedSides ?? [];
        $image = $item->thumb_image;
    
        // Log freeSides for debugging
        
    
        // Ensure all freeSides are mapped as names
        $mappedFreeSides = array_map(function($side) {
            return is_array($side) && isset($side['name']) ? $side['name'] : $side;
        }, $freeSides);
    
        $uniqueOptions = [
            "product_id" => $productId,
            "variation_id" => $variationId,
            "variation_name" => $variationName,
            "flavour" => $flavour,
            "freeSides" => $mappedFreeSides, // Use mapped freeSides
            "topping" => $topping,
            "dip" => $dip,
            "protein_name" => $proteinName,
            "protein_price" => $proteinPrice,
            "sides" => array_map(function($side) { return $side['name']; }, $sides),
        ];
    
        $uniqueKey = md5(json_encode($uniqueOptions));
    
        if ($productType === 'variable') {
            $cart = $this->updateCartForVariableProduct($cart, $uniqueKey, $request, $item, $totalPrice, $variationId, $flavour, $mappedFreeSides, $topping, $dip, $proteinName, $proteinPrice, $sides, $variationName, $image);
        } else {
            $cart = $this->updateCartForSingleProduct($cart, $uniqueKey, $request, $item, $totalPrice, $flavour, $mappedFreeSides, $topping, $dip, $proteinName, $proteinPrice, $sides, $variationName, $image);
        }
    
        session()->put('cart', $cart);
    
        return response()->json([
            'status' => true,
            'msg' => 'Product added to cart successfully!',
        ]);
    }
    
    private function updateCartForSingleProduct(
        $cart, $uniqueKey, $request, $item, $totalPrice, $flavour, $freeSides, $topping, $dip,
        $proteinName, $proteinPrice, $sides, $variationName, $image
    ) {
        $quantity = $request->quantity ?? 1;
    
        $cart[$uniqueKey] = [
            'product_id' => $item->id,
            "name" => $item->name,
            'image' => $image,
            "quantity" => isset($cart[$uniqueKey]) ? $cart[$uniqueKey]['quantity'] + $quantity : $quantity,
            "price" => $totalPrice,
            "flavour" => $flavour,
            "freeSides" => $freeSides, // Ensure freeSides is an array
            "topping" => $topping,
            "dip" => $dip,
            "protein_name" => $proteinName,
            "protein_price" => $proteinPrice,
            "sides" => $sides,
            "variation_name" => $variationName
        ];
    
        return $cart;
    }
    
    private function updateCartForVariableProduct(
        $cart, $uniqueKey, $request, $item, $totalPrice, $variationId, $flavour, $freeSides, 
        $topping, $dip, $proteinName, $proteinPrice, $sides, $variationName, $image
    ) {
        $quantity = $request->quantity ?? 1;
    
        $cart[$uniqueKey] = [
            "product_id" => $item->id,
            "name" => $item->name,
            "image" => $image,
            "quantity" => $quantity,
            "price" => $totalPrice,
            "flavour" => $flavour,
            "freeSides" => $freeSides, // Ensure freeSides is an array
            "topping" => $topping,
            "dip" => $dip,
            "protein_name" => $proteinName,
            "protein_price" => $proteinPrice,
            "sides" => $sides,
            'variation_name' => $variationName,
            'variation_id' => $variationId
        ];
    
        return $cart;
    }
    
    

    






    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $quantity = $request->input('quantity');
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
            $totalAmount = $this->calculateTotalAmount($cart);
            return response()->json([
                'status' => true,
                'msg' => 'Cart item quantity updated successfully!',
                'totalAmount' => $totalAmount,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'Cart item not found!',
            ], 404);
        }
    }

    private function calculateTotalAmount($cart)
    {
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += ($item['price'] * $item['quantity']);
        }
        return $totalAmount;
    }

    public function increaseQty($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $newQty = $cart[$id]['quantity'] + 1;
            $cart[$id]['quantity'] = $newQty;
            session()->put('cart', $cart);
            return response()->json([
                'status' => true,
                'totalItems' => $this->totalCartItems(),
                'msg' => 'Item quantity increased!'
            ], 200);
        }
    }

    public function decreaseQty($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] == 1) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                return response()->json([
                    'status' => true,
                    'totalItems' => $this->totalCartItems(),
                    'msg' => 'Item has been removed!'
                ], 200);
            } else {
                $cart[$id]['quantity'] -= 1;
                session()->put('cart', $cart);
                return response()->json([
                    'status' => true,
                    'msg' => 'Item quantity decreased!'
                ], 200);
            }
        }
    }

    public function removeItem($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return response()->json([
                'status' => true,
                'totalItems' => $this->totalCartItems(),
                'msg' => 'Item removed from cart!'
            ]);
        }
    }

    private function totalCartItems()
    {
        $cart = session()->get('cart', []);
        return count($cart);
    }

    public function destroy($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return response()->json([
                'status' => true,
                'totalItems' => $this->totalCartItems(),
                'msg' => 'Item has been removed!',
            ], 200);
        }
    }
}
