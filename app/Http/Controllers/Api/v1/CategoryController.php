<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class CategoryController extends Controller
{
    public function getCategories()
    {
        return CategoryResource::collection(Category::whereNull('parent_id')->get());
    }

    public function getChildren(Request $request)
    {
        $validate = validator()->make($request->all(),[
            'parent_id' => 'required',
        ]);

        if ($validate->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $category = Category::find($request->parent_id);
        if (!$category){
            return response()->json([
                'success' => false,
                'errors' => ['دسته بندی مورد نظر پیدا نشد']
            ]);
        }

        return CategoryResource::collection($category->children);
    }

    public function getProducts(Request $request)
    {
        $validate = validator()->make($request->all(), [
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validate->errors()->getMessages()
            ]);
        }

        $category_id = $request->category_id;

        // بررسی وجود دسته‌بندی
        $category = Category::find($category_id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'errors' => ['دسته بندی مورد نظر پیدا نشد']
            ]);
        }

        // ساخت کوئری برای دریافت محصولات همراه با اطلاعات آفر
        $query = Product::leftJoin('offers', 'products.id', '=', 'offers.product_id')
            ->select(
                'products.*',
                DB::raw('COALESCE(offers.price_after, products.price) as effective_price'),
                'offers.percentage as percentage',
                'offers.price_before as price_before',
                'offers.price_after as price_after'
            )
            ->where('products.category_id', $category_id)
            ->latest();

        // اجرای کوئری و برگرداندن نتایج
        $products = $query->paginate(10);

        if ($products->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        // اطلاعات مربوط به اتصال به دیتابیس خارجی
        $servername = "mpsystem.ir";
        $username = "admin_mandegarpars";
        $password = "^Ocj3z44GQA+";
        $dbname = "admin_mandegarpars";

        try {
            // اتصال به دیتابیس خارجی
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // تبدیل کدهای محصولات به رشته برای استفاده در کوئری SQL
            $productCodes = $products->pluck('code')->toArray();
            $productCodes = implode("','", $productCodes);

            // کوئری SQL برای دریافت موجودی‌ها
            $sql = "SELECT inventories.id, inventories.warehouse_id, inventories.title, inventories.code, inventories.type, inventories.current_count
                FROM inventories
                WHERE inventories.code IN ('{$productCodes}')";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $inventories = $stmt->fetchAll(PDO::FETCH_OBJ);
            $conn = null;

            // تبدیل موجودی‌ها به یک دیکشنری بر اساس کد محصولات
            $inventoryMap = [];
            foreach ($inventories as $inventory) {
                $inventoryMap[$inventory->code] = $inventory;
            }

            // ترکیب اطلاعات موجودی‌ها با محصولات
            $products->transform(function($product) use ($inventoryMap) {
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
