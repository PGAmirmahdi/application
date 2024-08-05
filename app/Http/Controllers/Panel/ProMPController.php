<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use PDO;

class ProMPController extends Controller
{
    public function index()
    {
        $servername = "mpsystem.ir";
        $username = "admin_mandegarpars";
        $password = "^Ocj3z44GQA+";
        $dbname = "admin_mandegarpars";

        // اتصال به دیتابیس
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // دریافت محصولات از جدول products
            $products = Product::all()->pluck('code')->toArray();

            if(empty($products)) {
                return "No products found.";
            }

            // تبدیل آرایه محصولات به رشته برای استفاده در SQL
            $productCodes = implode("','", $products);

            // ساخت عبارت SQL برای دریافت محصولات مطابق با کدها
            $sql = "SELECT invetories.id, invetories.warehouse_id, invetories.title, invetories.code, invetories.type, invetories.current_count
                FROM invetories
                WHERE invetories.code IN ('{$productCodes}')";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $inventories = $stmt->fetchAll(PDO::FETCH_OBJ);
            $conn = null;

            return view('panel.ProMP.index', compact('inventories'));
        } catch(\PDOException $e) {
            return "Connection failed: " . $e->getMessage();
        }
    }
}
