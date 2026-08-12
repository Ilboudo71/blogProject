<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLike;
use App\Support\MarketplaceVisitor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductLikeController extends Controller
{
    public function toggle(Request $request, Product $product): JsonResponse
    {
        if (! $product->isPublished()) {
            abort(404);
        }

        $visitorKey = MarketplaceVisitor::key($request);

        $result = DB::transaction(function () use ($product, $visitorKey) {
            $existing = ProductLike::query()
                ->where('product_id', $product->id)
                ->where('visitor_key', $visitorKey)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                $existing->delete();
                if ($product->likes_count > 0) {
                    $product->decrement('likes_count');
                }
                $product->refresh();

                return [
                    'liked' => false,
                    'likes_count' => max(0, (int) $product->likes_count),
                    'liked_at' => null,
                ];
            }

            $like = ProductLike::query()->create([
                'product_id' => $product->id,
                'visitor_key' => $visitorKey,
                'liked_at' => now(),
            ]);

            $product->increment('likes_count');
            $product->refresh();

            return [
                'liked' => true,
                'likes_count' => (int) $product->likes_count,
                'liked_at' => $like->liked_at?->format('d/m/Y H:i:s'),
            ];
        });

        return response()->json($result);
    }
}
