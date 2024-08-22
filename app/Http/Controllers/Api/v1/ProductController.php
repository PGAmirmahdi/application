<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class ProductController extends Controller
{
    public function getProducts()
    {
        // Log the user visit
        if (!Log::where(['activity_name' => 'visit', 'ip' => request()->ip()])->exists()) {
            activity_log('visit', __METHOD__);
        }

        // Database connection details
        $servername = "mpsystem.ir";
        $username = "admin_mandegarpars";
        $password = "^Ocj3z44GQA+";
        $dbname = "admin_mandegarpars";

        try {
            // Connect to the external database
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Get all products from the local database
            $products = Product::all();

            if ($products->isEmpty()) {
                return response()->json(["message" => "No products found."], 404);
            }

            // Convert product codes to a string for the SQL query
            $productCodes = $products->pluck('code')->toArray();
            $productCodes = implode("','", $productCodes);

            // SQL query to get matching inventories
            $sql = "SELECT inventories.id, inventories.warehouse_id, inventories.title, inventories.code, inventories.type, inventories.current_count
                    FROM inventories
                    WHERE inventories.code IN ('{$productCodes}')";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $inventories = $stmt->fetchAll(PDO::FETCH_OBJ);
            $conn = null;

            $inventoryMap = [];
            foreach ($inventories as $inventory) {
                $inventoryMap[$inventory->code] = $inventory;
            }

            // ترکیب اطلاعات موجودی‌ها با محصولات
            $products->getCollection()->transform(function($product) use ($inventoryMap) {
                $product->inventory = $inventoryMap[$product->code] ?? (object)[
                    'id' => null,
                    'warehouse_id' => null,
                    'title' => null,
                    'code' => null,
                    'type' => null,
                    'current_count' => null
                ]; // تنظیم مقادیر به null در صورت نبودن موجودی
                return $product;
            });

            return response()->json($products);

        } catch (\PDOException $e) {
            return response()->json(["message" => "Connection failed: " . $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'title' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $query = Product::leftJoin('offers', 'products.id', '=', 'offers.product_id')
            ->select('products.*',
                DB::raw('COALESCE(offers.price_after, products.price) as effective_price'),
                'offers.description as offer_description',
                'offers.percentage as percentage',
                'offers.price_before as price_before',
                'offers.price_after as price_after')
            ->where('products.title', 'like', '%' . $request->title . '%')
            ->latest();

        $products = $query->paginate(10);

        if ($products->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        // Database connection details
        $servername = "mpsystem.ir";
        $username = "admin_mandegarpars";
        $password = "^Ocj3z44GQA+";
        $dbname = "admin_mandegarpars";

        try {
            // Connect to the external database
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Convert product codes to a string for the SQL query
            $productCodes = $products->pluck('code')->toArray();
            $productCodes = implode("','", $productCodes);

            // SQL query to get matching inventories
            $sql = "SELECT inventories.id, inventories.warehouse_id, inventories.title, inventories.code, inventories.type, inventories.current_count
                    FROM inventories
                    WHERE inventories.code IN ('{$productCodes}')";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $inventories = $stmt->fetchAll(PDO::FETCH_OBJ);
            $conn = null;
            $inventoryMap = [];
            foreach ($inventories as $inventory) {
                $inventoryMap[$inventory->code] = $inventory;
            }

            // ترکیب اطلاعات موجودی‌ها با محصولات
            $products->getCollection()->transform(function($product) use ($inventoryMap) {
                $product->inventory = $inventoryMap[$product->code] ?? (object)[
                    'id' => null,
                    'warehouse_id' => null,
                    'title' => null,
                    'code' => null,
                    'type' => null,
                    'current_count' => null
                ]; // تنظیم مقادیر به null در صورت نبودن موجودی
                return $product;
            });

            return response()->json([
                'success' => true,
                'data' => $products
            ]);

        } catch (\PDOException $e) {
            return response()->json([
                'success' => false,
                'message' => "Connection failed: " . $e->getMessage()
            ]);
        }
    }

    public function filter(Request $request)
    {
        $validate = validator()->make($request->all(), [
            'sortBy' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $category_id = $request->category_id;

        // ساخت یک کوئری پایه برای محصولات
        $query = Product::leftJoin('offers', 'products.id', '=', 'offers.product_id')
            ->select('products.*',
                DB::raw('COALESCE(offers.price_after, products.price) as effective_price'),
                'products.description as description',
                'offers.percentage as percentage',
                'offers.price_before as price_before',
                'offers.price_after as price_after');

        if ($category_id) {
            $query->where('products.category_id', $category_id);
        }

        switch ($request->sortBy) {
            case 'cheapest':
                $query->orderBy('effective_price');
                break;

            case 'favorites':
                $query->orderByDesc('products.favorites');
                break;

            case 'expensive':
                $query->orderByDesc('effective_price');
                break;

            case 'bestselling':
                $orders_id = Payment::where('status', 'success')->pluck('order_id');
                $orders = OrderItem::whereIn('order_id', $orders_id)
                    ->select('product_id', DB::raw('COUNT(*) as count'))
                    ->groupBy('product_id');

                $query = $query->joinSub($orders, 'orders', function ($join) {
                    $join->on('products.id', '=', 'orders.product_id');
                })->orderByDesc('count');
                break;

            default:
                return response()->json([
                    'success' => false,
                    'errors' => ['یکی از 4 مقدار cheapest, expensive, favorites و یا bestselling الزامی است']
                ]);
        }

        // اجرای کوئری و برگرداندن نتایج
        $products = $query->orderByDesc('products.id')->paginate(10);

        if ($products->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        // Database connection details
        $servername = "mpsystem.ir";
        $username = "admin_mandegarpars";
        $password = "^Ocj3z44GQA+";
        $dbname = "admin_mandegarpars";

        try {
            // Connect to the external database
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Convert product codes to a string for the SQL query
            $productCodes = $products->pluck('code')->toArray();
            $productCodes = implode("','", $productCodes);

            // SQL query to get matching inventories
            $sql = "SELECT inventories.id, inventories.warehouse_id, inventories.title, inventories.code, inventories.type, inventories.current_count
                    FROM inventories
                    WHERE inventories.code IN ('{$productCodes}')";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $inventories = $stmt->fetchAll(PDO::FETCH_OBJ);
            $conn = null;

            $inventoryMap = [];
            foreach ($inventories as $inventory) {
                $inventoryMap[$inventory->code] = $inventory;
            }

            // ترکیب اطلاعات موجودی‌ها با محصولات
            $products->getCollection()->transform(function($product) use ($inventoryMap) {
                $product->inventory = $inventoryMap[$product->code] ?? (object)[
                    'id' => null,
                    'warehouse_id' => null,
                    'title' => null,
                    'code' => null,
                    'type' => null,
                    'current_count' => null
                ]; // تنظیم مقادیر به null در صورت نبودن موجودی
                return $product;
            });

            return response()->json([
                'success' => true,
                'data' => $products
            ]);

        } catch (\PDOException $e) {
            return response()->json([
                'success' => false,
                'message' => "Connection failed: " . $e->getMessage()
            ]);
        }
    }

}
